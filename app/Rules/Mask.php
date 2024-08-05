<?php

namespace App\Rules;

use App\Models\EquipmentType;
use Closure;
use Dflydev\DotAccessData\Data;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class Mask implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($attribute === 'serial_number') {
            $requestAttrubute = 'equipment_type_id';
            $equipmentType = EquipmentType::where('id', request($requestAttrubute))->exists();
        } else {
            // equipment.*.serial_number
            $index = explode('.', $attribute)[1];
            $requestAttrubute = 'equipment.' . $index . '.equipment_type_id';
            $equipmentType = EquipmentType::where('id', request($requestAttrubute))->exists();
        }
        if ($equipmentType) {
            $mask = EquipmentType::findOrFail(request($requestAttrubute))->mask;
            if (strlen($mask) !== strlen($value)) {
                $fail(':attribute must be a valid mask of ' . $mask . '.');
            } else {
                foreach (str_split($mask) as $i => $char) {
                    switch ($char) {
                        case 'N': // N – цифра от 0 до 9;
                            if (!preg_match('/[0-9]/', $value[$i])) {
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
                            if (!preg_match('/[A-Z0-9]/', $value[$i])) {
                                $fail(':attribute must be a valid mask of ' . $mask . '.');
                            }
                            break;
                        case 'Z': // Z –символ из списка: “-“, “_”, “@”.
                            if (!preg_match('/[-_@]/', $value[$i])) {
                                $fail(':attribute must be a valid mask of ' . $mask . '.Z');

                            }
                            break;
                    }
                }
            }
        } else {
            $fail(':attribute does not exist.');
        }
    }
}
