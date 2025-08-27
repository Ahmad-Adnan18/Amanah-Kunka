<?php

namespace App\Http\Requests\Pengajaran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSantriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $santriId = $this->route('santri')->id;

        return [
            'nis' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('santris')->ignore($santriId),
            ],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['Putra', 'Putri'])],
            'rayon' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // TAMBAHKAN ATURAN INI:
            // Memastikan kelas_id dikirim dan valid.
            'kelas_id' => 'required|exists:kelas,id',
        ];
    }
}
