<?php

declare(strict_types=1);

namespace Modules\Order\ValueObjects;

class Address
{
    private $city;
    private $district;
    private $street;

    public function __construct(string $city, string $district, string $street)
    {
        $this->city     = $city;
        $this->district = $district;
        $this->street   = $street;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['city'],
            $data['district'],
            $data['street']
        );
    }

    public function toArray(): array
    {
        return [
            'city'     => $this->city,
            'district' => $this->district,
            'street'   => $this->street,
        ];
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function getStreet(): string
    {
        return $this->street;
    }
}
