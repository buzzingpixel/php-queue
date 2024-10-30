<?php

declare(strict_types=1);

namespace App;

use DI\Definition\Helper\AutowireDefinitionHelper;
use DI\Definition\Reference;
use function DI\autowire;
use function DI\get;

class ContainerBindings
{
    /** @var array<string, string|callable|object> */
    private array $bindings = [];

    /** @return array<string, string|callable|object> */
    public function bindings(): array
    {
        return $this->bindings;
    }

    /**
     * @param callable|object|AutowireDefinitionHelper|Reference $to
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function addBinding(
        string $from,
        callable|object $to,
    ): self {
        $this->bindings[$from] = $to;

        return $this;
    }

    /** @param array<string, callable|object> $bindings */
    public function addBindings(array $bindings): self
    {
        foreach ($bindings as $from => $to) {
            $this->addBinding($from, $to);
        }

        return $this;
    }

    public function autowire(string|null $to = null): AutowireDefinitionHelper
    {
        return autowire($to);
    }

    public function resolveFromContainer(string $to): Reference
    {
        return get($to);
    }
}
