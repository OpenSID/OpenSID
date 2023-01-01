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

class Migrasi_fitur_premium_2109 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2108');

        $this->cache->hapus_cache_untuk_semua('status_langganan');
        $hasil = $hasil && $this->migrasi_2021080771($hasil);
        $hasil = $hasil && $this->migrasi_2021081851($hasil);
        $hasil = $hasil && $this->migrasi_2021082151($hasil);
        $hasil = $hasil && $this->migrasi_2021082871($hasil);
        $hasil = $hasil && $this->migrasi_2021082971($hasil);
        $hasil = $hasil && $this->migrasi_2021082972($hasil);

        return $hasil && $this->migrasi_2021082952($hasil);
    }

    protected function migrasi_2021080771($hasil)
    {
        if (! $this->db->field_exists('mac_address', 'anjungan')) {
            $hasil = $hasil && $this->dbforge->add_column('anjungan', ['mac_address' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
        }

        return $hasil;
    }

    protected function migrasi_2021081851($hasil)
    {
        // Cek log surat, hapus semua file view verifikasi berdasrkan surat yg sudah di cetak
        if ($list_data = $this->db->select('nama_surat')->get('log_surat')->result()) {
            foreach ($list_data as $data) {
                // Hapus file
                $file = LOKASI_ARSIP . '/' . str_replace('.rtf', '.php', $data->nama_surat);
                if ($data->nama_surat && file_exists($file)) {
                    $hasil = $hasil && unlink($file);
                }
            }
        }

        return $hasil;
    }

    protected function migrasi_2021082151($hasil)
    {
        $this->load->model('penduduk_model');

        // Sesuaikan struktur kolom nik di table tweb_penduduk
        $fields = [
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
            ],
        ];

        $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);

        // Ubah NIK 0 jadi 0[kode-desa-10-digit];
        $list_data = $this->db->select('id, nik')->get_where('tweb_penduduk', ['nik' => '0'])->result();
        if ($list_data) {
            foreach ($list_data as $data) {
                $nik_sementara = $this->penduduk_model->nik_sementara();
                $hasil         = $hasil && $this->db->where('id', $data->id)->update('tweb_penduduk', ['nik' => $nik_sementara]);
            }
        }

        return $hasil && $this->tambahIndeks('tweb_penduduk', 'nik');
    }

    protected function migrasi_2021082871($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 327,
            'modul'      => 'Lembaga [Desa]',
            'url'        => 'lembaga/clear',
            'aktif'      => 1,
            'ikon'       => 'fa-list',
            'urut'       => 4,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-list',
            'parent'     => 200,
        ]);

        $hasil = $hasil && $this->tambah_modul([
            'id'     => 328,
            'modul'  => 'Kategori Lembaga',
            'url'    => 'lembaga_master',
            'aktif'  => 1,
            'hidden' => 2,
            'parent' => 327,
        ]);

        if (! $this->db->field_exists('tipe', 'kelompok')) {
            $hasil = $hasil && $this->dbforge->add_column('kelompok', ['tipe' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'kelompok']]);
        }

        if (! $this->db->field_exists('tipe', 'kelompok_anggota')) {
            $hasil = $hasil && $this->dbforge->add_column('kelompok_anggota', [
                'tipe'                 => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'kelompok'],
                'periode'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'nmr_sk_pengangkatan'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'tgl_sk_pengangkatan'  => ['type' => 'date', 'null' => true],
                'nmr_sk_pemberhentian' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'tgl_sk_pemberhentian' => ['type' => 'date', 'null' => true],
            ]);
        }

        if (! $this->db->field_exists('tipe', 'kelompok_master')) {
            $hasil = $hasil && $this->dbforge->add_column('kelompok_master', ['tipe' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'kelompok']]);
        }

        return $hasil;
    }

    protected function migrasi_2021082971($hasil)
    {
        if (! $this->db->field_exists('no_antrian', 'permohonan_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('permohonan_surat', ['no_antrian' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
        }

        if (! $this->db->field_exists('printer_ip', 'anjungan')) {
            $hasil = $hasil && $this->dbforge->add_column('anjungan', ['printer_ip' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
        }

        if (! $this->db->field_exists('printer_port', 'anjungan')) {
            $hasil = $hasil && $this->dbforge->add_column('anjungan', ['printer_port' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
        }

        return $hasil;
    }

    protected function migrasi_2021082972($hasil)
    {
        if (! $this->db->field_exists('sumber_biaya_pemerintah', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sumber_biaya_pemerintah' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        }

        if (! $this->db->field_exists('sumber_biaya_provinsi', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sumber_biaya_provinsi' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        }

        if (! $this->db->field_exists('sumber_biaya_provinsi', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sumber_biaya_provinsi' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        }

        if (! $this->db->field_exists('sumber_biaya_kab_kota', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sumber_biaya_kab_kota' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        }

        if (! $this->db->field_exists('sumber_biaya_swadaya', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sumber_biaya_swadaya' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        }

        if (! $this->db->field_exists('sumber_biaya_jumlah', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sumber_biaya_jumlah' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        }

        if (! $this->db->field_exists('manfaat', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', ['manfaat' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
        }

        return $hasil;
    }

    protected function migrasi_2021082951($hasil)
    {
        $fields = [
            'tempat_cetak_ktp' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);
    }

    protected function migrasi_2021082952($hasil)
    {
        $gol6 = $this->db->where('golongan', 6)->where('bidang', '00')->where('nama', 'KONSTRUKSI DALAM PENGERJAAN')->get('tweb_aset')->row();
        if ($gol6) {
            $hasil = $hasil && $this->db->where('golongan', 6)->update('tweb_aset', ['golongan' => 7]);
        }

        $gol5 = $this->db->where('golongan', 5)->where('bidang', '00')->where('nama', 'ASET TETAP LAINNYA')->get('tweb_aset')->row();
        if ($gol5) {
            $hasil = $hasil && $this->db->where('golongan', 5)->update('tweb_aset', ['golongan' => 6]);
            $hasil = $hasil && $this->db->query("UPDATE inventaris_asset SET register = CONCAT('6', SUBSTRING(register, 2));");
        }

        $gol4 = $this->db->where('golongan', 4)->where('bidang', '00')->where('nama', 'JALAN')->get('tweb_aset')->row();
        if ($gol4) {
            $hasil = $hasil && $this->db->where('golongan', 4)->update('tweb_aset', ['golongan' => 5]);
            $hasil = $hasil && $this->db->query("UPDATE inventaris_jalan SET register = CONCAT('5', SUBSTRING(register, 2));");
        }

        $gol3 = $this->db->where('golongan', 3)->where('bidang', '00')->where('nama', 'GEDUNG DAN BANGUNAN')->get('tweb_aset')->row();
        if ($gol3) {
            $hasil = $hasil && $this->db->where('golongan', 3)->update('tweb_aset', ['golongan' => 4]);
            $hasil = $hasil && $this->db->query("UPDATE inventaris_gedung SET register = CONCAT('4', SUBSTRING(register, 2));");
        }

        $gol2 = $this->db->where('golongan', 2)->where('bidang', '00')->where('nama', 'PERALATAN DAN MESIN')->get('tweb_aset')->row();
        if ($gol2) {
            $hasil = $hasil && $this->db->where('golongan', 2)->update('tweb_aset', ['golongan' => 3]);
            $hasil = $hasil && $this->db->query("UPDATE inventaris_peralatan SET register = CONCAT('3', SUBSTRING(register, 2));");
        }

        $gol1 = $this->db->where('golongan', 1)->where('bidang', '00')->where('nama', 'TANAH')->get('tweb_aset')->row();
        if ($gol1) {
            $hasil = $hasil && $this->db->where('golongan', 1)->update('tweb_aset', ['golongan' => 2]);
            $hasil = $hasil && $this->db->query("UPDATE inventaris_tanah SET register = CONCAT('2', SUBSTRING(register, 2));");
        }

        return $hasil;
    }
}