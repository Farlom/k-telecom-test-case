<?php

namespace App\Http\Requests;

use App\Models\EquipmentType;
use App\Rules\Mask;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

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
                'unique:equipment,serial_number',
                new Mask(),
            ],
            'desc' => ['nullable'],
        ];
    }
}
