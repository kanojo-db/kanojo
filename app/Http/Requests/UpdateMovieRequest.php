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
            'release_date' => ['nullable', 'date'],
            'runtime' => ['nullable', 'integer'],
            'type_id' => ['nullable', 'integer', 'exists:types,id'],
            'studio_id' => ['nullable', 'integer', 'exists:studios,id'],
            'series_id' => ['nullable', 'integer', 'exists:series,id'],
            'is_vr' => ['nullable', 'boolean'],
            'is_3d' => ['nullable', 'boolean'],
        ];
    }
}
