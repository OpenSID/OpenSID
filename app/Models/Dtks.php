<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Enums\Dtks\DtksEnum;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Dtks extends BaseModel
{
    use ConfigId;

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
        'versi_kuisioner_name',
    ];
    protected $casts = [
        'created_at'          => 'date:Y-m-d H:i:s',
        'updated_at'          => 'date:Y-m-d H:i:s',
        'tanggal_pencacahan'  => 'date:Y-m-d',
        'tanggal_pemeriksaan' => 'date:Y-m-d',
        'tanggal_pendataan'   => 'date:Y-m-d',
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

    public function getVersiKuisionerNameAttribute(): string
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
        return $this->hasOne(Rtm::class, 'id', 'id_rtm')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'id_keluarga')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function getKeluargaInRTMAttribute()
    {
        $this->loadMissing([
            'rtm.anggota' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withOnly('keluarga')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);

        return $this->rtm->anggota->pluck('keluarga')->unique();
    }

    public function getAnggotaKeluargaInRTMAttribute()
    {
        $this->loadMissing([
            'rtm.anggota' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->without([
                    'jenisKelamin',
                    'agama',
                    'pendidikan',
                    'pendidikanKK',
                    'pekerjaan',
                    'wargaNegara',
                    'golonganDarah',
                    'cacat',
                    'statusKawin',
                    'pendudukStatus',
                    'wilayah',
                ]);
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
            'dtksAnggota',
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
            'keluarga.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->keluarga->kepalaKeluarga->nik;
    }

    public function getNikKrtAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->rtm->kepalaKeluarga->nik;
    }

    public function getAlamatAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga' => static function ($builder): void {
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
        return $this->hasMany(DtksAnggota::class, 'id_dtks')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function lampiran()
    {
        return $this->belongsToMany(DtksLampiran::class, 'dtks_ref_lampiran', 'id_dtks', 'id_lampiran')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::deleting(static function ($model): void {
            $id_lampiran = DB::table('dtks_ref_lampiran')->where('id_dtks', $model->id)->pluck('id_lampiran')->toArray();
            if (count($id_lampiran) > 0) {
                DtksLampiran::destroy($id_lampiran);
            }
        });
    }
}
