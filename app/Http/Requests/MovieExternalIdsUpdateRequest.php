<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MovieExternalIdsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'imdb_id' => ['nullable', 'string', 'max:255'],
            'tmdb_id' => ['nullable', 'string', 'max:255'],
            'fanza_id' => ['nullable', 'string', 'max:255'],
            'mgstage_id' => ['nullable', 'string', 'max:255'],
            'dmm_id' => ['nullable', 'string', 'max:255'],
            'fc2_id' => ['nullable', 'string', 'max:255'],
            'wikidata_id' => ['nullable', 'string', 'max:255'],
            'google_id' => ['nullable', 'string', 'max:255'],

        ];
    }
}
