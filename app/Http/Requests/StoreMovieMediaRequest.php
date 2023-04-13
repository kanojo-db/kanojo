<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\MediaCollectionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;

class StoreMovieMediaRequest extends FormRequest
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
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'collection_name' => ['required', new Enum(MediaCollectionType::class)],
            'media' => [
                'required',
                'file',
                File::types(['jpeg', 'png', 'webp'])->max(14000 /* 14MB */),
            ],
        ];
    }
}
