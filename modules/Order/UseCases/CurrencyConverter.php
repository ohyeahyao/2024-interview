<?php

declare(strict_types=1);
namespace Modules\Order\UseCases;

use Modules\Order\CurrencyConverterInterface;
use Modules\Order\CurrencyStrategyFactoryInterface;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\Currency;
use Modules\Order\Exceptions\CurrencyConvertDataInvalidException;
use Modules\Order\OrderFormatValidatorInterface;

final class CurrencyConverter implements CurrencyConverterInterface
{
    private CurrencyStrategyFactoryInterface $factory;
    private OrderFormatValidatorInterface $validator;
    
    public function __construct(
        CurrencyStrategyFactoryInterface $factory,
        OrderFormatValidatorInterface $validator
    ) {
        $this->factory   = $factory;
        $this->validator = $validator;
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
        $errors = $this->validator->exec($order);
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
}
