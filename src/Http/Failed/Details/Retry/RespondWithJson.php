<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details\Retry;

use BuzzingPixel\Queue\RetryFailedItemResult;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        RetryFailedItemResult $result,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write((string) json_encode(
            $result->asArray(),
        ));

        return $response->withHeader(
            'Content-type',
            'application/json',
        );
    }
}
