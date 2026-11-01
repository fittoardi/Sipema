<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
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
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama' => ['required', 'string', 'max:255'],
            'tahun_akademik' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:Ganjil,Genap'],
        ];
    }
}
