<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'original_title' => ['required', 'string'],
            'product_code' => ['required', 'string'],
            'release_date' => ['nullable', 'date'],
            'runtime' => ['nullable', 'integer'],
        ];
    }
}
