<?php

namespace Tests\Utils\Socialite\Expectations;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

final class GoogleUserFormTokenExpectation extends UserFormTokenExpectation
{
    public function andThrowInvalidTokenException(): void
    {
        $this->expectation->andReturnUsing(static function () {
            throw new ClientException('', new Request('POST', ''), new Response(401));
        });
    }
}
