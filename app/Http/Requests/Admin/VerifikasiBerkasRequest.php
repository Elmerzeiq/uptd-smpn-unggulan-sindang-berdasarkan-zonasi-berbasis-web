
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiBerkasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'aksi' => 'required|in:verifikasi,tolak',
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'aksi.required' => 'Aksi verifikasi harus dipilih.',
            'aksi.in' => 'Aksi verifikasi tidak valid.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 1000 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'aksi' => 'aksi verifikasi',
            'catatan' => 'catatan',
        ];
    }
}
