<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\MovieType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        /** @var int[] */
        $validTypes = MovieType::all(['id'])->toArray();

        return [
            'studio_id' => ['nullable', 'integer', 'exists:studios,id'],
            'movie_type_id' => ['required', 'integer', Rule::in($validTypes)],
            'title' => ['nullable', 'string'],
            'original_title' => ['required', 'string'],
            'product_code' => ['required', 'string'],
            'release_date' => ['nullable', 'date'],
            'length' => ['nullable', 'integer'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
        ];
    }
}
