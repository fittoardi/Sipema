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
            'kode_prodi' => [
                'required',
                'string',
                'max:10',
                Rule::unique('prodis', 'kode_prodi')->ignore($prodiId),
            ],
            'nama_prodi' => ['required', 'string', 'max:100'],
        ];
    }
}
