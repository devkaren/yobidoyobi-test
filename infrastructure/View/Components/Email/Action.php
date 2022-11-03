<?php

namespace Infrastructure\View\Components\Email;

use Illuminate\View\Component;

final class Action extends Component
{
    public function __construct(
        public readonly string $url,
    ) {
        //
    }

    public function render()
    {
        return view('components.email.action');
    }
}
