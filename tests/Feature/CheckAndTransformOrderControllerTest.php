<?php

declare(strict_types=1);

use App\Constants\RouteNames;
use Tests\TestCase;

final class CheckAndTransformOrderControllerTest extends TestCase
{
    public function testInvalidData(): void
    {
        // missing ID
        $payload = [
            // 'id'      => 'A0000001',
            // 'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 2050,
            'currency' => 'TWD',
        ];
        $response = $this->json('post', route(RouteNames::ORDER_CHECK_AND_TRANSFORM), $payload);
        $response->assertStatus(400);
    }

    public function testValidData(): void
    {
        $payload = [
            'id'      => 'A0000001',
            'name'    => 'Melody Holiday Inn',
            'address' => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road',
            ],
            'price'    => 2050,
            'currency' => 'TWD',
        ];
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
        );
    }
}
