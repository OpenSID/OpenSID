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

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukHubungan extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_penduduk_hubungan';

    protected function scopeKawin($query, $status_kawin_kk, $sex = 1)
    {
        if (! empty($status_kawin_kk)) {
            /*
                Untuk Kepala Keluarga yang belum kawin, hubungan berikut tidak berlaku:
                    menantu, cucu, mertua, suami, istri; anak hanya berlaku untuk kk perempuan
                Untuk semua Kepala Keluarga, hubungan 'kepala keluarga' tidak berlaku
            */

            if ($status_kawin_kk == 1) {
                ($sex == 2) ? $query->whereNotIn('id', ['1', '2', '3', '5', '6', '8'])
                    : $query->whereNotIn('id', ['1', '2', '3', '4', '5', '6', '8']);
            } else {
                $query->where('id', '!=', '1');
            }
        }

        return $query;
    }
}
