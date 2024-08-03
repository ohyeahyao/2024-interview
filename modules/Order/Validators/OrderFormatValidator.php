<?php

declare(strict_types=1);

namespace Modules\Order\Validators;

use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Order\Entities\Order;

class OrderFormatValidator
{
    public const PRICE_LIMIT = 2000;
    /**
     * @var array<string, ValidationRule[]>
     */
    private array $validationRules;

    public function __construct(array $validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function exec(Order $order): array
    {
        $errors = [];
        foreach ($this->validationRules as $attribute => $rules) {
            foreach ($rules as $rule) {
                $rule->validate(
                    $attribute,
                    $this->getOrderAttributeValue($order, $attribute),
                    static function ($message) use (&$errors, $attribute): void
                    {
                        $errors[$attribute][] = $message;
                    }
                );
            }
        }
        
        return $errors;
    }

    private function getOrderAttributeValue(Order $order, string $attribute): mixed
    {
        switch ($attribute) {
            case 'currency':
                return $order->getCurrency();
            case 'price':
                return $order->getPrice();
            case 'name':
                return $order->getName();
            default:
                throw new \InvalidArgumentException("Attribute {$attribute} not recognized");
        }
    }
}