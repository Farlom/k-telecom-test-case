<?php

namespace App\Rules;

use App\Models\EquipmentType;
use Closure;
use Dflydev\DotAccessData\Data;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class Mask implements ValidationRule
{
    private int|null $index;
    public function __construct(int|null $index = null)
    {
        $this->index = $index;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->index !== null)
        {
            $requestAttribute = 'equipment.' . $this->index . '.equipment_type_id';
        }
        else {
            $requestAttribute = 'equipment_type_id';
        }
        if (EquipmentType::where('id', request($requestAttribute))->exists()) {
            $mask = EquipmentType::findOrFail(request($requestAttribute))->mask;
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
                                $fail(':attribute must be a valid mask of ' . $mask . '.');

                            }
                            break;
                    }
                }
            }
        } else {
            $fail('Selected equipment_type does not exist.');
        }
    }
}
