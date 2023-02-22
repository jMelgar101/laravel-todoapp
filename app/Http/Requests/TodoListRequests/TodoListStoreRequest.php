<?php

namespace App\Http\Requests\TodoListRequests;

use Illuminate\Foundation\Http\FormRequest;

class TodoListStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:100'],
            'slug'              => ['string', 'max:255'],
            'is_all_complete'   => ['boolean'],
            'completed_at'      => ['nullable', 'date'],
        ];
    }
}
