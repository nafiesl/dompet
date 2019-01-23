<?php

namespace Tests\Feature\Api;

use App\Partner;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePartnerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_partner_list_in_partner_index_page()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);

        $this->getJson(route('api.partners.index'), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeJson(['name' => $partner->name]);
    }

    /** @test */
    public function user_can_create_a_partner()
    {
        $user = $this->createUser();

        $this->postJson(route('api.partners.store'), [
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeInDatabase('partners', [
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ]);

        $this->seeStatusCode(201);
        $this->seeJson([
            'message'     => __('partner.created'),
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ]);
    }

    /** @test */
    public function user_can_update_a_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create(['name' => 'Testing 123', 'creator_id' => $user->id]);

        $this->patchJson(route('api.partners.update', $partner), [
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeInDatabase('partners', [
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message'     => __('partner.updated'),
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ]);
    }

    /** @test */
    public function user_can_delete_a_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);

        $this->deleteJson(route('api.partners.destroy', $partner), [
            'partner_id' => $partner->id,
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->dontSeeInDatabase('partners', [
            'id' => $partner->id,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('partner.deleted'),
        ]);
    }
}
