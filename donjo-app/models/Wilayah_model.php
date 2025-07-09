<?php

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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Wilayah_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . '/models/Urut_model.php';
        $this->urut_model = new Urut_Model('tweb_wil_clusterdesa', 'id');
    }

    public function list_semua_wilayah()
    {
        $this->urut_semua_wilayah();

        $this->case_dusun = "w.config_id = '" . identitas('id') . "' and w.rt = '0' and w.rw = '0'";
        $this->case_rw    = "w.config_id = '" . identitas('id') . "' and w.rw <> '0' and w.rw <> '-' and w.rt = '0'";
        $this->case_rt    = "w.config_id = '" . identitas('id') . "' and w.rt <> '0' and w.rt <> '-'";

        $this->select_jumlah_rw_rt();
        $this->select_jumlah_warga();
        $this->select_jumlah_kk();

        return $this->config_id('w')
            ->select('w.*, p.nama AS nama_kepala, p.nik AS nik_kepala')
            ->select("(CASE WHEN w.rw = '0' THEN '' ELSE w.rw END) AS rw")
            ->select("(CASE WHEN w.rt = '0' THEN '' ELSE w.rt END) AS rt")
            ->from('tweb_wil_clusterdesa w')
            ->join('penduduk_hidup p', 'w.id_kepala = p.id', 'left')

            ->group_start()
            ->where("w.rt = '0' and w.rw = '0'")
            ->or_where("w.rw <> '-' and w.rt = '0'")
            ->or_where("w.rt <> '0' and w.rt <> '-'")
            ->group_end()

            ->order_by('w.urut_cetak, w.dusun, rw, rt')
            ->get()
            ->result_array();
    }

    private function select_jumlah_rw_rt(): void
    {
        $this->db
            ->select('(CASE
				WHEN ' . $this->case_dusun . " THEN (SELECT COUNT(id) FROM tweb_wil_clusterdesa WHERE dusun = w.dusun AND rw <> '-' AND rt = '-')
				END) AS jumlah_rw");

        $this->db
            ->select('(CASE
				WHEN ' . $this->case_dusun . " THEN (SELECT COUNT(id) FROM tweb_wil_clusterdesa WHERE dusun = w.dusun AND rt <> '0' AND rt <> '-')
				WHEN " . $this->case_rw . " THEN (SELECT COUNT(id) FROM tweb_wil_clusterdesa WHERE dusun = w.dusun AND rw = w.rw AND rt <> '0' AND rt <> '-')
				END) AS jumlah_rt");
    }

    private function select_jumlah_warga(): void
    {
        $this->db
            ->select('(CASE
				WHEN ' . $this->case_dusun . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun))
				WHEN ' . $this->case_rw . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun and rw = w.rw))
				WHEN ' . $this->case_rt . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster = w.id)
				END) AS jumlah_warga');

        $this->db
            ->select('(CASE
				WHEN ' . $this->case_dusun . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun) AND p.sex = 1)
				WHEN ' . $this->case_rw . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun and rw = w.rw) AND p.sex = 1)
				WHEN ' . $this->case_rt . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster = w.id and p.sex = 1)
				END) AS jumlah_warga_l');

        $this->db
            ->select('(CASE
				WHEN ' . $this->case_dusun . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun) AND p.sex = 2)
				WHEN ' . $this->case_rw . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun and rw = w.rw) AND p.sex = 2)
				WHEN ' . $this->case_rt . ' THEN (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster = w.id and p.sex = 2)
				END) AS jumlah_warga_p');
    }

    private function select_jumlah_kk(): void
    {
        $this->db
            ->select('(CASE
				WHEN ' . $this->case_dusun . ' THEN (SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala = p.id WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun) AND p.kk_level = 1)
				WHEN ' . $this->case_rw . ' THEN (SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala = p.id WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = w.dusun and rw = w.rw) AND p.kk_level = 1)
				WHEN ' . $this->case_rt . ' THEN (SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala = p.id WHERE p.id_cluster = w.id AND p.kk_level = 1)
				END) AS jumlah_kk ');
    }

    //Bagian RW
    public function list_data_rw($id = '', $offset = 0, $limit = 0)
    {
        $temp  = $this->cluster_by_id($id);
        $dusun = $temp['dusun'];

        $select_sql = "u.*, a.nama AS nama_ketua, a.nik AS nik_ketua,
        (SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE rt.config_id = u.config_id AND rt.dusun = u.dusun AND rt.rw = u.rw AND rt.rt <> '-' AND rt.rt <> '0' ) AS jumlah_rt,
        (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw)) AS jumlah_warga,
        (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw) AND p.sex = 1) AS jumlah_warga_l,
        (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw) AND p.sex = 2) AS jumlah_warga_p,
        (SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw) AND p.kk_level = 1) AS jumlah_kk";

        $this->config_id('u')
            ->select($select_sql)
            ->from('tweb_wil_clusterdesa u')
            ->join('penduduk_hidup a', 'u.id_kepala = a.id', 'LEFT')
            ->where('u.rt', '0')
            ->where('u.rw <>', '0')
            ->where('u.dusun', urldecode($dusun))
            ->order_by('u.urut', 'ASC');

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data = $this->db->get()->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']        = $j + 1;
            $data[$i]['deletable'] = 1;
            if ($data[$i]['jumlah_warga'] > 0 || $data[$i]['jumlah_kk'] > 0) {
                $data[$i]['deletable'] = 0;
            }
            $j++;
        }

        return $data;
    }

    private function list_data_rt_query($dusun = '', $rw = ''): void
    {
        $this->config_id('u')
            ->from('tweb_wil_clusterdesa u')
            ->join('penduduk_hidup a', 'u.id_kepala = a.id', 'LEFT')
            ->where('u.rt <>', '0')
            ->where('u.rt <>', '-')
            ->where('u.rw', urldecode($rw))
            ->where('u.dusun', urldecode($dusun));
    }

    //Bagian RT
    public function list_data_rt($dusun = '', $rw = '', $offset = 0, $limit = 0)
    {
        $this->list_data_rt_query($dusun, $rw);
        $select_sql = 'u.*, a.nama AS nama_ketua, a.nik AS nik_ketua,
        (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt)) AS jumlah_warga,
        (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt) AND p.sex = 1) AS jumlah_warga_l,
        (SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt) AND p.sex = 2) AS jumlah_warga_p,
        (SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt) AND p.kk_level = 1) AS jumlah_kk';

        $this->db
            ->select($select_sql)
            ->order_by('u.urut', 'ASC');

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data = $this->db->get()->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']        = $j + 1;
            $data[$i]['deletable'] = 1;
            if ($data[$i]['jumlah_warga'] > 0 || $data[$i]['jumlah_kk'] > 0) {
                $data[$i]['deletable'] = 0;
            }
            $j++;
        }

        return $data;
    }

    public function list_penduduk()
    {
        return $this->config_id('p')
            ->select('p.id, p.nik, p.nama, c.dusun')
            ->from('penduduk_hidup p')
            ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
            ->where('p.id NOT IN (SELECT c.id_kepala FROM tweb_wil_clusterdesa c WHERE c.id_kepala != 0 AND c.config_id = p.config_id)')
            ->get()
            ->result_array();
    }

    public function get_penduduk($id = 0)
    {
        return $this->config_id()
            ->select('id, nik, nama')
            ->where('id', $id)
            ->get('penduduk_hidup')
            ->row_array();
    }

    public function list_wil()
    {
        return $this->config_id()
            ->where('zoom >', '0')
            ->get('tweb_wil_clusterdesa')
            ->result_array();
    }

    public function list_dusun()
    {
        return $this->config_id()
            ->where('rt', '0')
            ->where('rw', '0')
            ->order_by('urut', 'ASC')
            ->get('tweb_wil_clusterdesa')
            ->result_array();
    }

    public function list_rw($dusun = '')
    {
        if ($dusun) {
            $this->db
                ->where('dusun', urldecode($dusun));
        }

        return $this->config_id()
            ->where('rt', '0')
            ->where('rw <>', '0')
            ->order_by('urut', 'ASC')
            ->get('tweb_wil_clusterdesa')
            ->result_array();
    }

    public function list_rt($dusun = '', $rw = '')
    {
        if ($dusun && $rw) {
            $this->db
                ->where('dusun', urldecode($dusun))
                ->where('rw', urldecode($rw));
        }

        return $this->config_id()
            ->where('rt <>', '0')
            ->order_by('urut', 'ASC')
            ->get('tweb_wil_clusterdesa')
            ->result_array();
    }

    public function cluster_by_id($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('tweb_wil_clusterdesa')
            ->row_array() ?? show_404();
    }

    public function total()
    {
        $sql = "SELECT
		(SELECT COUNT(w.id) FROM tweb_wil_clusterdesa w WHERE w.config_id = u.config_id AND w.rw <> '-' AND w.rt = '-') AS total_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE v.config_id = u.config_id AND v.rt <> '0' AND v.rt <> '-') AS total_rt,
		(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id)) AS total_warga,
		(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id) AND p.sex = 1) AS total_warga_l,
		(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id) AND p.sex = 2) AS total_warga_p,
		(SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala=p.id WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id) AND p.kk_level = 1) AS total_kk
		FROM tweb_wil_clusterdesa u
		LEFT JOIN penduduk_hidup a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0' AND u.config_id = '" . identitas('id') . "' limit 1";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    public function total_rw($dusun = '')
    {
        $sql = "SELECT sum(jumlah_rt) AS jmlrt, sum(jumlah_warga) AS jmlwarga, sum(jumlah_warga_l) AS jmlwargal, sum(jumlah_warga_p) AS jmlwargap, sum(jumlah_kk) AS jmlkk
			FROM
			(SELECT u.*, a.nama AS nama_ketua, a.nik AS nik_ketua,
				(SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE rt.config_id = u.config_id AND rt.dusun = u.dusun AND rw = u.rw AND rt.rt <> '-' AND rt.rt <> '0' ) AS jumlah_rt,
				(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw )) AS jumlah_warga,
				(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw) AND p.sex = 1) AS jumlah_warga_l,
				(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw) AND p.sex = 2) AS jumlah_warga_p,
				(SELECT COUNT(p.id) FROM  keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala=p.id   WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND dusun = u.dusun AND c.rw = u.rw) AND p.kk_level = 1) AS jumlah_kk
				FROM tweb_wil_clusterdesa u
				LEFT JOIN penduduk_hidup a ON u.id_kepala = a.id
				WHERE u.rt = '0' AND u.rw <> '0' AND u.dusun = '{$dusun}'  AND u.config_id = '" . identitas('id') . "') AS x ";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    public function total_rt($dusun = '', $rw = '')
    {
        $sql = "SELECT sum(jumlah_warga) AS jmlwarga, sum(jumlah_warga_l) AS jmlwargal, sum(jumlah_warga_p) AS jmlwargap, sum(jumlah_kk) AS jmlkk
			FROM
				(SELECT u.*, a.nama AS nama_ketua,a.nik AS nik_ketua,
					(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt)) AS jumlah_warga,
					(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt) AND p.sex = 1) AS jumlah_warga_l,
					(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt) AND p.sex = 2) AS jumlah_warga_p,
					(SELECT COUNT(p.id) FROM  keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala=p.id WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun AND c.rw = u.rw AND rt = u.rt) AND p.kk_level = 1) AS jumlah_kk
					FROM tweb_wil_clusterdesa u
					LEFT JOIN penduduk_hidup a ON u.id_kepala = a.id
					WHERE u.rt <> '0' AND u.rt <> '-' AND u.rw = '{$rw}' AND u.dusun = '{$dusun}'  AND u.config_id = '" . identitas('id') . "') AS x  ";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    // TO DO : Gunakan untuk get_alamat mendapatkan alamat penduduk
    public function get_alamat_wilayah($data)
    {
        $dusun          = (setting('sebutan_dusun') == '-') ? '' : ucwords(strtolower(setting('sebutan_dusun'))) . ' ' . ucwords(strtolower($data['dusun']));
        $alamat_wilayah = "{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . $dusun;

        return trim($alamat_wilayah);
    }

    public function get_alamat($id_penduduk)
    {
        $sebutan_dusun = ucwords(setting('sebutan_dusun'));

        $data = $this->db
            ->select("(
				case when (p.id_kk IS NUL)
					then
						case when (cp.dusun = '-' or cp.dusun = '')
							then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
							else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutan_dusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
						end
					else
						case when (ck.dusun = '-' or ck.dusun = '')
							then CONCAT(COALESCE(k.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
							else CONCAT(COALESCE(k.alamat, ''), ' {$sebutan_dusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
						end
				end) AS alamat")
            ->from('tweb_penduduk p')
            ->join('tweb_wil_clusterdesa cp', 'p.id_cluster = cp.id', 'left')
            ->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
            ->join('tweb_wil_clusterdesa ck', 'k.id_cluster = ck.id', 'left')
            ->where('p.id', $id_penduduk)
            ->get()
            ->row_array();

        return $data['alamat'];
    }

    // $arah:
    //		1 - turun
    // 		2 - naik
    public function urut($tipe = 0, $id = 0, $arah = 0, $id_dusun = 0, $id_rw = 0): void
    {
        switch ($tipe) {
            case 'dusun':
                $subset = "rt = '0' AND rw = '0'";
                break;

            case 'rw':
                $temp   = $this->cluster_by_id($id_dusun);
                $dusun  = $temp['dusun'];
                $subset = " rt = '0' AND rw <> '0' AND dusun = '{$dusun}'";
                break;

            case 'rt':
                $temp  = $this->cluster_by_id($id_dusun);
                $dusun = $temp['dusun'];

                $data_rw = $this->cluster_by_id($id_rw);
                $rw      = $data_rw['rw'];

                $subset = " rt <> '0' AND rw = '{$rw}' AND dusun = '{$dusun}'";
                break;

            default:
                // code...
                break;
        }

        $this->urut_model->urut($id, $arah, $subset);
    }

    // Samakan nomor urut semua subwilayah dusun untuk laporan cetak
    private function urut_semua_wilayah(): void
    {
        $urut       = 1;
        $urut_dusun = $this->config_id()
            ->select('id, dusun, urut')
            ->where('rt', '0')
            ->where('rw', '0')
            ->order_by('urut')
            ->get('tweb_wil_clusterdesa')
            ->result_array();

        foreach ($urut_dusun as $dusun) {
            $this->update_urut($urut, $dusun['id']);
            $urut++;

            $urut_rw = $this->config_id()
                ->select('id, dusun, rw, urut')
                ->where('rt', '0')
                ->where('rw  <>', '0')
                ->where('dusun', $dusun['dusun'])
                ->order_by('urut')
                ->get('tweb_wil_clusterdesa')
                ->result_array();

            foreach ($urut_rw as $rw) {
                $this->update_urut($urut, $rw['id']);
                $urut++;

                $urut_rt = $this->config_id()
                    ->select('id, dusun, rw, urut')
                    ->where('rt <>', '0')
                    ->where('rw', $rw['rw'])
                    ->where('dusun', $rw['dusun'])
                    ->order_by('urut')
                    ->get('tweb_wil_clusterdesa')
                    ->result_array();

                foreach ($urut_rt as $rt) {
                    $this->update_urut($urut, $rt['id']);
                    $urut++;
                }
            }
        }
    }

    private function update_urut($urut = 1, $id = 1): void
    {
        $this->config_id()
            ->set('urut_cetak', $urut)
            ->where('id', $id)
            ->update('tweb_wil_clusterdesa');
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;
        $select_sql = "SELECT u.*, a.nama AS nama_kadus, a.nik AS nik_kadus,
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE rw.config_id = u.config_id AND rw.dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
		(SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE rt.config_id = u.config_id AND rt.dusun = u.dusun AND rt.rt <> '0' AND rt.rt <> '-') AS jumlah_rt,
		(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun)) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun) AND p.sex = 1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM penduduk_hidup p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun) AND p.sex = 2) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM keluarga_aktif k inner join penduduk_hidup p ON k.nik_kepala = p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun) AND p.kk_level = 1) AS jumlah_kk";
        $sql = $select_sql . $this->list_data_sql();
        $sql .= 'ORDER BY`u`.`urut` ASC';
        $sql .= $paging_sql;
        $query = $this->db->query($sql);
        $data  = $query->result_array();
        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']        = $j + 1;
            $data[$i]['deletable'] = 1;
            if ($data[$i]['jumlah_warga'] > 0 || $data[$i]['jumlah_kk'] > 0) {
                $data[$i]['deletable'] = 0;
            }
            $j++;
        }

        return $data;
    }

    private function list_data_sql()
    {
        $sql = " FROM tweb_wil_clusterdesa u
			LEFT JOIN penduduk_hidup a ON u.id_kepala = a.id
			WHERE u.config_id = '" . identitas('id') . "' AND u.rt = '0' AND u.rw = '0' ";

        return $sql . $this->search_sql();
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $kw = $this->db->escape_like_str($_SESSION['cari']);
            $kw = '%' . $kw . '%';

            return " AND u.dusun LIKE '{$kw}'";
        }
    }

    public function daftar_wilayah_dusun()
    {
        // Daftar Dusun
        $dusun = [];
        if ($daftar_dusun = $this->list_data()) {
            foreach ($daftar_dusun as $data_dusun) {
                $rw = [];
                if ($daftar_rw = $this->list_data_rw($data_dusun['id'])) {
                    foreach ($daftar_rw as $data_rw) {
                        // Daftar RW
                        $rt = [];
                        if ($daftar_rt = $this->list_data_rt($data_rw['dusun'], $data_rw['rw'])) {
                            foreach ($daftar_rt as $data_rt) {
                                // Daftar RT
                                $rt[] = $data_rt;
                            }
                        }

                        $data_rw['daftar_rt'] = $rt;
                        array_merge($data_rw, $data_rw['daftar_rt']);
                        $rw[] = $data_rw;
                    }
                }

                $data_dusun['daftar_rw'] = $rw;
                array_merge($data_dusun, $data_dusun['daftar_rw']);
                $dusun[] = $data_dusun;
            }
        }

        return $dusun;
    }
}
