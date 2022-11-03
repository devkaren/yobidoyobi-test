<?php

namespace Infrastructure\View\Components\Email;

use Illuminate\View\Component;

final class Title extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.email.title');
    }
}
