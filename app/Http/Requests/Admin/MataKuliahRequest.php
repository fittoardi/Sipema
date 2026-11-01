<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MataKuliahRequest extends FormRequest
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
        $matakuliah = $this->route('matakuliah');
        $matakuliahId = is_object($matakuliah) ? $matakuliah->id : $matakuliah;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliahs', 'kode')->ignore($matakuliahId),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1'],
            'prodi_id' => ['required', 'exists:prodis,id'],
        ];
    }
}
