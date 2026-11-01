<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NilaiRequest extends FormRequest
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
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mahasiswa_id' => ['required', 'exists:mahasiswa,id'],
            'kuis' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
