<?php

declare(strict_types=1);

use Modules\Order\Entities\Order;
use Modules\Order\ValueObjects\Address;
use Tests\TestCase;

final class OrderTest extends TestCase
{
    public function testInit(): void
    {
        $address = $this->createMock(Address::class);
        $address->method('getCity')->willReturn('taipei-city');
        $address->method('getDistrict')->willReturn('da-an-district');
        $address->method('getStreet')->willReturn('fuxing-south-road');
        
        $order = new Order('A0000001', 'Melody Holiday Inn', $address, 2050.0, 'TWD');

        self::assertSame('A0000001', $order->getId());
        self::assertSame('Melody Holiday Inn', $order->getName());
        self::assertSame($address, $order->getAddress());
        self::assertSame(2050.0, $order->getPrice());
        self::assertSame('TWD', $order->getCurrency());
    }

    public function testFromArray(): void
    {
        $data = [
            'id'      => 'A0000001',
            'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 2050.0,
            'currency' => 'TWD',
        ];

        $order   = Order::fromArray($data);
        $address = $order->getAddress();

        self::assertSame('A0000001', $order->getId());
        self::assertSame('Melody Holiday Inn', $order->getName());
        self::assertSame('taipei-city', $address->getCity());
        self::assertSame('da-an-district', $address->getDistrict());
        self::assertSame('fuxing-south-road', $address->getStreet());
        self::assertSame(2050.0, $order->getPrice());
        self::assertSame('TWD', $order->getCurrency());
    }

    public function testToArray(): void
    {
        // Mock Address object
        $address = $this->createMock(Address::class);
        $address->method('toArray')->willReturn([
            'city'     => 'taipei-city',
            'district' => 'da-an-district',
            'street'   => 'fuxing-south-road',
        ]);

        $order = new Order(
            'A0000001',
            'Melody Holiday Inn',
            $address,
            2050.0,
            'TWD'
        );

        $expectedArray = [
            'id'      => 'A0000001',
            'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 2050.0,
            'currency' => 'TWD',
        ];

        self::assertSame($expectedArray, $order->toArray());
    }
}
