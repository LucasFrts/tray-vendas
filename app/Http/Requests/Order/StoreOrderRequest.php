<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'seller_id' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'seller_id.required' => 'O seller_id é obrigatório.',
            'date.required' => 'A data é obrigatória.',
            'amount.required' => 'O amount é obrigatório.',
            'amount.integer' => 'O amount deve ser um número inteiro.',
        ];
    }
}
