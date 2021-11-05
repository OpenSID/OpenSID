<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2111_ke_2112.php
 *
 */

/**
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
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Migrasi_2111_ke_2112 extends MY_model {

	public function up()
	{
		if (!$this->db->table_exists('tweb_penduduk_kader_berdaya'))
		{
			$query1 = "
            CREATE TABLE `tweb_penduduk_kader_berdaya` (
                `id` INT(5) NOT NULL AUTO_INCREMENT,
                `idpenduduk` INT(11) NOT NULL,
                `umur` VARCHAR(25) NOT NULL COLLATE 'latin1_general_ci',
                `jeniskelamin` VARCHAR(10) NOT NULL COLLATE 'latin1_general_ci',
                `pendidikankursus` TEXT NOT NULL DEFAULT '' COLLATE 'latin1_general_ci',
                `pendidikanahli` TEXT NOT NULL DEFAULT '' COLLATE 'latin1_general_ci',
                `keterangan` TEXT NOT NULL COLLATE 'latin1_general_ci',
                PRIMARY KEY (`id`) USING BTREE
            ) ";
			

            $query2 = "
            CREATE TABLE `tweb_penduduk_keahlian` (
                `id` INT(5) NOT NULL AUTO_INCREMENT,
                `nama` TEXT NOT NULL DEFAULT '' COLLATE 'latin1_general_ci',
                PRIMARY KEY (`id`) USING BTREE
            )";

            $query3 = "INSERT INTO tweb_penduduk_keahlian (`id`, `nama`) VALUES
            (1, 'Service Komputer'),
            (2, 'Operator Buldoser'),
            (3, 'Operator Komputer'),
            (4, 'Operator Genset'),
            (5, 'Service HP'),
            (6, 'Rias Pengantin'),
            (7, 'Design Grafis'),
            (8, 'Menjahit'),
            (9, 'Menulis'),
            (10, 'Reporter'),
            (11, 'Sosial Media Manajer'),
            (12, 'Manajemen Trainee'),
            (13, 'Kasir'),
            (14, 'HRD'),
            (15, 'Guru'),
            (16, 'Digital Marketing'),
            (17, 'Customer Services'),
            (18, 'Welder'),
            (19, 'Mekanik Alat Berat'),
            (20, 'Teknisi Listrik'),
            (21, 'Internet Marketing')";

            $query4 = "
            CREATE TABLE `tweb_penduduk_kursus` (
                `id` INT(5) NOT NULL AUTO_INCREMENT,
                `nama` TEXT NOT NULL DEFAULT '' COLLATE 'latin1_general_ci',
                PRIMARY KEY (`id`) USING BTREE
            )";

            $query5 = "INSERT INTO tweb_penduduk_kursus (`id`, `nama`) VALUES
            (1, 'Kursus Komputer'),
            (2, 'Kursus Menjahit'),
            (3, 'Pelatihan Kelistrikan'),
            (4, 'Kursus Mekanik Motor'),
            (5, 'Pelatihan Security'),
            (6, 'Kursus Otomotif'),
            (7, 'Kursus Bahasa Inggris'),
            (8, 'Kursus Tata Kecantikan Kulit'),
            (9, 'Kursus Megemudi'),
            (10, 'Kursus Tata Boga'),
            (11, 'Kursus Meubeler'),
            (12, 'Kursus Las'),
            (13, 'Kursus Sablon'),
            (14, 'Kursus Penerbangan'),
            (15, 'Kursus Desain Interior'),
            (16, 'Kursus Teknisi HP'),
            (17, 'Kursus Garment'),
            (18, 'Kursus Akupuntur'),
            (19, 'Kursus Senam'),
            (20, 'Kursus Pendidik PAUD'),
            (21, 'Kursus Baby Sitter'),
            (22, 'Kursus Desain Grafis'),
            (23, 'Kursus Bahasa Indonesia'),
            (24, 'Kursus Photografi'),
            (25, 'Kursus Expor Impor'),
            (26, 'Kursus Jurnalistik'),
            (27, 'Kursus Bahasa Arab'),
            (28, 'Kursus Bahasa Jepang'),
            (29, 'Kursus Anak Buah Kapal'),
            (30, 'Kursus Refleksi'),
            (31, 'Kursus Akupuntur'),
            (32, 'Kursus Perhotelan'),
            (33, 'Kursus Tata Rias'),
            (34, 'Kursus Administrasi Perkantoran'),
            (35, 'Kursus Broadcasting'),
            (36, 'Kursus Kerajinan Tangan'),
            (37, 'Kursus Sosial Media Marketing'),
            (38, 'Kursus Internet Marketing'),
            (39, 'Kursus Sekretaris'),
            (40, 'Kursus Perpajakan'),
            (41, 'Kursus Publik Speaking'),
            (42, 'Kursus Publik Relation'),
            (43, 'Kursus Batik'),
            (44, 'Kursus Pengobatan Tradisional')";


			$this->db->query($query1);
            $this->db->query($query2);
            $this->db->query($query3);
            $this->db->query($query4);
            $this->db->query($query5);
                        
		}
	}
}
