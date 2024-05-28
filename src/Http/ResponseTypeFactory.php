<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

use Psr\Http\Message\ServerRequestInterface;

readonly class ResponseTypeFactory
{
    public function __construct(private IsJsonRequest $isJsonRequest)
    {
    }

    public function check(ServerRequestInterface $request): ResponseType
    {
        $jsonQueryString = $request->getQueryParams()['json'] ?? null;

        if (
            $this->isJsonRequest->checkHttpAcceptString(
                $request->getHeader('Accept')[0] ?? '',
            ) ||
            $jsonQueryString !== null
        ) {
            return ResponseType::JSON;
        }

        return ResponseType::HTML;
    }
}
