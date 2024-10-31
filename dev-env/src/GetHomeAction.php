<?php


declare(strict_types=1);

namespace App;

use BuzzingPixel\Queue\QueueHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class GetHomeAction
{
    public function __construct(private QueueHandler $queueHandler)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        dd($this->queueHandler);
    }
}
