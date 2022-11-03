<?php

namespace Infrastructure\Http;

use Infrastructure\Http\Responses\Macros\Id;
use Infrastructure\Http\Responses\Macros\Errors;
use Infrastructure\Http\Responses\Macros\Schema;
use Infrastructure\Http\Responses\Macros\Message;
use Infrastructure\Http\Responses\Macros\NotFound;
use Infrastructure\Http\Responses\Macros\Forbidden;
use Infrastructure\Http\Responses\Macros\ErrorMessage;
use Infrastructure\Http\Responses\Macros\Unauthenticated;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Schema::bind();
        Message::bind();
        Id::bind();
        Errors::bind();
        ErrorMessage::bind();
        Unauthenticated::bind();
        Forbidden::bind();
        NotFound::bind();
    }
}
