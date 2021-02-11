<?php

namespace Tests\Feature;

use App\Loan;
use App\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageLoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_loan_list_in_loan_index_page()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create(['partner_id' => $partner->id]);

        $this->visitRoute('loans.index');
        $this->see($loan->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'type_id'               => Loan::TYPE_RECEIVABLE,
            'amount'                => 2000,
            'planned_payment_count' => 5,
            'start_date'            => '2020-01-01',
            'end_date'              => '2020-02-29',
            'description'           => 'Loan 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_loan()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $this->visitRoute('loans.index');

        $this->click(__('loan.create'));
        $this->seeRouteIs('loans.create');

        $this->submitForm(__('loan.create'), $this->getCreateFields([
            'partner_id' => $partner->id,
            'type_id'    => Loan::TYPE_DEBT,
            'amount'     => 2000,
        ]));

        $this->seeRouteIs('loans.show', Loan::first());

        $this->seeInDatabase('loans', $this->getCreateFields([
            'partner_id' => $partner->id,
            'type_id'    => Loan::TYPE_DEBT,
            'amount'     => 2000,
        ]));
    }

    /** @test */
    public function validate_loan_partner_id_is_required()
    {
        $this->loginAsUser();

        // partner_id empty
        $this->post(route('loans.store'), $this->getCreateFields(['partner_id' => '']));
        $this->assertSessionHasErrors('partner_id');
    }

    /** @test */
    public function validate_loan_type_id_is_required()
    {
        $this->loginAsUser();

        // type_id empty
        $this->post(route('loans.store'), $this->getCreateFields(['type_id' => '']));
        $this->assertSessionHasErrors('type_id');
    }

    /** @test */
    public function validate_loan_amount_is_required()
    {
        $this->loginAsUser();

        // amount empty
        $this->post(route('loans.store'), $this->getCreateFields(['amount' => '']));
        $this->assertSessionHasErrors('amount');
    }

    /** @test */
    public function validate_loan_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('loans.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'type_id'               => Loan::TYPE_RECEIVABLE,
            'amount'                => 2000,
            'planned_payment_count' => 5,
            'start_date'            => '2020-01-01',
            'end_date'              => '2020-02-29',
            'description'           => 'Loan 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_loan()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'partner_id' => $partner->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('loans.show', $loan);
        $this->click('edit-loan-'.$loan->id);
        $this->seeRouteIs('loans.edit', $loan);

        $this->submitForm(__('loan.update'), $this->getEditFields([
            'partner_id' => $loan->partner_id,
            'amount'     => 1000,
        ]));

        $this->seeRouteIs('loans.show', $loan);

        $this->seeInDatabase('loans', $this->getEditFields([
            'id'     => $loan->id,
            'amount' => 1000,
        ]));
    }

    /** @test */
    public function validate_loan_partner_id_update_is_required()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['partner_id' => 500]);

        // partner_id empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['partner_id' => '']));
        $this->assertSessionHasErrors('partner_id');
    }

    /** @test */
    public function validate_loan_type_id_update_is_required()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['type_id' => 500]);

        // type_id empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['type_id' => '']));
        $this->assertSessionHasErrors('type_id');
    }

    /** @test */
    public function validate_loan_amount_update_is_required()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['amount' => 500]);

        // amount empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['amount' => '']));
        $this->assertSessionHasErrors('amount');
    }

    /** @test */
    public function validate_loan_description_update_is_not_more_than_255_characters()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id]);

        // description 256 characters
        $this->patch(route('loans.update', $loan), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_loan()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create(['partner_id' => $partner->id]);
        factory(Loan::class)->create(['partner_id' => $partner->id]);

        $this->visitRoute('loans.edit', $loan);
        $this->click('del-loan-'.$loan->id);
        $this->seeRouteIs('loans.edit', [$loan, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('loans', [
            'id' => $loan->id,
        ]);
    }
}
