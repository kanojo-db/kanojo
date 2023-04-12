<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'original_name' => ['required'],
            // 'gender' => ['required', 'integer', 'min:0', 'max:3'],
            'birthdate' => ['nullable', 'date'],
            'country' => ['nullable', 'string'],
            'career_start' => ['nullable', 'date'],
            'career_end' => ['nullable', 'date'],
            'blood_type' => ['nullable', Rule::in(['AB', 'A', 'B', 'O'])],
            'cup_size' => ['nullable', Rule::in([
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z',
            ])],
            'height' => ['nullable', 'integer', 'max:250'],
            'bust' => ['nullable', 'integer', 'max:250'],
            'waist' => ['nullable', 'integer', 'max:250'],
            'hip' => ['nullable', 'integer', 'max:250'],
            'dob_doubt' => ['boolean'],
        ];
    }
}
