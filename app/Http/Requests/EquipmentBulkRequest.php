<?php

namespace App\Http\Requests;

use App\Models\EquipmentType;
use App\Rules\Mask;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            'equipment' => ['array'],

            'equipment.*.equipment_type_id' => ['required', 'exists:equipment_types,id'],
            'equipment.*.serial_number' => [
                'required',
                'string',
                'unique:equipment,serial_number',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    if (EquipmentType::where('id', $this->input('equipment.' . $index . '.equipment_type_id'))->exists()) {
                        $mask = EquipmentType::findOrFail($this->input('equipment.' . $index . '.equipment_type_id'))->mask;
                        if (strlen($mask) !== strlen($value)) {
                            $fail(':attribute must be a valid mask of ' . $mask . '.');
                        } else {

                            foreach (str_split($mask) as $i => $char) {
                                switch ($char) {
                                    case 'N': // N – цифра от 0 до 9;
                                        if (!is_numeric($value[$i])) {
                                            $fail(':attribute must be a valid mask of ' . $mask . '.');
                                        }
                                        break;
                                    case 'A': // A – прописная буква латинского алфавита;
                                        if (!preg_match('/[A-Z]/', $value[$i])) {
                                            $fail(':attribute must be a valid mask of ' . $mask . '.');
                                        }
                                        break;
                                    case 'a': // a – строчная буква латинского алфавита;
                                        if (!preg_match('/[a-z]/', $value[$i])) {
                                            $fail(':attribute must be a valid mask of ' . $mask . '.');
                                        }
                                        break;
                                    case 'X': // X – прописная буква латинского алфавита либо цифра от 0 до 9;
                                        if (!is_numeric($value[$i]))
                                            if (!preg_match('/[A-Z]/', $value[$i])) {
                                                $fail(':attribute must be a valid mask of ' . $mask . '.');
                                            }
                                        break;
                                    case 'Z': // Z –символ из списка: “-“, “_”, “@”.
                                        if ($value[$i] !== '-') {
                                            if ($value[$i] !== '_') {
                                                if ($value[$i] !== '@') {
                                                    $fail(':attribute must be a valid mask of ' . $mask . '.');
                                                }
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                    }
                    else {
                        $fail(EquipmentType::where('id', $index)->exists() ? "123" : '321');
                    }

                }
            ],
            'equipment.*.desc' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $input = $this->input('equipment');
        foreach ($validator->errors()->toArray() as $key => $message) {
            $index = explode('.', $key)[1];
            unset($input[$index]);
            $this->invalidData[$index]['id'] = $index;
            $this->invalidData[$index]['message'][] = $message[0];
        }
        $this->merge([
            'equipment' => $input,
        ]);
    }

    protected function prepareForValidation(): void
    {
        $input = $this->input('equipment');
        foreach ($input as $key => &$value) {
            $value['id'] = $key;
            $value['valid'] = true;
            $value['message'] = null;
        }
        $this->merge([
            'equipment' => $input,
        ]);
    }
}
