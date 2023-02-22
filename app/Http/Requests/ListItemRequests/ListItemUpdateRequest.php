<?php

namespace App\Http\Requests\ListItemRequests;

use Illuminate\Foundation\Http\FormRequest;

class ListItemUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'              => ['string', 'max:255'],
            'is_complete'       => ['boolean'],
            'to_complete_by'    => ['nullable', 'date'],
            'completed_at'      => ['nullable', 'date'],
            'parent_id'         => ['nullable'],
        ];
    }
}
