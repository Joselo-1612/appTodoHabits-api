<?php

namespace App\Http\Requests\Habit;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitRequest extends FormRequest
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
            "hab_id" => "nullable|integer|exists:habits,hab_id",
            "hab_name" => "required|string",
            "hab_description" => "nullable|string",
            "hab_type_recurrence" => "required|string",
            "hab_days_of_week" => "nullable|array",
            "hab_is_pinned" => "boolean",
            "hab_schedule" => "nullable|string"
        ];
    }
}
