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

class Migrasi_2011_ke_2012 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Tambah kolom masa_berlaku & satuan_masa_berlaku di tweb_surat_format
        if (! $this->db->field_exists('masa_berlaku', 'tweb_surat_format')) {
            $fields = [
                'masa_berlaku' => [
                    'type'       => 'INT',
                    'constraint' => 3,
                    'default'    => '1',
                ],
                'satuan_masa_berlaku' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 15,
                    'default'    => 'M',
                ],
            ];

            $hasil = $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        // Pengaturan Token TrackSID
        if (! $this->db->field_exists('token_opensid', 'setting_aplikasi')) {
            $query = "
                INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES
                (43, 'token_opensid', '', 'Token OpenSID', '', 'sistem')
                ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)";
            $hasil = $hasil && $this->db->query($query);
        }

        status_sukses($hasil);

        return $hasil;
    }
}
