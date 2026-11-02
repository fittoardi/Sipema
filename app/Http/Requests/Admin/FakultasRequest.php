<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FakultasRequest extends FormRequest
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
        $fakultas = $this->route('fakultas');
        $fakultasId = is_object($fakultas) ? $fakultas->id : $fakultas;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('fakultas', 'kode')->ignore($fakultasId),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'dekan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode fakultas wajib diisi.',
            'kode.unique' => 'Kode fakultas sudah terdaftar.',
            'nama.required' => 'Nama fakultas wajib diisi.',
        ];
    }
}

