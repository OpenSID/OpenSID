<?php

declare(strict_types=1);

namespace Yajra\DataTables\Html\Enums;

use Illuminate\Support\Str;

enum LayoutPosition: string
{
    case Top = 'top';
    case TopStart = 'topStart';
    case TopEnd = 'topEnd';
    case Bottom = 'bottom';
    case BottomStart = 'bottomStart';
    case BottomEnd = 'bottomEnd';

    public function withOrder(?int $order): string
    {
        if ($order && $order > 0) {
            $parts = Str::of($this->value)->ucsplit();

            return $parts->shift().$order.$parts->first();
        }

        return $this->value;
    }
}
