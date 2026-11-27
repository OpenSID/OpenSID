<?php

namespace Yajra\DataTables\Html\Editor\Fields;

class TextArea extends Field
{
    protected string $type = 'textarea';

    public function rows(int $value): static
    {
        return $this->attr('rows', $value);
    }

    public function cols(int $value): static
    {
        return $this->attr('cols', $value);
    }
}
