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

class Migrasi_1908_ke_1909 extends CI_model
{
    public function up()
    {
        if (! $this->db->table_exists('keluarga_aktif')) {
            $sql = 'CREATE VIEW keluarga_aktif AS SELECT k.*
	  			FROM tweb_keluarga k
	  			LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id
	  			WHERE p.status_dasar = 1';
            $this->db->query($sql);
        }
        // Tambah kolom slug untuk artikel
        if (! $this->db->field_exists('slug', 'artikel')) {
            $fields         = [];
            $fields['slug'] = [
                'type'       => 'varchar',
                'constraint' => 200,
                'null'       => true,
                'default'    => null,
            ];
            $this->dbforge->add_column('artikel', $fields);
        }
        // Tambahkan slug untuk setiap artikel yg belum memiliki
        $list_artikel = $this->db->select('id, judul, slug')->get('artikel')->result_array();
        if ($list_artikel) {
            foreach ($list_artikel as $artikel) {
                if (! empty($artikel['slug'])) {
                    continue;
                }
                $slug = url_title($artikel['judul'], 'dash', true);
                $this->db->where('id', $artikel['id'])->update('artikel', ['slug' => $slug]);
            }
        }

        //tambah kolom keterangan untuk log_surat
        if (! $this->db->field_exists('keterangan', 'log_surat')) {
            $fields               = [];
            $fields['keterangan'] = [
                'type'       => 'varchar',
                'constraint' => 200,
                'null'       => true,
                'default'    => null,
            ];
            $this->dbforge->add_column('log_surat', $fields);
        }
    }
}
