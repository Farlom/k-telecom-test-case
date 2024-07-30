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
    private array $invalidData = [];

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
        if ($this->method() === 'POST') {
            return [
                'equipment' => ['array'],

                'equipment.*.equipment_type_id' => ['required', 'exists:equipment_types,id'],
                'equipment.*.serial_number' => [
                    'required',
                    'string',
                    'unique:equipment,serial_number',
                ],
                'equipment.*.desc' => ['required'],
//                '*.equipment_type_id' => ['required', 'exists:equipment_types,id'],
//                '*.serial_number' => [
//                    'required',
//                    'string',
//                    'unique:equipment,serial_number',
//                ],
//                '*.desc' => ['required'],
            ];
        }
        return [
            'equipment' => ['array'],
            '*.equipment_type_id' => ['required', 'exists:equipment_types,id'],
            '*.serial_number' => [
                'required',
                'string',
                'unique:equipment,serial_number',
//                new Mask(),
                function ($attribute, $value, $fail) {
                    $mask = EquipmentType::findOrFail($this->input('equipment_type_id'))->mask;
//                    $fail($mask);
                    if (strlen($mask) !== strlen($value)) {
                        $fail('НЕТ LEN');
                    } else {

                        foreach (str_split($mask) as $i => $char) {
                            switch ($char) {
                                case 'N': // N – цифра от 0 до 9;
//                                    $fail($char);
                                    if (!is_numeric($value[$i])) {
                                        $fail('НЕТ N');
                                    }
                                    break;
                                case 'A': // A – прописная буква латинского алфавита;
                                    if (!preg_match('/[A-Z]/', $value[$i])) {
                                        $fail('НЕТ A');
                                    }
                                    break;
                                case 'a': // a – строчная буква латинского алфавита;
                                    if (!preg_match('/[a-z]/', $value[$i])) {
                                        $fail('НЕТ a');
                                    }
                                    break;
                                case 'X': // X – прописная буква латинского алфавита либо цифра от 0 до 9;
                                    if (!is_numeric($value[$i]))
                                        if (!preg_match('/[A-Z]/', $value[$i])) {
                                            $fail('НЕТ X');
                                        }
                                    break;
                                case 'Z': // Z –символ из списка: “-“, “_”, “@”.
                                    if ($value[$i] !== '-') {
                                        if ($value[$i] !== '_') {
                                            if ($value[$i] !== '@') {
                                                $fail('НЕТ Z');
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
            ],
            '*.desc' => ['required'],
        ];
    }

    public function invalid(): array
    {
        return $this->invalidData;
    }

    public function validated($key = null, $default = null)
    {
        return $this->input('equipment');
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
