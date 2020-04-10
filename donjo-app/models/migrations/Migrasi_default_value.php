<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migrasi_default_value extends CI_model {

	public function up() {

		$this->dbforge->modify_column('tweb_penduduk', array('id_rtm' => array('id_rtm','type' => 'VARCHAR(30)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('rtm_level' => array('rtm_level','type' => 'INT(11)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('tempatlahir' => array('tempatlahir','type' => 'VARCHAR(100)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('agama_id' => array('agama_id','type' => 'INT(1)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('pendidikan_kk_id' => array('pendidikan_kk_id','type' => 'INT(1)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('pendidikan_sedang_id' => array('pendidikan_sedang_id','type' => 'INT(1)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('pekerjaan_id' => array('pekerjaan_id','type' => 'INT(1)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('status_kawin' => array('status_kawin','type' => 'TINYINT', 'null' => true)));
		$this->dbforge->modify_column('tweb_penduduk', array('ayah_nik' => array('ayah_nik','type' => 'VARCHAR(16)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('ibu_nik' => array('ibu_nik','type' => 'VARCHAR(16)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('nama_ayah' => array('nama_ayah','type' => 'VARCHAR(100)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('nama_ibu' => array('nama_ibu','type' => 'VARCHAR(100)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('foto' => array('foto','type' => 'VARCHAR(100)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('golongan_darah_id' => array('golongan_darah_id','type' => 'INT(11)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('alamat_sebelumnya' => array('alamat_sebelumnya','type' => 'VARCHAR(200)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('alamat_sekarang' => array('alamat_sekarang','type' => 'VARCHAR(200)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('akta_lahir' => array('akta_lahir','type' => 'VARCHAR(40)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('akta_perkawinan' => array('akta_perkawinan','type' => 'VARCHAR(40)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('akta_perceraian' => array('akta_perceraian','type' => 'VARCHAR(40)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk', array('waktu_lahir' => array('waktu_lahir','type' => 'VARCHAR(5)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk_agama', array('nama' => array('nama','type' => 'VARCHAR(100)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_asuransi', array('nama' => array('nama','type' => 'VARCHAR(50)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_hubungan', array('nama' => array('nama','type' => 'VARCHAR(100)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_kawin', array('nama' => array('nama','type' => 'VARCHAR(100)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_mandiri', array('pin' => array('pin','type' => 'CHAR(32)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_map', array('id' => array('id','type' => 'INT(11)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_map', array('lat' => array('lat','type' => 'VARCHAR(24)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk_map', array('lng' => array('lng','type' => 'VARCHAR(24)', 'null' => true, 'default' => NULL)));
		$this->dbforge->modify_column('tweb_penduduk_pendidikan', array('nama' => array('nama','type' => 'VARCHAR(50)', 'null' => false)));
		$this->dbforge->modify_column('tweb_penduduk_pendidikan_kk', array('nama' => array('nama','type' => 'VARCHAR(50)', 'null' => false)));
		$this->dbforge->modify_column('tweb_rtm', array('kelas_sosial' => array('kelas_sosial','type' => 'INT(11)', 'null' => true, 'default' => NULL)));
	}

}
