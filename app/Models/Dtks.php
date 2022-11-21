<?php

namespace App\Models;

use App\Enums\Dtks\DtksEnum;

class Dtks extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtks';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = [
        'kepala_keluarga',
        'jumlah_keluarga',
        'jumlah_anggota_dtks',
        'no_kk_art',
        'versi_kuisioner_name',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'tanggal_pencacahan' => 'date:Y-m-d',
        'tanggal_pemeriksaan' => 'date:Y-m-d',
        'tanggal_pendataan' => 'date:Y-m-d',
    ];
    // /**
    //  * The fillable with the model.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'versi_kuisioner',
    //     'is_draft'
    // ];

    public function getVersiKuisionerNameAttribute()
    {
        return DtksEnum::VERSION_LIST[$this->attributes['versi_kuisioner']] ?? 'Tidak Ditemukan';
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function rtm()
    {
        return $this->hasOne(Rtm::class, 'id', 'id_rtm');
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'id_keluarga');
    }

    public function getKeluargaInRTMAttribute()
    {
        $this->loadMissing([
            'rtm.anggota' => function($builder){
                // override all items within the $with property in Penduduk
                $builder->withOnly('keluarga');
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);

        return $this->rtm->anggota->pluck('keluarga')->unique();
    }

    public function getAnggotaKeluargaInRTMAttribute()
    {
        $this->loadMissing([
            'rtm.anggota' => function($builder){
                // override all items within the $with property in Penduduk
                $builder->withOnly('');
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);

        return $this->rtm->anggota->groupBy('id_kk');
    }

    public function getKepalaRumahTanggaAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga',
        ]);

        return $this->rtm->kepalaKeluarga;
    }

    public function getKepalaKeluargaAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga',
        ]);

        return $this->keluarga->kepalaKeluarga;
    }

    public function getKepalaKeluargaDTKSAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga',
            'dtksAnggota'
        ]);

        return $this->dtksAnggota->where('id_penduduk', $this->keluarga->kepalaKeluarga->id)->first();
    }

    public function getJumlahAnggotaDTKSAttribute()
    {
        $this->loadMissing('dtksAnggota');

        return $this->dtksAnggota->count();
    }

    public function getNikKKAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga' => function($builder){
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->keluarga->kepalaKeluarga->nik;
    }

    public function getNikKrtAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga' => function($builder){
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->rtm->kepalaKeluarga->nik;
    }

    public function getAlamatAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga' => function($builder){
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->rtm->kepalaKeluarga->alamat_wilayah;
    }

    public function getJumlahKeluargaAttribute()
    {
        return $this->getNoKKArtAttribute()->count();
    }

    public function getNoKKAttribute()
    {
        return $this->getKepalaKeluargaAttribute()->keluarga->no_kk;
    }

    public function getNoKkArtAttribute()
    {
        return $this->getKeluargaInRTMAttribute()->pluck('no_kk');
    }

    public function dtksAnggota()
    {
        return $this->hasMany(DtksAnggota::class, 'id_dtks');
    }

    public function lampiran()
    {
        return $this->belongsToMany(DtksLampiran::class, 'dtks_ref_lampiran', 'id_dtks', 'id_lampiran');
    }
}