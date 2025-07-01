<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class HapusButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $url,
        public bool $confirmDelete = false,
        public bool $selectData = false,
        public string $target = 'confirm-delete',
        public string $judul = 'Hapus'
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract|Closure|string
    {
        return View::make('admin.layouts.components.buttons.hapus');
    }
}
