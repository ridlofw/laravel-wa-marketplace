<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|max:2048',
            'address' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
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
            'name.required' => 'Nama produk wajib diisi.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'category.required' => 'Kategori produk wajib diisi.',
            'images.required' => 'Minimal 1 foto produk harus diunggah.',
            'images.min' => 'Minimal 1 foto produk harus diunggah.',
            'images.max' => 'Maksimal 5 foto produk dapat diunggah.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
