<?php

namespace Tests\Unit\Requests\Transactions;

use Validator;
use Tests\TestCase;
use App\Http\Requests\Transactions\CreateRequest as TransactionCreateRequest;

class CreateRequestTest extends TestCase
{
    /** @test */
    public function it_pass_for_required_attributes()
    {
        $formRequest = new TransactionCreateRequest();
        $attributes = [
            'date'        => '2018-03-03',
            'amount'      => '150000',
            'in_out'      => '1', // 0:spending, 1:income
            'description' => 'Transaction description.',
        ];
        $validator = Validator::make($attributes, $formRequest->rules(), $formRequest->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_for_empty_attributes()
    {
        $formRequest = new TransactionCreateRequest();
        $validator = Validator::make([], $formRequest->rules(), $formRequest->rules());

        $this->assertFalse($validator->passes());
        $errors = $validator->getMessageBag();

        $this->assertCount(4, $errors);
        $this->assertEquals(__('validation.required'), $errors->first('date'));
        $this->assertEquals(__('validation.required'), $errors->first('in_out'));
        $this->assertEquals(__('validation.required'), $errors->first('amount'));
        $this->assertEquals(__('validation.required'), $errors->first('description'));
    }
}
