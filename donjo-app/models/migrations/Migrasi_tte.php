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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_tte extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->jalankan_migrasi('migrasi_template_tte');
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_log_tte');

        return $hasil && $this->atur_tte('migrasi_template_tte');
    }

    public function atur_tte($hasil)
    {
        // Pengaturan TTE Bsre
        $hasil && $this->tambah_setting([
            'key'        => 'tte',
            'value'      => '0',
            'keterangan' => 'TTE - Aktifkan Modul TTE',
            'kategori'   => 'tte',
            'jenis'      => 'boolean',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'tte_api',
            'value'      => '',
            'keterangan' => 'TTE - URL API TTE',
            'kategori'   => 'tte',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'tte_username',
            'value'      => '',
            'keterangan' => 'TTE - Username untuk TTE',
            'kategori'   => 'tte',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'tte_password',
            'value'      => '',
            'keterangan' => 'TTE - Password untuk TTE',
            'kategori'   => 'tte',
        ]);

        return $hasil;
    }
}
