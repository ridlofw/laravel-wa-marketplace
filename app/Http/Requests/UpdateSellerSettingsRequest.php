<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string',
            'shop_whatsapp' => 'required|string|max:20',
            'shop_logo' => 'nullable|image|max:2048',
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
            'shop_name.required' => 'Nama toko wajib diisi.',
            'shop_address.required' => 'Alamat toko wajib diisi.',
            'shop_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'shop_whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter.',
            'shop_logo.image' => 'File harus berupa gambar.',
            'shop_logo.max' => 'Ukuran logo maksimal 2MB.',
        ];
    }
}
