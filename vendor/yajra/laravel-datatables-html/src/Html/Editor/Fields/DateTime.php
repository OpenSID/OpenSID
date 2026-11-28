<?php

namespace Yajra\DataTables\Html\Editor\Fields;

class DateTime extends Field
{
    protected string $type = 'datetime';

    /**
     * Make a new instance of a field.
     */
    public static function make(array|string $name, string $label = ''): static
    {
        return parent::make($name, $label)->format('YYYY-MM-DD hh:mm a');
    }

    /**
     * Set format to military time (24 hrs).
     *
     * @return $this
     */
    public function military(): static
    {
        return $this->format('YYYY-MM-DD HH:mm');
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/options-min-max.html
     */
    public function minDate(\DateTime $dateTime, string $format = 'Y-m-d'): static
    {
        return $this->opts(['minDate' => "new Date('".$dateTime->format($format)."')"]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/options-min-max.html
     */
    public function maxDate(\DateTime $dateTime, string $format = 'Y-m-d'): static
    {
        return $this->opts(['maxDate' => "new Date('".$dateTime->format($format)."')"]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/options-week-numbers.html
     */
    public function showWeekNumber(bool $state = true): static
    {
        return $this->opts(['showWeekNumber' => $state]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/options-disable-days.html
     */
    public function disableDays(array $days): static
    {
        return $this->opts(['disableDays' => $days]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/time-increment.html
     */
    public function minutesIncrement(int $minutes): static
    {
        return $this->opts(['minutesIncrement' => $minutes]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/time-increment.html
     */
    public function secondsIncrement(int $seconds): static
    {
        return $this->opts(['secondsIncrement' => $seconds]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/datetime.html
     */
    public function hoursAvailable(array $hours): static
    {
        return $this->opts(['hoursAvailable' => $hours]);
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/dates/datetime.html
     */
    public function minutesAvailable(array $minutes): static
    {
        return $this->opts(['minutesAvailable' => $minutes]);
    }

    /**
     * The format of the date string loaded from the server for the field's
     * value and also for sending to the server on form submission.
     * The formatting options are defined by Moment.js.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/field/datetime#Options
     * @see https://momentjs.com/docs/#/displaying/format/
     */
    public function wireFormat(string $format = 'YYYY-MM-DDTHH:mm:ss.000000Z'): static
    {
        $this->attributes['wireFormat'] = $format;

        return $this;
    }

    /**
     * Allow (default), or disallow, the end user to type into the date / time input element.
     * If disallowed, they must use the calendar picker to enter data. This can be useful
     * if you are using a more complex date format and wish to disallow the user from
     * potentially making typing mistakes, although note that it does also disallow
     * pasting of data.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/field/datetime#Options
     */
    public function keyInput(bool $state = true): static
    {
        $this->attributes['keyInput'] = $state;

        return $this;
    }

    /**
     * The format of the date string that will be shown to the end user in the input element.
     * The formatting options are defined by Moment.js. If a format is used that is not
     * ISO8061 (i.e. YYYY-MM-DD) and Moment.js has not been included, Editor will
     * throw an error stating that Moment.js must be included for custom
     * formatting to be used.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/field/datetime#Options
     * @see https://momentjs.com/docs/#/displaying/format/
     */
    public function displayFormat(string $format = 'YYYY-MM-DD hh:mm a'): static
    {
        $this->attributes['displayFormat'] = $format;

        return $this;
    }
}
