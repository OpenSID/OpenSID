<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class ImporEksporGrupButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $impor,
        public string $ekspor,
        public string $target
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract|Closure|string
    {
        return View::make('admin.layouts.components.tombol_impor_ekspor_grup');
    }
}
