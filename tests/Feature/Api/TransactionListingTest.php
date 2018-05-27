<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_get_their_transaction_list()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);

        $this->getJson(route('api.transactions.index'), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeJson([
            'date'           => $transaction->date,
            'amount'         => $transaction->amount,
            'amount_string'  => $transaction->amount_string,
            'description'    => $transaction->description,
            'category'       => optional($transaction->category)->name,
            'category_color' => optional($transaction->category)->color,
            'difference'     => $transaction->amount_string,
        ]);
    }

    /** @test */
    public function user_can_get_a_transaction_detail()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);

        $this->getJson(route('api.transactions.show', $transaction), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeJson([
            'date'           => $transaction->date,
            'amount'         => $transaction->amount,
            'amount_string'  => $transaction->amount_string,
            'description'    => $transaction->description,
            'in_out'         => $transaction->in_out,
            'category_id'    => $transaction->category_id,
            'category'       => optional($transaction->category)->name,
            'category_color' => optional($transaction->category)->color,
            'created_at'     => $transaction->created_at->format('Y-m-d H:i:s'),
            'updated_at'     => $transaction->updated_at->format('Y-m-d H:i:s'),
        ]);
    }
}
