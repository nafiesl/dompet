<?php

namespace Tests\Feature;

use App\Loan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageLoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_loan_list_in_loan_index_page()
    {
        $loan = factory(Loan::class)->create();

        $this->loginAsUser();
        $this->visitRoute('loans.index');
        $this->see($loan->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Loan 1 name',
            'description' => 'Loan 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_loan()
    {
        $this->loginAsUser();
        $this->visitRoute('loans.index');

        $this->click(__('loan.create'));
        $this->seeRouteIs('loans.create');

        $this->submitForm(__('loan.create'), $this->getCreateFields());

        $this->seeRouteIs('loans.show', Loan::first());

        $this->seeInDatabase('loans', $this->getCreateFields());
    }

    /** @test */
    public function validate_loan_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('loans.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_loan_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // name 70 characters
        $this->post(route('loans.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
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
            'name'        => 'Loan 1 name',
            'description' => 'Loan 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_loan()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['name' => 'Testing 123']);

        $this->visitRoute('loans.show', $loan);
        $this->click('edit-loan-'.$loan->id);
        $this->seeRouteIs('loans.edit', $loan);

        $this->submitForm(__('loan.update'), $this->getEditFields());

        $this->seeRouteIs('loans.show', $loan);

        $this->seeInDatabase('loans', $this->getEditFields([
            'id' => $loan->id,
        ]));
    }

    /** @test */
    public function validate_loan_name_update_is_required()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_loan_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['name' => 'Testing 123']);

        // name 70 characters
        $this->patch(route('loans.update', $loan), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_loan_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create(['name' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('loans.update', $loan), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_loan()
    {
        $this->loginAsUser();
        $loan = factory(Loan::class)->create();
        factory(Loan::class)->create();

        $this->visitRoute('loans.edit', $loan);
        $this->click('del-loan-'.$loan->id);
        $this->seeRouteIs('loans.edit', [$loan, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('loans', [
            'id' => $loan->id,
        ]);
    }
}
