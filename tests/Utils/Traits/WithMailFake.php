<?php

namespace Tests\Utils\Traits;

use Illuminate\Support\Facades\Mail;

trait WithMailFake
{
    private function setUpMailFake(): void
    {
        Mail::fake();
    }

    private function tearDownMailFake(): void
    {
        //
    }
}
