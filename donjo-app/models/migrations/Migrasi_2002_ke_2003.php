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

class Migrasi_2002_ke_2003 extends CI_model
{
    public function up()
    {
        $this->surat_mandiri();
        $this->surat_mandiri_tersedia();
        $this->mailbox();

        // Hapus setting tombol cetak surat langsung
        $this->db->where('key', 'tombol_cetak_surat')
            ->delete('setting_aplikasi');
        // Setting nilai default supaya tidak error pada sql strict mode
        $fields            = [];
        $fields['parrent'] = ['type' => 'INT', 'constraint' => 4, 'default' => 0];
        $fields['tipe']    = ['type' => 'INT', 'constraint' => 4, 'default' => 0];
        $this->dbforge->modify_column('gambar_gallery', $fields);
        $fields               = [];
        $fields['kode_surat'] = ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true, 'default' => null];
        $this->dbforge->modify_column('tweb_surat_format', $fields);
        // Tambah kolom hits pada artikel
        if (! $this->db->field_exists('hit', 'artikel')) {
            $this->db->query("ALTER TABLE artikel ADD COLUMN hit INT NULL DEFAULT '0'");
        }
        // Tambah Modul Pengunjung pada Admin WEB
        $data = [
            'id'         => 205,
            'modul'      => 'Pengunjung',
            'url'        => 'pengunjung/clear',
            'aktif'      => 1,
            'ikon'       => 'fa-bar-chart',
            'urut'       => 10,
            'level'      => 4,
            'hidden'     => 0,
            'ikon_kecil' => '',
            'parent'     => 13,
        ];
        $sql = $this->db->insert_string('setting_modul', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				aktif = VALUES(aktif),
				ikon = VALUES(ikon),
				urut = VALUES(urut),
				level = VALUES(level),
				hidden = VALUES(hidden),
				ikon_kecil = VALUES(ikon_kecil),
				parent = VALUES(parent)
				';
        $this->db->query($sql);
    }

