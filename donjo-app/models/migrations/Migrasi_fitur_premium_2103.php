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

class Migrasi_fitur_premium_2103 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2102');

        // Updates for issues #2834
        $hasil = $hasil && $this->penduduk_sementara($hasil);
        // Updates for issues #2835
        return $hasil && $this->ktp_kk($hasil);
    }

    protected function penduduk_sementara($hasil)
    {
        // Menambahkan column maksud_tujuan_kedatangan pada table tweb_penduduk, digunakan untuk define maksud dan tujuan kedatangan penduduk tidak tetap
        if (! $this->db->field_exists('maksud_tujuan_kedatangan', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', [
                'maksud_tujuan_kedatangan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
            ]);
        }

        // Menambahkan column negara_asal pada table tweb_penduduk, digunakan untuk define negara asal untuk WNA
        if (! $this->db->field_exists('negara_asal', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', [
                'negara_asal' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
            ]);
        }

        // Tambah status dasar 'PERGI'
        $data = [
            'id'   => 6,
            'nama' => 'PERGI', ];
        $sql = $this->db->insert_string('tweb_status_dasar', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
                id = VALUES(id),
                nama = VALUES(nama)';
        $this->db->query($sql);

        // Tambah ref peristiwa 'PERGI'
        $data = [
            'id'   => 6,
            'nama' => 'Pergi', ];
        $sql = $this->db->insert_string('ref_peristiwa', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
                id = VALUES(id),
                nama = VALUES(nama)';
        $this->db->query($sql);

        return $hasil;
    }

    protected function ktp_kk($hasil)
    {
        // Menambahkan column tempat_cetak_ktp pada table tweb_penduduk, digunakan untuk define tempat penerbitan KTP untuk penduduk yang sudah memiliki KTP-EL
        if (! $this->db->field_exists('tempat_cetak_ktp', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', [
                'tempat_cetak_ktp' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
            ]);
        }

        // Menambahkan column tanggal_cetak_ktp pada table tweb_penduduk, digunakan untuk define tanggal penerbitan KTP untuk penduduk yang sudah memiliki KTP-EL
        if (! $this->db->field_exists('tanggal_cetak_ktp', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', [
                'tanggal_cetak_ktp' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
            ]);
        }

        return $hasil;
    }
}