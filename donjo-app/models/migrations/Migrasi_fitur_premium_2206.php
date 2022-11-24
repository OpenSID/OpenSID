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

class Migrasi_fitur_premium_2206 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2205');

        $hasil = $hasil && $this->migrasi_2022050951($hasil);
        $hasil = $hasil && $this->migrasi_2022051051($hasil);

        return $hasil && $this->migrasi_2022052451($hasil);
    }

    protected function migrasi_2022050951($hasil)
    {
        $parrent = $this->db->select('parrent')->like('link', 'https://berputar.opendesa.id/')->get('menu')->row()->parrent;

        if ($parrent) {
            $hasil = $hasil && $this->db->where('id', $parrent)->delete('menu');
        }

        return $hasil && $this->db->like('link', 'https://berputar.opendesa.id/')->delete('menu');
    }

    protected function migrasi_2022051051($hasil)
    {
        return $hasil && $this->dbforge->modify_column('keuangan_ta_spj_sisa', [
            'keterangan' => ['type' => 'text', 'null' => true],
        ]);
    }

    protected function migrasi_2022052451($hasil)
    {
        // Hapus token_opensid; cukup gunakan token_pantau
        return $hasil && $this->db->where('key', 'token_opensid')->delete('setting_aplikasi');
    }
}
