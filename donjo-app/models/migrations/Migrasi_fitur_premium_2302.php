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
        $hasil = $hasil && $this->migrasi_2023010852($hasil);
        $hasil = $hasil && $this->migrasi_2023010171($hasil);
        $hasil = $hasil && $this->migrasi_2023010452($hasil);
        $hasil = $hasil && $this->migrasi_2023012451($hasil);
        $hasil = $hasil && $this->migrasi_2023012571($hasil);
        $hasil = $hasil && $this->migrasi_2023012751($hasil);
        $hasil = $hasil && $this->migrasi_2023013051($hasil);
        $hasil = $hasil && $this->migrasi_2023013152($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023010171($hasil)
    {
        if (! $this->db->field_exists('pertanyaan_statis', 'buku_kepuasan')) {
            $hasil = $hasil && $this->dbforge->add_column('buku_kepuasan', [
                'pertanyaan_statis' => ['type' => 'TEXT', 'null' => true, 'default' => null, 'after' => 'id_jawaban'],
            ]);
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

    public function migrasi_2023010852($hasil)
    {
        if (! $this->db->table_exists('login_attempts')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                    'null'           => false,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
                'ip_address' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 45,
                    'null'       => false,
                ],
                'time' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
            ];

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('login_attempts', true);
        }

        return $hasil;
    }

    protected function migrasi_2023012451($hasil)
    {
        $check = $this->db
            ->where_in('Nama_Bidang', [
                'BIDANG PEMBINAAN KEMASYARAKATAN',
                'BIDANG PEMBERDAYAAN MASYARAKAT',
            ])
            ->get('keuangan_manual_ref_bidang')
            ->result_array();

        if ($check) {
            // keuangan manual ref bidang
            foreach ([
                ['3', 'BIDANG PEMBINAAN KEMASYARAKATAN DESA'],
                ['4', 'BIDANG PEMBERDAYAAN MASYARAKAT DESA'],
            ] as $value) {
                [$id, $nama_bidang] = $value;

                $hasil = $hasil && $this->db
                    ->where('id', $id)
                    ->set('Nama_Bidang', $nama_bidang)
                    ->update('keuangan_manual_ref_bidang');
            }

            // keuangan manual ref rek1
            foreach ([
                ['4', 'PENDAPATAN DESA'],
                ['5', 'BELANJA DESA'],
                ['6', 'PEMBIAYAAN DESA'],
            ] as $value) {
                [$id, $nama_akun] = $value;

                $hasil = $hasil && $this->db
                    ->where('id', $id)
                    ->set('Nama_Akun', $nama_akun)
                    ->update('keuangan_manual_ref_rek1');
            }
        }

        return $hasil;
    }

    public function migrasi_2023012571($hasil)
    {
        if (! $this->db->field_exists('nomor_operator', 'config')) {
            $hasil = $this->dbforge->add_column('config', [
                'nomor_operator' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                    'after'      => 'telepon',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2023012751($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'         => 359,
            'modul'      => 'Optimasi Gambar',
            'url'        => 'optimasi_gambar',
            'aktif'      => 1,
            'ikon'       => 'fa-picture-o',
            'urut'       => 7,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-picture-o',
            'parent'     => 11,
        ]);
    }

    protected function migrasi_2023013051($hasil)
    {
        $check = $this->db
            ->where_in('Nama_Bidang', [
                'BIDANG PEMBINAAN KEMASYARAKATAN DESA',
                'BIDANG PEMBERDAYAAN MASYARAKAT DESA',
            ])
            ->get('keuangan_manual_ref_bidang')
            ->result_array();

        if ($check) {
            // keuangan manual ref bidang
            foreach ([
                ['3', 'BIDANG PEMBINAAN KEMASYARAKATAN'],
                ['4', 'BIDANG PEMBERDAYAAN MASYARAKAT'],
            ] as $value) {
                [$id, $nama_bidang] = $value;

                $hasil = $hasil && $this->db
                    ->where('id', $id)
                    ->set('Nama_Bidang', $nama_bidang)
                    ->update('keuangan_manual_ref_bidang');
            }

            // keuangan manual ref rek1
            foreach ([
                ['4', 'PENDAPATAN'],
                ['5', 'BELANJA'],
                ['6', 'PEMBIAYAAN'],
            ] as $value) {
                [$id, $nama_akun] = $value;

                $hasil = $hasil && $this->db
                    ->where('id', $id)
                    ->set('Nama_Akun', $nama_akun)
                    ->update('keuangan_manual_ref_rek1');
            }
        }

        return $hasil;
    }

    protected function migrasi_2023013152($hasil)
    {
        // Hapus unsigned pada kolom id di tabel ref_pindah
        if (! $this->cek_indeks('log_penduduk', 'id_ref_pindah')) {
            $hasil = $hasil && $this->dbforge->modify_column('ref_pindah', [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'null'           => false,
                    'unsigned'       => false,
                ],
            ]);
        }

        return $hasil;
    }
}
