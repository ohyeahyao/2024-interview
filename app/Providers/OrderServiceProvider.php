<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Order\ConversionStrategies\CurrencyStrategyFactory;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\CurrencyStrategyFactoryInterface;
use Modules\Order\OrderFormatValidatorInterface;
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
        $this->app->singleton(CurrencyConverterInterface::class, static function($app)
        {
            return new CurrencyConverter(
                app(CurrencyStrategyFactoryInterface::class),
                app(OrderFormatValidatorInterface::class)
            );
        });

        $this->app->singleton(CurrencyStrategyFactoryInterface::class, CurrencyStrategyFactory::class);
        
        $this->app->singleton(OrderFormatValidatorInterface::class, static function ($app)
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