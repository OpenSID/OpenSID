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

use App\Enums\StatusSuratKecamatanEnum;
use App\Models\BukuKepuasan;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2304 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2303');
        $hasil = $hasil && $this->migrasi_2023030271($hasil);
        $hasil = $hasil && $this->migrasi_2023031551($hasil);
        $hasil = $hasil && $this->tambah_kolom_kecamatan($hasil);
        $hasil = $hasil && $this->migrasi_2023032351($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023030271($hasil)
    {
        // Ubah tipe kolom id_telegram int menjadi varchar (100)
        $fields = [
            'id_telegram' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('user', $fields);
    }

    protected function migrasi_2023031551($hasil)
    {
        $data = BukuKepuasan::query()->has('pertanyaan')->get()->pluck('pertanyaan.pertanyaan', 'id');

        if (count($data) !== 0) {
            foreach ($data as $key => $value) {
                $batch[] = [
                    'id'                => $key,
                    'pertanyaan_statis' => $value,
                ];
            }

            if ($batch) {
                $hasil = $hasil && $this->db->update_batch('buku_kepuasan', $batch, 'id');
            }
        }

        return $hasil;
    }

    protected function tambah_kolom_kecamatan($hasil)
    {
        if (!$this->db->field_exists('kecamatan', 'log_surat')) {
            $fields = [
                'kecamatan' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => StatusSuratKecamatanEnum::TidakAktif,
                    'after'      => 'isi_surat',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }
    }

    protected function migrasi_2023032351($hasil)
    {
        $config = DB::table('config')->first();

        if ($config) {
            $hasil = $hasil && DB::table('config')->update([
                'kode_desa'      => bilangan($config->kode_desa),
                'kode_kecamatan' => bilangan($config->kode_kecamatan),
                'kode_kabupaten' => bilangan($config->kode_kabupaten),
                'kode_propinsi'  => bilangan($config->kode_propinsi),
            ]);
        }

        return $hasil;
    }
}
