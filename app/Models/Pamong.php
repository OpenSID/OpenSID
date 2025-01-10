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

use App\Enums\StatusEnum;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\Schema;
use Modules\Kehadiran\Models\Kehadiran;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Pamong extends BaseModel
{
    use ConfigId;
    use SortableTrait;
    use QueryCacheable;

    public const LOCK   = 1;
    public const UNLOCK = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweb_desa_pamong';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pamong_id';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['penduduk', 'jabatan'];

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => true,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'media_sosial' => 'json',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'foto_staff',
    ];

    public function getFotoStaffAttribute()
    {

        // jika foto ada, ambil foto pengurus
        if (empty($this->foto) || ! file_exists(LOKASI_USER_PICT . $this->foto)) {
            // menggunakan ternari operator jika pengurus adalah penduduk ambil foto penduduk jika tidak maka null
            return $this->penduduk()->exists() ? $this->penduduk->foto : null;
        }

            // Jika foto pengurus ada, ambil foto pengurus
            return $this->foto;

    }

    // TODO: OpenKab - Sementara di disable dulu observer pada relasi ini
    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_pend')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'pamong_id', 'pamong_id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function jabatan()
    {
        return $this->hasOne(RefJabatan::class, 'id', 'jabatan_id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function kehadiranPerangkat()
    {
        return $this->hasMany(Kehadiran::class, 'pamong_id', 'pamong_id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function kehadiranPengaduan()
    {
        return $this->hasMany(KehadiranPengaduan::class, 'id_pamong', 'id');
    }

    public function scopeSelectData($query)
    {
        $query->select(['pamong_id', 'pamong_nama', 'jabatan_id', 'ref_jabatan.jenis', 'ref_jabatan.nama AS nama_jabatan', 'pamong_nip', 'pamong_niap', 'pamong_ttd', 'pamong_ub', 'pamong_status', 'pamong_nik'])
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS pamong_nama')
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nik, tweb_penduduk.nik) AS pamong_nik')
            ->selectRaw('gelar_depan')
            ->selectRaw('gelar_belakang')
            ->leftJoin('tweb_penduduk', 'tweb_penduduk.id', '=', 'tweb_desa_pamong.id_pend')
            ->leftJoin('ref_jabatan', 'ref_jabatan.id', '=', 'tweb_desa_pamong.jabatan_id');

        if (Schema::hasColumn('tweb_desa_pamong', 'status_pejabat')) {
            $pejabat = setting('sebutan_pj_kepala_desa');
            $query->selectRaw('IF(tweb_desa_pamong.status_pejabat = 1, CONCAT("' . $pejabat . ' ", ref_jabatan.nama), ref_jabatan.nama) AS pamong_jabatan');
        }

        return $query;
    }

    public function scopeListAtasan($query, $id = null)
    {
        if ($id) {
            $atasan = $query->where('pamong_id', '<>', $id);
        }

        return $query->select(['pamong_id as id', 'ref_jabatan.nama AS jabatan', 'jabatan_id'])
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS nama')
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nik, tweb_penduduk.nik) AS nik')
            ->leftJoin('tweb_penduduk', 'tweb_penduduk.id', '=', 'tweb_desa_pamong.id_pend')
            ->leftJoin('ref_jabatan', 'ref_jabatan.id', '=', 'tweb_desa_pamong.jabatan_id')
            ->where('pamong_status', 1)
            ->orderBy('nama');
    }

    /**
     * Scope query untuk status pamong
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatus($query, mixed $value = 1)
    {
        return $query->where('pamong_status', $value);
    }

    /**
     * Scope query untuk kepala desa
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeKepalaDesa($query)
    {
        return $this->scopeSelectData($query)
            ->where('jabatan_id', kades()->id)
            ->where('pamong_status', StatusEnum::YA);
    }

    /**
     * Scope query untuk sekretaris desa
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSekretarisDesa($query)
    {
        return $this->scopeSelectData($query)
            ->where('jabatan_id', sekdes()->id)
            ->where('pamong_status', StatusEnum::YA);
    }

    /**
     * Scope query untuk Penanda Tangan
     *
     * Ket :
     * - a.n => untuk sekretaris yang dipilih
     * - u.b => untuk pamong selain kades dan sekretaris yang dipilih
     *
     * @param Builder    $query
     * @param mixed|null $jenis
     *
     * @return Builder
     */
    public function scopeTtd($query, $jenis = null)
    {
        if ($jenis === 'a.n') {
            $query->where('pamong_ttd', 1)->where('jabatan_id', sekdes()->id);
        } elseif ($jenis === 'u.b') {
            $query->where('pamong_ub', 1)->whereNotIn('jabatan_id', RefJabatan::getKadesSekdes());
        }

        return $this->scopeSelectData($query)
            ->where('pamong_status', StatusEnum::YA);
    }

    /**
     * Scope query untuk daftar penanda tangan
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePenandaTangan($query)
    {
        return $this->scopeSelectData($query)
            ->where(static function ($query): void {
                $query->whereIn('jabatan_id', RefJabatan::getKadesSekdes())
                    ->orWhere('pamong_ttd', '1')
                    ->orWhere('pamong_ub', '1');
            })
            ->where('pamong_status', StatusEnum::YA)
            ->orderBy('jenis')
            ->orderBy('urut');
    }

    public function scopeKehadiranPamong($query)
    {
        return $query
            ->leftJoin('kehadiran_perangkat_desa as k', 'tweb_desa_pamong.pamong_id', '=', 'k.pamong_id')
            ->leftJoin('kehadiran_pengaduan as p', 'tweb_desa_pamong.pamong_id', '=', 'p.id_pamong');
    }

    /**
     * Scope query untuk daftar kehadiran pamong
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeDaftar($query, mixed $value = 1)
    {
        return $query->aktif()
            ->where('kehadiran', $value);
    }

    /**
     * Getter status pamong_nama attribute.
     *
     * @return string
     */
    public function getPamongNamaAttribute()
    {
        $pamong_nama = $this->attributes['id_pend'] != null ? $this->penduduk->nama : $this->attributes['pamong_nama'];

        if ($this->gelar_depan) {
            $pamong_nama = $this->gelar_depan . ' ' . $pamong_nama;
        }

        if ($this->gelar_belakang) {
            return $pamong_nama . ', ' . $this->gelar_belakang;
        }

        return $pamong_nama;
    }

    public function getPamongNikAttribute()
    {
        return $this->attributes['id_pend'] != null ? $this->penduduk->nik : $this->attributes['pamong_nik'];
    }

    /**
     * Getter status pamong_sex attribute.
     *
     * @return string
     */
    public function getPamongSexAttribute()
    {
        return $this->attributes['id_pend'] != null ? $this->penduduk->sex : $this->attributes['pamong_sex'];
    }

    /**
     * Getter status pamong_tempatlahir attribute.
     *
     * @return string
     */
    public function getPamongTempatlahirAttribute()
    {
        return $this->attributes['id_pend'] != null ? $this->penduduk->tempatlahir : $this->attributes['pamong_tempatlahir'];
    }

    /**
     * Getter status pamong_tanggallahir attribute.
     *
     * @return string
     */
    public function getPamongTanggallahirAttribute()
    {
        return $this->attributes['id_pend'] != null ? $this->penduduk->tanggallahir : $this->attributes['pamong_tanggallahir'];
    }

    /**
     * Getter status pamong_agama attribute.
     *
     * @return string
     */
    public function getPamongAgamaAttribute()
    {
        return $this->attributes['id_pend'] != null ? $this->penduduk->agama_id : $this->attributes['pamong_agama'];
    }

    /**
     * Getter status pamong_pendidikan attribute.
     *
     * @return string
     */
    public function getPamongPendidikanAttribute()
    {
        return $this->attributes['id_pend'] != null ? $this->penduduk->pendidikan_kk_id : $this->attributes['pamong_pendidikan'];
    }

    /**
     * Scope query untuk pamong yang aktif
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeAktif($query)
    {
        return $query->where('pamong_status', self::LOCK);
    }

    /**
     * Scope query untuk pamong kecuali yang sudah digunakan di user
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeBukanPengguna($query, mixed $id = '')
    {
        return $query->whereNotIn('pamong_id', static function ($q) use ($id) {
            if ($id) {
                return $q->select(['pamong_id'])->where('id', '!=', $id)->whereNotNull('pamong_id')->from('user');
            }

            return $q->select(['pamong_id'])->whereNotNull('pamong_id')->from('user');
        });
    }

    public function scopeUrut($query)
    {
        $kades  = kades()->id ?: 0;
        $sekdes = sekdes()->id ?: 0;

        return $query->orderByRaw(sprintf('
            case
                when jabatan_id=%s then 1
                when jabatan_id=%s then 2
                else 3
            end
            ', $kades, $sekdes))
            ->orderBy('urut');
    }

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $kecil  = LOKASI_USER_PICT . 'kecil_' . $model->getOriginal($file);
            $sedang = LOKASI_USER_PICT . $model->getOriginal($file);
            if (file_exists($kecil)) {
                unlink($kecil);
            }
            if (file_exists($sedang)) {
                unlink($sedang);
            }
        }
    }
}
