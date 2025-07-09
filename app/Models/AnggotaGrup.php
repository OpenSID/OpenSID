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
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class AnggotaGrup extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anggota_grup_kontak';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_grup_kontak';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'id_grup',
        'id_kontak',
        'id_penduduk',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function grupKontak()
    {
        return $this->hasOne(GrupKontak::class, 'id_grup', 'id_grup');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function daftarKontak()
    {
        return $this->hasOne(DaftarKontak::class, 'id_kontak', 'id_kontak');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_penduduk')->status();
    }

    public function scopeDataAnggota($query)
    {
        return $query
            ->leftJoin('kontak as k', 'anggota_grup_kontak.id_kontak', '=', 'k.id_kontak')
            ->leftJoin('tweb_penduduk as p', static function ($penduduk): void {
                $penduduk->on('anggota_grup_kontak.id_penduduk', '=', 'p.id')
                    ->where('p.status_dasar', '=', 1);
            })
            ->select(
                'anggota_grup_kontak.*',
                DB::raw('(CASE WHEN anggota_grup_kontak.id_kontak IS NULL THEN p.nama ELSE k.nama END) AS nama'),
                DB::raw('(CASE WHEN anggota_grup_kontak.id_kontak IS NULL THEN p.telepon ELSE k.telepon END) AS telepon'),
                DB::raw('(CASE WHEN anggota_grup_kontak.id_kontak IS NULL THEN p.email ELSE k.email END) AS email'),
                DB::raw('(CASE WHEN anggota_grup_kontak.id_kontak IS NULL THEN p.telegram ELSE k.telegram END) AS telegram'),
                DB::raw('(CASE WHEN anggota_grup_kontak.id_kontak IS NULL THEN p.hubung_warga ELSE k.hubung_warga END) AS hubung_warga'),
            );
    }
}
