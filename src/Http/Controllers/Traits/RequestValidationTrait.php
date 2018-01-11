<?php

namespace Fomvasss\FieldFile\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait RequestValidationTrait
{
    protected $validationRules = [];

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $fieldName
     * @return mixed
     */
    protected function validate(Request $request, string $fieldName)
    {
        $validator = $request->validate([
            $fieldName => $this->validationRules['single']['item'] ?? 'file',
        ]);

        if ($request->expectsJson()) {
            return $this->validateJsonResponse($validator);
        }

        return $validator;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $fieldName
     * @return mixed
     */
    protected function validateArray(Request $request, string $fieldName)
    {
        $validator = $request->validate([
            $fieldName => $this->validationRules['multiple']['array'] ?? 'array',
            $fieldName.'.*' => $this->validationRules['multiple']['item'] ?? 'file',
        ]);

        if ($request->expectsJson()) {
            return $this->validateJsonResponse($validator);
        }

        return $validator;
    }

    protected function validateJsonResponse($validator)
    {
        $errors = $validator->errors();

        $key = config('field-file.response.json.error_key_name', 'errors');

        return new JsonResponse([
            $key => $errors
        ], 422);
    }
}
