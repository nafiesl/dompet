<?php

namespace Tests\Traits;

use Closure;
use Validator;
use Illuminate\Foundation\Http\FormRequest;

trait ValidateFormRequest
{
    protected function assertValidationPasses(FormRequest $formRequest, array $attributes)
    {
        $validator = $this->getValidator($formRequest, $attributes);

        $this->assertTrue($validator->passes());

        return $validator;
    }

    protected function assertValidationFails(FormRequest $formRequest, array $attributes, Closure $callback = null)
    {
        $validator = $this->getValidator($formRequest, $attributes);

        $this->assertTrue($validator->fails());

        if ($callback) {
            call_user_func($callback, $validator->getMessageBag());
        }

        return $validator;
    }

    protected function getValidator(FormRequest $formRequest, array $attributes)
    {
        return Validator::make($attributes, $formRequest->rules(), $formRequest->rules());
    }
}
