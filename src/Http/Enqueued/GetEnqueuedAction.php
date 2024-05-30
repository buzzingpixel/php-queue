<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\Http\ResponseType;
use BuzzingPixel\Queue\Http\ResponseTypeFactory;
use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\QueueHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class GetEnqueuedAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::GET,
            $routePrefix . '/enqueued',
            self::class,
        );
    }

    public function __construct(
        private QueueHandler $queueHandler,
        private RespondWithHtml $respondWithHtml,
        private RespondWithJson $respondWithJson,
        private ResponseTypeFactory $responseTypeFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $queueItems = $this->queueHandler->getEnqueuedItemsFromAllQueues();

        return match ($this->responseTypeFactory->check($request)) {
            ResponseType::JSON => $this->respondWithJson->respond(
                $queueItems,
                $response,
            ),
            ResponseType::HTML => $this->respondWithHtml->respond(
                $queueItems,
                $response,
            ),
        };
    }
}
