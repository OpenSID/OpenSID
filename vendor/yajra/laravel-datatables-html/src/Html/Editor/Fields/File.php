<?php

namespace Yajra\DataTables\Html\Editor\Fields;

/**
 * @see https://editor.datatables.net/reference/field/upload
 * @see https://editor.datatables.net/examples/advanced/upload.html
 * @see https://editor.datatables.net/examples/advanced/upload-many.html
 */
class File extends Field
{
    protected string $type = 'upload';

    /**
     * Editor instance variable name.
     */
    protected string $editor = 'editor';

    public static function make(array|string $name, string $label = ''): static
    {
        $field = parent::make($name, $label);

        return $field->displayFile()->clearText()->noImageText();
    }

    /**
     * @return $this
     */
    public function ajax(string $value): static
    {
        $this->attributes['ajax'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function ajaxData(string $value): static
    {
        $this->attributes['ajaxData'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function dragDrop(bool $value = true): static
    {
        $this->attributes['dragDrop'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function dragDropText(string $value): static
    {
        $this->attributes['dragDropText'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function fileReadText(string $value): static
    {
        $this->attributes['fileReadText'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function noFileText(string $value): static
    {
        $this->attributes['noFileText'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function processingText(string $value): static
    {
        $this->attributes['processingText'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function uploadText(string $value): static
    {
        $this->attributes['uploadText'] = $value;

        return $this;
    }

    /**
     * Set editor instance for file upload.
     *
     * @return $this
     */
    public function editor(string $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Display image upon upload.
     *
     * @return $this
     */
    public function displayImage(): static
    {
        // TODO: Use Laravel filesystem instead of hard coded storage path
        return $this->display(<<<'SCRIPT'
            function (file_id) { 
                return file_id ? '<img src="storage/' + file_id + '" alt=""/>' : null; 
            }
SCRIPT
        );
    }

    /**
     * @return $this
     */
    public function display(string $value): static
    {
        $this->attributes['display'] = $value;

        return $this;
    }

    /**
     * Display the file path.
     *
     * @return $this
     */
    public function displayFile(): static
    {
        return $this->display('function (file_id) { return file_id; }');
    }

    /**
     * @return $this
     */
    public function clearText(string $value = 'Clear'): static
    {
        $this->attributes['clearText'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function noImageText(string $value = 'No image'): static
    {
        $this->attributes['noImageText'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function multiple(bool $state = true): static
    {
        if ($state) {
            $this->type('uploadMany');
        }

        return $this;
    }
}
