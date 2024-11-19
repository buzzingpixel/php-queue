<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use BuzzingPixel\Queue\Http\ResponseType;
use BuzzingPixel\Queue\Http\ResponseTypeFactory;
use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class GetCompletedAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::GET,
            $routePrefix . '/completed',
            self::class,
        );
    }

    public function __construct(
        private RespondWithHtml $respondWithHtml,
        private RespondWithJson $respondWithJson,
        private ResponseTypeFactory $responseTypeFactory,
        private CompletedItemsFactory $completedItemsFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $result = $this->completedItemsFactory->createFromRequest(
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
