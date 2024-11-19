<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use ReflectionProperty;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function array_map;
use function implode;
use function is_string;
use function mb_strlen;
use function mb_substr;

readonly class NamespaceFactory
{
    private string $namespace;

    public function __construct(private RedisAdapter $cachePool)
    {
        $redisNamespaceProperty = new ReflectionProperty(
            AbstractAdapter::class,
            'namespace',
        );

        /** @noinspection PhpExpressionResultUnusedInspection */
        $redisNamespaceProperty->setAccessible(true);

        $namespace = $redisNamespaceProperty->getValue($this->cachePool);

        $this->namespace = is_string($namespace) ? $namespace : '';
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function createKey(string ...$key): string
    {
        return $this->namespace . implode('_', $key);
    }

    public function removeNamespaceFromKey(string $key): string
    {
        if ($this->namespace === '') {
            return $key;
        }

        return mb_substr(
            $key,
            mb_strlen($this->namespace),
        );
    }

    /**
     * @param string[] $keys
     *
     * @return string[]
     */
    public function removeNamespaceFromKeys(array $keys): array
    {
        return array_map(
            fn (string $key) => $this->removeNamespaceFromKey(
                $key,
            ),
            $keys,
        );
    }
}
