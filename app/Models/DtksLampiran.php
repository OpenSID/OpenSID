<?php

namespace App\Models;

class DtksLampiran extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtks_lampiran';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'keterangan',
        'foto',
        'id_rtm'
    ];

    public function getFotoKecilAttribute()
    {
        $path = LOKASI_FOTO_DTKS . 'kecil_' . $this->attributes['foto'];
        if( ! file_exists(FCPATH . $path)){
            return '';
        }
        return base_url() . $path;
    }

    public function dtks()
    {
        return $this->belongsToMany(Dtks::class, 'dtks_ref_lampiran', 'id_lampiran', 'id_dtks');
    }

}