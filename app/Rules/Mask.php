<?php

namespace App\Rules;

use App\Models\EquipmentType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Mask implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mask = EquipmentType::find($this->request('equipment_type_id'))->mask;

        if (strlen($mask) !== strlen($value)) {
            $fail('НЕТ');
        }
        foreach (str_split($value) as $char) {
            switch ($char) {
                case 'N': // N – цифра от 0 до 9;
                    break;
                case 'A': // A – прописная буква латинского алфавита;
                    break;
                case 'a': // a – строчная буква латинского алфавита;
                    break;
                case 'X': // X – прописная буква латинского алфавита либо цифра от 0 до 9;
                    break;
                case 'Z': // Z –символ из списка: “-“, “_”, “@”.
                    break;
            }
        }
        //
    }
}
