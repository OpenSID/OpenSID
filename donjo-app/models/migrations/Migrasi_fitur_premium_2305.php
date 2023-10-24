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

class Migrasi_fitur_premium_2305 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2304');

        $hasil = $hasil && $this->migrasi_2023041251($hasil);
        $hasil = $hasil && $this->migrasi_2023041951($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023041251($hasil)
    {
        // Hapus kolom id_pertanyaan di tabel buku_kepuasan
        if ($this->db->field_exists('id_pertanyaan', 'buku_kepuasan')) {
            $data = DB::table('buku_kepuasan as k')
                ->select('k.id', 'p.pertanyaan')
                ->join('buku_pertanyaan as p', 'p.id', '=', 'k.id_pertanyaan')
                ->where('k.pertanyaan_statis', '=', '')
                ->orWhereNull('k.pertanyaan_statis')
                ->get()
                ->pluck('pertanyaan', 'id');

            if (count($data) !== 0) {
                foreach ($data as $id => $pertanyaan_statis) {
                    $batch_pertanyaan[] = [
                        'id'                => $id,
                        'pertanyaan_statis' => $pertanyaan_statis,
                    ];
                }

                if ($batch_pertanyaan) {
                    $hasil = $hasil && $this->db->update_batch('buku_kepuasan', $batch_pertanyaan, 'id');
                }
            }
        }

        // Tambahkan kolom bidang di tabel buku_tamu
        if (! $this->db->field_exists('bidang', 'buku_tamu')) {
            $hasil = $hasil && $this->dbforge->add_column('buku_tamu', [
                'bidang' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'alamat',
                ],
            ]);

            $data = DB::table('buku_tamu as t')
                ->select('t.id', 'r.nama as bidang')
                ->join('ref_jabatan as r', 'r.id', '=', 't.id_bidang')
                ->where('t.bidang', '=', '')
                ->orWhereNull('t.bidang')
                ->get()
                ->pluck('bidang', 'id');

            if (count($data) !== 0) {
                foreach ($data as $id => $bidang) {
                    $batch_bidang[] = [
                        'id'     => $id,
                        'bidang' => $bidang,
                    ];
                }

                if ($batch_bidang) {
                    $hasil = $hasil && $this->db->update_batch('buku_tamu', $batch_bidang, 'id');
                }
            }

            $hasil = $hasil && $this->dbforge->drop_column('buku_tamu', 'id_bidang');
        }

        // Tambahkan kolom keperluan di tabel buku_tamu
        if (! $this->db->field_exists('keperluan', 'buku_tamu')) {
            $hasil = $hasil && $this->dbforge->add_column('buku_tamu', [
                'keperluan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'bidang',
                ],
            ]);

            $data = DB::table('buku_tamu as t')
                ->select('t.id', 'k.keperluan')
                ->join('buku_keperluan as k', 'k.id', '=', 't.id_keperluan')
                ->where('t.keperluan', '=', '')
                ->orWhereNull('t.keperluan')
                ->get()
                ->pluck('keperluan', 'id');

            if (count($data) !== 0) {
                foreach ($data as $id => $keperluan) {
                    $batch_keperluan[] = [
                        'id'        => $id,
                        'keperluan' => $keperluan,
                    ];
                }

                if ($batch_keperluan) {
                    $hasil = $hasil && $this->db->update_batch('buku_tamu', $batch_keperluan, 'id');
                }
            }

            $hasil = $hasil && $this->dbforge->drop_column('buku_tamu', 'id_keperluan');
        }

        return $hasil;
    }

    protected function migrasi_2023041951($hasil)
    {
        $config = DB::table('config')->get();

        if ($config->count() > 0) {
            foreach ($config as $key => $value) {
                DB::table('config')
                    ->where('id', $value->id)
                    ->update([
                        'kode_desa'      => bilangan($value->kode_desa),
                        'kode_kecamatan' => bilangan($value->kode_kecamatan),
                        'kode_kabupaten' => bilangan($value->kode_kabupaten),
                        'kode_propinsi'  => bilangan($value->kode_propinsi),
                    ]);
            }
        }

        return $hasil;
    }
}
