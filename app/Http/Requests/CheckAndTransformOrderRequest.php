<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckAndTransformOrderRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'messages' => $validator->errors()->all(),
            ], 400)
        );
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'id'   => 'required|string',
            'name' => 'required|string',
        ];
    }
}