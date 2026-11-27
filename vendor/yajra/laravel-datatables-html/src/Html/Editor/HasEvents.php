<?php

namespace Yajra\DataTables\Html\Editor;

use Illuminate\Support\Str;

/**
 * @method $this onClose($script)
 * @method $this onClosed($script)
 * @method $this onCreate($script)
 * @method $this onDisplayOrder($script)
 * @method $this onEdit($script)
 * @method $this onInitCreate($script)
 * @method $this onInitEdit($script)
 * @method $this onInitEditor($script)
 * @method $this onInitRemove($script)
 * @method $this onInitSubmit($script)
 * @method $this onOpen($script)
 * @method $this onOpened($script)
 * @method $this onPostCreate($script)
 * @method $this onPostEdit($script)
 * @method $this onPostRemove($script)
 * @method $this onPostSubmit($script)
 * @method $this onPostUpload($script)
 * @method $this onPreBlur($script)
 * @method $this onPreBlurCancelled($script)
 * @method $this onPreClose($script)
 * @method $this onPreCreate($script)
 * @method $this onPreEdit($script)
 * @method $this onPreOpen($script)
 * @method $this onPreOpenCancelled($script)
 * @method $this onPreRemove($script)
 * @method $this onPreSubmit($script)
 * @method $this onPreSubmitCancelled($script)
 * @method $this onPreUpload($script)
 * @method $this onPreUploadCancelled($script)
 * @method $this onProcessing($script)
 * @method $this onRemove($script)
 * @method $this onSetData($script)
 * @method $this onSubmitComplete($script)
 * @method $this onSubmitError($script)
 * @method $this onSubmitSuccess($script)
 * @method $this onSubmitUnsuccessful($script)
 * @method $this onUploadXhrError($script)
 * @method $this onUploadXhrSuccess($script)
 */
trait HasEvents
{
    /**
     * Magic method handler for editor events.
     *
     * @param  string  $method
     * @param  array{0: string}  $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'on')) {
            $event = Str::camel(substr($method, 2, strlen($method) - 2));

            return $this->on($event, $parameters[0]);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Add Editor event listener scripts.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/event
     */
    public function on(string $event, mixed $script): static
    {
        $this->events[] = [
            'event' => $event,
            'script' => value($script),
        ];

        return $this;
    }
}
