<?php

namespace Yajra\DataTables\Html;

use Illuminate\Support\Arr;

trait HasTable
{
    protected ?string $theadClass = null;

    /**
     * Get table computed table attributes.
     */
    public function getTableAttributes(): array
    {
        return $this->tableAttributes;
    }

    /**
     * Sets HTML table "id" attribute.
     *
     * @return $this
     */
    public function setTableId(string $id): static
    {
        return $this->setTableAttribute('id', $id);
    }

    /**
     * Sets HTML table attribute(s).
     *
     * @return $this
     */
    public function setTableAttribute(array|string $attribute, ?string $value = null): static
    {
        if (is_array($attribute)) {
            return $this->setTableAttributes($attribute);
        }

        $this->tableAttributes[$attribute] = $value;

        return $this;
    }

    /**
     * Sets multiple HTML table attributes at once.
     *
     * @return $this
     */
    public function setTableAttributes(array $attributes): static
    {
        foreach ($attributes as $attribute => $value) {
            $this->tableAttributes[$attribute] = $value;
        }

        return $this;
    }

    /**
     * Get HTML table "id" attribute.
     */
    public function getTableId(): string
    {
        return $this->getTableAttribute('id');
    }

    /**
     * Retrieves HTML table attribute value.
     */
    public function getTableAttribute(string $attribute): string
    {
        return $this->tableAttributes[$attribute] ?? '';
    }

    /**
     * Add class names to the "class" attribute of HTML table.
     *
     * @return $this
     */
    public function addTableClass(array|string $class): static
    {
        $class = is_array($class) ? implode(' ', $class) : $class;
        $currentClass = Arr::get(array_change_key_case($this->tableAttributes), 'class');

        $classes = preg_split('#\s+#', $currentClass.' '.$class, -1, PREG_SPLIT_NO_EMPTY);
        $class = implode(' ', array_unique((array) $classes));

        return $this->setTableAttribute('class', $class);
    }

    /**
     * Set table > thead class names.
     *
     * @return $this
     */
    public function setTableHeadClass(string $class): static
    {
        $this->theadClass = " class=\"$class\"";

        return $this;
    }

    public function getTheadClass(): ?string
    {
        return $this->theadClass;
    }

    /**
     * Remove class names from the "class" attribute of HTML table.
     *
     * @return $this
     */
    public function removeTableClass(array|string $class): static
    {
        $class = is_array($class) ? implode(' ', $class) : $class;
        $currentClass = $this->getTableAttribute('class');

        $classes = array_diff(
            (array) preg_split('#\s+#', (string) $currentClass, -1, PREG_SPLIT_NO_EMPTY),
            (array) preg_split('#\s+#', $class, -1, PREG_SPLIT_NO_EMPTY)
        );
        $class = implode(' ', array_unique($classes));

        return $this->setTableAttribute('class', $class);
    }

    /**
     * Compile table headers and to support responsive extension.
     */
    protected function compileTableHeaders(): array
    {
        $th = [];

        $this->collection->each(function (Column $column) use (&$th) {
            $only = Arr::only(
                $column->toArray(),
                ['class', 'id', 'title', 'width', 'style', 'data-class', 'data-hide']
            );

            $attributes = array_merge(
                $only,
                $column->attributes,
                isset($column['titleAttr']) ? ['title' => $column['titleAttr']] : []
            );

            $thAttr = $this->html->attributes($attributes);
            $th[] = '<th'.$thAttr.'>'.$column['title'].'</th>';
        });

        return $th;
    }

    /**
     * Compile table search headers.
     */
    protected function compileTableSearchHeaders(): array
    {
        $search = [];

        $this->collection->each(function (Column $column) use (&$search) {
            $search[] = $column['searchable'] ? '<th>'.($column['search'] ?? '').'</th>' : '<th></th>';
        });

        return $search;
    }

    /**
     * Compile table footer contents.
     */
    protected function compileTableFooter(): array
    {
        $footer = [];

        $this->collection->each(function (Column $column) use (&$footer) {
            if (is_array($column->footer)) {
                $footerAttr = $this->html->attributes(
                    Arr::only($column->footer, ['class', 'id', 'title', 'width', 'style', 'data-class', 'data-hide'])
                );

                $title = $column->footer['title'] ?? '';

                $footer[] = '<th '.$footerAttr.'>'.$title.'</th>';
            } else {
                $footer[] = '<th>'.$column->footer.'</th>';
            }
        });

        return $footer;
    }
}
