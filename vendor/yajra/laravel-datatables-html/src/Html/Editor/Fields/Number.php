<?php

namespace Yajra\DataTables\Html\Editor\Fields;

class Number extends Field
{
    public static function make(array|string $name, string $label = ''): static
    {
        return parent::make($name, $label)->attr('type', 'number');
    }
}
