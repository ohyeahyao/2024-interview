<?php

declare(strict_types=1);
namespace Modules\Order\UseCases;

use Modules\Order\ConversionStrategies\CurrencyStrategyFactory;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\Currency;
use Modules\Order\Exceptions\CurrencyConvertDataInvalidException;
use Modules\Order\Rules\ValidCurrencyRule;

final class CurrencyConverter implements CurrencyConverterInterface
{
    private CurrencyStrategyFactory $factory;

    public function __construct(CurrencyStrategyFactory $factory)
    {
        $this->factory = $factory;
    }

    private function validate(Order $order): void
    {
        $rule = new ValidCurrencyRule();
        $rule->validate(
            'currency',
            $order->getCurrency(),
            static function ($message): void
            {
                throw new CurrencyConvertDataInvalidException($message);
            }
        );
    }

    /**
     * @throws CurrencyConvertDataInvalidException
     *
     * @param  array $data
     * @return Order
     */
    public function convert(array $data): Order
    {
        $order = Order::fromArray($data);
        $this->validate($order);

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
}
