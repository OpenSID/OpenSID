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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Modules\DTSEN\Models;

use App\Models\BaseModel;
use App\Models\Keluarga;
use App\Traits\ConfigId;
use Modules\DTSEN\Enums\DtsenEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class Dtsen extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtsen';

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

    /**
     * Accessor untuk nama versi kuisioner
     */
    public function getVersiKuisionerNameAttribute(): string
    {
        return DtsenEnum::VERSION_LIST[$this->attributes['versi_kuisioner']] ?? 'Tidak Ditemukan';
    }

    /**
     * Relasi ke tabel keluarga
     */
    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'id_keluarga')
            ->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Ambil semua anggota keluarga yang masih hidup
     */
    public function getAnggotaKeluargaAttribute()
    {
        $this->loadMissing([
            'keluarga.anggota' => static function ($builder): void {
                $builder->without(['wilayah']);
                // Hanya ambil data anggota yang masih hidup (status_dasar = 1)
                $builder->where('status_dasar', 1);
            },
        ]);

        return $this->keluarga->anggota ?? collect([]);
    }

    /**
     * Ambil kepala keluarga
     */
    public function getKepalaKeluargaAttribute()
    {
        $this->loadMissing(['keluarga.kepalaKeluarga']);

        return $this->keluarga->kepalaKeluarga;
    }

    /**
     * Ambil kepala keluarga yang terdaftar di DTSEN
     */
    public function getKepalaKeluargaDTSENAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga',
            'dtsenAnggota',
        ]);

        return $this->dtsenAnggota
            ->where('id_penduduk', $this->keluarga->kepalaKeluarga->id)
            ->first();
    }

    /**
     * Hitung jumlah anggota DTSEN
     */
    public function getJumlahAnggotaDTSENAttribute()
    {
        $this->loadMissing('dtsenAnggota');

        return $this->dtsenAnggota->count();
    }

    /**
     * Ambil NIK kepala keluarga
     */
    public function getNikKKAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga' => static function ($builder): void {
                $builder->withoutRelations();
            },
        ]);

        return $this->keluarga->kepalaKeluarga->nik ?? null;
    }

    /**
     * Ambil alamat lengkap keluarga
     */
    public function getAlamatAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga' => static function ($builder): void {
                $builder->withoutRelations();
            },
        ]);

        return $this->keluarga->kepalaKeluarga->alamat_wilayah ?? $this->keluarga->alamat ?? null;
    }

    /**
     * Ambil nomor KK
     */
    public function getNoKKAttribute()
    {
        return $this->keluarga->no_kk ?? null;
    }

    public function dtsenAnggota()
    {
        return $this->hasMany(DtsenAnggota::class, 'id_dtsen')
            ->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Relasi ke lampiran DTSEN
     */
    public function lampiran()
    {
        return $this->belongsToMany(DtsenLampiran::class, 'dtsen_ref_lampiran', 'id_dtsen', 'id_lampiran')
            ->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }
}
