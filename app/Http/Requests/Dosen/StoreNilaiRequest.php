<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'mahasiswa_id' => ['required', 'integer', 'exists:mahasiswas,id'],
            'kuis' => ['required', 'numeric', 'between:0,100'],
            'tugas' => ['required', 'numeric', 'between:0,100'],
            'uts' => ['required', 'numeric', 'between:0,100'],
            'uas' => ['required', 'numeric', 'between:0,100'],
        ];
    }
}
