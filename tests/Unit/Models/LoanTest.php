<?php

namespace Tests\Unit\Models;

use App\User;
use App\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_loan_has_name_link_attribute()
    {
        $loan = factory(Loan::class)->create();

        $this->assertEquals(
            link_to_route('loans.show', $loan->name, [$loan], [
                'title' => __(
                    'app.show_detail_title',
                    ['name' => $loan->name, 'type' => __('loan.loan')]
                ),
            ]), $loan->name_link
        );
    }

    /** @test */
    public function a_loan_has_belongs_to_creator_relation()
    {
        $loan = factory(Loan::class)->make();

        $this->assertInstanceOf(User::class, $loan->creator);
        $this->assertEquals($loan->creator_id, $loan->creator->id);
    }
}
