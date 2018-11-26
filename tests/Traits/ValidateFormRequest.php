<?php

namespace Tests\Traits;

use Validator;
use Illuminate\Foundation\Http\FormRequest;

trait ValidateFormRequest
{
    public function assertValidationPasses(FormRequest $formRequest, array $attributes)
    {
        $validator = $this->getValidator($formRequest, $attributes);

        $this->assertTrue($validator->passes());

        return $validator;
    }

    public function assertValidationFails(FormRequest $formRequest, array $attributes)
    {
        $validator = $this->getValidator($formRequest, $attributes);

        $this->assertTrue($validator->fails());

        return $validator;
    }

    protected function getValidator(FormRequest $formRequest, array $attributes)
    {
        return Validator::make($attributes, $formRequest->rules(), $formRequest->rules());
    }
}
