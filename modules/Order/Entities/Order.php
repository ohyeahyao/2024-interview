<?php

declare(strict_types=1);
namespace Modules\Order\Entities;

use Modules\Order\ValueObjects\Address;

class Order
{
    private $id;
    private $name;
    private Address $address;
    private $price;
    private $currency;

    public function __construct(
        string $id,
        string $name,
        Address $address,
        float $price,
        string $currency
    ) {
        $this->id       = $id;
        $this->name     = $name;
        $this->address  = $address;
        $this->price    = $price;
        $this->currency = $currency;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            Address::fromArray($data['address']),
            $data['price'],
            $data['currency']
        );
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'address'  => $this->address->toArray(),
            'price'    => $this->price,
            'currency' => $this->currency,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
