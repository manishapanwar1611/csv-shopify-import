<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store_upload_product extends FormRequest
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
            'product_csv' => 'required|file|mimes:csv,txt|max:5120' // 5MB
        ];
    }
}
