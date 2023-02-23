<?php

namespace App\Http\Requests\ListItemRequests;

use Illuminate\Foundation\Http\FormRequest;

class ListItemStoreRequest extends FormRequest
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
            'name'                  => ['required', 'string', 'max:255'],
            'is_complete'           => ['boolean'],
            'to_complete_by_date'   => ['nullable', 'date'],
            'to_complete_by_time'   => ['nullable', 'date_format:H:i'],
            'completed_at'          => ['nullable', 'date'],
            'todo_list_id'          => ['required'],
            'parent_id'             => ['nullable'],
        ];
    }
}
