<?php

namespace Tests\Infrastructure\Http\Resources;

use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Infrastructure\Http\Resources\JsonResource;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class JsonResourceTest extends TestCase
{
    use WithFaker;

    public function testLengthAwarePaginatorResponse(): void
    {
        $collection = collect(array_fill(0, 1, []));

        $paginator = new LengthAwarePaginator(
            $collection->toArray(),
            $this->faker->randomDigitNotNull(),
            $this->faker->randomDigitNotNull(),
            $this->faker->randomDigitNotNull()
        );

        $resource = JsonResource::collection($paginator);
        $response = $resource->toResponse(request());

        $this->assertEquals($paginator->perPage(), $response->headers->get('x-pagination-per-page'));
        $this->assertEquals($paginator->lastPage(), $response->headers->get('x-pagination-last'));
        $this->assertEquals($paginator->total(), $response->headers->get('x-pagination-total'));
    }

    public function testCursorPaginatorResponse(): void
    {
        $collection = collect(array_fill(0, 1, []));

        $paginator = new CursorPaginator(
            $collection->toArray(),
            $this->faker->randomDigitNotNull(),
            new Cursor([]),
        );

        $resource = JsonResource::collection($paginator);
        $response = $resource->toResponse(request());

        $this->assertEquals($paginator->perPage(), $response->headers->get('x-pagination-per-page'));
        $this->assertEquals($paginator->previousCursor()?->encode(), $response->headers->get('x-pagination-previous'));
        $this->assertEquals($paginator->nextCursor()?->encode(), $response->headers->get('x-pagination-next'));
    }
}
