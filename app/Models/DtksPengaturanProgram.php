<?php

namespace App\Models;

use App\Enums\Dtks\DtksEnum;

class DtksPengaturanProgram extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtks_pengaturan_program';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'date:Y-m-d H:i:s',
        'updated_at'  => 'date:Y-m-d H:i:s',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function bantuan()
    {
        return $this->belongsTo(Bantuan::class, 'id_bantuan', 'id');
    }

    public function getVersiKuisionerNameAttribute()
    {
        return DtksEnum::VERSION_LIST[$this->attributes['versi_kuisioner']];
    }
}