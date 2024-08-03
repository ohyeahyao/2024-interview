<?php

declare(strict_types=1);
namespace Modules\Order\UseCases;

use Modules\Order\ConversionStrategies\CurrencyStrategyFactory;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Entities\Order;

final class CurrencyConverter implements CurrencyConverterInterface
{
    private CurrencyStrategyFactory $factory;

    public function __construct(CurrencyStrategyFactory $factory)
    {
        $this->factory = $factory;
    }

    public function convert(array $data): Order
    {
        $order = Order::fromArray($data);
        if ($order->getCurrency() !== 'TWD') {
            $strategy       = $this->factory->create($order->getCurrency());
            $convertedPrice = $strategy->convert($order->getPrice());
            $convertedOrder = new Order(
                $order->getId(),
                $order->getName(),
                $order->getAddress(),
                $convertedPrice,
                'TWD'
            );

            return $convertedOrder;
        }

        return $order;
    }
}
