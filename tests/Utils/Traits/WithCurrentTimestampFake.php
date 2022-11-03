<?php

namespace Tests\Utils\Traits;

use Illuminate\Support\Carbon;

trait WithCurrentTimestampFake
{
    final protected function fakeCurrentTimestamp(?Carbon $timestamp = null): Carbon
    {
        Carbon::setTestNow($now = $timestamp ?? Carbon::now());

        return $now;
    }
}
