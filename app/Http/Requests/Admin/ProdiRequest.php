<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdiRequest extends FormRequest
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
        $prodi = $this->route('prodi');
        $prodiId = is_object($prodi) ? $prodi->id : $prodi;

        return [
            'fakultas_id' => ['nullable', 'exists:fakultas,id'],
            'kode_prodi' => [
                'required',
                'string',
                'max:20',
                Rule::unique('program_studi', 'kode')->ignore($prodiId),
            ],
            'nama_prodi' => ['required', 'string', 'max:255'],
        ];
    }
}
