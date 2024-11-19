<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed;

use BuzzingPixel\Queue\Http\ResponseType;
use BuzzingPixel\Queue\Http\ResponseTypeFactory;
use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class GetFailedAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::GET,
            $routePrefix . '/failed',
            self::class,
        );
    }

    public function __construct(
        private RespondWithHtml $respondWithHtml,
        private RespondWithJson $respondWithJson,
        private FailedItemsFactory $failedItemsFactory,
        private ResponseTypeFactory $responseTypeFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $result = $this->failedItemsFactory->createFromRequest(
            $request,
        );

        return match ($this->responseTypeFactory->check($request)) {
            ResponseType::JSON => $this->respondWithJson->respond(
                $result,
                $response,
            ),
            ResponseType::HTML => $this->respondWithHtml->respond(
                $result,
                $request,
                $response,
            ),
        };
    }
}
