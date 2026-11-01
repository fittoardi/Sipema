<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MahasiswaRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20'],
            'prodi_id' => ['required', 'exists:prodis,id'],
            'angkatan' => ['required', 'integer'],
        ];
    }
}
