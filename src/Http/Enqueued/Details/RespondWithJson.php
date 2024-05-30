<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued\Details;

use BuzzingPixel\Queue\QueueItemWithKey;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        QueueItemWithKey $queueItem,
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
