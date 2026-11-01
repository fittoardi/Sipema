<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DosenRequest extends FormRequest
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
            'nidn' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'nip' => ['required', 'string', 'max:30'],
            'prodi_id' => ['required', 'exists:program_studi,id'],
        ];
    }
}
