<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class KembaliButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id = '',
        public string $onclick = '',
        public string $url = '',
        public string $judul = 'Kembali',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract|Closure|string
    {
        return View::make('admin.layouts.components.buttons.kembali');
    }
}
