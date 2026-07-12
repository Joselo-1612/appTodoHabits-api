<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
            'act_id' => 'nullable|exists:activities,act_id',
            'act_name' => 'required|string|max:255',
            'act_description' => 'nullable|string',
            'act_date_start' => 'nullable|date',
            'act_date_end' => 'nullable|date',
            'act_sea_id' => 'required|exists:activity_sections,acs_id',
        ];
    }
}
