<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details\Retry;

use BuzzingPixel\Queue\Http\Failed\Details\DetailsUrlFactory;
use BuzzingPixel\Queue\RetryFailedItemResult;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(private DetailsUrlFactory $detailsUrlFactory)
    {
    }

    public function respond(
        string $key,
        RetryFailedItemResult $result,
        ResponseInterface $response,
    ): ResponseInterface {
        return $response->withStatus(302)
            ->withHeader(
                'Location',
                $this->detailsUrlFactory->make($key),
            );
    }
}
