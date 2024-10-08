<?php

declare(strict_types=1);

namespace Tests\Unit\UserCases;

use Modules\Order\CurrencyConverterStrategyInterface;
use Modules\Order\CurrencyStrategyFactoryInterface;
use Modules\Order\Exceptions\CurrencyConvertDataInvalidException;
use Modules\Order\OrderFormatValidatorInterface;
use Modules\Order\UseCases\CurrencyConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(CurrencyConverter::class)]
final class CurrencyConverterTest extends TestCase
{
    public function testConvertAndDontCallFactory(): void
    {
        $data = [
            'id'      => 'A0000001',
            'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 1050.0,
            'currency' => 'TWD',
        ];
        $mockFactory = $this->createMock(CurrencyStrategyFactoryInterface::class);
        $mockFactory->expects(self::never())->method(self::anything());

        $mockValidator = $this->createMock(OrderFormatValidatorInterface::class);
        $mockValidator->expects(self::once())
             ->method('exec')
             ->willReturn([]);

        $converter = new CurrencyConverter($mockFactory, $mockValidator);   
        self::assertSame($data, $converter->convert($data)->toArray());
    }

    public function testUsdToTwd(): void
    {
        $data = [
            'id'      => 'A0000001',
            'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 1000.0, 
            'currency' => 'USD',
        ];

        $expectedData = [
            'id'      => 'A0000001',
            'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 31000.0, 
            'currency' => 'TWD',
        ];
        
        $mockStrategy = $this->createMock(CurrencyConverterStrategyInterface::class);
        $mockStrategy->expects(self::once())
            ->method('convert')
            ->with(1000.0)
            ->willReturn(31000.0);

        $mockFactory = $this->createMock(CurrencyStrategyFactoryInterface::class);
        $mockFactory->expects(self::once())
             ->method('create')
             ->willReturn($mockStrategy);
        
        $mockValidator = $this->createMock(OrderFormatValidatorInterface::class);
        $mockValidator->expects(self::once())
             ->method('exec')
             ->willReturn([]);

        $converter = new CurrencyConverter($mockFactory, $mockValidator);   
        self::assertSame($expectedData, $converter->convert($data)->toArray());
    }

    public static function CurrencyConvertDataInvalidExceptionProvider(): iterable
    {
        // throw CurrencyConvertDataInvalidException
        return [
            '貨幣格式若非 TWD 或 USD' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 1000.0, 
                    'currency' => 'JPY',
                ], 
                ['currency'],
            ],
            '訂單金額超過 2000' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 5000.0, 
                    'currency' => 'TWD',
                ], 
                ['price'],
            ],
            '訂單金額超過 2000' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 5000.0, 
                    'currency' => 'TWD',
                ], 
                ['price'],
            ],
        ];
    }

    #[DataProvider('CurrencyConvertDataInvalidExceptionProvider')]
    public function testCurrencyConvertDataInvalidException($data, $expectedErrors): void
    {
        $this->expectException(CurrencyConvertDataInvalidException::class);
        $expectedErrors = [
            'currency' => ['Currency format is wrong'],
            'name'     => ['The name contains non-English characters', 'Each word in name must be capitalized'],
        ];

        $mockFactory = $this->createMock(CurrencyStrategyFactoryInterface::class);
        $mockFactory->expects(self::never())->method(self::anything());

        $mockValidator = $this->createMock(OrderFormatValidatorInterface::class);
        $mockValidator->expects(self::once())
             ->method('exec')
             ->willReturn($expectedErrors);

        $converter = new CurrencyConverter($mockFactory, $mockValidator);   

        try {
            $converter->convert($data);
        } catch (CurrencyConvertDataInvalidException $e) {
            self::assertSame($expectedErrors, $e->getErrors());
            throw $e;
        }
    }
}
