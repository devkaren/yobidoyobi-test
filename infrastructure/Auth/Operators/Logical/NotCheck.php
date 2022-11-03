<?php

namespace Infrastructure\Auth\Operators\Logical;

use Illuminate\Auth\Access\Response;
use Infrastructure\Auth\CheckMessage;
use Infrastructure\Auth\AbstractCheck;

final class NotCheck extends AbstractCheck
{
    /**
     * @var AbstractCheck[]
     */
    private readonly array $assertions;

    public function __construct(
        AbstractCheck ...$assertions,
    ) {
        $this->assertions = $assertions;
    }

    public function execute(): Response
    {
        foreach ($this->assertions as $assertion) {
            $response = $this->check($assertion);

            if ($response->allowed()) {
                return Response::deny(
                    CheckMessage::getMessageAsCode($assertion, false)
                );
            }
        }

        return Response::allow();
    }
}
