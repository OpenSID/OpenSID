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

use App\Models\BukuKepuasan;
use App\Models\LogSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2302 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2301');
        $hasil = $hasil && $this->migrasi_2023010851($hasil);
        $hasil = $hasil && $this->migrasi_2023010171($hasil);
        $hasil = $hasil && $this->migrasi_2023010452($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023010171($hasil)
    {
        if (! $this->db->field_exists('pertanyaan_statis', 'buku_kepuasan')) {
            $hasil = $hasil && $this->dbforge->add_column('buku_kepuasan', [
                'pertanyaan_statis' => ['type' => 'TEXT', 'null' => true, 'default' => null, 'after' => 'id_jawaban'],
            ]);

            $data = BukuKepuasan::query()->has('pertanyaan')->get()->pluck('pertanyaan.pertanyaan', 'id');

            if (count($data) !== 0) {
                foreach ($data as $key => $value) {
                    $batch[] = [
                        'id'                => $key,
                        'pertanyaan_statis' => $value,
                    ];
                }

                $hasil = $hasil && $this->db->update_batch('buku_kepuasan', $batch, 'id');
            }

            return $hasil;
        }

        return $hasil;
    }

    protected function migrasi_2023010851($hasil)
    {
        if (! $this->db->field_exists('nama_pamong', 'log_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('log_surat', [
                'nama_pamong' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'id_pamong',
                    'comment'    => 'Nama pamong agar tidak berubah saat ada perubahan di master pamong',
                ],
            ]);
        }

        // ganti nama pamong yang masih null
        $check = LogSurat::whereNull('nama_pamong')->get();

        foreach ($check as $surat) {
            $surat->nama_pamong = $surat->pamong->pamong_nama;
            $hasil              = $surat->save();
        }

        return $hasil;
    }

    protected function migrasi_2023010452($hasil)
    {
        if (! $this->db->field_exists('status_alasan', 'anjungan')) {
            $hasil = $hasil && $this->dbforge->add_column('anjungan', [
                'status_alasan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ]);
        }

        return $hasil;
    }
}
