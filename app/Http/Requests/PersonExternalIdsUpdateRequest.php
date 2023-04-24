<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PersonExternalIdsUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'twitter_id' => ['nullable', 'string', 'max:255'],
            'instagram_id' => ['nullable', 'string', 'max:255'],
            'tiktok_id' => ['nullable', 'string', 'max:255'],
            'ameblo_id' => ['nullable', 'string', 'max:255'],
            'wikidata_id' => ['nullable', 'string', 'max:255'],
            'youtube_id' => ['nullable', 'string', 'max:255'],
            'google_id' => ['nullable', 'string', 'max:255'],
            'imdb_id' => ['nullable', 'string', 'max:255'],
            'fanza_id' => ['nullable', 'string', 'max:255'],
            'tmdb_id' => ['nullable', 'string', 'max:255'],
            'line_blog_id' => ['nullable', 'string', 'max:255'],
            'onlyfans_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}
