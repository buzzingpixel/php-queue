<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details;

use BuzzingPixel\Queue\QueueItemFailed;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        QueueItemFailed $queueItem,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write((string) json_encode(
            $queueItem->asArray(),
        ));

        return $response->withHeader(
            'Content-type',
            'application/json',
        );
    }
}
