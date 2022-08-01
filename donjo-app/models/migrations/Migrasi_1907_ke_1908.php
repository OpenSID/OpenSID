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

class Migrasi_1907_ke_1908 extends CI_model
{
    public function up()
    {
        // Tambah kolom asaldana dan modify kolom status
        if (! $this->db->field_exists('asaldana', 'program')) {
            $fields             = [];
            $fields['asaldana'] = [
                'type'       => 'char',
                'constraint' => 30,
                'null'       => true,
                'default'    => null,
            ];
            $this->dbforge->add_column('program', $fields);
        }
        $fields           = [];
        $fields['status'] = [
            'type'       => 'tinyint',
            'constraint' => 1,
            'null'       => false,
            'default'    => 0,
        ];
        if (! $this->db->field_exists('status', 'program')) {
            $this->dbforge->add_column('program', $fields);
        } else {
            $this->dbforge->modify_column('program', $fields);
        }

        // Tambah kolom pengurus untuk ttd u.b
        if (! $this->db->field_exists('pamong_ub', 'tweb_desa_pamong')) {
            $fields              = [];
            $fields['pamong_ub'] = [
                'type'       => 'tinyint',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ];
            $this->dbforge->add_column('tweb_desa_pamong', $fields);
            // Pindahkan setting sebutan_pimpinan_desa ke tweb_desa_pamong, terus hapus
            $this->load->model('pamong_model');
            $sebutan_pimpinan_desa = $this->db->where('key', 'sebutan_pimpinan_desa')->get('setting_aplikasi')->row()->value;
            $ttd                   = $this->db->limit(1)->where('jabatan', $sebutan_pimpinan_desa)->get('tweb_desa_pamong')->row()->pamong_id;
            $this->pamong_model->ttd('pamong_ttd', $ttd, 1);
            $this->db->where('key', 'sebutan_pimpinan_desa')->delete('setting_aplikasi');
        }
        // Setting nilai default supaya tidak error pada strict mode
        $fields              = [];
        $fields['id_kepala'] = ['type' => 'INT', 'constraint' => 11, 'null' => true, 'default' => null];
        $fields['lat']       = ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'default' => null];
        $fields['lng']       = ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'default' => null];
        $fields['zoom']      = ['type' => 'INT', 'null' => true, 'default' => null];
        $fields['path']      = ['type' => 'TEXT', 'constraint' => 11, 'null' => true, 'default' => null];
        $fields['map_tipe']  = ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'default' => null];
        $this->dbforge->modify_column('tweb_wil_clusterdesa', $fields);
        // Tambah kolom kode untuk setting_aplikasi_options
        if (! $this->db->field_exists('kode', 'setting_aplikasi_options')) {
            $fields         = [];
            $fields['kode'] = ['type' => 'TINYINT', 'constraint' => 4, 'null' => true, 'default' => null];
            $this->dbforge->add_column('setting_aplikasi_options', $fields);
        }
        // Perbaiki setting offline_mode
        $this->db->where('key', 'offline_mode')->update('setting_aplikasi', ['jenis' => 'option-kode']);
        $setting_id = $this->db->select('id')->where('key', 'offline_mode')->get('setting_aplikasi')->row()->id;
        $this->db->where('id_setting', $setting_id)->delete('setting_aplikasi_options');
        $this->db->insert_batch(
            'setting_aplikasi_options',
            [
                ['id_setting' => $setting_id, 'kode' => '0', 'value' => 'Web bisa diakses publik'],
                ['id_setting' => $setting_id, 'kode' => '1', 'value' => 'Web hanya bisa diakses petugas web'],
                ['id_setting' => $setting_id, 'kode' => '2', 'value' => 'Web non-aktif sama sekali'],
            ]
        );
        // Tambah Surat Perintah Perjalanan Dinas
        // Tambah surat keterangan penghasilan orangtua
        $data = [
            'nama'       => 'Perintah Perjalanan Dinas',
            'url_surat'  => 'surat_perintah_perjalanan_dinas',
            'kode_surat' => 'S-46',
            'jenis'      => 1, ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);
        // Tambah surat kuasa
        $data = [
            'nama'       => 'Kuasa',
            'url_surat'  => 'surat_kuasa',
            'kode_surat' => 'S-43',
            'jenis'      => 1, ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);
    }
}
