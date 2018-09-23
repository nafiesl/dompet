<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BalanceFunctionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function balance_function_is_exists()
    {
        $this->assertTrue(function_exists('balance'), 'The balance() function does not exists.');
    }

    /** @test */
    public function balance_can_returns_current_balance_of_alltime_transactions()
    {
        $user = $this->loginAsUser();

        // Income transaction
        factory(Transaction::class)->create(['amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        // Spending transaction
        factory(Transaction::class)->create(['amount' => 3000, 'in_out' => 0, 'creator_id' => $user->id]);

        // Assert balance with no specific date range
        $this->assertEquals(7000, balance());
    }

    /** @test */
    public function balance_can_returns_balance_until_specified_date()
    {
        $user = $this->loginAsUser();

        // Income transaction
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        // Spending transaction
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'creator_id' => $user->id]);

        // Other transaction after specified date
        factory(Transaction::class)->create(['date' => '2015-01-31', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);

        // Assert balance until date '2015-01-30'
        $this->assertEquals(6000, balance('2015-01-30'));
    }

    /** @test */
    public function balance_can_returns_balance_within_date_ranges()
    {
        $user = $this->loginAsUser();

        // Other transaction outside date range
        factory(Transaction::class)->create(['date' => '2015-01-01', 'amount' => 900, 'in_out' => 1, 'creator_id' => $user->id]);

        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-31', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);

        // Assert balance from '2015-01-03' until '2015-01-30'
        $this->assertEquals(16000, balance('2015-01-31', '2015-01-03'));
    }

    /** @test */
    public function make_sure_balance_only_for_authenticated_user()
    {
        $user = $this->loginAsUser();

        // Other transaction outside specified date
        factory(Transaction::class)->create(['date' => '2015-01-01', 'amount' => 900, 'in_out' => 1, 'creator_id' => $user->id]);

        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'creator_id' => $user->id]);

        // Other user's transaction within date range
        factory(Transaction::class)->create(['date' => '2015-01-18', 'amount' => 10000, 'in_out' => 1, 'creator_id' => 999]);

        // Assert balance from '2015-01-03' until '2015-01-30'
        $this->assertEquals(6000, balance('2015-01-31', '2015-01-03'));
    }

    /** @test */
    public function unauthenticated_user_has_0_balance()
    {
        factory(Transaction::class)->create();

        $this->assertEquals(0, balance());
    }
}
