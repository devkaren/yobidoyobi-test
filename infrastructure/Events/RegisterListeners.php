<?php

namespace Infrastructure\Events;

use Illuminate\Support\Facades\Event;

trait RegisterListeners
{
    protected function registerListeners(): void
    {
        foreach ($this->listeners as $event => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    Event::listen($event, $listener);
                }
            } else {
                Event::listen($event, $listeners);
            }
        }
    }
}
