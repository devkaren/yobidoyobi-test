<?php

namespace Modules\HealthCheck\Checks;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Storage;
use Modules\HealthCheck\Exceptions\CheckFailedException;

final class StorageCheck extends AbstractCheck
{
    private const FILENAME = 'health_check';
    private const CONTENTS = '1';

    public function check(): void
    {
        try {
            Storage::put(self::FILENAME, self::CONTENTS);

            if (Storage::get(self::FILENAME) !== self::CONTENTS) {
                throw new Exception('Storage get error!');
            }

            Storage::delete(self::FILENAME);
        } catch (Throwable $e) {
            throw new CheckFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
