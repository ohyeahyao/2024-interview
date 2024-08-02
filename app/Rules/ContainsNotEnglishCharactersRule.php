<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class ContainsNotEnglishCharactersRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! preg_match('/^[A-Za-z ]+$/', $value)) {
            $fail(ucfirst($attribute) . ' contains non-English characters');
        }
    }
}
