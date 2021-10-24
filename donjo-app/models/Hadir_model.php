<?php 
/*
 * File ini:
 *
 * Model pamong untuk modul Kehadiran 
 *
 * donjo-app/models/Hadir_model.php
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
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
class Hadir_model extends CI_Model {
private $table,$table_log;
	public function __construct()
	{
		$this->table='hadir_pamong_hari';
		$this->table_log='hadir_pamong_logs';
		parent::__construct();
	}

	public function _get($params = array(), $limit = 30, $start = 0, $debug = false )
	{
		$this->db->from($this->table);
		if (isset($params['blank']))
		{
			$this->db
				->where('waktu_masuk is null')
				->where('waktu_keluar is null');
		}
		
		if (isset($params['pamong_id']))
		{
			$this->db->where('pamong_id', $params['pamong_id'] );
		}
		
		if (isset($params['id']))
		{
			$this->db->where('id', $params['id'] );
		}
		
		if (isset($params['waktu_masuk']))
		{
			$this->db->where('waktu_masuk is NOT NULL', NULL, FALSE );
		}
		
		if (isset($params['isReported']))
		{
			$this->db->where('lapor_logs is NOT NULL', NULL, FALSE );
		}
		
		if (isset($params['datatable_search_lapor']))
		{
			$this->db->where('pamong_info like "%'.$params['datatable_search_lapor'].'%"')
			->or_where('lapor_logs like "%'.$params['datatable_search_lapor'].'%"');
		}
		
		if (isset($params['datatable_search']))
		{
			$this->db->where('pamong_info like "%'.$params['datatable_search'].'%"')
			->or_where('waktu_masuk like "%'.$params['datatable_search'].'%"')
			->or_where('waktu_keluar like "%'.$params['datatable_search'].'%"');
		}
		
		if (isset($params['keluarKosong']))
		{
			$this->db
				->where('waktu_keluar is null')
				->where('tanggal <', date("Y-m-d"));;
		}
		
		if (isset($params['tanggal']))
		{
			$this->db->where('tanggal', $params['tanggal']);
		}
		
		if (isset($params['now']))
		{
			$this->db->where('tanggal', date("Y-m-d"));
		}
		
		if (isset($params['pamong']))
		{
			$this->db->where('pamong_id', $params['pamong']);
		}
		
		if ( isset($params['count']))
		{
			$this->db->select('count(*) c');
			$selectTable=1;
			$row= $this->db
				->get()
				->row_array();
			return $row['c'];
		}
		
		$this->db->limit($limit, $start);
		
		if ( isset($params['orders']))
		{
			$this->db->order_by($params['orders'][0], $params['orders'][1]);
			$isSorted = 1;
		}
		
		if ( !isset($isSorted))
		{
			$this->db->order_by('id', 'asc');
		}
		
		if ( isset($params['select']))
		{
			$this->db->select($params['select']);
			$selectTable=1;
		}
		if ( !isset($selectTable))
		{
			$this->db->select('id, pamong_id, tanggal, waktu_masuk, waktu_keluar');
		}
		
		if ( isset($params['first']))
		{
			return $this->db
				->get()
				->row_array();
		}
		
		return $this->db
			->get()
			->result_array();
	}
	
	public function _count($params = array())
	{
		$params['count'] = 1;
		return $this->_get($params);
	}
	
	public function _update($params,$where,$cond)
	{
		$this->db
			->where($where, $cond)
			->update($this->table, $params);
		return $this->db->last_query();
	}

	public function _add($params,$ignore=0)
	{
		$insert_query = $this->db->insert_string($this->table, $params);
		if ( $ignore)
		{
			$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
		}

		$this->db->query($insert_query);
		return ;
	}

	public function _blank()
	{
		$params = ['blank' => 1];
		return $this->_count($params);
	}
	
	public function tidakKeluar()
	{
		$params = [
			'keluarKosong'=>1, 
			'select'=>'id, waktu_masuk, tanggal, pamong_id'
		];
		$total  = $this->_count($params);
		$data   = $this->_get($params,$total);
		$return = ['total'=>$total,'data'=>$data];
		return $return;
	}
	
	public function _get_id($id)
	{
		$params = [ 'id' => $id ];
		$params['select']='*';
		$params['first']=1;
		return $this->_get($params);
		
	}
}