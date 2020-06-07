<?php
class Keuangan_manual_model extends CI_model {

  // Manual Input Anggaran dan Realisasi APBDes

  public function list_tahun_anggaran_manual()
  {
    $data = $this->db->select('Tahun')
      ->order_by('Tahun DESC')
      ->group_by('Tahun')
      ->get('keuangan_manual_rinci')->result_array();
    return array_column($data, 'Tahun');
  }

  public function list_apbdes()
  {
    $hasil = $this->db->query("SELECT * FROM keuangan_manual_rinci WHERE 1");
    return $hasil->result();
  }

  public function list_pendapatan()
  {
    $hasil = $this->db->query("SELECT * FROM keuangan_manual_rinci WHERE Kd_Akun='4.PENDAPATAN' ");
    return $hasil->result();
  }

  public function list_belanja()
  {
    $hasil = $this->db->query("SELECT * FROM keuangan_manual_rinci WHERE Kd_Akun='5.BELANJA' ");
    return $hasil->result();
  }

  public function list_pembiayaan()
  {
    $hasil = $this->db->query("SELECT * FROM keuangan_manual_rinci WHERE Kd_Akun='6.PEMBIAYAAN' ");
    return $hasil->result();
  }

  public function simpan_anggaran($Tahun,$Kd_Akun,$Kd_Keg,$Kd_Rincian,$Nilai_Anggaran,$Nilai_Realisasi)
  {
    $hasil = $this->db->query("INSERT INTO keuangan_manual_rinci (Tahun,Kd_Akun,Kd_Keg,Kd_Rincian,Nilai_Anggaran,Nilai_Realisasi) VALUES ('$Tahun','$Kd_Akun','$Kd_Keg','$Kd_Rincian','$Nilai_Anggaran','$Nilai_Realisasi')");
    return $hasil;
  }

  public function get_anggaran($id)
  {
    $hsl = $this->db->query("SELECT * FROM keuangan_manual_rinci WHERE id='$id'");
    if ($hsl->num_rows()>0)
    {
      foreach ($hsl->result() as $data)
      {
        $hasil=array(
          'id' => $data->id,
          'Tahun' => $data->Tahun,
          'Kd_Akun' => $data->Kd_Akun,
          'Kd_Keg' => $data->Kd_Keg,
          'Kd_Rincian' => $data->Kd_Rincian,
          'Nilai_Anggaran' => $data->Nilai_Anggaran,
          'Nilai_Realisasi' => $data->Nilai_Realisasi,
        );
      }
    }
    return $hasil;
  }

  public function update_anggaran($id,$Tahun,$Kd_Akun,$Kd_Keg,$Kd_Rincian,$Nilai_Anggaran,$Nilai_Realisasi){
    $hasil = $this->db->query("UPDATE keuangan_manual_rinci SET id='$id',Tahun='$Tahun',Kd_Akun='$Kd_Akun',Kd_Keg='$Kd_Keg',Kd_Rincian='$Kd_Rincian',Nilai_Anggaran='$Nilai_Anggaran',Nilai_Realisasi='$Nilai_Realisasi' WHERE id='$id'");
    return $hasil;
  }

  public function list_rek_pendapatan()
	{
    $this->db->select('*');
    $this->db->where("Jenis LIKE '4.%'");
    $this->db->order_by('Jenis', 'asc');
    $data = $this->db->get('keuangan_manual_ref_rek3')->result_array();
    return $data;
	}

  public function list_rek_belanja()
  {
    $this->db->select('*');
    $this->db->order_by('Kd_Bid', 'asc');
    $data = $this->db->get('keuangan_manual_ref_bidang')->result_array();
    return $data;
	}

  public function list_rek_biaya()
  {
    $this->db->select('*');
    $this->db->where("Jenis LIKE '6.%'");
    $this->db->order_by('Jenis', 'asc');
    $data = $this->db->get('keuangan_manual_ref_rek3')->result_array();
    return $data;
	}

  public function list_akun()
	{
    $this->db->select('*');
    $this->db->where("Akun NOT LIKE '1.%'");
    $this->db->where("Akun NOT LIKE '2.%'");
    $this->db->where("Akun NOT LIKE '3.%'");
    $this->db->where("Akun NOT LIKE '7.%'");
    $this->db->order_by('Akun', 'asc');
    $data = $this->db->get('keuangan_manual_ref_rek1')->result_array();
    return $data;
	}

  public function delete_input($id = '')
	{
		$sql = "DELETE FROM keuangan_manual_rinci WHERE id = ?";
		$hasil = $this->db->query($sql, array($id));

    if ($hasil)
		{
	    $_SESSION['error_msg'] = 'Sukses menghapus data';
			$_SESSION['success'] = 1;
		}
	}

	public function delete_all()
	{
    $id_cb = $_POST['id_cb'];
    // Cek apakah ada data yang dicentang atau dipilih
    if (!is_null($id_cb))
    {
      foreach ($id_cb as $id)
      {
        $this->delete_input($id);
      }
    }
    else
    {
      $_SESSION['error_msg'] = 'Tidak ada data yang dipilih';
      $_SESSION['success'] = -1;
    }
  }

  public function get_anggaran_tpl()
  {
    $hsl = $this->db->query("SELECT * FROM keuangan_manual_rinci_tpl WHERE 1");
    if ($hsl->num_rows()>0)
    {
      foreach ($hsl->result() as $data)
      {
        $hasil = array(
          'id' => $data->id,
          'Tahun' => $data->Tahun,
          'Kd_Akun' => $data->Kd_Akun,
          'Kd_Keg' => $data->Kd_Keg,
          'Kd_Rincian' => $data->Kd_Rincian,
          'Nilai_Anggaran' => $data->Nilai_Anggaran,
          'Nilai_Realisasi' => $data->Nilai_Realisasi,
        );
      }
    }
    return $hasil;
  }

  public function salin_anggaran_tpl($thn_apbdes)
  {
    $this->db->set('Tahun', "$thn_apbdes")
			->update('keuangan_manual_rinci_tpl');

    $this->db->select('Tahun,Kd_Akun,Kd_Keg,Kd_Rincian,Nilai_Anggaran,Nilai_Realisasi');
    $result_set = $this->db->get('keuangan_manual_rinci_tpl')->result();
    if (count($result_set) > 0)
    {
      $this->db->insert_batch('keuangan_manual_rinci', $result_set);
    }
    return $result_set;
  }

}
