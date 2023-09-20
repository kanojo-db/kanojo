<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ContentReportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ContentReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()?->can('create content report');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                new Enum(ContentReportType::class),
            ],
            'message' => ['nullable', 'string'],
            'public' => ['required', 'boolean'],
        ];
    }
}
