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

class Migrasi_fitur_premium_2104 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2103');

        // Hapus id_peristiwa = 1 lama di log_keluarga karena pengertiannya sudah tidak konsisten dengan penggunaan yg baru. Sekarang hanya terbatas pada keluarga baru yg dibentuk dari penduduk yg sudah ada.

        // Jangan jalankan jika log_keluarga telah diisi ulang (di Migrasi_fitur_premium_2105.php)

        if (! $this->db->field_exists('id_pend', 'log_keluarga')) {
            $hasil = $hasil && $this->db
                ->where('id_peristiwa', 1)
                ->where("date(tgl_peristiwa) < '2021-03-04'")
                ->delete('log_keluarga');
        }

        // Buat tabel url shortener
        $hasil = $hasil && $this->buat_tabel_url_shortener($hasil);
        // Buat tabel url statistik
        $hasil = $hasil && $this->buat_tabel_url_statistik($hasil);
        // Tambah field qr_code pada tabel tweb_surat_format
        $hasil = $hasil && $this->field_qr_code($hasil);
        // Ubah field tag_id_card menjadi index dan unique
        $hasil = $hasil && $this->ubah_tag_id_card_unique_index($hasil);
        // Sesuaikan struktur dan isi tabel config
        $hasil = $hasil && $this->config($hasil);
        // Sesuaikan sulang STATUS_PERMOHONAN
        $hasil = $hasil && $this->ubah_status($hasil);
        // Sesuaikan struktur tabel analisis_indikator
        $hasil = $hasil && $this->analisis_indikator($hasil);
        // Sesuaikan data kartu peserta bantuan
        $hasil = $hasil && $this->kartu_bantuan($hasil);
        // Sesuaikan key offline mode
        return $hasil && $this->ubah_setting_offline_mode($hasil);
    }

    // Buat tabel url shortener
    protected function buat_tabel_url_shortener($hasil)
    {
        $this->dbforge->add_field([
            'id'      => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'url'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'alias'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'created' => ['type' => 'datetime', 'null' => false],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('alias');

        return $hasil && $this->dbforge->create_table('urls', true);
    }

    // Buat tabel url statistik
    protected function buat_tabel_url_statistik($hasil)
    {
        $this->dbforge->add_field([
            'id'      => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'url_id'  => ['type' => 'INT', 'null' => false],
            'created' => ['type' => 'datetime', 'null' => false],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('url_id');

        return $hasil && $this->dbforge->create_table('statistics', true);
    }

    // Tambah field qr_code pada tabel tweb_surat_format
    protected function field_qr_code($hasil)
    {
        if (! $this->db->field_exists('qr_code', 'tweb_surat_format')) {
            $fields = [
                'qr_code' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        return $hasil;
    }

    // Indeksasi field tag_id_card
    protected function ubah_tag_id_card_unique_index($hasil)
    {
        $hasil = $hasil && $this->db
            ->set('tag_id_card', null)
            ->where('tag_id_card', '')
            ->update('tweb_penduduk');

        return $hasil && $this->tambahIndeks('tweb_penduduk', 'tag_id_card');
    }

    // Tabel Config
    protected function config($hasil)
    {
        // Buat fild pamong_id
        if (! $this->db->field_exists('pamong_id', 'config')) {
            $fields = [
                'pamong_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('config', $fields);

            // Sesuaikan data kepala desa dengan data pamong, ubah menjadi pamong_id
            $config = $this->db->select('*')->limit(1)->get('config')->row_array();

            $pamong_id = $this->db
                ->select('pamong_id')
                ->where(['pamong_nama' => $config['nama_kepala_desa'], 'pamong_nip' => $config['nip_kepala_desa']])
                ->get('tweb_desa_pamong')
                ->row()
                ->pamong_id;

            if ($pamong_id) {
                $this->db->where('id', $config['id'])->update('config', ['pamong_id' => $pamong_id]);
            }

            // Hapus field nama_kepala_desa dan nip_kepala_desa
            if ($this->db->field_exists('nama_kepala_desa', 'config')) {
                $hasil = $hasil && $this->dbforge->drop_column('config', 'nama_kepala_desa');
            }

            if ($this->db->field_exists('nip_kepala_desa', 'config')) {
                $hasil = $hasil && $this->dbforge->drop_column('config', 'nip_kepala_desa');
            }
        }

        return $hasil;
    }

    // Tabel Config
    protected function ubah_status($hasil)
    {
        // Jika masih ditemakan status = 9, lakukan migrasi
        if ($this->db->get_where('permohonan_surat', ['status' => 9])->row()) {
            // Ubah sementara
            $hasil = $hasil && $this->db->where('status', 0)->update('permohonan_surat', ['status' => 100]);

            // Sesuaikan ulang
            $hasil = $hasil && $this->db->where('status', 1)->update('permohonan_surat', ['status' => 0]);
            $hasil = $hasil && $this->db->where('status', 100)->update('permohonan_surat', ['status' => 1]);
            $hasil = $hasil && $this->db->where('status', 9)->update('permohonan_surat', ['status' => 5]);
        }

        return $hasil;
    }

    protected function analisis_indikator($hasil)
    {
        $fields = [
            'nomor' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('analisis_indikator', $fields);
    }

    protected function kartu_bantuan($hasil)
    {
        // Pastikan data kartu peserta tidak kosong
        $kartu_kosong = $this->db
            ->select('k.*, p.nik, p.nama, p.tempatlahir, p.tanggallahir')
            ->select("concat('RT ', w.rt, ' / RW ', w.rw, ' DUSUN ', w.dusun) AS alamat")
            ->from('program_peserta k')
            ->join('tweb_penduduk p', 'p.id = k.kartu_id_pend', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->where("kartu_nik is NULL or kartu_nik = ''")
            ->or_where("kartu_nama is NULL or kartu_nama = ''")
            ->or_where("kartu_tempat_lahir is NULL or kartu_tempat_lahir = ''")
            ->or_where('kartu_tanggal_lahir is NULL')
            ->or_where("kartu_alamat is NULL or kartu_alamat = ''")
            ->get()
            ->result_array();

        if ($kartu_kosong) {
            foreach ($kartu_kosong as $kartu) {
                $data = [
                    'kartu_nik'           => $kartu['nik'] ?? 0,
                    'kartu_nama'          => $kartu['nama'] ?? '',
                    'kartu_tempat_lahir'  => $kartu['tempatlahir'] ?? '',
                    'kartu_tanggal_lahir' => $kartu['tanggallahir'] ?? date('Y-m-d H:i:s'),
                    'kartu_alamat'        => $kartu['alamat'] ?? '',
                ];
                $hasil = $hasil && $this->db->where('id', $kartu['id'])->update('program_peserta', $data);
            }
        }

        // Ubah kolom supaya tidak boleh kosong
        $fields = [
            'kartu_nik'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => false],
            'kartu_nama'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'kartu_tempat_lahir'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'kartu_tanggal_lahir' => ['type' => 'DATE', 'null' => false],
            'kartu_alamat'        => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
        ];

        return $hasil && $this->dbforge->modify_column('program_peserta', $fields);
    }

    protected function ubah_setting_offline_mode($hasil)
    {
        $hasil = $hasil && $this->db->where('value', 'Web bisa diakses publik')->update('setting_aplikasi', ['value' => 0]);
        $hasil = $hasil && $this->db->where('value', 'Web hanya bisa diakses petugas web')->update('setting_aplikasi', ['value' => 1]);
        $hasil = $hasil && $this->db->where('value', 'Web non-aktif sama sekali')->update('setting_aplikasi', ['value' => 2]);

        return $hasil && $this->db->where('key', 'offline_mode')->update('setting_aplikasi', ['jenis' => 'option-kode']);
    }
}