    private function surat_mandiri()
    {
        // Table ref_syarat_surat tempat nama dokumen sbg syarat Permohonan surat
        if (! $this->db->table_exists('ref_syarat_surat')) {
            $this->dbforge->add_field([
                'ref_syarat_id' => [
                    'type'           => 'INT',
                    'constraint'     => 1,
                    'unsigned'       => true,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'ref_syarat_nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
            ]);
            $this->dbforge->add_key('ref_syarat_id', true);
            $this->dbforge->create_table('ref_syarat_surat', true);

            // Menambahkan Data Table ref_syarat_surat
            $query = "
	    INSERT INTO `ref_syarat_surat` (`ref_syarat_id`, `ref_syarat_nama`) VALUES
		    (1, 'Surat Pengantar RT/RW'),
		    (2, 'Fotokopi KK'),
		    (3, 'Fotokopi KTP'),
		    (4, 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan'),
		    (5, 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak'),
		    (6, 'Surat Pindah Datang dari tempat asal'),
		    (7, 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter'),
		    (8, 'Surat Keterangan Cerai'),
		    (9, 'Fotokopi Ijasah Terakhir'),
		    (10, 'SK. PNS/KARIP/SK. TNI – POLRI'),
		    (11, 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan'),
		    (12, 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)');
	    ";
            $this->db->query($query);
        }

        // Table syarat_surat sbg link antara surat yg dimohon dan dokumen yg diperlukan
        if (! $this->db->table_exists('syarat_surat')) {
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'surat_format_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'null'       => false,

                ],
                'ref_syarat_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'null'       => false,

                ],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table('syarat_surat', true);
            $this->dbforge->add_column(
                'syarat_surat',
                ['CONSTRAINT `id_surat_format` FOREIGN KEY (`surat_format_id`) REFERENCES `tweb_surat_format` (`id`) ON DELETE CASCADE ON UPDATE CASCADE']
            );
        }

        // Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
        $data   = [];
        $data[] = [
            'id'         => '97',
            'modul'      => 'Daftar Persyaratan',
            'url'        => 'surat_mohon',
            'aktif'      => '1',
            'ikon'       => 'fa fa-book',
            'urut'       => '5',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => '',
            'parent'     => 4, ];

        foreach ($data as $modul) {
            $sql = $this->db->insert_string('setting_modul', $modul);
            $sql .= ' ON DUPLICATE KEY UPDATE
                id = VALUES(id),
                modul = VALUES(modul),
                url = VALUES(url),
                aktif = VALUES(aktif),
                ikon = VALUES(ikon),
                urut = VALUES(urut),
                level = VALUES(level),
                hidden = VALUES(hidden),
                ikon_kecil = VALUES(ikon_kecil),
                parent = VALUES(parent)';
            $this->db->query($sql);
        }

        // Tambah kolom tanda surat yg tersedia untuk layanan mandiri
        if (! $this->db->field_exists('mandiri', 'tweb_surat_format')) {
            $this->db->query('ALTER TABLE tweb_surat_format ADD mandiri tinyint(1) default 0');
        }

        // Tabel mendaftarkan permohonan surat dari layanan mandiri
        if (! $this->db->table_exists('permohonan_surat')) {
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'id_pemohon' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'id_surat' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'isian_form' => [
                    'type' => 'TEXT',
                ],
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'no_hp_aktif' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'syarat' => [
                    'type' => 'TEXT',
                ],
            ]);
            $this->dbforge->add_field('created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $this->dbforge->add_field('updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table('permohonan_surat', true);
        }
        // Menu permohonan surat untuk operator
        $modul = [
            'id'         => '98',
            'modul'      => 'Permohonan Surat',
            'url'        => 'permohonan_surat_admin/clear',
            'aktif'      => '1',
            'ikon'       => 'fa-files-o',
            'urut'       => '0',
            'level'      => '0',
            'parent'     => '14',
            'hidden'     => '0',
            'ikon_kecil' => '',
        ];
        $sql = $this->db->insert_string('setting_modul', $modul) . ' ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), ikon = VALUES(ikon), parent = VALUES(parent)';
        $this->db->query($sql);
    }

    private function surat_mandiri_tersedia()
    {
        // Surat yg tersedia di permohonan surat melalui layanan mandiri plus syarat masing2
        $surat_tersedia = [
            1  => [1, 2, 3], //surat_ket_pengantar
            2  => [2, 3], //surat_ket_penduduk
            3  => [2, 3], //surat_bio_penduduk
            5  => [1, 2, 3], //surat_ket_pindah_penduduk
            6  => [1, 2, 3], //surat_ket_jual_beli
            8  => [1, 2, 3], //surat_ket_catatan_kriminal
            9  => [2, 3], //surat_ket_ktp_dalam_proses
            10 => [1, 2, 3], //surat_ket_beda_nama
            11 => [1, 2, 3], //surat_jalan
            12 => [1, 2, 3], //surat_ket_kurang_mampu
            13 => [1, 2, 3], //surat_izin_keramaian
        ];

        foreach ($surat_tersedia as $surat_format_id => $list_syarat) {
            $this->db->where('id', $surat_format_id)->update('tweb_surat_format', ['mandiri' => 1]);

            if ($list_syarat) {
                foreach ($list_syarat as $syarat_id) {
                    $ada = $this->db->where('surat_format_id', $surat_format_id)->where('ref_syarat_id', $syarat_id)
                        ->get('syarat_surat')->num_rows();
                    if (! $ada) {
                        $this->db->insert('syarat_surat', ['surat_format_id' => $surat_format_id, 'ref_syarat_id' => $syarat_id]);
                    }
                }
            }
        }
    }

    private function mailbox()
    {
        $modul_mailbox = [
            'modul' => 'Kotak Pesan',
            'url'   => 'mailbox/clear',
        ];

        $this->db
            ->where('id', '55')
            ->update('setting_modul', $modul_mailbox);

        // Tambahkan kolom untuk menandai apakah pesan diarsipkan atau belum
        if (! $this->db->field_exists('is_archived', 'komentar')) {
            $fields = [
                'is_archived' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
            ];
            $this->dbforge->add_column('komentar', $fields);
        }

        // ubah nama kolom menjadi status untuk penanda status di mailbox
        if ($this->db->field_exists('enabled', 'komentar')) {
            $this->dbforge->modify_column('komentar', [
                'enabled' => [
                    'name'       => 'status',
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                ],
            ]);
        }

        // Tambahkan kolom tipe untuk membedakan pesan inbox dan outbox
        if (! $this->db->field_exists('tipe', 'komentar')) {
            $fields = [
                'tipe' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'after'      => 'status',
                ],
            ];
            $this->dbforge->add_column('komentar', $fields);
        }

        // Paksa data lapor yang sudah ada memiliki tipe inbox
        $tipe = [
            'tipe' => '1',
        ];
        $this->db
            ->where('id_artikel', '775')
            ->where('tipe', null)
            ->update('komentar', $tipe);

        // Tambahkan kolom subjek untuk digunakan di menu mailbox
        if (! $this->db->field_exists('subjek', 'komentar')) {
            $this->dbforge->add_column('komentar', [
                'subjek' => [
                    'type'  => 'TINYTEXT',
                    'after' => 'email',
                ],
            ]);
        }

        $subjek = [
            'subjek' => 'Tidak ada subjek pesan',
        ];
        $this->db
            ->where('id_artikel', '775')
            ->where('subjek', null)
            ->update('komentar', $subjek);

        // Tambahkan kolom id_syarat untuk link ke dokumen syarat
        if (! $this->db->field_exists('id_syarat', 'dokumen')) {
            $fields = [
                'id_syarat' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'after'      => 'deleted',
                ],
            ];
            $this->dbforge->add_column('dokumen', $fields);
        }
    }
}
