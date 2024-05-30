<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\QueueNameWithItemsCollection;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        QueueNameWithItemsCollection $queues,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write((string) json_encode(
            $queues->asArray(),
        ));

        return $response->withHeader('Content-type', 'application/json');
    }
}
