<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCurrencyConversionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'currency_code' => ['array', 'required'],
            'currency_code.origin' => ['required', 'string', 'max:255'],
            'currency_code.target' => ['required', 'string', 'max:255', 'different:currency_code.origin'],
            'amount' => ['required', 'numeric', 'min:100'],
        ];
    }
}
