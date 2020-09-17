<?php
/*
 * File ini:
 *
 * Model keuangan manual untuk Modul Keuangan Manual
 *
 * /donjo-app/models/Keuangan_manual_model.php
 *
 */

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
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

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

  public function list_apbdes($tahun = NULL)
  {
    if ( ! empty($tahun)) $this->db->where('Tahun', $tahun);
    return $this->db->get('keuangan_manual_rinci')->result();
  }

  public function list_pendapatan($tahun = NULL)
  {
    $filter = ['Kd_Akun' => '4.PENDAPATAN'];
    if ( ! empty($tahun))
    {
      $filter['Tahun'] = $tahun;
    }
    return $this->db->where($filter)->get('keuangan_manual_rinci')->result();
  }

  public function list_belanja($tahun = NULL)
  {
    $filter = ['Kd_Akun' => '5.BELANJA'];
    if ( ! empty($tahun))
    {
      $filter['Tahun'] = $tahun;
    }
    return $this->db->where($filter)->get('keuangan_manual_rinci')->result();
  }

  public function list_pembiayaan($tahun = NULL)
  {
    $filter = ['Kd_Akun' => '6.PEMBIAYAAN'];
    if ( ! empty($tahun))
    {
      $filter['Tahun'] = $tahun;
    }
    return $this->db->where($filter)->get('keuangan_manual_rinci')->result();
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
