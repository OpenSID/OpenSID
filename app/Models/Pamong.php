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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class Pamong extends BaseModel
{
    use ConfigId;

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

    // TODO: OpenKab - Sementara di disable dulu observer pada relasi ini
    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_pend')->withoutGlobalScope('App\Scopes\ConfigIdScope');
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
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'pamong_id', 'pamong_id');
    }

    public function scopeSelectData($query)
    {
        $new_query = $query->select(['pamong_id', 'pamong_nama', 'jabatan_id', 'ref_jabatan.nama AS pamong_jabatan', 'ref_jabatan.jenis', 'pamong_nip', 'pamong_niap', 'pamong_ttd', 'pamong_ub', 'pamong_status', 'pamong_nik'])
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS pamong_nama')
            ->selectRaw('IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nik, tweb_penduduk.nik) AS pamong_nik')
            ->leftJoin('tweb_penduduk', 'tweb_penduduk.id', '=', 'tweb_desa_pamong.id_pend')
            ->leftJoin('ref_jabatan', 'ref_jabatan.id', '=', 'tweb_desa_pamong.jabatan_id');

        if (ci_db()->field_exists('gelar_depan', 'tweb_desa_pamong')) {
            $new_query = $new_query->selectRaw('gelar_depan')->selectRaw('gelar_belakang');
        }

        return $new_query;
    }

    /**
     * Scope query untuk status pamong
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeStatus($query, $value = 1)
    {
        return $query->where('pamong_status', $value);
    }

    /**
     * Scope query untuk kepala desa
     *
     * @param Builder $query
     * @param mixed   $value
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
     * @param mixed   $value
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
     * @param mixed      $value
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
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopePenandaTangan($query)
    {
        return $this->scopeSelectData($query)
            ->where(static function ($query) {
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
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeDaftar($query, $value = 1)
    {
        return $query->where('pamong_status', StatusEnum::YA)
            ->where('kehadiran', $value);
    }

    /**
     * Getter status pamong_nama attribute.
     *
     * @return string
     */
    public function getPamongNamaAttribute()
    {
        if ($this->attributes['id_pend'] != null) {
            $pamong_nama = $this->penduduk->nama;
        } else {
            $pamong_nama = $this->attributes['pamong_nama'];
        }

        if ($this->gelar_depan) {
            $pamong_nama = $this->gelar_depan . ' ' . $pamong_nama;
        }

        if ($this->gelar_belakang) {
            $pamong_nama = $pamong_nama . ', ' . $this->gelar_belakang;
        }

        return $pamong_nama;
    }
}
