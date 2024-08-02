<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class AmountExceedsRule implements ValidationRule
{
    protected $limit;

    public function __construct($limit)
    {
        if (! is_numeric($limit)) {
            throw new InvalidArgumentException('Limit must be a numeric value.');
        }
        
        $this->limit = $limit;
    }
    
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value > $this->limit) {
            $fail("Price is over {$this->limit}.");
        }
    }
}
