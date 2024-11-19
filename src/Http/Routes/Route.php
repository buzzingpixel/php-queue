<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Routes;

readonly class Route
{
    /** @param class-string $class */
    public function __construct(
        public RequestMethod $requestMethod,
        public string $pattern,
        public string $class,
    ) {
    }

    /** @return string[] */
    public function asArray(): array
    {
        return [
            'requestMethod' => $this->requestMethod->name,
            'pattern' => $this->pattern,
            'class' => $this->class,
        ];
    }
}
