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

use App\Enums\StatusEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2310 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2309', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && $this->migrasi_xxxxxxxxxx($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        //     $hasil = $hasil && $this->migrasi_xxxxxxxxxx($hasil, $id);
        // }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_23090451($hasil);

        return $hasil && $this->migrasi_23090651($hasil);
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    protected function migrasi_23090451($hasil)
    {
        $this->db->where('status', '2')->update('teks_berjalan', ['status' => '0']);
        $this->dbforge->modify_column('teks_berjalan', [
            'status' => [
                'type'       => 'TINYINT',
                'null'       => false,
                'constraint' => 1,
                'default'    => StatusEnum::TIDAK,
            ],
        ]);

        return $hasil;
    }

    protected function migrasi_23090651($hasil)
    {
        $table = 'artikel';

        $this->dbforge->modify_column($table, [
            'headline' => [
                'type'       => 'TINYINT',
                'null'       => false,
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);

        $slider['slider'] = [
            'type'       => 'TINYINT',
            'null'       => false,
            'constraint' => 1,
            'default'    => 0,
        ];

        if ($this->db->field_exists('slider', $table)) {
            $this->dbforge->modify_column($table, $slider);
        } else {
            $this->dbforge->add_column($table, $slider);
        }

        $this->db->where('headline', '3')->update($table, ['headline' => '0', 'slider' => '1']);
        $this->db->where('headline', '2')->update($table, ['headline' => '1', 'slider' => '1']);

        return $hasil;
    }
}
