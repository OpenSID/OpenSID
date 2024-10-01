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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

defined('BASEPATH') || exit('No direct script access allowed');

class Persil extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persil';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the refKelas associated with the Persil
     */
    public function refKelas(): HasOne
    {
        return $this->hasOne(RefPersilKelas::class, 'id', 'kelas');
    }

    /**
     * Get the wilayah associated with the Persil
     */
    public function wilayah(): HasOne
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_wilayah');
    }

    /**
     * Get all of the mutasi for the Persil
     */
    public function mutasi(): HasMany
    {
        return $this->hasMany(MutasiCdesa::class, 'id_persil');
    }

    /**
     * Get the cdesa associated with the Persil
     */
    public function cdesa(): HasOne
    {
        return $this->hasOne(Cdesa::class, 'id', 'cdesa_awal');
    }

    public static function activeMap()
    {
        return self::with(['cdesa', 'refKelas', 'wilayah'])->withCount('mutasi')
            ->orderBy('nomor')->orderBy('nomor_urut_bidang')->get()->map(static function ($item) {
            $item->kode             = $item->refKelas->kode ?? '';
            $item->jml_bidang       = $item->mutasi_count;
            $item->nomor_cdesa_awal = $item->cdesa->nomor ?? '';
            $item->nama_kepemilikan = $item->cdesa->nama_kepemilikan;
            $item->alamat           = $item->wilayah ? ($item->wilayah->rt != 0 ? 'RT ' . $item->wilayah->rt . ' / ' : '') . ($item->wilayah->rw != 0 ? 'RW ' . $item->wilayah->rw . ' - ' : '') . $item->wilayah->dusun : ($item->lokasi ?? '=== Lokasi Tidak Ditemukan ===');
            unset($item->refKelas, $item->cdesa, $item->wilayah);

            return $item;
        })->toArray();
    }
}
