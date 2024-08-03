<?php

declare(strict_types=1);

namespace Modules\Order\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Order\Enums\Currency;

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
