<?php class Web_Widget_Model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('first_gallery_m');
		$this->load->model('first_penduduk_m');
	}

	function get_widget(){

		$sql   = "SELECT * FROM widget limit 1";
		$query = $this->db->query($sql);
		$data=$query->row_array();

		return $data;
	}

	function update($id=0){

		$data = $_POST;

		$sql="SELECT * FROM widget WHERE 1 ";
		$query = $this->db->query($sql);
		$hasil=$query->result_array();

		if($hasil){
			$this->db->where('id',$id);
			$outp = $this->db->update('widget',$data);
		}else{
			$outp = $this->db->insert('widget',$data);
		}

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_widget_data(&$data){
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['stat_widget'] = $this->first_penduduk_m->list_data(4);
	}

}
?>
