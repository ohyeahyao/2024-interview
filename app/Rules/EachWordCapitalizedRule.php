<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EachWordCapitalizedRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $words = explode(' ', $value);

        foreach ($words as $word) {
            if ($word !== '' && $word[0] !== strtoupper($word[0])) {
                $fail(ucfirst($attribute) . ' is not capitalized');

                return;
            }
        }
    }
}
