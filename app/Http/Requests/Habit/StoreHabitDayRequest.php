<?php

namespace App\Http\Requests\Habit;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitDayRequest extends FormRequest
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
            "had_id" => "nullable|integer|exists:habit_days,had_id",
            "had_hab_id" => "required",
            "had_day" => "required|string",
            "had_description" => "nullable|string",
            "had_schedule" => "nullable|string"
        ];
    }
}
