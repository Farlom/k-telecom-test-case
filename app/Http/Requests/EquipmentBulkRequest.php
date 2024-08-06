<?php

namespace App\Http\Requests;

use App\Models\EquipmentType;
use App\Rules\Mask;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class EquipmentBulkRequest extends FormRequest
{
    private array $invalidData = [];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function invalid(): array
    {
        return $this->invalidData;
    }

    public function validated($key = null, $default = null)
    {
        return $this->input('equipment');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'equipment' => ['required', 'array'],
            'equipment.*.equipment_type_id' => ['required', 'exists:equipment_types,id'],
            'equipment.*.serial_number' => Rule::forEach(function ($value, $attribute) {
                $index = explode('.', $attribute)[1];
                return [
                    'required',
                    'string',
                    Rule::unique('equipment', 'serial_number')->where(function ($query) use ($index) {
                        return $query->where('equipment_type_id', $this->input('equipment.' . $index . '.equipment_type_id'));
                    }),
                    new Mask($index),
                ];
            }),
            'equipment.*.desc' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $input = $this->input('equipment');
        if ($input) {
            foreach ($validator->errors()->toArray() as $key => $message) {
                $index = explode('.', $key)[1];
                unset($input[$index]);
                $this->invalidData[$index]['id'] = $index;
                $this->invalidData[$index]['message'][] = $message[0];
            }
            $this->merge([
                'equipment' => $input,
            ]);
        } else {
            $this->invalidData[0]['id'] = 0;
            $this->invalidData[0]['message'] = $validator->errors()->toArray()['equipment'][0];
            $this->merge([
                'equipment' => [],
            ]);
        }

    }
}
