<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Routes;

enum RequestMethod
{
    case CONNECT;
    case DELETE;
    case GET;
    case HEAD;
    case OPTIONS;
    case PATCH;
    case POST;
    case PUT;
    case TRACE;
}
