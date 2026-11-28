<?php

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $config_id
 * @property string $authenticatable_type
 * @property int $authenticatable_id
 * @property string $password
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read Model|\Eloquent $authenticatable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PasswordHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PasswordHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PasswordHistory query()
 * @mixin \Eloquent
 */
class PasswordHistory extends Model
{
    use ConfigId;

    public $timestamps = false;

    protected $fillable = ['password'];

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }
}
