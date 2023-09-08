<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
     * @return array<string, array<int, \Illuminate\Validation\Rules\In|string>>
     */
    public function rules(): array
    {
        return [
            'studioId' => ['nullable', 'integer', 'exists:studios,id'],
            'seriesId' => ['nullable', 'integer', 'exists:series,id'],
            'movieTypeId' => ['required', 'integer', 'exists:types,id'],
            'title' => ['nullable', 'string'],
            'originalTitle' => ['required', 'string'],
            'runtime' => ['nullable', 'integer'],
        ];
    }
}
