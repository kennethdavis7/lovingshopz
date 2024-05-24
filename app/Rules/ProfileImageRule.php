<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfileImageRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_object($value)) {
            $validate_extension = in_array($value->extension(), ['jpg', 'jpeg', 'png', 'webp']);
            if (!$validate_extension) {
                $fail('The file extension must be jpg, jpeg, or png');
            }
            if ($value->getSize() / 1000 > 100) {
                $fail('The file must be below 100 KB');
            }
        }
    }
}
