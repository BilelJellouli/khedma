<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

abstract class TestCase extends BaseTestCase
{
    final protected const string DEFAULT_PASSWORD = 'P4sSWord!!';

    protected function assertRouteMiddleware(string $name, ...$middlewares): void
    {
        if (class_exists($name)) {
            $route = Route::getRoutes()->getByAction($name);
        } else {
            $route = Route::getRoutes()->getByName($name);
        }

        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Route %s not found', $name));
        }

        $routeMiddlewares = $route->gatherMiddleware() ?? [];

        $middlewares = Collection::make(Arr::flatten($middlewares))
            ->sort()
            ->values()
            ->toArray();

        sort($routeMiddlewares);

        $this->assertSame($middlewares, $routeMiddlewares, 'Route middlewares are not the same');
    }
}
