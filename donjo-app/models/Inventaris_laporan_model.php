<?php

class Inventaris_laporan_model extends CI_Model
{

	protected $table = 'inventaris_gedung';
	protected $table_mutasi = 'mutasi_inventaris_gedung';
	protected $table_pamong = 'tweb_desa_pamong';

	function __construct()
	{
		parent::__construct();
	}

	function list_inventaris()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->table.'.visible',1);
		$data = $this->db->get()->result();
		return $data;
	}

	function sum_inventaris()
	{
		$this->db->select_sum('harga');
		$this->db->where($this->table.'.visible',1);
		$this->db->where($this->table.'.status',0);
		$result = $this->db->get($this->table)->row();
		return $result->harga;
	}

	function sum_print($tahun)
	{
		$this->db->select_sum('harga');
		$this->db->where($this->table.'.visible',1);
		$this->db->where($this->table.'.status',0);
		if ($tahun != 1)
		{
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$result = $this->db->get($this->table)->row();
		return $result->harga;
	}

	function list_mutasi_inventaris()
	{
		$this->db->select('mutasi_inventaris_gedung.id as id,mutasi_inventaris_gedung.*,  inventaris_gedung.nama_barang, inventaris_gedung.kode_barang, inventaris_gedung.tanggal_dokument');
		$this->db->from($this->table_mutasi);
		$this->db->where($this->table_mutasi.'.visible',1);
		$this->db->join($this->table, $this->table.'.id = '.$this->table_mutasi.'.id_inventaris_gedung');
		$data = $this->db->get()->result();
		return $data;
	}

	public function add($data)
	{
		$this->db->insert($this->table, $data);
		$id = $this->db->insert_id();
		$inserted = $this->db->get_where($this->table, array('id' => $id))->row();
		return $inserted;
	}

	public function add_mutasi($data)
	{
		$this->db->insert($this->table_mutasi, $data);
		$this->db->update($this->table, array('status' => 1), array('id' => $data['id_inventaris_gedung']));
		$id = $this->db->insert_id();
		$inserted = $this->db->get_where($this->table_mutasi, array('id' => $id))->row();
		return $inserted;
	}

	public function view($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
        $this->db->where($this->table.'.id', $id);
		$data = $this->db->get()->row();
		return $data;
	}

	function view_mutasi($id)
	{
		$this->db->select('mutasi_inventaris_gedung.id as id,mutasi_inventaris_gedung.*,  inventaris_gedung.nama_barang, inventaris_gedung.kode_barang, inventaris_gedung.tanggal_dokument, inventaris_gedung.register');
		$this->db->from($this->table_mutasi);
		$this->db->where($this->table_mutasi.'.id',$id);
		$this->db->join($this->table, $this->table.'.id = '.$this->table_mutasi.'.id_inventaris_gedung');
		$data = $this->db->get()->row();
		return $data;
	}

	function edit_mutasi($id)
	{
		$this->db->select('mutasi_inventaris_gedung.id as id,mutasi_inventaris_gedung.*,  inventaris_gedung.nama_barang, inventaris_gedung.kode_barang, inventaris_gedung.tanggal_dokument, inventaris_gedung.register');
		$this->db->from($this->table_mutasi);
		$this->db->where($this->table_mutasi.'.id',$id);
		$this->db->join($this->table, $this->table.'.id = '.$this->table_mutasi.'.id_inventaris_gedung');
		$data = $this->db->get()->row();
		return $data;
	}

	public function delete($id)
	{
		$this->db->update($this->table, array('visible' => 0), array('id' => $id));
		$id = $this->db->insert_id();
		$updated = $this->db->get_where($this->table, array('id' => $id))->row();
		return $updated;
	}

	public function delete_mutasi($id)
	{
		$this->db->update($this->table_mutasi, array('visible' => 0), array('id' => $id));
		$id = $this->db->insert_id();
		$updated = $this->db->get_where($this->table_mutasi, array('id' => $id))->row();
		return $updated;
	}

	public function update($id, $data)
	{
		$this->db->update($this->table, $data, array('id' => $id));
		$id = $this->db->insert_id();
		$updated = $this->db->get_where($this->table, array('id' => $id))->row();
		return $updated;
	}

	public function update_mutasi($id, $data)
	{
		$this->db->update($this->table_mutasi, $data, array('id' => $id));
		$id = $this->db->insert_id();
		$updated = $this->db->get_where($this->table_mutasi, array('id' => $id))->row();
		return $updated;
	}

	public function cetak($tahun)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->table.'.status',0);
		$this->db->where($this->table.'.visible',1);
		if ($tahun != 1)
		{
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->order_by('year(tanggal_dokument)', "asc");
		$data = $this->db->get()->result();
		return $data;
	}

	public function pamong($pamong)
	{
		$this->db->select('*');
		$this->db->from($this->table_pamong);
		// $this->db->where($this->table.'.tahun_pengadaan',$tahun);
		$this->db->where($this->table_pamong.'.pamong_id', $pamong);
		$data = $this->db->get()->row();
		return $data;
	}



	// digunakan untuk menampilkan data inventari tanah
	function inventaris_tanah_pribadi(){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		$this->db->where('inventaris_tanah.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_tanah_pemerintah(){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		$this->db->where('inventaris_tanah.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_tanah_provinsi(){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		$this->db->where('inventaris_tanah.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_tanah_kabupaten(){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		$this->db->where('inventaris_tanah.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_tanah_sumbangan(){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		$this->db->where('inventaris_tanah.asal','Sumbangan');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris tanah

	// digunakan untuk menampilkan data inventari peralatan
	function inventaris_peralatan_pribadi(){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		$this->db->where('inventaris_peralatan.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_peralatan_pemerintah(){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		$this->db->where('inventaris_peralatan.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_peralatan_provinsi(){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		$this->db->where('inventaris_peralatan.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_peralatan_kabupaten(){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		$this->db->where('inventaris_peralatan.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_peralatan_sumbangan(){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		$this->db->where('inventaris_peralatan.asal','Sumbangan');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris peralatan

	// digunakan untuk menampilkan data inventari gedung
	function inventaris_gedung_pribadi(){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		$this->db->where('inventaris_gedung.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_gedung_pemerintah(){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		$this->db->where('inventaris_gedung.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_gedung_provinsi(){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		$this->db->where('inventaris_gedung.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_gedung_kabupaten(){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		$this->db->where('inventaris_gedung.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_gedung_sumbangan(){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		$this->db->where('inventaris_gedung.asal','Sumbangan');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris gedung

	// digunakan untuk menampilkan data inventari jalan
	function inventaris_jalan_pribadi(){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		$this->db->where('inventaris_jalan.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_jalan_pemerintah(){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		$this->db->where('inventaris_jalan.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_jalan_provinsi(){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		$this->db->where('inventaris_jalan.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_jalan_kabupaten(){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		$this->db->where('inventaris_jalan.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_jalan_sumbangan(){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		$this->db->where('inventaris_jalan.asal','Sumbangan');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris jalan

	// digunakan untuk menampilkan data inventari asset
	function inventaris_asset_pribadi(){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		$this->db->where('inventaris_asset.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_asset_pemerintah(){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		$this->db->where('inventaris_asset.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_asset_provinsi(){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		$this->db->where('inventaris_asset.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_asset_kabupaten(){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		$this->db->where('inventaris_asset.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_asset_sumbangan(){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		$this->db->where('inventaris_asset.asal','Sumbangan');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris asset

	// digunakan untuk menampilkan data inventari kontruksi
	function inventaris_kontruksi_pribadi(){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		$this->db->where('inventaris_kontruksi.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_kontruksi_pemerintah(){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		$this->db->where('inventaris_kontruksi.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_kontruksi_provinsi(){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		$this->db->where('inventaris_kontruksi.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_kontruksi_kabupaten(){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		$this->db->where('inventaris_kontruksi.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function inventaris_kontruksi_sumbangan(){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		$this->db->where('inventaris_kontruksi.asal','Sumbangan');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris kontruksi


	// digunakan untuk menampilkan data inventari tanah
	function cetak_inventaris_tanah_pribadi($tahun){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_tanah.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_tanah_pemerintah($tahun){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_tanah.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_tanah_provinsi($tahun){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_tanah.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_tanah_kabupaten($tahun){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_tanah.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_tanah_sumbangan($tahun){
		$this->db->select('count(inventaris_tanah.asal) as total');
		$this->db->where('inventaris_tanah.visible',1);
		$this->db->where('inventaris_tanah.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_tanah.asal','Sumbangan');
		$result = $this->db->get('inventaris_tanah')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris tanah

	// digunakan untuk menampilkan data inventari peralatan
	function cetak_inventaris_peralatan_pribadi($tahun){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_peralatan.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_peralatan_pemerintah($tahun){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_peralatan.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_peralatan_provinsi($tahun){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_peralatan.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_peralatan_kabupaten($tahun){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_peralatan.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_peralatan_sumbangan($tahun){
		$this->db->select('count(inventaris_peralatan.asal) as total');
		$this->db->where('inventaris_peralatan.visible',1);
		$this->db->where('inventaris_peralatan.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_peralatan.asal','Sumbangan');
		$result = $this->db->get('inventaris_peralatan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris peralatan

	// digunakan untuk menampilkan data inventari gedung
	function cetak_inventaris_gedung_pribadi($tahun){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_gedung.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_gedung_pemerintah($tahun){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_gedung.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_gedung_provinsi($tahun){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_gedung.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_gedung_kabupaten($tahun){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_gedung.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_gedung_sumbangan($tahun){
		$this->db->select('count(inventaris_gedung.asal) as total');
		$this->db->where('inventaris_gedung.visible',1);
		$this->db->where('inventaris_gedung.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_gedung.asal','Sumbangan');
		$result = $this->db->get('inventaris_gedung')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris gedung

	// digunakan untuk menampilkan data inventari jalan
	function cetak_inventaris_jalan_pribadi($tahun){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_jalan.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_jalan_pemerintah($tahun){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_jalan.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_jalan_provinsi($tahun){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_jalan.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_jalan_kabupaten($tahun){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_jalan.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_jalan_sumbangan($tahun){
		$this->db->select('count(inventaris_jalan.asal) as total');
		$this->db->where('inventaris_jalan.visible',1);
		$this->db->where('inventaris_jalan.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_jalan.asal','Sumbangan');
		$result = $this->db->get('inventaris_jalan')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris jalan

	// digunakan untuk menampilkan data inventari asset
	function cetak_inventaris_asset_pribadi($tahun){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_asset.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_asset_pemerintah($tahun){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_asset.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_asset_provinsi($tahun){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_asset.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_asset_kabupaten($tahun){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_asset.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_asset_sumbangan($tahun){
		$this->db->select('count(inventaris_asset.asal) as total');
		$this->db->where('inventaris_asset.visible',1);
		$this->db->where('inventaris_asset.status',0);
		if($tahun != 1){
			$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
		}
		$this->db->where('inventaris_asset.asal','Sumbangan');
		$result = $this->db->get('inventaris_asset')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris asset

	// digunakan untuk menampilkan data inventari kontruksi
	function cetak_inventaris_kontruksi_pribadi($tahun){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_kontruksi.asal','Pembelian Sendiri');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_kontruksi_pemerintah($tahun){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_kontruksi.asal','Bantuan Pemerintah');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_kontruksi_provinsi($tahun){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_kontruksi.asal','Bantuan Provinsi');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_kontruksi_kabupaten($tahun){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_kontruksi.asal','Bantuan Kabupaten');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}

	function cetak_inventaris_kontruksi_sumbangan($tahun){
		$this->db->select('count(inventaris_kontruksi.asal) as total');
		$this->db->where('inventaris_kontruksi.visible',1);
		$this->db->where('inventaris_kontruksi.status',0);
		if($tahun != 1){
			$this->db->where('year(tanggal_dokument)',$tahun);
		}
		$this->db->where('inventaris_kontruksi.asal','Sumbangan');
		$result = $this->db->get('inventaris_kontruksi')->row();
		if(!empty($result)){
			return $result;
		}else{
			return $result = 0;
		}
	}
	// end data inventaris kontruksi

}




















