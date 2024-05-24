<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BelowStockRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $stock;

    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->stock != null && $this->stock < $value) {
            $fail('The min order must be below stock');
        }
    }
}
