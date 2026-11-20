<?php

namespace Yajra\DataTables\Html\Editor\Fields;

class Time extends DateTime
{
    /**
     * Make a new instance of a field.
     */
    public static function make(array|string $name, string $label = ''): static
    {
        $field = parent::make($name, $label);

        return $field->format('hh:mm a');
    }

    /**
     * Set format to military time (24 hrs).
     *
     * @return $this
     */
    public function military(): static
    {
        return $this->format('HH:mm');
    }
}
