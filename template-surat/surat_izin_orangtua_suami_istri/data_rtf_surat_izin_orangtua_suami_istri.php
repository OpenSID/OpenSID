<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	# Data yang diberi izin dari database penduduk
	if ($input['id_diberi_izin'])
	{
		$diberi_izin = $this->get_data_surat($input['id_diberi_izin']);
		if ($diberi_izin['sex_id'] == '1')
			$status_pekerja = "Tenaga Kerja Indonesia (TKI)";
		else
			$status_pekerja = "Tenaga Kerja Wanita (TKW)";
		$array_replace = array(
	    "[diberi_izin_nama]"     			=> $diberi_izin['nama'],
	    "[diberi_izin_tempatlahir]"  	=> $diberi_izin['tempatlahir'],
	    "[diberi_izin_tanggallahir]"	=> tgl_indo_dari_str($diberi_izin['tanggallahir']),
	    "[diberi_izin_wni]"  					=> $diberi_izin['warganegara'],
	    "[diberi_izin_agama]"       	=> $diberi_izin['agama'],
	    "[diberi_izin_pekerjaan]" 		=> $diberi_izin['pekerjaan'],
	    "[diberi_izin_alamat]"    		=> $diberi_izin['alamat'],
	    "[diberi_izin_rt]"    				=> $diberi_izin['rt'],
	    "[diberi_izin_rw]"    				=> $diberi_izin['rw'],
	    "[diberi_izin_dusun]"    			=> $diberi_izin['dusun'],
	    "[form_pekerja]"							=> $status_pekerja
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		$buffer=$this->case_replace("[selaku]",$input['selaku'],$buffer); //Di judul huruf besar semua
	}
?>
