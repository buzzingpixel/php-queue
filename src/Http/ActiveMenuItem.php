<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

enum ActiveMenuItem
{
    case ENQUEUED;
    case COMPLETED;
}
