<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\NotFound;

use BuzzingPixel\Queue\Http\ResponseType;
use BuzzingPixel\Queue\Http\ResponseTypeFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class RespondWith404
{
    public function __construct(
        private RespondWith404Json $respondWithJson,
        private RespondWith404Html $respondWithHtml,
        private ResponseTypeFactory $responseTypeFactory,
    ) {
    }

    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        return match ($this->responseTypeFactory->check($request)) {
            ResponseType::JSON => $this->respondWithJson->respond(
                $response,
            ),
            ResponseType::HTML => $this->respondWithHtml->respond(
                $response,
            ),
        };
    }
}
