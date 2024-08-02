<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\Currency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCurrencyRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validCurrencies = array_column(Currency::cases(), 'value');

        if (! \in_array($value, $validCurrencies, true)) {
            $fail('Currency format is wrong');
        }
    }
}
