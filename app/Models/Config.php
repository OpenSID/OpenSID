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

use App\Traits\Author;

defined('BASEPATH') || exit('No direct script access allowed');

class Config extends BaseModel
{
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'nama_desa',
        'kode_desa',
        'kode_pos',
        'nama_kecamatan',
        'kode_kecamatan',
        'nama_kepala_camat',
        'nip_kepala_camat',
        'nama_kabupaten',
        'kode_kabupaten',
        'nama_propinsi',
        'kode_propinsi',
        'logo',
        'lat',
        'lng',
        'zoom',
        'map_tipe',
        'path',
        'alamat_kantor',
        'email_desa',
        'telepon',
        'website',
        'kantor_desa',
        'warna',
        'created_by',
        'updated_by',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'nip_kepala_desa',
        'nama_kepala_desa',
    ];

    /**
     * Getter untuk nip kepala desa dari pengurus
     *
     * @return string
     */
    public function getNipKepalaDesaAttribute()
    {
        return $this->pamong()->pamong_nip;
    }

    /**
     * Getter untuk nama kepala desa dari pengurus
     *
     * @return string
     */
    public function getNamaKepalaDesaAttribute()
    {
        return $this->pamong()->pamong_nama;
    }

    public function pamong()
    {
        return Pamong::kepalaDesa()->first();
    }
}
