<?php

namespace App\Http\Requests;

use App\Models\EquipmentType;
use App\Rules\Mask;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'desc' => ['required'],
        ];
    }
}
