<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivitySectiontRequest extends FormRequest
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
            'acs_id' => 'nullable|exists:activity_sections,acs_id',
            'acs_name' => 'required|string|max:255',
            'acs_pro_id' => 'required|exists:projects,pro_id',
        ];
    }
}
