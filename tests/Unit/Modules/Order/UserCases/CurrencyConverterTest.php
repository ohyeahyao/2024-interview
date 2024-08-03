<?php

declare(strict_types=1);

namespace Tests\Unit\UserCases;

use Modules\Order\ConversionStrategies\CurrencyStrategyFactory;
use Modules\Order\UseCases\CurrencyConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(CurrencyConverter::class)]
final class CurrencyConverterTest extends TestCase
{
    public function testTransform(): void
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
        $converter = new CurrencyConverter();
        
        self::assertSame($data, $converter->convert($data)->toArray());
    }
}
