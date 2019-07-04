<?php
class Migrasi_1907_ke_1908 extends CI_model {

  public function up() {
  	// Tambah kolom pengurus untuk ttd u.b
	 	if (!$this->db->field_exists('pamong_ub', 'tweb_desa_pamong'))
  	{
			$fields = array();
			$fields['pamong_ub'] = array(
					'type' => 'tinyint',
					'constraint' => 1,
				  'null' => FALSE,
				  'defualt' => 0
			);
			$this->dbforge->add_column('tweb_desa_pamong', $fields);
			// Pindahkan setting sebutan_pimpinan_desa ke tweb_desa_pamong, terus hapus
			$this->load->model('pamong_model');
			$sebutan_pimpinan_desa = $this->db->where('key', 'sebutan_pimpinan_desa')->get('setting_aplikasi')->row()->value;
			$ttd = $this->db->limit(1)->where('jabatan', $sebutan_pimpinan_desa)->get('tweb_desa_pamong')->row()->pamong_id;
			$this->pamong_model->ttd($ttd, 1);
			$this->db->where('key', 'sebutan_pimpinan_desa')->delete('setting_aplikasi');
		}
  }
}
