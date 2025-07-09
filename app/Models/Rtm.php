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

use App\Enums\SasaranEnum;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Rtm extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweb_rtm';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'jumlah_kk',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function kepalaKeluarga()
    {
        return $this->hasOne(Penduduk::class, 'id', 'nik_kepala');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'id_rtm', 'no_kk')->status();
    }

    /**
     * Scope query untuk status rumah tangga
     *
     * @return Builder
     */
    public function scopeStatus()
    {
        return static::whereHas('kepalaKeluarga', static function ($query): void {
            $query->status()->where('rtm_level', '1');
        });
    }

    public function judulStatistik($tipe = 0, $nomor = 0, $sex = 0)
    {
        if ($nomor == JUMLAH) {
            $judul = ['nama' => ' JUMLAH'];
        } elseif ($nomor == BELUM_MENGISI) {
            $judul = ['nama' => ' BELUM MENGISI'];
        } elseif ($nomor == TOTAL) {
            $judul = ['nama' => ' TOTAL'];
        } else {
            $judul = match ($tipe) {
                'penerima_bantuan' => ['nama' => 'PESERTA'],
                default            => Rtm::where(['id' => $nomor])->first()->toArray(),
            };
        }

        if ($sex == 1) {
            $judul['nama'] .= ' - LAKI-LAKI';
        } elseif ($sex == 2) {
            $judul['nama'] .= ' - PEREMPUAN';
        }

        return $judul;
    }

    public static function boot(): void
    {
        parent::boot();
        static::deleting(static function ($model): void {
            static::deletePenduduk($model);
        });
    }

    public static function deletePenduduk($model): void
    {
        $reset['id_rtm']     = 0;
        $reset['rtm_level']  = 0;
        $reset['updated_at'] = date('Y-m-d H:i:s');
        Penduduk::where(['id_rtm' => $model->no_kk])->update($reset);

        BantuanPeserta::where('peserta', $model->no_kk)->whereHas('bantuan', static fn ($q) => $q->where(['sasaran' => SasaranEnum::RUMAH_TANGGA]))->delete();
    }

    public static function get_kepala_rtm($id, $is_no_kk = false)
    {
        if (empty($id)) {
            return null;
        }

        $kolom_id = $is_no_kk ? 'r.no_kk' : 'r.id';

        $data = DB::table('tweb_rtm as r')
            ->select([
                'u.id',
                'u.nik',
                'u.nama',
                'u.status_dasar',
                'r.no_kk',
                'r.bdt',
                'x.nama as sex',
                'u.tempatlahir',
                'u.tanggallahir',
                DB::raw('(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(u.tanggallahir)), "%Y") + 0) AS umur'),
                'd.nama as pendidikan',
                'f.nama as warganegara',
                'a.nama as agama',
                'wil.rt',
                'wil.rw',
                'wil.dusun',
            ])
            ->leftJoin('penduduk_hidup as u', static function ($join) {
                $join->on('r.no_kk', '=', 'u.id_rtm')
                    ->where('u.rtm_level', '=', 1);
            })
            ->leftJoin('tweb_penduduk_sex as x', 'u.sex', '=', 'x.id')
            ->leftJoin('tweb_penduduk_pendidikan_kk as d', 'u.pendidikan_kk_id', '=', 'd.id')
            ->leftJoin('tweb_penduduk_warganegara as f', 'u.warganegara_id', '=', 'f.id')
            ->leftJoin('tweb_penduduk_agama as a', 'u.agama_id', '=', 'a.id')
            ->leftJoin('tweb_wil_clusterdesa as wil', 'wil.id', '=', 'u.id_cluster')
            ->where($kolom_id, $id)
            ->first()->toArray();

        if ($data) {
            $data['alamat_wilayah'] = Penduduk::get_alamat_wilayah($data['id']);
        }

        return $data ?? null;
    }

    public function getJumlahKkAttribute()
    {
        return $this->anggota()->distinct('id_kk')->count('id_kk');
    }
}
