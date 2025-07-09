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

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class SuplemenTerdata extends BaseModel
{
    use ConfigId;

    public const PENDUDUK = 1;
    public const KELUARGA = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suplemen_terdata';

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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['suplemen', 'penduduk'];

    protected $casts = [
        'data_form_isian' => 'array',
    ];

    public function suplemen()
    {
        return $this->belongsTo(Suplemen::class, 'id_suplemen');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id');
    }

    public function scopeAnggota($query, $sasaran, $suplemen): ?array
    {
        switch ($sasaran) {
            case SuplemenTerdata::PENDUDUK:
                $query->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'suplemen_terdata.penduduk_id', 'left')
                    ->join('tweb_keluarga', 'tweb_keluarga.id', '=', 'tweb_penduduk.id_kk', 'left')
                    ->selectRaw('no_kk as terdata_info')
                    ->selectRaw('nik as terdata_plus')
                    ->selectRaw('nama as terdata_nama');
                break;

            case SuplemenTerdata::KELUARGA:
                $query->join('tweb_keluarga', 'tweb_keluarga.id', '=', 'suplemen_terdata.keluarga_id', 'left')
                    ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'tweb_keluarga.nik_kepala', 'left')
                    ->selectRaw('nik as terdata_info')
                    ->selectRaw('no_kk as terdata_plus')
                    ->selectRaw('nama as terdata_nama');
                break;

            default:
                return [];
        }

        $query->join('tweb_wil_clusterdesa', 'tweb_wil_clusterdesa.id', '=', 'tweb_penduduk.id_cluster', 'left')
            ->selectRaw('suplemen_terdata.*, tweb_penduduk.nik, tweb_penduduk.nama, tweb_penduduk.tempatlahir, tweb_penduduk.tanggallahir, tweb_penduduk.sex, tweb_keluarga.no_kk, tweb_wil_clusterdesa.rt, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.dusun')
            ->selectRaw('(case when (tweb_penduduk.id_kk is null) then tweb_penduduk.alamat_sekarang else tweb_keluarga.alamat end) AS alamat')
            ->where('id_suplemen', $suplemen);

        return null;
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['sex'])) {
            $query->where('sex', $filters['sex']);
        }

        if (! empty($filters['dusun'])) {
            $query->where('dusun', $filters['dusun']);
        }

        if (! empty($filters['rw'])) {
            $query->where('rw', $filters['rw']);
        }

        if (! empty($filters['rt'])) {
            $query->where('rt', $filters['rt']);
        }

        return $query;
    }

    public function scopeSasaranPenduduk($query)
    {
        return $query->where('sasaran', self::PENDUDUK);
    }

    public function scopeSasaranKeluarga($query)
    {
        return $query->where('sasaran', self::KELUARGA);
    }
}
