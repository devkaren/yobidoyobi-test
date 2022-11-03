<?php

namespace Modules\OAuth\Utils;

use Psr\Http\Message\ServerRequestInterface;

final class RequestMeta
{
    public static function getUserAgent(ServerRequestInterface $request): ?string
    {
        $header = $request->getHeaderLine('user-agent');

        if (!$header) {
            return null;
        }

        return substr($header, 0, 255);
    }

    public static function getRemoteAddr(ServerRequestInterface $request): string
    {
        return $request->getServerParams()['REMOTE_ADDR'];
    }
}
