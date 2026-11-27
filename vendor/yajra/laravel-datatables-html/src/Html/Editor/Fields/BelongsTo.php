<?php

namespace Yajra\DataTables\Html\Editor\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BelongsTo extends Select
{
    /**
     * @param  class-string<Model>|Builder  $class
     */
    public static function model(Builder|string $class, string $text, string $id = 'id', ?string $foreign = null): static
    {
        if ($class instanceof Builder) {
            $table = $class->getModel()->getTable();
        } else {
            $table = (new $class)->getTable();
        }

        $table = Str::singular($table);
        $foreign ??= $table.'_id';

        return self::make($foreign, Str::title($table))
            ->modelOptions($class, $text, $id);
    }
}
