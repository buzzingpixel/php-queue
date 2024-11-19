<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed\Details;

use BuzzingPixel\Queue\QueueItemCompleted;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        QueueItemCompleted $queueItem,
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
