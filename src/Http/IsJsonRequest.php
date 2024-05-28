<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

use function explode;
use function in_array;

readonly class IsJsonRequest
{
    public function checkHttpAcceptString(string $httpAccept): bool
    {
        $httpAccept = $httpAccept !== '' ? $httpAccept : 'text/html';

        $acceptArray = explode(',', $httpAccept);

        return in_array(
            'application/json',
            $acceptArray,
            true,
        );
    }
}
