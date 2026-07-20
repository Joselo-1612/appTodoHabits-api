<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pro_id' => 'nullable|exists:projects,pro_id',
            'pro_name' => 'required|string|max:255',
            'pro_description' => 'nullable|string',
            'pro_priority' => 'required|integer|between:1,5',
            'pro_date_start' => 'required|date',
            'pro_date_end' => 'required|date|after:pro_date_start',
            'pro_group' => 'required|string',
            // 'pro_use_id' => 'required|exists:users,usu_id'
        ];
    }
}
