<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Rules\ValidCurrencyRule;
use Modules\Order\UseCases\CurrencyConverter;
use Modules\Order\Validators\OrderFormatValidator;
use Modules\Shared\Rules\AmountExceedsRule;
use Modules\Shared\Rules\ContainsNotEnglishCharactersRule;
use Modules\Shared\Rules\EachWordCapitalizedRule;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CurrencyConverterInterface::class, CurrencyConverter::class);
        
        $this->app->singleton(OrderFormatValidator::class, static function ($app)
        {
            return new OrderFormatValidator([
                'currency' => [
                    new ValidCurrencyRule(),
                ],
                'price' => [
                    new AmountExceedsRule(OrderFormatValidator::PRICE_LIMIT),
                ],
                'name' => [
                    new ContainsNotEnglishCharactersRule(),
                    new EachWordCapitalizedRule(),
                ],
            ]);
        });
    }

    public function boot(): void
    {
        //
    }
}