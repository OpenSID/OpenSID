<?php class Config_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function get_data(){
		$query = $this->db->select('*')->limit(1)->get('config')->row_array();
		return $query;
	}

	function insert(){
		$data = $_POST;
		$data['id'] = 1; // Hanya ada satu row data desa
		// Data lokasi peta default. Diperlukan untuk menampilkan widget peta lokasi
		$data['lat'] = '-8.488005310891758';
		$data['lng'] = '116.0406072534065';
		$data['zoom'] = '19';
		$data['map_tipe'] = 'roadmap';
		unset($data['old_logo']);
		$outp = $this->db->insert('config',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=0){
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';
		$data = $_POST;
		$lokasi_file = $_FILES['logo']['tmp_name'];
		$tipe_file   = $_FILES['logo']['type'];
		$nama_file   = $_FILES['logo']['name'];
	  $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_logo    = $data['old_logo'];
		if (!empty($lokasi_file)){
			if (UploadLogo($nama_file,$old_logo,$tipe_file))
				$data['logo'] = $nama_file;
			else
				unset($data['logo']);
		}else{
			unset($data['logo']);
		}

		unset($data['file_logo']);
		unset($data['old_logo']);
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);

		$pamong['pamong_nama'] = $data['nama_kepala_desa'];
		$pamong['pamong_nip'] = $data['nip_kepala_desa'];
		$this->db->where('pamong_id','707');
		$outp = $this->db->update('tweb_desa_pamong',$pamong);

		if(!$outp) $_SESSION['success']=-1;
	}

	function update_kantor(){
		$data = $_POST;
		$id = "1";
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_wilayah(){
		$data = $_POST;
		$id = "1";
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function kosong_pend(){
		$a="TRUNCATE tweb_wil_clusterdesa";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE tweb_keluarga";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE tweb_rtm";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE tweb_penduduk";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE log_penduduk";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE log_surat";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE log_perubahan_penduduk";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE log_bulanan";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE garis";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE lokasi";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE area";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE point";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE line";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE polygon";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_master";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_indikator";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_parameter";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_periode";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_respon";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_respon_hasil";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_klasifikasi";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_kategori_indikator";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE kelompok";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE kelompok_anggota";
		$b = $this->db->simple_query($a);
		$a="TRUNCATE data_persil";
		$b = $this->db->simple_query($a);
		$a="TRUNCATE tweb_penduduk_map";
		$b = $this->db->simple_query($a);
		$a="TRUNCATE sys_traffic";
		$b = $this->db->simple_query($a);
	}

	function kosong_web(){
		$a="TRUNCATE tweb_wil_clusterdesa";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE tweb_keluarga";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE tweb_penduduk";
		$b = $this->db->simple_query($a);
	}

	function upgrade(){

		$a="DROP TABLE tweb_rtm";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE hasil_analisis_keluarga";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE analisis_keluarga";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE klasifikasi_analisis_keluarga";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE master_analisis_keluarga";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE sub_analisis_keluarga";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE tipe_analisis";
		$b = $this->db->simple_query($a);

		$a="DROP TABLE tweb_rtm_hubungan";
		$b = $this->db->simple_query($a);

		$a="UPDATE tweb_penduduk SET id_rtm = 0, rtm_level = 0 WHERE 1";
		$b = $this->db->simple_query($a);

		$a="CREATE TABLE IF NOT EXISTS analisis_keluarga (
			  id int(16) NOT NULL AUTO_INCREMENT,
			  id_kel int(11) NOT NULL,
			  id_master int(16) NOT NULL,
			  id_sub_analisis int(11) NOT NULL,
			  bulan varchar(2) NOT NULL,
			  tahun varchar(4) NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$b = $this->db->simple_query($a);


		$a="CREATE TABLE IF NOT EXISTS hasil_analisis_keluarga (
			  id int(16) NOT NULL AUTO_INCREMENT,
			  id_kel int(11) NOT NULL,
			  jenis varchar(6) NOT NULL,
			  hasil decimal(3,2) NOT NULL,
			  hasil2 decimal(3,2) NOT NULL,
			  bulan varchar(2) NOT NULL,
			  tahun varchar(4) NOT NULL,
			  id_klasifikasi int(11) DEFAULT NULL,
			  verifikasi varchar(1) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$b = $this->db->simple_query($a);


		$a="CREATE TABLE IF NOT EXISTS klasifikasi_analisis_keluarga (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  nama varchar(50) DEFAULT NULL,
			  dari float DEFAULT NULL,
			  sampai float DEFAULT NULL,
			  dari1 float NOT NULL,
			  sampai1 float NOT NULL,
			  dari2 float NOT NULL,
			  sampai2 float NOT NULL,
			  dari3 float NOT NULL,
			  sampai3 float NOT NULL,
			  dari4 float NOT NULL,
			  sampai4 float NOT NULL,
			  dari5 float NOT NULL,
			  sampai5 float NOT NULL,
			  jenis int(11) DEFAULT NULL,
			  tipe tinyint(2) NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";
		$b = $this->db->simple_query($a);

		$a="INSERT INTO klasifikasi_analisis_keluarga (id, nama, dari, sampai, dari1, sampai1, dari2, sampai2, dari3, sampai3, dari4, sampai4, dari5, sampai5, jenis, tipe) VALUES
			(1, 'Sangat Miskin', 0, 0.37, 0, 0.33, 0, 0.35, 0, 0.35, 0, 0.35, 0, 0.36, 1, 0),
			(2, 'Miskin', 0.37, 0.52, 0.33, 0.5, 0.35, 0.51, 0.35, 0.51, 0.35, 0.51, 0.36, 0.52, 1, 0),
			(3, 'Hampir Miskin', 0.52, 0.68, 0.5, 0.66, 0.51, 0.67, 0.51, 0.67, 0.51, 0.67, 0.52, 0.67, 1, 0),
			(4, 'Rentan Miskin', 0.68, 0.83, 0.66, 0.83, 0.67, 0.83, 0.67, 0.83, 0.67, 0.83, 0.67, 0.83, 1, 0),
			(5, 'Tidak Miskin', 0.83, 1, 0.83, 1, 0.83, 1, 0.83, 1, 0.83, 1, 0.83, 1, 1, 0);";
		$b = $this->db->simple_query($a);

		$a="CREATE TABLE IF NOT EXISTS master_analisis_keluarga (
			  id int(3) DEFAULT NULL,
			  nama varchar(200) DEFAULT NULL,
			  bobot int(2) DEFAULT NULL,
			  b1 int(2) DEFAULT NULL,
			  b2 int(2) DEFAULT NULL,
			  b3 int(2) DEFAULT NULL,
			  b4 int(2) DEFAULT NULL,
			  b5 int(2) DEFAULT NULL,
			  aktif tinyint(4) NOT NULL DEFAULT '1',
			  jenis tinyint(4) NOT NULL DEFAULT '1'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$b = $this->db->simple_query($a);

		$a="INSERT INTO master_analisis_keluarga (id, nama, bobot, b1, b2, b3, b4, b5, aktif, jenis) VALUES
			(1, 'Pendapatan perkapita perbulan', 36, 15, 24, 23, 26, 28, 1, 1),
			(2, 'Mendapatkan program/bantuan', 35, 0, 9, 1, 5, 0, 1, 1),
			(3, 'Rata-rata makan per hari', 34, 1, 14, 16, 18, 18, 1, 1),
			(4, 'Dalam satu minggu mampu membeli daging / unggas / susu / ikan', 33, 2, 18, 13, 11, 1, 1, 1),
			(5, 'Pelayanan kesehatan yang bisa diakses untuk berobat', 32, 6, 22, 8, 16, 12, 1, 1),
			(6, 'Penggunaan / Pemakaian Alat KB', 31, 0, 12, 0, 1, 11, 1, 1),
			(7, 'Status penguasaan bangunan tempat tinggal yang ditempati', 30, 13, 23, 24, 25, 24, 1, 1),
			(8, 'Jumlah keluarga dalam rumah tangga', 29, 7, 21, 21, 22, 5, 1, 1),
			(9, 'Luas lantai bangunan tempat tinggal _______________ m�', 28, 0, 20, 22, 21, 20, 1, 1),
			(10, 'Jenis lantai tempat tinggal terluas (60% lebih) terbuat dari :', 27, 11, 15, 20, 23, 21, 1, 1),
			(11, 'Jenis dinding tempat tinggal terluas (60% lebih) terbuat dari :', 26, 10, 19, 17, 20, 22, 1, 1),
			(12, 'Jenis atap tempat tinggal terluas (60% lebih) terbuat dari :', 25, 9, 13, 18, 19, 23, 1, 1),
			(13, 'Sumber penerangan utama', 24, 3, 10, 11, 13, 10, 1, 1),
			(14, 'Sumber air minum', 23, 4, 8, 15, 15, 17, 1, 1),
			(15, 'Bahan bakar/energi utama untuk memasak', 22, 0, 4, 12, 14, 2, 1, 1),
			(16, 'Penggunaan fasilitas tempat buang air besar', 21, 5, 3, 6, 12, 15, 1, 1),
			(17, 'Tempat pembuangan akhir tinja', 20, 0, 2, 5, 4, 14, 1, 1),
			(18, 'Jarak tempat pembuangan akhir tinja dari sumber air minum', 19, 0, 1, 4, 3, 13, 1, 1),
			(19, 'Kepemilikan aset (selain tanah, bangunan dan emas)', 18, 0, 17, 10, 24, 6, 1, 1),
			(20, 'kepemilikan tanah (selain yang ditempati), berapa luasannya _______________ m�', 17, 12, 6, 7, 10, 27, 1, 1),
			(21, 'kepemilikan emas, berapa gram kepemilikan emas ____10 gram___', 16, 0, 5, 2, 9, 7, 1, 1),
			(22, 'Cara memperoleh aset', 15, 0, 16, 9, 8, 9, 1, 1),
			(23, 'Kepemilikan sarana tekekomunikasi', 14, 0, 7, 3, 2, 8, 1, 1),
			(24, 'Pendidikan yang ditamatkan (Kepala Keluarga)', 13, 8, 25, 14, 6, 0, 1, 1),
			(25, 'Ketrampilan', 12, 0, 0, 0, 0, 0, 1, 1),
			(26, 'Pekerjaan', 11, 14, 0, 0, 0, 19, 1, 1),
			(27, 'Kepemilikan Usaha', 10, 0, 0, 0, 7, 0, 1, 1),
			(28, 'jarak terhadap pelayanan publik dasar', 9, 0, 0, 0, 0, 3, 1, 1),
			(29, 'Tingkat kesulitan terhadap akses pelayanan publik dasar', 8, 0, 0, 0, 0, 4, 1, 1),
			(30, 'Akses Pemasaran', 7, 0, 0, 0, 0, 0, 1, 1),
			(31, 'Membeli lebih dari satu stel pakaian baru bagi setiap anggota keluarga dalam satu tahun', 6, 0, 11, 19, 17, 16, 1, 1),
			(32, 'Intensitas terhadap bencana', 5, 0, 0, 0, 0, 26, 1, 1),
			(33, 'Terdapat anggota keluarga Berkebutuhan Khusus (Diffabel)', 4, 0, 0, 0, 0, 0, 1, 1),
			(34, 'Terdapat anggota keluarga menderita Penyakit Kronis', 3, 0, 0, 0, 0, 0, 1, 1),
			(35, 'Dukungan saluran irigasi', 2, 0, 0, 0, 0, 25, 1, 1),
			(36, 'Pemanfaatan waktu luang', 1, 0, 0, 0, 0, 0, 1, 1);";
		$b = $this->db->simple_query($a);

		$a="CREATE TABLE IF NOT EXISTS sub_analisis_keluarga (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  id_master int(2) DEFAULT NULL,
			  no_jawaban int(2) DEFAULT NULL,
			  nama varchar(100) DEFAULT NULL,
			  nilai int(2) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=159 ;";
		$b = $this->db->simple_query($a);

		$a="INSERT INTO sub_analisis_keluarga (id, id_master, no_jawaban, nama, nilai) VALUES
			(1, 1, 1, '> 1.000.000', 4),
			(2, 1, 2, '> 750.000 - 1.000.000', 3),
			(3, 1, 3, '> 500.000 - 750.000', 2),
			(4, 1, 4, '<= 500.000', 1),
			(5, 2, 1, 'Tidak mendapatkan program', 2),
			(6, 2, 2, 'Program Keluarga Harapan/PKH', 1),
			(7, 2, 3, 'Raskin', 1),
			(8, 2, 4, 'Jamkesmas/jamkesda/JKN', 1),
			(9, 2, 5, 'Beasiswa Miskin', 1),
			(10, 2, 6, 'BLSM', 1),
			(11, 2, 8, 'Pemugaran Rumah', 1),
			(12, 2, 9, 'Program Lainnya ...', 1),
			(13, 3, 1, '>= 3 kali per hari', 2),
			(14, 3, 2, '<= 2 kali per hari', 1),
			(15, 4, 1, 'Mampu dan untuk dikonsumsi', 3),
			(16, 4, 2, 'Mampu tetapi tidak dikonsumsi alasan', 2),
			(17, 4, 3, 'Tidak Mampu', 1),
			(18, 5, 1, 'Rumah Sakit Umum Kelas 1 dan 2', 8),
			(19, 5, 2, 'Klinik', 7),
			(20, 5, 3, 'Mantri / Bidan', 6),
			(21, 5, 4, 'Rumah Sakit Umum kelas 3', 5),
			(22, 5, 5, 'Puskesmas', 4),
			(23, 5, 6, 'Pustu (puskesmas pembantu)', 3),
			(24, 5, 7, 'PKD', 2),
			(25, 5, 8, 'Alternatif', 1),
			(26, 6, 1, 'Mandiri', 3),
			(27, 6, 2, 'Subsidi', 2),
			(28, 6, 3, 'Program KB', 1),
			(29, 7, 1, 'Milik Sendiri', 5),
			(30, 7, 2, 'Kontrak', 4),
			(31, 7, 3, 'Sewa', 3),
			(32, 7, 4, 'Bebas Sewa', 2),
			(33, 7, 5, 'Milik Orang tua/sanak/saudara', 1),
			(34, 8, 1, '1 keluarga', 2),
			(35, 8, 2, '> 1 keluarga', 1),
			(36, 9, 1, '=> 8 m�/orang', 2),
			(37, 9, 2, '< 8 m�/orang', 1),
			(38, 10, 1, 'Marmer/ batu alam', 6),
			(39, 10, 2, 'Keramik', 5),
			(40, 10, 3, 'Tegel', 4),
			(41, 10, 4, 'Semen/plur', 3),
			(42, 10, 5, 'bata', 2),
			(43, 10, 6, 'Tanah', 1),
			(44, 11, 1, 'Tembok diplester', 7),
			(45, 11, 2, 'Tembok setengah diplester', 6),
			(46, 11, 3, 'tembok tidak di plester', 5),
			(47, 11, 4, 'Kayu kualitas tinggi (milik sendiri)', 4),
			(48, 11, 5, 'Kayu kualitas rendah (milik sendiri)', 3),
			(49, 11, 6, 'gipsum/triplek', 2),
			(50, 11, 7, 'Bambu/gedeg', 1),
			(51, 12, 1, 'Beton kualitas tinggi (mesin)', 9),
			(52, 12, 2, 'Beton kualitas rendah (manual)', 8),
			(53, 12, 3, 'Genteng kualitas tinggi (= Genteng Kodok)', 7),
			(54, 12, 4, 'Genteng kualitas rendah (< Genteng Kodok)', 6),
			(55, 12, 5, 'Seng kualitas tinggi (Multiproof)', 5),
			(56, 12, 6, 'Asbes kualitas tinggi (Kerang)', 4),
			(57, 12, 7, 'Seng kualitas rendah (Seng dumbrang)', 3),
			(58, 12, 8, 'Asbes kualitas rendah (< Kerang)', 2),
			(59, 12, 9, 'Ijuk/rumbia/welid/alang2/widik', 1),
			(60, 13, 1, 'Listrik PLN > 2.200 watt', 7),
			(61, 13, 2, 'Listrik PLN 900 - 2.200 watt', 6),
			(62, 13, 3, 'Listrik PLN < 900 watt', 5),
			(63, 13, 4, 'Listrik PLN tanpa meteran', 4),
			(64, 13, 5, 'Listrik non PLN (Penerangan malam)', 3),
			(65, 13, 6, 'Petromak/aladin', 2),
			(66, 13, 7, 'Pelita/sentir/obor/lilin/damar/istilah lainnya', 1),
			(67, 14, 1, 'Air kemasan bermerk', 10),
			(68, 14, 2, 'Air isi ulang', 9),
			(69, 14, 3, 'Ledeng meteran (berlangganan PDAM)', 8),
			(70, 14, 4, 'Ledeng eceran (berlangganan selain PDAM)', 7),
			(71, 14, 5, 'Sumur bor/pompa', 6),
			(72, 14, 6, 'Sumur terlindung (disemen/ beratap)', 5),
			(73, 14, 7, 'Sumur tak terlindung (tidak disemen/ tidak beratap)', 4),
			(74, 14, 8, 'Mata air terlindung (sudah dikelola)', 3),
			(75, 14, 9, 'Mata air tak terlindung (tidak dikelola)', 2),
			(76, 14, 10, 'air hujan', 1),
			(77, 15, 1, 'Listrik', 6),
			(78, 15, 2, 'Gas/Elpiji', 5),
			(79, 15, 3, 'briket/batubara', 4),
			(80, 15, 4, 'Minyak tanah', 3),
			(81, 15, 5, 'Kayu bakar', 2),
			(82, 15, 6, 'sekam/merang/Arang', 1),
			(83, 16, 1, 'Sendiri', 4),
			(84, 16, 2, 'Bersama (Kelompok)', 3),
			(85, 16, 3, 'Umum (tidak terbatas)', 2),
			(86, 16, 4, 'Tidak ada', 1),
			(87, 17, 1, 'Tangki/SPAL', 4),
			(88, 17, 2, 'Kolam/sawah', 3),
			(89, 17, 3, 'Sungai/laut', 2),
			(90, 17, 4, 'Lubang tanah/Pantai/tanah lapang/kebun', 1),
			(91, 18, 1, '> 10 m', 3),
			(92, 18, 2, '< 10 m', 2),
			(93, 18, 3, 'Tidak tahu', 1),
			(94, 19, 1, '> 30 juta', 5),
			(95, 19, 2, '> 20 - 30 juta', 4),
			(96, 19, 3, '> 10 - 20 juta', 3),
			(97, 19, 4, '> 5 - 10 juta', 2),
			(98, 19, 5, '<= 5 juta', 1),
			(99, 20, 1, '> 75 ubin (1050 m2 )', 5),
			(100, 20, 2, '> 35 ubin - 75 ubin (490 - 1050 m2)', 4),
			(101, 20, 3, '8 ubin - 35 ubin (112 - 490 m2 )', 3),
			(102, 20, 4, '< 8 ubin (112 m2 )', 2),
			(103, 20, 5, 'tidak punya', 1),
			(104, 21, 1, '10 GRAM atau lebih', 3),
			(105, 21, 2, '< 10 GRAM', 2),
			(106, 21, 3, 'tidak punya', 1),
			(107, 22, 1, 'Membeli tunai', 4),
			(108, 22, 2, 'Membeli kredit', 3),
			(109, 22, 3, 'Bantuan/hibah/warisan', 2),
			(110, 22, 4, 'Bagi hasil', 1),
			(111, 23, 1, 'Telepon rumah kabel paralel', 5),
			(112, 23, 2, 'Telepon rumah kabel tunggal', 4),
			(113, 23, 3, '> 4 HP', 3),
			(114, 23, 4, '<= 4 HP', 2),
			(115, 23, 5, 'Tidak Punya', 1),
			(116, 24, 1, 'Sarjana', 8),
			(117, 24, 2, 'Diploma', 7),
			(118, 24, 3, 'SLTA Sederajat', 6),
			(119, 24, 4, 'SLTP Sederajat', 5),
			(120, 24, 5, 'SD Sederajat', 4),
			(121, 24, 6, 'Tidak tamat SD', 3),
			(122, 24, 7, 'Pesantren salaf', 2),
			(123, 24, 8, 'Tidak pernah sekolah', 1),
			(124, 25, 1, 'Punya', 2),
			(125, 25, 2, 'Tidak Punya', 1),
			(126, 26, 1, 'Tetap', 4),
			(127, 26, 2, 'Kontrak', 3),
			(128, 26, 3, 'Buruh harian lepas', 2),
			(129, 26, 4, 'Tidak Bekerja', 1),
			(130, 27, 1, 'Sendiri', 4),
			(131, 27, 2, 'Keluarga', 3),
			(132, 27, 3, 'Patungan/Bagi Hasil/Kemitraan', 2),
			(133, 27, 4, 'Tidak Punya', 1),
			(134, 28, 1, 'Dekat (< 1 km)', 3),
			(135, 28, 2, 'Sedang (1 - 3 km)', 2),
			(136, 28, 3, 'Jauh (> 3 km)', 1),
			(137, 29, 1, 'mudah (< 10 menit)', 3),
			(138, 29, 2, 'sedang (10 - 30 menit)', 2),
			(139, 29, 3, 'sulit (> 30 menit)', 1),
			(140, 30, 1, 'Mudah (tersedia jalan, transportasi, pasar)', 3),
			(141, 30, 2, 'Sedang (tersedia 2 diantara 3 (jalan, transportasi, pasar)', 2),
			(142, 30, 3, 'Sulit (tersedia 1 diantara 3 (jalan, transportasi, pasar)', 1),
			(143, 31, 1, 'Ya', 2),
			(144, 31, 2, 'Tidak', 1),
			(145, 32, 1, 'Rendah (<= 1 kali dalam satu tahun)', 3),
			(146, 32, 2, 'sedang (2 - 3 kali dalam satu tahun)', 2),
			(147, 32, 3, 'tinggi (> 3 kali dalam satu tahun)', 1),
			(148, 33, 1, 'Tidak ada', 2),
			(149, 33, 2, 'Ada', 1),
			(150, 34, 1, 'Tidak ada', 2),
			(151, 34, 2, 'Ada', 1),
			(152, 35, 1, 'Teknis', 5),
			(153, 35, 2, 'Setengah Teknis', 4),
			(154, 35, 3, 'Ada saluran pembuangan', 3),
			(155, 35, 4, 'tadah hujan', 2),
			(156, 35, 5, 'tidak ada saluran pembuangan', 1),
			(157, 36, 1, 'Produktif', 2),
			(158, 36, 2, 'Non Produktif', 1);";
		$b = $this->db->simple_query($a);

		$a="tipe_analisis (
			  id tinyint(4) NOT NULL AUTO_INCREMENT,
			  nama varchar(20) NOT NULL,
			  aktif tinyint(4) NOT NULL DEFAULT '0',
			  max int(6) NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";
		$b = $this->db->simple_query($a);

		$a="INSERT INTO tipe_analisis (id, nama, aktif, max) VALUES
			(1, 'Perkotaan', 0, 673),
			(2, 'Pesisir', 0, 1593),
			(3, 'Pesisir Bergunung', 1, 1483),
			(4, 'Pegunungan', 0, 1721),
			(5, 'Bonorawan', 0, 1938);";
		$b = $this->db->simple_query($a);

		$a="CREATE TABLE IF NOT EXISTS tweb_rtm (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  nik_kepala int(11) NOT NULL,
		  no_kk varchar(20) NOT NULL,
		  tgl_daftar timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  kelas_sosial int(11) NOT NULL,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;";
		$b = $this->db->simple_query($a);

		$a="CREATE TABLE IF NOT EXISTS tweb_rtm_hubungan (
			  id tinyint(4) NOT NULL AUTO_INCREMENT,
			  nama varchar(20) NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";
		$b = $this->db->simple_query($a);

		$a="INSERT INTO tweb_rtm_hubungan (id, nama) VALUES
			(1, 'Kepala Rumah Tangga'),
			(2, 'Anggota');";
		$b = $this->db->simple_query($a);

		$a="ALTER TABLE tweb_penduduk ADD id_rtm INT NOT NULL AFTER kk_level, ADD rtm_level INT NOT NULL AFTER id_rtm;";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE tweb_rtm";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE hasil_analisis_keluarga";
		$b = $this->db->simple_query($a);

		$a="TRUNCATE analisis_keluarga";
		$b = $this->db->simple_query($a);


		if($b) $_SESSION['success']=1;
			else $_SESSION['success']=-1;

	}


}
