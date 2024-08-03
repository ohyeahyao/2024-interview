<?php

declare(strict_types=1);

use App\Constants\RouteNames;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class CheckAndTransformOrderControllerTest extends TestCase
{
    public static function invalidDataProvider(): iterable
    {
        return [
            'ID missing' => [
                [
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
                ['id'],
            ],
            'Name missing' => [
                [
                    'id'      => 'A0000001',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
                ['name'],
            ],
            'City missing' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
                ['address.city'],
            ],
            'District missing' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'   => 'taipei-city',
                        'street' => 'fuxing-south-road',
                    ],
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
                ['address.district'],
            ],
            'Street missing' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                    ],
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
                ['address.street'],
            ],
            'Price missing' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'currency' => 'TWD',
                ],
                ['price'],
            ],
            'Currency missing' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price' => 2050,
                ],
                ['currency'],
            ],
            'Price is not numeric' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => '2050xx',
                    'currency' => 'TWD',
                ],
                ['price'],
            ],
        ];
    }
    
    #[DataProvider('invalidDataProvider')]
    public function testInvalidData($payload, $expectedErrors): void
    {
        $response = $this->json('post', route(RouteNames::ORDER_CHECK_AND_TRANSFORM), $payload);
        $response->assertStatus(422)->assertJsonValidationErrors($expectedErrors);
    }

    public static function validDataProvider(): iterable
    {
        return [
            'TWD' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
                [
                    'price'    => 2050,
                    'currency' => 'TWD',
                ],
            ],
            'USD' => [
                [
                    'id'      => 'A0000001',
                    'name'    => 'Melody Holiday Inn',
                    'address' => [
                        'city'     => 'taipei-city',
                        'district' => 'da-an-district',
                        'street'   => 'fuxing-south-road',
                    ],
                    'price'    => 1000,
                    'currency' => 'USD',
                ],
                [
                    'price'    => 31000,
                    'currency' => 'TWD',
                ],
            ],
        ];
    }
    
    #[DataProvider('validDataProvider')]
    public function testValidData($payload, $expected): void
    {
        $response = $this->json('post', route(RouteNames::ORDER_CHECK_AND_TRANSFORM), $payload);
        $response->assertStatus(200)
        ->assertJsonStructure(
            [
                'id',
                'name',
                'address' => [
                    'city',
                    'district',
                    'street',
                ],
                'price',
                'currency',
            ]
        )->assertJson($expected);
    }
}
