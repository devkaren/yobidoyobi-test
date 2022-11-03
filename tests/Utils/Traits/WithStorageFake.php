<?php

namespace Tests\Utils\Traits;

use Illuminate\Support\Facades\Storage;

trait WithStorageFake
{
    private function setUpStorageFake(): void
    {
        Storage::fake();
    }

    private function tearDownStorageFake(): void
    {
        //
    }
}
