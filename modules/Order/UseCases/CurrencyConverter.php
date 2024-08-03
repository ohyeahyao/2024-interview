<?php

declare(strict_types=1);
namespace Modules\Order\UseCases;

use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Order\ConversionStrategies\CurrencyStrategyFactory;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\Currency;
use Modules\Order\Exceptions\CurrencyConvertDataInvalidException;
use Modules\Order\Rules\ValidCurrencyRule;
use Modules\Shared\Rules\AmountExceedsRule;

final class CurrencyConverter implements CurrencyConverterInterface
{
    public const PRICE_LIMIT = 2000;
    private CurrencyStrategyFactory $factory;
    /**
     * @var ValidationRule[]
     */
    private array $rules;

    public function __construct(CurrencyStrategyFactory $factory)
    {
        $this->factory = $factory;
        $this->rules   = [
            'currency' => new ValidCurrencyRule(),
            'price'    => new AmountExceedsRule(self::PRICE_LIMIT),
        ];
    }

    /**
     * @throws CurrencyConvertDataInvalidException
     *
     * @param  array $data
     * @return Order
     */
    public function convert(array $data): Order
    {
        $order  = Order::fromArray($data);
        $errors = $this->validate($order);
        if (! empty($errors)) {
            throw new CurrencyConvertDataInvalidException('Validated failed', $errors);
        }

        if ($order->getCurrency() !== Currency::TWD->value) {
            $strategy       = $this->factory->create($order->getCurrency());
            $convertedPrice = $strategy->convert($order->getPrice());
            $convertedOrder = new Order(
                $order->getId(),
                $order->getName(),
                $order->getAddress(),
                $convertedPrice,
                Currency::TWD->value
            );

            return $convertedOrder;
        }

        return $order;
    }

    private function validate(Order $order): array
    {
        $errors = [];
        foreach ($this->rules as $attribute => $rule) {
            $rule->validate(
                $attribute,
                $this->getOrderAttributeValue($order, $attribute),
                static function ($message) use (&$errors, $attribute): void
                {
                    $errors[$attribute][] = $message;
                }
            );
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
