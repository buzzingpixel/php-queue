<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        CompletedItemsResult $result,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write((string) json_encode(
            $result->filteredItems->asArray(),
        ));

        return $response->withHeader(
            'Content-type',
            'application/json',
        );
    }
}
