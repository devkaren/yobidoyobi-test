<?php

namespace Infrastructure\Http\Resources;

use Infrastructure\Http\Resources\Traits\PreparesPaginatedResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as BaseAnonymousResourceCollection;

class AnonymousResourceCollection extends BaseAnonymousResourceCollection
{
    use PreparesPaginatedResponse;
}
