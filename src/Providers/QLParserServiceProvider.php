<?php

declare(strict_types=1);

namespace QLParser\Providers;

use Illuminate\Support\ServiceProvider;
use QLParser\ConditionEvaluator\ConditionEvaluator;
use QLParser\ConditionEvaluator\ConditionEvaluatorInterface;
use QLParser\Operation\OperationContext;
use QLParser\Operation\OperationStrategyFactory;
use QLParser\QLParser;
use QLParser\QueryEvaluator\QueryEvaluator;
use QLParser\QueryEvaluator\QueryEvaluatorInterface;

class QLParserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OperationStrategyFactory::class, fn() => new OperationStrategyFactory());

        $this->app->singleton(OperationContext::class, function ($app) {
            return new OperationContext($app[OperationStrategyFactory::class]);
        });

        $this->app->bind(ConditionEvaluatorInterface::class, fn() => new ConditionEvaluator());

        $this->app->bind(QueryEvaluatorInterface::class, function ($app) {
            return new QueryEvaluator($app[ConditionEvaluatorInterface::class]);
        });

        $this->app->singleton(QLParser::class, function ($app) {
            return new QLParser($app[QueryEvaluatorInterface::class], $app[OperationContext::class]);
        });
    }

    public function boot(): void
    {
        // Boot any additional resources here (routes, views, etc.)
    }
}
