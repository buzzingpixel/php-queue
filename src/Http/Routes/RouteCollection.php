<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Routes;

use function array_filter;
use function array_map;
use function array_values;

readonly class RouteCollection
{
    /** @var Route[] */
    public array $routes;

    /** @param Route[] $routes */
    public function __construct(array $routes)
    {
        $this->routes = array_values(array_map(
            static fn (Route $r) => $r,
            $routes,
        ));
    }

    /** @return array<mixed> */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->routes);
    }

    /** @return array<array-key, array<string>> */
    public function asArray(): array
    {
        /** @phpstan-ignore-next-line */
        return $this->map(static fn (Route $r) => $r->asArray());
    }

    public function filter(callable $callback): self
    {
        return new self(array_filter(
            $this->routes,
            $callback,
        ));
    }

    /** @param class-string $className */
    public function pluckClassName(string $className): Route
    {
        return $this->filter(
            static fn (Route $route) => $route->class === $className,
        )->first();
    }

    public function first(): Route
    {
        return $this->routes[0];
    }
}
