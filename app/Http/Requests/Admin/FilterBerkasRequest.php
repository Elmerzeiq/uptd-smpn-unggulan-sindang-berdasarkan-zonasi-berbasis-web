<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FilterBerkasRequest extends FormRequest
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
            'jalur_pendaftaran' => 'nullable|in:domisili,prestasi,afirmasi,mutasi',
            'status_pendaftaran' => 'nullable|in:menunggu_verifikasi_berkas,berkas_diverifikasi,berkas_tidak_lengkap,lulus_seleksi,tidak_lulus_seleksi',
            'status_berkas' => 'nullable|in:ada_berkas,belum_upload',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:10|max:100',
            'sort_by' => 'nullable|in:nama,created_at,updated_at',
            'sort_order' => 'nullable|in:asc,desc',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jalur_pendaftaran.in' => 'Jalur pendaftaran tidak valid.',
            'status_pendaftaran.in' => 'Status pendaftaran tidak valid.',
            'status_berkas.in' => 'Status berkas tidak valid.',
            'search.max' => 'Kata kunci pencarian terlalu panjang.',
            'per_page.integer' => 'Jumlah data per halaman harus berupa angka.',
            'per_page.min' => 'Minimal 10 data per halaman.',
            'per_page.max' => 'Maksimal 100 data per halaman.',
            'sort_by.in' => 'Kolom pengurutan tidak valid.',
            'sort_order.in' => 'Urutan pengurutan tidak valid.',
        ];
    }
}
