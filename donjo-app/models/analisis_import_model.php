<?php
class analisis_import_Model extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->helper('excel');
	}
	function import_excel(){

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		//master
		$sheet=0;

		$master['nama']			= $data->val(1,2,$sheet);
		$master['subjek_tipe']	= $data->val(2,2,$sheet);
		$master['lock']			= $data->val(3,2,$sheet);
		$master['pembagi']		= $data->val(4,2,$sheet);
		$master['deskripsi']	= $data->val(5,2,$sheet);

		$outp = $this->db->insert('analisis_master',$master);
		$id_master = $this->db->insert_id();

		$periode['id_master']			= $id_master;
		$periode['nama']				= $data->val(6,2,$sheet);
		$periode['tahun_pelaksanaan']	= $data->val(7,2,$sheet);
		$periode['keterangan']			= $data->val(5,2,$sheet);
		$periode['aktif']				= 1;
		$this->db->insert('analisis_periode',$periode);

		//pertanyaan
		$sheet=1;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		//cek kategori
		for ($i=2; $i<=$baris; $i++){

			$sql = "SELECT * FROM analisis_kategori_indikator WHERE kategori=? AND id_master=?";
			$query = $this->db->query($sql,array($data->val($i,3,$sheet),$id_master));
			$cek = $query->row_array();

			if(!$cek){
				$kategori['id_master']		= $id_master;
				$kategori['kategori']		= $data->val($i,3,$sheet);
				$this->db->insert('analisis_kategori_indikator',$kategori);
			}
		}

		//isert pertanyaan
		for ($i=2; $i<=$baris; $i++){

			$indikator['id_master']		= $id_master;
			$indikator['nomor']			= $data->val($i,1,$sheet);
			$indikator['pertanyaan']	= $data->val($i,2,$sheet);

			$sql = "SELECT * FROM analisis_kategori_indikator WHERE kategori=? AND id_master=?";
			$query = $this->db->query($sql,array($data->val($i,3,$sheet),$id_master));
			$kategori = $query->row_array();

			$indikator['id_kategori']	= $kategori['id'];
			$indikator['id_tipe']		= $data->val($i,4,$sheet);
			$indikator['bobot']			= $data->val($i,5,$sheet);
			$indikator['act_analisis']	= $data->val($i,6,$sheet);

			$this->db->insert('analisis_indikator',$indikator);
		}

		//jawaban
		$sheet=2;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		//isert jawaban
		for ($i=2; $i<=$baris; $i++){
			$kode						= explode(".",$data->val($i,3,$sheet));

			$parameter['kode_jawaban']	= $data->val($i,2,$sheet);
			$parameter['jawaban']	= $data->val($i,3,$sheet);

			$sql 		= "SELECT id FROM analisis_indikator WHERE nomor=? AND id_master=?";
			$query 		= $this->db->query($sql,array($data->val($i,1,$sheet),$id_master));
			$indikator = $query->row_array();

			$parameter['id_indikator']	= $indikator['id'];
			$parameter['nilai']			= $data->val($i,4,$sheet);
			$parameter['asign']			= 1;

			$this->db->insert('analisis_parameter',$parameter);
		}

		//klasifikasi
		$sheet=3;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		//isert klasifikasi
		for ($i=2; $i<=$baris; $i++){

			$klasifikasi['id_master']	= $id_master;
			$klasifikasi['nama']		= $data->val($i,1,$sheet);
			$klasifikasi['minval']		= $data->val($i,2,$sheet);
			$klasifikasi['maxval']		= $data->val($i,3,$sheet);

			$this->db->insert('analisis_klasifikasi',$klasifikasi);
		}

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}