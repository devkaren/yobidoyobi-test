<?php

namespace Tests\Infrastructure\Eloquent;

use SplFileInfo;
use ReflectionClass;
use ReflectionException;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class EnforceMorphMapTest extends TestCase
{
    private function getApplicationModels(): Collection
    {
        $path = base_path('infrastructure/Eloquent/Models');

        return collect(File::allFiles($path))
            ->map(static function (SplFileInfo $a) use ($path): string {
                return Str::of($a->getRealPath())
                    ->remove($path)
                    ->replace('/', '\\')
                    ->remove('.php')
                    ->prepend('Infrastructure\\Eloquent\\Models')
                    ->__toString();
            })
            ->filter(static function (string $class): bool {
                try {
                    $ref = new ReflectionClass($class);

                    return !$ref->isTrait() && $ref->newInstanceWithoutConstructor() instanceof Model;
                } catch (ReflectionException) {
                    return false;
                }
            })
            ->values();
    }

    public function testModelsMorphMapEnforced(): void
    {
        foreach ($this->getApplicationModels() as $class) {
            $this->assertNotSame(
                (new $class())->getMorphClass(),
                $class,
                "{$class} is not strictly morphed!"
            );
        }
    }
}
