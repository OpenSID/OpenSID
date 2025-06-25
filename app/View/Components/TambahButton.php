<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class TambahButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $url,
        public bool $modal = false,
        public string $modalTarget = 'modalBox',
        public string $judul = 'Tambah'
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract|Closure|string
    {
        return View::make('admin.layouts.components.buttons.tambah');
    }
}
