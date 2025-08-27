<?php

namespace App\Http\Requests\Perizinan;

use Illuminate\Foundation\Http\FormRequest;

class StorePerizinanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ditangani oleh Policy di Controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'santri_id' => 'required|exists:santris,id',
            'jenis_izin' => 'required|string',
            'kategori' => 'required|string',
            'keterangan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            // 'tanggal_akhir' wajib diisi jika jenis_izin mengandung kata "Pulang"
            'tanggal_akhir' => 'nullable|required_if:jenis_izin,Pulang,Sakit Berat (Izin Pulang)|date|after_or_equal:tanggal_mulai',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'tanggal_akhir.required_if' => 'Tanggal kembali wajib diisi untuk izin pulang.',
            'tanggal_akhir.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal mulai.',
        ];
    }
}
