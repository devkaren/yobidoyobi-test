<?php

namespace Tests\Utils\Socialite\Expectations;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

final class FacebookUserFormTokenExpectation extends UserFormTokenExpectation
{
    public function andThrowInvalidTokenException(): void
    {
        $this->expectation->andReturnUsing(static function () {
            throw new ClientException('', new Request('POST', ''), new Response(400, body: json_encode([
                'error' => [
                    'code' => 190,
                    'message' => 'Invalid token',
                ],
            ])));
        });
    }
}
