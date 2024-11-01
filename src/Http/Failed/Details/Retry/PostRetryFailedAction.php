<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details\Retry;

use BuzzingPixel\Queue\Http\NotFound\RespondWith404;
use BuzzingPixel\Queue\Http\ResponseType;
use BuzzingPixel\Queue\Http\ResponseTypeFactory;
use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\QueueHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_string;

readonly class PostRetryFailedAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::POST,
            $routePrefix . '/failed/details/{key}/retry',
            self::class,
        );
    }

    public function __construct(
        private QueueHandler $queueHandler,
        private RespondWith404 $respondWith404,
        private RespondWithHtml $respondWithHtml,
        private RespondWithJson $respondWithJson,
        private ResponseTypeFactory $responseTypeFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $key = $request->getAttribute('key');
        assert(is_string($key));

        $failedItemResult = $this->queueHandler->findFailedItemByKey($key);

        if (! $failedItemResult->hasResult) {
            return $this->respondWith404->respond(
                $request,
                $response,
            );
        }

        $retryResult = $this->queueHandler->retryFailedItemByKey($key);

        return match ($this->responseTypeFactory->check($request)) {
            ResponseType::JSON => $this->respondWithJson->respond(
                $retryResult,
                $response,
            ),
            ResponseType::HTML => $this->respondWithHtml->respond(
                $key,
                $retryResult,
                $response,
            ),
        };
    }
}
