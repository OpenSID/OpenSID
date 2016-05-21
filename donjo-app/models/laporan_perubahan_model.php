<?php
/*
 * Berkas default dari halaman web utk publik
 * 
 * Copyright 2013 
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * SID adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan SID RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin SID terlebih dahulu, 
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin SID, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-online/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * SID Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan. 
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?>

<?php

class Laporan_Perubahan_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	

	function bulan($bulan)
		{
		Switch ($bulan){
		    case 1 : $bulan="Januari";
			Break;
		    case 2 : $bulan="Februari";
			Break;
		    case 3 : $bulan="Maret";
			Break;
		    case 4 : $bulan="April";
			Break;
		    case 5 : $bulan="Mei";
			Break;
		    case 6 : $bulan="Juni";
			Break;
		    case 7 : $bulan="Juli";
			Break;
		    case 8 : $bulan="Agustus";
			Break;
		    case 9 : $bulan="September";
			Break;
		    case 10 : $bulan="Oktober";
			Break;
		    case 11 : $bulan="November";
			Break;
		    case 12 : $bulan="Desember";
			Break;
		    }
		return $bulan;
		}
	
	function configku(){
		$sql   = "SELECT * FROM config limit 1 ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_data(){
		$sql   = "SELECT x.dusun, 
(SELECT COUNT(id_pend) FROM log_perubahan_penduduk c LEFT JOIN tweb_penduduk b ON c.id_pend=b.id WHERE b.sex ='1') AS lalu_L,
(SELECT COUNT(id_pend) FROM log_perubahan_penduduk c LEFT JOIN tweb_penduduk b ON c.id_pend=b.id WHERE b.sex ='1') AS lalu_P,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='7') AS pecah_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='7') AS pecah_P ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='5') AS datang_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='5') AS datang_P ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='3') AS pergi_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='3') AS pergi_P ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='2') AS mati_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='2') AS mati_P
FROM tweb_wil_clusterdesa x WHERE rw='0' AND rt='0'  ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function total_data(){
		$sql   = "SELECT SUM(lalu_L) as tlaluL,SUM(lalu_P) as tlaluP,SUM(pecah_L) as tpecahL,SUM(pecah_P) as tpecahP,SUM(datang_L) as tdatangL,SUM(datang_p) as tdatangP,SUM(pergi_L) as tpergiL,SUM(pergi_P) as tpergiP,SUM(mati_L) as tmatiL,SUM(mati_P) as tmatiP FROM(SELECT x.dusun, 
(SELECT COUNT(id_pend) FROM log_perubahan_penduduk c LEFT JOIN tweb_penduduk d ON c.id_pend=d.id WHERE d.sex ='1' ) AS lalu_L,
(SELECT COUNT(id_pend) FROM log_perubahan_penduduk c LEFT JOIN tweb_penduduk d ON c.id_pend=d.id WHERE d.sex ='1' ) AS lalu_P,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='7') AS pecah_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='7') AS pecah_P ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='5') AS datang_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='5') AS datang_P ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='3') AS pergi_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='3') AS pergi_P ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='1' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='2') AS mati_L ,
(SELECT COUNT(id_pend) FROM log_penduduk a LEFT JOIN tweb_penduduk b ON a.id_pend=b.id LEFT JOIN tweb_wil_clusterdesa c ON b.id_cluster=c.id WHERE b.sex='2' AND month(a.tanggal)=month(curdate()) AND  year(a.tanggal)=year(curdate()) AND c.dusun=x.dusun AND a.id_detail='2') AS mati_P
FROM tweb_wil_clusterdesa x WHERE rw='0' AND rt='0') as z  ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

}

?>
