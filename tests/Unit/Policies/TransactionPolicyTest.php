<?php

namespace Tests\Unit\Policies;

use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase as TestCase;

class TransactionPolicyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_transaction()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Transaction));
    }

    /** @test */
    public function user_can_view_transaction()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create();
        $this->assertTrue($user->can('view', $transaction));
    }

    /** @test */
    public function user_can_update_transaction()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create();
        $this->assertTrue($user->can('update', $transaction));
    }

    /** @test */
    public function user_can_delete_transaction()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create();
        $this->assertTrue($user->can('delete', $transaction));
    }
}
