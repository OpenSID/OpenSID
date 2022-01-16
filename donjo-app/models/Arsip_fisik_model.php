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

    const INFORMASI_PUBLIK = 1;
    const SKKADES = 2;
    const PERDES = 3;

    private $column_arsip_desa;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('keluar_model');
        $this->column_arsip_desa = [
            "column_surat" => ["`id` as id", "`nomor_surat` as no", "`tanggal_surat` as tanggal_dokumen", "`isi_singkat` as nama_dokumen", "`berkas_scan` as berkas", "EXTRACT(YEAR FROM `tanggal_surat`) as tahun"],
            "column_dokumen" => ['id', 'attr', 'attr', 'nama', 'satuan', 'tahun', 'kategori'],
            "column_kependudukan" => ['id', '', 'updated_at', 'id_pend', 'satuan', 'EXTRACT(YEAR FROM `updated_at`)', 'id_syarat'],
            "column_layanan_surat" => ['id', 'no_surat', 'tanggal', 'id_pend', 'nama_surat', 'tahun', 'id_format_surat']
        ];
    }

    public function ambil_total_data($jenis)
    {
        if($jenis == 'surat_desa'){
            $query = $this->db->query("SELECT COUNT(id) as jml FROM ((SELECT `id` FROM `surat_masuk` WHERE `berkas_scan` IS NOT NULL) UNION ALL (SELECT `id` FROM `surat_keluar` WHERE `berkas_scan` IS NOT NULL))x");
            return $query->row()->jml;
        }
        $this->db->select('count(`id`) as jml');
        $this->datas_jenis($jenis);
        
        return $this->db->get()->row()->jml;
    }

    public function ambil_semua_filter($kategori)
    {
        $jenis = [];
        $tahun = [];
        switch($kategori){
            case 'dokumen_desa':
                $jenis = [2=>'sk_kades', 3=>'perdes'];

                $this->db->select('tahun')->distinct();
                $this->datas_jenis($kategori);
                $result = $this->db->get()->result_array();
                foreach($result as $value){
                    $tahun[] = $value['tahun'];
                }
                break;
            case 'surat_desa':
                $jenis = [1=>'surat_masuk', 2=>'surat_keluar'];

                $sql = "SELECT EXTRACT(YEAR FROM `tanggal_surat`) as tahun FROM `surat_masuk` WHERE `berkas_scan` IS NOT NULL UNION ALL SELECT EXTRACT(YEAR FROM `tanggal_surat`) as tahun FROM `surat_keluar` WHERE `berkas_scan` IS NOT NULL";
                $hasil = $this->db->query($sql)->result_array();
                foreach($hasil as $value){
                    if(!in_array($value['tahun'], $tahun)){
                        $tahun[] = $value['tahun'];
                    }
                }
                break;
            case 'kependudukan':
                $result = $this->db->select('*')->get('ref_syarat_surat')->result_array();
                foreach($result as $value){
                    $jenis[$value['ref_syarat_id']] = $value['ref_syarat_nama'];
                }

                $this->db->select('EXTRACT(YEAR FROM `updated_at`) as tahun')->distinct();
                $this->datas_jenis($kategori);
                $result = $this->db->get()->result_array();
                foreach($result as $value){
                    $tahun[] = $value['tahun'];
                }
                break;
            case 'layanan_surat':
                $result = $this->db->select("*")->get('tweb_surat_format')->result_array();
                foreach($result as $value){
                    $jenis[$value['id']] = $value['nama'];
                }

                $this->db->select('tahun')->distinct();
                $this->datas_jenis($kategori);
                $result = $this->db->get()->result_array();
                foreach($result as $value){
                    $tahun[] = $value['tahun'];
                }
                break;
        }

        return ['jenis' => $jenis, 'tahun'=>$tahun];
    }

    public function ambil_dokumen_perpage($kategori, $limit=true, $perpage=50, $p=1)
    {
        $p = ($p-1)*$perpage;
        if($kategori == 'surat_desa'){

            $result = null;
            $sm = $this->column_arsip_desa['column_surat'];
            $sk = $this->column_arsip_desa['column_surat'];
            $sm[] = "`lokasi_arsip`, 'surat_masuk' as jenis";
            $sk[] = "`lokasi_arsip`, 'surat_keluar' as jenis";
            $str_sm = implode(',', $sm);
            $str_sk = implode(',', $sk);
            $filter_jenis = $this->session->data_filter_jenis;
            if($filter_jenis){
                $col = $filter_jenis==1 ? $str_sm : $str_sk;
                $jenis = $filter_jenis==1 ? 'surat_masuk' : 'surat_keluar';
                $sql = "SELECT $col FROM `$jenis`";
            }
            else
                $sql = "SELECT * FROM (SELECT $str_sm FROM `surat_masuk` UNION ALL SELECT $str_sk FROM `surat_keluar`) as t";
            $berkas = $filter_jenis ? '`berkas_scan`' : 't.berkas';
            $sql .= " WHERE $berkas IS NOT NULL";
            $tahun = $this->session->data_filter_tahun;
            $tgl = $filter_jenis ? '`tanggal_surat`' : 't.tanggal_dokumen';
            if($tahun){
                $sql .= " AND EXTRACT(YEAR FROM $tgl) = $tahun";
            }
            if($cari = $this->session->data_filter_cari){
                $nama = $filter_jenis ? '`isi_singkat`' : 't.nama_dokumen';
                $sql .= " AND $nama LIKE '%".$this->db->escape_like_str($cari)."%' ESCAPE '!'";
            }
            $sql .= " ORDER BY $tgl DESC";
            if($limit){
                $sql .=  " LIMIT $p, $perpage";
            }
            $result = $this->db->query($sql)->result_array();
            foreach($result as $key => $row){
                $result[$key]['modul_asli'] = $result[$key]['jenis'];
            }
            return $result;
        }

        $result = null;
        $data = null;
        switch($kategori){
            case 'dokumen_desa':
                $data = $this->column_arsip_desa['column_dokumen'];
                break;
            case 'kependudukan':
                $data = $this->column_arsip_desa['column_kependudukan'];
                break;
            case 'layanan_surat':
                $data = $this->column_arsip_desa['column_layanan_surat'];
                break;
        }
        $data[1] = empty($data[1]) ? "''" : "`$data[1]`";
        $data[2] = empty($data[2]) ? "''" : "`$data[2]`";
        $data[3] = empty($data[3]) ? "''" : "`$data[3]`";
        $data[4] = empty($data[4]) ? "''" : "`$data[4]`";
        $this->datas_jenis($kategori);
        $this->db->select("$data[0] as id")
                ->select("$data[1] as no")
                ->select("$data[2] as tanggal_dokumen")
                ->select("$data[3] as nama_dokumen")
                ->select("$data[4] as berkas")
                ->select("$data[5] as tahun")
                ->select("$data[6] as jenis")
                ->select("`lokasi_arsip` as lokasi_arsip");

        if($jenis = $this->session->data_filter_jenis)
            $this->db->where("$data[6] = '$jenis'");
        if($tahun = $this->session->data_filter_tahun)
            $this->db->where("$data[5] = '$tahun'");
        if ($cari = $this->session->data_filter_cari){
            if($kategori == 'kependudukan' || $kategori == 'layanan_surat'){
                $id = '';
                $tabel = '';
                switch ($kategori){
                    case 'kependudukan':
                        $id = 'd.id_pend AS id';
                        $tabel = 'dokumen_hidup AS d';
                        $nama = 'p.nama';
                        $tgl = 'd.tgl_upload';
                        break;
                    case 'layanan_surat':
                        $id = 's.id_pend AS id';
                        $tabel = 'log_surat AS s';
                        $nama = 'p.nama';
                        $tgl = 's.tanggal';
                        break;
                    default:
                        break;
                }
                $sql = "SELECT $id FROM $tabel";
                $sql .= $this->build_statement($tabel);
                $sql .= " AND $nama LIKE '%".$this->db->escape_like_str($cari)."%' ESCAPE '!' ";
                $sql .= "ORDER BY $tgl DESC";
                $rows = $this->db->query($sql)->result();
                $id_cari = [];
                foreach($rows as $row){
                    $id_cari[] = $row->id;
                }
                $this->db->where_in('id_pend', $id_cari);
            }
            else {
                $this->db
                ->group_start()
                ->like("$data[3]", $cari)
                ->group_end();
            }
        }
        $this->db->order_by('tanggal_dokumen', 'DESC');
        if($limit) $this->db->limit($perpage, $p);
        $result = $this->db->get()->result_array();
        
        foreach($result as $key => $row){
            if($kategori == 'dokumen_desa')
            $result[$key]['jenis'] = $row['jenis']==3 ? 'perdes' : 'sk_kades';
            else if($kategori == 'kependudukan'){
                $result[$key]['modul_asli'] = 'penduduk/dokumen/'.$row['nama_dokumen'];
                $result[$key]['nama_dokumen'] = $this->db->select('nama')->from('tweb_penduduk')->where('id', $row['nama_dokumen'])->get()->row()->nama;
                $result[$key]['jenis'] = $this->db->select('ref_syarat_nama')->from('ref_syarat_surat')->where('ref_syarat_id', $row['jenis'])->get()->row()->ref_syarat_nama;
            }
            else if($kategori == 'layanan_surat'){
                $lampiran = $this->keluar_model->get_surat($row['id'])->lampiran;
                $nik = $this->db->select('nik')->where('id', $row['nama_dokumen'])->get('tweb_penduduk')->row()->nik;
                $result[$key]['nama_dokumen'] = $this->db->select('nama')->from('tweb_penduduk')->where('id', $row['nama_dokumen'])->get()->row()->nama;
                $result[$key]['jenis'] = $this->db->select('nama')->from('tweb_surat_format')->where('id', $row['jenis'])->get()->row()->nama;
                $result[$key]['modul_asli'] = 'keluar/perorangan/'.$nik;
                $result[$key]['lampiran'] = $lampiran;
            }

            if($row['jenis'] == 3 && $kategori == 'dokumen_desa'){
                $result[$key]['no'] = json_decode($row['no'], true)['no_ditetapkan'];
                $result[$key]['tanggal_dokumen'] = json_decode($row['tanggal_dokumen'], true)['tgl_kesepakatan'];
                $result[$key]['modul_asli'] = 'dokumen_sekretariat/clear/'.$row['jenis'];
            }
            else if($row['jenis'] == 2 && $kategori == 'dokumen_desa'){
                $result[$key]['no'] = json_decode($row['tanggal_dokumen'], true)['no_kep_kades'];
                $result[$key]['tanggal_dokumen'] = json_decode($row['tanggal_dokumen'], true)['tgl_kep_kades'];
                $result[$key]['modul_asli'] = 'dokumen_sekretariat/clear/'.$row['jenis'];
            }
        }
        
        return $result;
    }

    private function build_statement($tabel)
    {
        switch($tabel){
            case 'dokumen_hidup AS d':
                return " INNER JOIN tweb_penduduk AS p ON d.id_pend=p.id WHERE d.id_pend != 0 AND d.satuan IS NOT NULL";
                break;
            case 'log_surat AS s':
                return " INNER JOIN tweb_penduduk AS p ON s.id_pend=p.id WHERE 1=1";
                break;
        }

        return;
    }

    public function paging($p = 1, $kategori)
    {
        $jml_data = count($this->ambil_dokumen_perpage($kategori, false));

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->data_perpage;
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

    public function hapus_dokumen($tabel, $id)
    {
        $this->db->where('id', $id);
        if($tabel == 'dokumen_hidup'){
            $this->db->set('deleted', 1);
            $this->db->update($tabel);
            return ;
        }
        $this->db->delete($tabel);
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

    private function datas_jenis($kategori)
    {
        switch ($kategori) {
            case 'dokumen_desa':
                $this->db->where('`satuan` IS NOT NULL')
                ->where('`kategori` != 1')
                ->from('dokumen_hidup');
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
