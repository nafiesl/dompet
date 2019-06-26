<?php

namespace Tests\Feature\Api;

use App\Category;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_category_list_in_category_index_page()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->getJson(route('api.categories.index'), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeJson(['name' => $category->name]);
    }

    /** @test */
    public function user_can_create_a_category()
    {
        $user = $this->createUser();

        $this->postJson(route('api.categories.store'), [
            'name'        => 'Category 1 name',
            'color'       => '#00aabb',
            'description' => 'Category 1 description',
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeInDatabase('categories', [
            'name'        => 'Category 1 name',
            'color'       => '#00aabb',
            'description' => 'Category 1 description',
        ]);

        $this->seeStatusCode(201);
        $this->seeJson([
            'message'     => __('category.created'),
            'color'       => '#00aabb',
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
        ]);
    }

    /** @test */
    public function user_can_update_a_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['name' => 'Testing 123', 'creator_id' => $user->id]);

        $this->patchJson(route('api.categories.update', $category), [
            'name'        => 'Category 1 name',
            'color'       => '#00aabb',
            'description' => 'Category 1 description',
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeInDatabase('categories', [
            'name'        => 'Category 1 name',
            'color'       => '#00aabb',
            'description' => 'Category 1 description',
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message'     => __('category.updated'),
            'color'       => '#00aabb',
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
        ]);
    }

    /** @test */
    public function user_can_delete_a_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->create([
            'category_id' => $category->id,
            'creator_id' => $user->id,
        ]);

        $this->deleteJson(route('api.categories.destroy', $category), [
            'category_id' => $category->id,
            'delete_transactions' => 0,
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        // check for related transactions
        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
            'category_id' => null,
        ]);

        $this->dontSeeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('category.deleted'),
        ]);
    }

    /** @test */
    public function user_can_delete_a_category_and_transactions()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->create([
            'category_id' => $category->id,
            'creator_id' => $user->id,
        ]);

        $this->deleteJson(route('api.categories.destroy', $category), [
            'category_id' => $category->id,
            'delete_transactions' => 1,
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        // check for related transactions
        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);

        $this->dontSeeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('category.deleted'),
        ]);
    }
}
