<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Available to all users (guest & authenticated)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama pembeli wajib diisi.',
            'address.required' => 'Alamat pengiriman wajib diisi.',
            'quantity.required' => 'Jumlah pesanan wajib diisi.',
            'quantity.integer' => 'Jumlah pesanan harus berupa angka.',
            'quantity.min' => 'Jumlah pesanan minimal 1.',
            'note.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
