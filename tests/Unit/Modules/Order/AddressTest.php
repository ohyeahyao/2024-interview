<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use Modules\Order\ValueObjects\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testInit(): void
    {
        $address = new Address('taipei-city', 'da-an-district', 'fuxing-south-road');

        self::assertSame('taipei-city', $address->getCity());
        self::assertSame('da-an-district', $address->getDistrict());
        self::assertSame('fuxing-south-road', $address->getStreet());
    }

    public function testFromArray(): void
    {
        $data = [
            'city'     => 'taipei-city',
            'district' => 'da-an-district',
            'street'   => 'fuxing-south-road',
        ];

        $address = Address::fromArray($data);

        self::assertSame('taipei-city', $address->getCity());
        self::assertSame('da-an-district', $address->getDistrict());
        self::assertSame('fuxing-south-road', $address->getStreet());
    }

    public function testToArray(): void
    {
        $address = new Address('taipei-city', 'da-an-district', 'fuxing-south-road');

        $expectedArray = [
            'city'     => 'taipei-city',
            'district' => 'da-an-district',
            'street'   => 'fuxing-south-road',
        ];

        self::assertSame($expectedArray, $address->toArray());
    }
}