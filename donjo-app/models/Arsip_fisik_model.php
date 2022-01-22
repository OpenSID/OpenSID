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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Arsip_fisik_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('keluar_model');
    }

    public function ambil_total_data($kategori)
    {
        $this->db->select('count(`id`) as jml');
        $this->datas_jenis($kategori);
        
        return $this->db->get()->row()->jml;
    }

    public function ambil_semua_filter()
    {
        $jenis = [];
        $tahun = [];
        $jenis['1-1'] = 'Informasi Desa';
        $jenis['1-2'] = 'SK Kades';
        $jenis['1-3'] = 'Perdes';
        $jenis['2-1'] = 'Surat Masuk';
        $jenis['3-1'] = 'Surat Keluar';
        $syarat_surat = $this->db->select('*')->get('ref_syarat_surat')->result_array();
                foreach($syarat_surat as $value){
                    $jenis['4-'.$value['ref_syarat_id']] = $value['ref_syarat_nama'];
                }
        $format_surat = $this->db->select("*")->get('tweb_surat_format')->result_array();
                foreach($format_surat as $value){
                    $jenis['5-'.$value['id']] = $value['nama'];
                }
        
        $tahun = tahun(2015);
        

        return ['jenis' => $jenis, 'tahun'=>$tahun];
    }

    public function ambil_dokumen_perpage($limit=true, $perpage=50, $p=1, $o=4)
    {
        $p = ($p-1)*$perpage;
        $query_dokumen_desa = $this->db->select("`id` as id")
                                ->select("IF(kategori=3, JSON_VALUE(`attr`, '$.no_ditetapkan'), JSON_VALUE(`attr`, '$.no_kep_kades')) as nomor_dokumen")
                                ->select("STR_TO_DATE(IF(kategori=2, JSON_VALUE(`attr`, '$.tgl_kep_kades'), JSON_VALUE(`attr`, '$.tgl_ditetapkan')), '%d-%m-%Y') as tanggal_dokumen")
                                ->select("`nama` as `nama_dokumen`")
                                ->select("IF(`kategori`=3, '1-3', IF(`kategori`=2, '1-2', '1-1')) as jenis")
                                ->select("IF(`kategori`=3, 'perdes', IF(`kategori`=2, 'sk_kades', 'informasi_desa')) as nama_jenis")
                                ->select("`lokasi_arsip`")
                                ->select("IF(kategori=3, 'dokumen_sekretariat/clear/3', IF(kategori=2, 'dokumen_sekretariat/clear/2', '')) as modul_asli")
                                ->select("`tahun`")
                                ->select("'dokumen_desa' as kategori")
                                ->select("NULL as lampiran", false)
                                ->from('dokumen_hidup')
                                ->where("`id_pend` = 0 AND `satuan` IS NOT NULL")
                                ->get_compiled_select();
        $query_surat_masuk = $this->db->select("`id` as id")
                                ->select("`nomor_surat` as nomor_dokumen")
                                ->select("`tanggal_surat` as tanggal_dokumen")
                                ->select("`isi_singkat` as nama_dokumen")
                                ->select("'2-1' as jenis")
                                ->select("'surat_masuk' as nama_jenis")
                                ->select("`lokasi_arsip`")
                                ->select("'surat_masuk' as modul_asli")
                                ->select("EXTRACT(YEAR FROM `tanggal_surat`) as tahun")
                                ->select("'surat_masuk' as kategori")
                                ->select("NULL as lampiran", false)
                                ->where("`berkas_scan` IS NOT NULL")
                                ->from('surat_masuk')->get_compiled_select();
        $query_surat_keluar = $this->db->select("`id` as id")
                                ->select("`nomor_surat` as nomor_dokumen")
                                ->select("`tanggal_surat` as tanggal_dokumen")
                                ->select("`isi_singkat` as nama_dokumen")
                                ->select("'3-1' as jenis")
                                ->select("'surat_keluar' as nama_jenis")
                                ->select("`lokasi_arsip`")
                                ->select("'surat_keluar' as modul_asli")
                                ->select("EXTRACT(YEAR FROM `tanggal_surat`) as tahun")
                                ->select("'surat_keluar' as kategori")
                                ->select("NULL as lampiran", false)
                                ->from('surat_keluar')
                                ->where("`berkas_scan` IS NOT NULL")
                                ->get_compiled_select();
        $query_kependudukan = $this->db->select("d.`id` as id")
                                ->select("'' as nomor_dokumen")
                                ->select("DATE(d.`updated_at`) as tanggal_dokumen")
                                ->select("p.`nama` as nama_dokumen")
                                ->select("concat('4-',s.`ref_syarat_id`) as jenis")
                                ->select("s.`ref_syarat_nama` as nama_jenis")
                                ->select("d.`lokasi_arsip`")
                                ->select("concat('penduduk/dokumen/',d.`id_pend`) as modul_asli")
                                ->select("EXTRACT(YEAR FROM d.`updated_at`) as tahun")
                                ->select("'kependudukan' as kategori")
                                ->select("NULL as lampiran", false)
                                ->from("`dokumen_hidup` AS d")
                                ->join('`tweb_penduduk` AS p', 'd.`id_pend` = p.`id`')
                                ->join('`ref_syarat_surat` AS s', 'd.`id_syarat` = s.`ref_syarat_id`')
                                ->where("d.`id_pend` !=0 AND d.`satuan` IS NOT NULL")
                                ->get_compiled_select();
        $query_layanan_surat = $this->db->select("s.`id` as id")
                                ->select("s.`no_surat` as nomor_dokumen")
                                ->select("DATE(s.`tanggal`) as tanggal_dokumen")
                                ->select("s.`nama_surat` as nama_dokumen")
                                ->select("concat('5-',f.`id`) as jenis")
                                ->select("f.`nama` as nama_jenis")
                                ->select("s.`lokasi_arsip`")
                                ->select("concat('keluar/perorangan/',p.`nik`) as modul_asli")
                                ->select("s.`tahun`")
                                ->select("'layanan_surat' as kategori")
                                ->select("IF(s.`lampiran` IS NOT NULL, s.`lampiran`, '') as lampiran")
                                ->from("`log_surat` AS s")
                                ->join('`tweb_penduduk` AS p', 's.`id_pend` = p.`id`')
                                ->join('`tweb_surat_format` AS f', 's.`id_format_surat` = f.`id`')
                                ->get_compiled_select();
        $filter = 'WHERE 1';
        if($jenis = $this->session->data_filter_jenis)
            $filter .= " AND `jenis` = '{$jenis}'";
        if($kategori = $this->session->data_filter_kategori)
            $filter .= " AND `kategori` = '{$kategori}'";
        if($tahun = $this->session->data_filter_tahun)
            $filter .= " AND `tahun` = '{$tahun}'";
        if($cari = $this->session->data_filter_cari)
            $filter .= " AND `nama_dokumen` LIKE '%".$this->db->escape_like_str($cari)."%' ESCAPE '!'";

        $order = '';
        if($limit){
            $order .= $this->orderby($o);
            $limit = "LIMIT {$p}, {$perpage}";
        }
        else 
            $limit = '';

        $sql = "SELECT * FROM (($query_dokumen_desa) UNION ($query_surat_masuk) UNION ($query_surat_keluar) UNION ($query_kependudukan) UNION ($query_layanan_surat)) as x {$filter} {$order} {$limit}";
        return $this->db->query($sql)->result_array();
    }

    private function orderby($o)
    {
        $result = "ORDER BY ";
        switch($o){
            case 1:
                $result .= "LPAD(`nomor_dokumen`, 10, ' ') ASC";
                break;
            case 2:
                $result .= "LPAD(`nomor_dokumen`, 10, ' ') DESC";
                break;
            case 3:
                $result .= "`tanggal_dokumen` ASC";
                break;
            case 4:
                $result .= "`tanggal_dokumen` DESC";
                break;
            case 5:
                $result .= "`nama_dokumen` ASC";
                break;
            case 6:
                $result .= "`nama_dokumen` DESC";
                break;
            case 7:
                $result .= "`nama_jenis` ASC";
                break;
            case 8:
                $result .= "`nama_jenis` DESC";
                break;
            case 9:
                $result .= "`lokasi_arsip` ASC";
                break;
            case 10:
                $result .= "`lokasi_arsip` DESC";
                break;
        }

        return $result;
    }

    public function paging($p = 1)
    {
        $perpage = $this->session->data_perpage;
        $jml_data = count($this->ambil_dokumen_perpage(false, $perpage));

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $perpage;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function update_lokasi($tabel, $id, $value)
    {
        $this->db->set('lokasi_arsip', $value);
        $this->db->where('id', $id);
        $this->db->update($tabel);

    }

    public function get_nama_berkas($tabel, $id, $lampiran=false)
    {
        $berkas = null;
        switch($tabel){
            case 'surat_masuk':
            case 'surat_keluar':
                $berkas = 'berkas_scan';
                break;
            case 'dokumen_hidup':
                $berkas = 'satuan';
                break;
            case 'log_surat':
                $berkas = $lampiran ? 'lampiran':'nama_surat';
                break;
        }
        return $this->db->select("`$berkas` as berkas")
            ->where('id', $id)
            ->get($tabel)->row()->berkas;
    }

    public function get_lokasi_arsip($id, $tabel)
    {
        return $this->db->select('lokasi_arsip')
                    ->where('id', $id)
                    ->get($tabel)
                    ->row()->lokasi_arsip;
    }

    private function datas_jenis($kategori)
    {
        switch ($kategori) {
            case 'dokumen_desa':
                $this->db->where('`satuan` IS NOT NULL')
                ->where('`id_pend` = 0')
                ->from('dokumen_hidup');
                break;
            case 'surat_masuk':
                $this->db->where('`berkas_scan` IS NOT NULL')
                        ->from('surat_masuk');
                break;
            case 'surat_keluar':
                $this->db->where('`berkas_scan` IS NOT NULL')
                        ->from('surat_keluar');
                break;
            case 'kependudukan':
                $this->db->where('id_pend !=', 0)
                ->from('dokumen_hidup')
                ->where('`satuan` IS NOT NULL');
                break;
            case 'layanan_surat':
                $this->db->from('log_surat');
                break;
        }
    }
}
