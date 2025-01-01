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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class MutasiCdesa extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mutasi_cdesa';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getList($id_cdesa, $id_persil = null)
    {
        // Ambil nomor dari tabel cdesa
        $nomor = DB::table('cdesa')
            ->where('id', $id_cdesa)
            ->value('nomor');

        // Query utama untuk daftar mutasi
        $query = DB::table('mutasi_cdesa as m')
            ->selectRaw("CASE
                        WHEN p.id_wilayah = w.id
                            THEN CONCAT(
                                IF(w.rt != '0', CONCAT('RT ', w.rt, ' / '), ''),
                                IF(w.rw != '0', CONCAT('RW ', w.rw, ' - '), ''),
                                w.dusun
                            )
                        ELSE
                            IF(p.lokasi IS NOT NULL, p.lokasi, '=== Lokasi Tidak Ditemukan ===')
                    END AS alamat")
            ->addSelect('m.*', 'p.nomor', 'rk.kode as kelas_tanah')
            ->addSelect(DB::raw("IF(m.id_cdesa_masuk = {$id_cdesa}, m.luas, '') AS luas_masuk"))
            ->addSelect(DB::raw("IF(m.cdesa_keluar = {$id_cdesa}, m.luas, '') AS luas_keluar"))
            ->addSelect(DB::raw("IF(m.jenis_mutasi = '9', 0, 1) AS awal"))
            ->leftJoin('cdesa as c', 'c.id', '=', 'm.id_cdesa_masuk')
            ->leftJoin('persil as p', 'p.id', '=', 'm.id_persil')
            ->leftJoin('ref_persil_kelas as rk', 'p.kelas', '=', 'rk.id')
            ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_wilayah')
            ->where(static function ($query) use ($id_cdesa) {
                $query->where('m.id_cdesa_masuk', $id_cdesa)
                    ->orWhere('m.cdesa_keluar', $id_cdesa);
            })
            ->orderBy('awal')
            ->orderBy('tanggal_mutasi');

        // Tambahkan kondisi untuk $id_persil jika diberikan
        if ($id_persil) {
            $query->where('m.id_persil', $id_persil);
        }

        return $query->get()->toArray();
    }

    /**
     * Get the cdesaMasuk associated with the MutasiCdesa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cdesaMasuk(): BelongsTo
    {
        return $this->belongsTo(Cdesa::class, 'id_cdesa_masuk', 'id');
    }

    public function cdesaKeluar(): BelongsTo
    {
        return $this->belongsTo(Cdesa::class, 'cdesa_keluar', 'nomor');
    }
}
