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

class Migrasi_2005_ke_2006 extends CI_model
{
    public function up()
    {
        $this->grup_akses_covid19();
        $this->load->model('migrations/migrasi_2004_ke_2005');
        $this->migrasi_2004_ke_2005->up(); // untuk yang sudah terlanjur mengkosongkan DB sebelum kosongkan_db diperbaiki

        // Ubah nama kode status penduduk
        $this->db->where('id', 2)
            ->update('tweb_penduduk_status', ['nama' => 'TIDAK TETAP']);

        //Ganti nama folder widget menjadi widgets
        rename('desa/widget', 'desa/widgets');
        rename('desa/upload/widget', 'desa/upload/widgets');
        // Arahkan semua widget statis ubahan desa ke folder desa/widgets
        $list_widgets = $this->db->where('jenis_widget', 2)->get('widget')->result_array();

        if ($list_widgets) {
            foreach ($list_widgets as $widgets) {
                $ganti = str_replace('desa/widget', 'desa/widgets', $widgets['isi']); // Untuk versi 20.04-pasca ke atas
                $cek   = explode('/', $ganti); // Untuk versi 20.04 ke bawah
                if ($cek[0] !== 'desa' && $cek[1] === null) { // agar migrasi bisa dijalankan berulang kali
                    $this->db->where('id', $widgets['id'])->update('widget', ['isi' => 'desa/widgets/' . $widgets['isi']]);
                }
            }
        }
        // Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
        $this->db->query('ALTER TABLE outbox MODIFY COLUMN CreatorID text NULL');
        // Hapus field sasaran
        if ($this->db->field_exists('sasaran', 'program_peserta')) {
            $this->db->query('ALTER TABLE `program_peserta` DROP COLUMN `sasaran`');
        }
        //tambah kolom email di tabel tweb_penduduk
        if (! $this->db->field_exists('email', 'tweb_penduduk')) {
            $this->dbforge->add_column('tweb_penduduk', [
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
            ]);
        }
        //tambah kolom kantor_desa di tabel config
        if (! $this->db->field_exists('kantor_desa', 'config')) {
            $this->dbforge->add_column('config', [
                'kantor_desa' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
            ]);
        }
    }

    private function grup_akses_covid19()
    {
        // Menambahkan menu 'Group / Hak Akses' covid19 table 'user_grup'
        $data[] = [
            'id'         => '5',
            'nama'       => 'Satgas Covid-19',
            'updated_by' => $this->session->user,
        ];

        // karena di versi ini belum ada updated_by, tp dilakukan migrasi mundur jadi updated_by perlu diisi
        foreach ($data as $grup) {
            $sql = $this->db->insert_string('user_grup', $grup);
            $sql .= ' ON DUPLICATE KEY UPDATE
			id = VALUES(id),
			nama = VALUES(nama),
            updated_by = VALUES(updated_by)';
            $this->db->query($sql);
        }
    }
}
