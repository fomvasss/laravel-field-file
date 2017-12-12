<?php

namespace Fomvasss\FieldFile\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return mixed
     */
    abstract public function rules();

    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            $key = config('field_file.json_response_request.key_name', 'errors');
            return new JsonResponse([
                $key => $errors
            ], 422);
        }

        parent::response($errors);
    }
}
