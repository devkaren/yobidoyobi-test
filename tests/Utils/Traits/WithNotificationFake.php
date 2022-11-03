<?php

namespace Tests\Utils\Traits;

use Illuminate\Support\Facades\Notification;

trait WithNotificationFake
{
    private function setUpNotificationFake(): void
    {
        Notification::fake();
    }

    private function tearDownNotificationFake(): void
    {
        //
    }
}
