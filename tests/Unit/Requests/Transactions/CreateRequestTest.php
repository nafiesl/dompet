<?php

namespace Tests\Unit\Requests\Transactions;

use Tests\TestCase;
use Tests\Traits\ValidateFormRequest;
use App\Http\Requests\Transactions\CreateRequest as TransactionCreateRequest;

class CreateRequestTest extends TestCase
{
    use ValidateFormRequest;

    /** @test */
    public function it_pass_for_required_attributes()
    {
        $this->assertValidationPasses(new TransactionCreateRequest(), [
            'date'        => '2018-03-03',
            'amount'      => '150000',
            'in_out'      => '1', // 0:spending, 1:income
            'description' => 'Transaction description.',
        ]);
    }

    /** @test */
    public function it_fails_for_empty_attributes()
    {
        $this->assertValidationFails(new TransactionCreateRequest(), [], function ($errors) {
            $this->assertCount(4, $errors);
            $this->assertEquals(__('validation.required'), $errors->first('date'));
            $this->assertEquals(__('validation.required'), $errors->first('in_out'));
            $this->assertEquals(__('validation.required'), $errors->first('amount'));
            $this->assertEquals(__('validation.required'), $errors->first('description'));
        });
    }

    /** @test */
    public function it_fails_if_description_is_more_than_255_characters()
    {
        $this->assertValidationFails(new TransactionCreateRequest(), [
            'date'        => '2018-03-03',
            'amount'      => '150000',
            'in_out'      => '1', // 0:spending, 1:income
            'description' => str_repeat('Transaction description.', 11),
        ], function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'description', 'max' => 255]),
                $errors->first('description')
            );
        });
    }
}
