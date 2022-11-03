<?php

namespace Infrastructure\Auth;

use Illuminate\Auth\Access\Response;

trait CheckProxy
{
    final protected function check(AbstractCheck $assertion): Response
    {
        $response = $assertion->execute();

        return new Response(
            $response->allowed(),
            $response->message()
                ? $response->message()
                : CheckMessage::getMessageAsCode($assertion),
        );
    }
}
