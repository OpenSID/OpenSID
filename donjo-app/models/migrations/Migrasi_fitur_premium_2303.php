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

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2303 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2302');
        $hasil = $hasil && $this->migrasi_2023020251($hasil);
        $hasil = $hasil && $this->migrasi_2023021471($hasil);
        $hasil = $hasil && $this->migrasi_2023021671($hasil);

        return $hasil && $this->migrasi_2023022271($hasil);
    }

    protected function migrasi_2023020251($hasil)
    {
        // Sesuaikan data jabatan agar bisa digunakan di OpenKAB
        // Parameter migrasi ditentukan dari jabatan sekreatis dengan id = 2 jika jenis = 1 akan dilakukan migrasi
        DB::table('ref_jabatan')->where('id', 2)->update(['jenis' => 2]);

        return $hasil;
    }

    protected function migrasi_2023021471($hasil)
    {
        $fields = [
            'tinggi_badan' => [
                'type'       => 'FLOAT',
                'constraint' => '',
            ],
        ];

        return $hasil && $this->dbforge->modify_column('bulanan_anak', $fields);
    }

    protected function migrasi_2023021671($hasil)
    {
        // Sesuaikan data jabatan agar bisa digunakan di OpenKAB
        // Parameter migrasi ditentukan dari jabatan sekretaris dengan nama like sekretaris akan dilakukan migrasi
        $this->db
            ->like('nama', 'sekretaris')
            ->set('jenis', '2')
            ->update('ref_jabatan');

        return $hasil;
    }

    protected function migrasi_2023022271($hasil)
    {
        if (! $this->db->field_exists('slug', 'setting_modul')) {
            // Tambahkan kolom slug pada tabel setting_modul
            $hasil = $hasil && $this->dbforge->add_column('setting_modul', [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'modul',
                    'unique'     => true,
                ],
            ]);
        }

        return $hasil;
    }
}
