<?php

namespace App\Http\Requests;

use App\Rules\Mask;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
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
            'equipment_type_id' => ['required', 'exists:equipment_types,id'],
            'serial_number' => [
                'required',
                'string',
                Rule::unique('equipment', 'serial_number')->where(function ($query) {
                    return $query->where('equipment_type_id', $this->equipment_type_id);
                })->ignore($this->route()->equipment->id),
                new Mask(),
            ],
            'desc' => ['nullable', 'string'],
        ];
    }
}
