<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class SplitButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $judul,
        public string $icon = 'fa fa-plus',
        public string $type = 'btn-success',
        public array $list = []
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract|Closure|string
    {
        return View::make('admin.layouts.components.buttons.split');
    }
}
