<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckAndTransformOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'id'               => 'required|string',
            'name'             => 'required|string',
            'address.city'     => 'required|string',
            'address.district' => 'required|string',
            'address.street'   => 'required|string',
            'price'            => 'required|numeric',
            'currency'         => 'required|string',
        ];
    }
}