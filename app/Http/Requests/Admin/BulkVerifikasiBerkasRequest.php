<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BulkVerifikasiBerkasRequest extends FormRequest
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
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:users,id',
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
            'siswa_ids.required' => 'Pilih minimal satu siswa untuk diverifikasi.',
            'siswa_ids.array' => 'Data siswa tidak valid.',
            'siswa_ids.min' => 'Pilih minimal satu siswa.',
            'siswa_ids.*.exists' => 'Salah satu siswa yang dipilih tidak valid.',
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
            'siswa_ids' => 'siswa yang dipilih',
            'aksi' => 'aksi verifikasi',
            'catatan' => 'catatan',
        ];
    }
}
