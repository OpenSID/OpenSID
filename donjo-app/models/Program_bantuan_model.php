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

class Program_bantuan_model extends MY_Model
{
    // Untuk datatables peserta bantuan di themes/nama_tema/partials/statistik.php (web)
    public $column_order  = [null, 'program', 'peserta', null]; //set column field database for datatable orderable
    public $column_search = []; // Daftar kolom yg bisa dicari
    public $order         = ['peserta' => 'asc']; // default order

    public function __construct()
    {
        $this->load->model(['penduduk_model', 'rtm_model', 'kelompok_model', 'wilayah_model']);
    }

    public function autocomplete($id, $cari)
    {
        // Jika parameter yg digunakan sama
        $tabel = 'program_peserta';
        $where = "program_id = {$id}";
        $joins = [
            ['penduduk_hidup', "{$tabel}.kartu_id_pend = penduduk_hidup.id", 'right'],
        ];

        $list_kode = [
            ['peserta', $tabel, $where, $cari],
            ['kartu_nik', $tabel, $where, $cari],
            ['kartu_nama', $tabel, $where, $cari],
        ];

        $data = $this->union($list_kode, $joins);

        return autocomplete_data_ke_str($data);
    }

    public function list_program($sasaran = 0)
    {
        if ($sasaran > 0) {
            $strSQL = 'SELECT p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.userid, p.status
				FROM program p WHERE p.sasaran=' . $sasaran;
        } else {
            $strSQL = "SELECT p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.userid, p.status, CONCAT('50',p.id) as lap
				FROM program p WHERE 1";
        }
        $query = $this->db->query($strSQL);

        return $query->result_array();
    }

    public function list_program_keluarga($kk_id)
    {
        $this->load->model('keluarga_model'); // Di-load di sini karena tidak bisa diload di constructor, karena keluarga_model juga load program_bantuan_model
        $no_kk   = $this->keluarga_model->get_nokk($kk_id);
        $sasaran = 2;
        $strSQL  = "
			SELECT p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.userid, p.status, CONCAT('50',p.id) as lap, pp.peserta
			FROM program p
			LEFT OUTER JOIN program_peserta pp ON p.id = pp.program_id AND pp.peserta = '{$no_kk}'
			WHERE p.sasaran = {$sasaran}";
        $query = $this->db->query($strSQL);

        return $query->result_array();
    }

    public function paging_peserta($p, $slug, $sasaran)
    {
        $sql      = $this->get_peserta_sql($slug, $sasaran, true);
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['jumlah'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function paging_bantuan($p)
    {
        $this->sasaran_sql();

        $jml_data = $this->db
            ->select('count(p.id) as jumlah')
            ->from('program p')
            ->get()->row()->jumlah;
        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // Mengambil data individu peserta
    public function get_peserta($peserta_id, $sasaran)
    {
        $this->load->model('wilayah_model');

        switch ($sasaran) {
            case 1:
                // Data Penduduk; $peserta_id adalah NIK
                $data                   = $this->get_penduduk($peserta_id);
                $data['alamat_wilayah'] = $this->wilayah_model->get_alamat_wilayah($data);
                $data['kartu_nik']      = $data['id_peserta']      = $data['nik']; /// NIK Penduduk digunakan sebagai peserta
                $data['judul_nik']      = 'NIK Penduduk';
                $data['judul']          = 'Penduduk';
                break;

            case 2:
                // Data Penduduk; $peserta_id adalah NIK
                // NIK bisa untuk anggota keluarga, belum tentu kepala KK
                $data = $this->get_penduduk($peserta_id);
                // Data KK
                $kk                     = $this->get_kk($data['id_kk']);
                $data['no_kk']          = $data['id_peserta']          = $kk['no_kk']; // No KK digunakan sebagai peserta
                $data['nik_kk']         = $kk['nik_kk'];
                $data['nama_kk']        = $kk['nama_kk'];
                $data['alamat_wilayah'] = $this->wilayah_model->get_alamat_wilayah($kk);
                $data['kartu_nik']      = $data['nik'];
                $data['judul_nik']      = 'NIK Penduduk';
                $data['judul']          = 'Peserta';
                break;

            case 3:
                // Data Penduduk; $peserta_id adalah No RTM (kolom no_kk)
                $data                    = $this->rtm_model->get_kepala_rtm($peserta_id, $is_no_kk                    = true);
                $data['id_peserta']      = $data['no_kk']; // No RTM digunakan sebagai peserta
                $data['nama_kepala_rtm'] = $data['nama'];
                $data['kartu_nik']       = $data['nik'];
                $data['judul_nik']       = 'NIK Kepala RTM';
                $data['judul']           = 'Kepala RTM';
                break;

            case 4:
                // Data Kelompok; $peserta_id adalah id kelompok
                $data               = $this->kelompok_model->get_ketua_kelompok($peserta_id);
                $data['kartu_nik']  = $data['nik'];
                $data['id_peserta'] = $peserta_id; // Id_kelompok digunakan sebagai peserta
                $data['judul_nik']  = 'Nama Kelompok';
                $data['judul']      = 'Ketua Kelompok';
                break;

            default:
                break;
        }

        return $data;
    }

    private function search_peserta_sql()
    {
        $value = $this->session->cari;

        if ($this->session->has_userdata('cari')) {
            $kw = $this->db->escape_like_str($value);
            $kw = '%' . $kw . '%';

            return " AND (o.nama LIKE '{$kw}' OR peserta LIKE '{$kw}' OR p.kartu_nik LIKE '{$kw}' OR p.kartu_nama LIKE '{$kw}' OR o.tag_id_card LIKE '{$kw}')";
        }
    }

    // Query dibuat pada satu tempat, supaya penghitungan baris untuk paging selalu
    // konsisten dengan data yang diperoleh
    private function get_peserta_sql($slug, $sasaran, $jumlah = false)
    {
        if ($jumlah) {
            $select_sql = 'COUNT(p.id) as jumlah';
        }

        switch ($sasaran) {
            case 1:
                // Data penduduk
                if (! $jumlah) {
                    $select_sql = 'p.*, o.nama, s.nama as status_dasar, x.nama AS sex, w.rt, w.rw, w.dusun, k.no_kk';
                }
                $strSQL = 'SELECT ' . $select_sql . ' FROM program_peserta p
					RIGHT JOIN tweb_penduduk o ON p.peserta = o.nik
                    LEFT JOIN tweb_status_dasar s ON o.status_dasar = s.id
					LEFT JOIN tweb_penduduk_sex x ON x.id = o.sex
					LEFT JOIN tweb_keluarga k ON k.id = o.id_kk
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE p.program_id =' . $slug;
                break;

            case 2:
                // Data KK
                if (! $jumlah) {
                    $select_sql = 'p.*, p.peserta as nama, k.nik_kepala, k.no_kk, o.nik as nik_kk, o.nama as nama_kk, x.nama AS sex, w.rt, w.rw, w.dusun, s.nama as status_dasar';
                }
                $strSQL = 'SELECT ' . $select_sql . '
					FROM program_peserta p
					JOIN tweb_keluarga k ON p.peserta = k.no_kk
					RIGHT JOIN tweb_penduduk o ON k.nik_kepala = o.id
                    LEFT JOIN tweb_status_dasar s ON o.status_dasar = s.id
					RIGHT JOIN tweb_penduduk kartu on p.kartu_id_pend = kartu.id
					LEFT JOIN tweb_penduduk_sex x ON x.id = kartu.sex
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE p.program_id =' . $slug;
                break;

            case 3:
                // Data RTM
                if (! $jumlah) {
                    $select_sql = 'p.*, o.nama, o.nik, r.no_kk, x.nama AS sex, w.rt, w.rw, w.dusun, s.nama as status_dasar';
                }
                $strSQL = 'SELECT ' . $select_sql . ' FROM program_peserta p
					LEFT JOIN tweb_rtm r ON r.no_kk = p.peserta
					RIGHT JOIN tweb_penduduk o ON o.id = r.nik_kepala
                    LEFT JOIN tweb_status_dasar s ON o.status_dasar = s.id
					LEFT JOIN tweb_penduduk_sex x ON x.id = o.sex
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE p.program_id=' . $slug;
                break;

            case 4:
                // Data Kelompok
                if (! $jumlah) {
                    $select_sql = 'p.*, o.nama, o.nik, x.nama AS sex, k.no_kk, r.nama as nama_kelompok, w.rt, w.rw, w.dusun, s.nama as status_dasar';
                }
                $strSQL = 'SELECT ' . $select_sql . ' FROM program_peserta p
					LEFT JOIN kelompok r ON r.id = p.peserta
					RIGHT JOIN tweb_penduduk o ON o.id = r.id_ketua
                    LEFT JOIN tweb_status_dasar s ON o.status_dasar = s.id
					LEFT JOIN tweb_penduduk_sex x ON x.id = o.sex
					LEFT JOIN tweb_keluarga k on k.id = o.id_kk
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE p.program_id=' . $slug;
                break;

            default:
                break;
        }

        $strSQL .= $this->search_peserta_sql();

        return $strSQL;
    }

    public function get_sasaran($id)
    {
        $this->db->select('sasaran, nama');
        $this->db->where('id', $id);
        $query = $this->db->get('program');
        $data  = $query->row_array();

        switch ($data['sasaran']) {
            case 1:
                $data['judul_sasaran'] = 'Sasaran Penduduk';
                break;

            case 2:
                $data['judul_sasaran'] = 'Sasaran Keluarga';
                break;

            case 3:
                $data['judul_sasaran'] = 'Sasaran Rumah Tangga';
                break;

            case 4:
                $data['judul_sasaran'] = 'Sasaran Kelompok';
                break;

            default:
                $data['judul_sasaran'] = 'Sasaran Penduduk';
                break;
        }

        return $data;
    }

    private function get_program_data($p, $slug)
    {
        $strSQL = 'SELECT p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.userid, p.status, p.asaldana, p.status
			FROM program p
			WHERE p.id = ' . $slug;
        $query  = $this->db->query($strSQL);
        $hasil0 = $query->row_array();

        $hasil0['paging'] = $this->paging_peserta($p, $slug, $hasil0['sasaran']);

        switch ($hasil0['sasaran']) {
            case 1:
                // Data penduduk
                $hasil0['judul_peserta']      = 'NIK';
                $hasil0['judul_peserta_plus'] = 'No. KK';
                $hasil0['judul_peserta_info'] = 'Nama Penduduk';
                $hasil0['judul_cari_peserta'] = 'NIK / Nama Penduduk';
                break;

            case 2:
                // Data KK
                $hasil0['judul_peserta']      = 'No. KK';
                $hasil0['judul_peserta_plus'] = 'NIK';
                $hasil0['judul_peserta_info'] = 'Kepala Keluarga';
                $hasil0['judul_cari_peserta'] = 'No. KK / Nama Kepala Keluarga';
                break;

            case 3:
                // Data RTM
                $hasil0['judul_peserta']      = 'No. Rumah Tangga';
                $hasil0['judul_peserta_info'] = 'Kepala Rumah Tangga';
                $hasil0['judul_cari_peserta'] = 'No. RT / Nama Kepala Rumah Tangga';
                break;

            case 4:
                // Data Kelompok
                $hasil0['judul_peserta']      = 'Nama Kelompok';
                $hasil0['judul_peserta_info'] = 'Ketua Kelompok';
                $hasil0['judul_cari_peserta'] = 'Nama Kelompok / Nama Kepala Keluarga';
        }

        return $hasil0;
    }

    private function get_data_peserta($hasil0, $slug)
    {
        $paging_sql = ' LIMIT ' . $hasil0['paging']->offset . ',' . $hasil0['paging']->per_page;
        $strSQL     = $this->get_peserta_sql($slug, $hasil0['sasaran']);
        $strSQL .= $paging_sql;
        $query = $this->db->query($strSQL);

        switch ($hasil0['sasaran']) {
            case 1:
                return $this->get_data_peserta_penduduk($query);
                break;

            case 2:
                return $this->get_data_peserta_kk($query);
                break;

            case 3:
                return $this->get_data_peserta_rumah_tangga($query);
                break;

            case 4:
                return $this->get_data_peserta_kelompok($query);
        }
    }

    private function get_data_peserta_penduduk($query)
    {
        // Data penduduk
        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['id']           = $data[$i]['id'];
                $data[$i]['nik']          = $data[$i]['peserta'];
                $data[$i]['peserta_plus'] = $data[$i]['no_kk'] ?: '-';
                $data[$i]['peserta_nama'] = $data[$i]['peserta'];
                $data[$i]['peserta_info'] = $data[$i]['nama'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']);
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }
            $hasil1 = $data;
        } else {
            $hasil1 = false;
        }

        return $hasil1;
    }

    private function get_data_peserta_kk($query)
    {
        // Data KK
        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['id']           = $data[$i]['id'];
                $data[$i]['nik']          = $data[$i]['peserta'];
                $data[$i]['peserta_plus'] = $data[$i]['nik_kk'];
                $data[$i]['peserta_nama'] = $data[$i]['peserta'];
                $data[$i]['peserta_info'] = $data[$i]['nama_kk'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']);
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }
            $hasil1 = $data;
        } else {
            $hasil1 = false;
        }

        return $hasil1;
    }

    private function get_data_peserta_rumah_tangga($query)
    {
        // Data RTM
        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['id']           = $data[$i]['id'];
                $data[$i]['nik']          = $data[$i]['peserta'];
                $data[$i]['peserta_nama'] = $data[$i]['no_kk'];
                $data[$i]['peserta_info'] = $data[$i]['nama'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']) . ' [' . $data[$i]['nik'] . ' - ' . $data[$i]['no_kk'] . ']';
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }
            $hasil1 = $data;
        } else {
            $hasil1 = false;
        }

        return $hasil1;
    }

    private function get_data_peserta_kelompok($query)
    {
        // Data Kelompok
        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['id']           = $data[$i]['id'];
                $data[$i]['nik']          = $data[$i]['nama_kelompok'];
                $data[$i]['peserta_nama'] = $data[$i]['nama_kelompok'];
                $data[$i]['peserta_info'] = $data[$i]['nama'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']);
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }
            $hasil1 = $data;
        } else {
            $hasil1 = false;
        }

        return $hasil1;
    }

    private function get_pilihan_penduduk($filter)
    {
        // Data penduduk
        $strSQL = 'SELECT p.nik, p.nama, w.rt, w.rw, w.dusun
			FROM penduduk_hidup p
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
			WHERE 1 ORDER BY nama';
        $query = $this->db->query($strSQL);
        $data  = '';
        $data  = $query->result_array();
        if ($query->num_rows() > 0) {
            $j = 0;

            for ($i = 0; $i < count($data); $i++) {
                // Abaikan penduduk yang sudah terdaftar pada program
                if (! in_array($data[$i]['nik'], $filter)) {
                    if ($data[$i]['nik'] != '') {
                        $data1[$j]['id']   = $data[$i]['nik'];
                        $data1[$j]['nik']  = $data[$i]['nik'];
                        $data1[$j]['nama'] = strtoupper($data[$i]['nama']) . ' [' . $data[$i]['nik'] . ']';
                        $data1[$j]['info'] = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
                        $j++;
                    }
                }
            }
            $hasil2 = $data1;
        } else {
            $hasil2 = false;
        }

        return $hasil2;
    }

    private function get_pilihan_kk($filter)
    {
        // Data KK

        // Daftar keluarga, tidak termasuk keluarga yang sudah menjadi peserta
        $query = $this->db
            ->select('k.no_kk, p.nama, p.nik, h.nama as kk_level, w.dusun, w.rw, w.rt')
            ->from('penduduk_hidup p')
            ->join('tweb_penduduk_hubungan h', 'h.id = p.kk_level', 'LEFT')
            ->join('keluarga_aktif k', 'k.id = p.id_kk', 'OUTER JOIN')
            ->join('tweb_wil_clusterdesa w', 'w.id = k.id_cluster', 'LEFT')
            ->where_in('p.kk_level', ['1', '2', '3', '4'])
            ->order_by('p.id_kk')
            ->get();

        $hasil2 = [];
        $data   = $query->result_array();
        if ($query->num_rows() > 0) {
            $j = 0;

            for ($i = 0; $i < count($data); $i++) {
                // Abaikan keluarga yang sudah terdaftar pada program
                if (! in_array($data[$i]['no_kk'], $filter)) {
                    $hasil2[$j]['id']   = $data[$i]['nik'];
                    $hasil2[$j]['nik']  = $data[$i]['nik'];
                    $hasil2[$j]['nama'] = strtoupper('KK[' . $data[$i]['no_kk'] . '] - [' . $data[$i]['kk_level'] . '] ' . $data[$i]['nama'] . ' [' . $data[$i]['nik'] . ']');
                    $hasil2[$j]['info'] = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
                    $j++;
                }
            }
        } else {
            $hasil2 = false;
        }

        return $hasil2;
    }

    private function get_pilihan_rumah_tangga($filter)
    {
        // Data RTM

        $strSQL = 'SELECT r.no_kk as id, o.nama, w.rt, w.rw, w.dusun  FROM tweb_rtm r
			LEFT JOIN tweb_penduduk o ON o.id = r.nik_kepala
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
			WHERE 1
			';
        $query  = $this->db->query($strSQL);
        $hasil2 = [];
        $data   = $query->result_array();
        if ($query->num_rows() > 0) {
            $j = 0;

            for ($i = 0; $i < count($data); $i++) {
                // Abaikan RTM yang sudah terdaftar pada program
                if (! in_array($data[$i]['id'], $filter)) {
                    $hasil2[$j]['id']   = $data[$i]['id'];
                    $hasil2[$j]['nik']  = $data[$i]['id'];
                    $hasil2[$j]['nama'] = strtoupper($data[$i]['nama']) . ' [' . $data[$i]['id'] . ']';
                    $hasil2[$j]['info'] = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
                    $j++;
                }
            }
        } else {
            $hasil2 = false;
        }

        return $hasil2;
    }

    private function get_pilihan_kelompok($filter)
    {
        // Data Kelompok
        $strSQL = 'SELECT k.id,k.nama as nama_kelompok, o.nama, w.rt, w.rw, w.dusun FROM kelompok k
			LEFT JOIN tweb_penduduk o ON o.id = k.id_ketua
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
			WHERE 1';
        $query  = $this->db->query($strSQL);
        $hasil2 = [];
        $data   = $query->result_array();
        if ($query->num_rows() > 0) {
            $j = 0;

            for ($i = 0; $i < count($data); $i++) {
                // Abaikan kelompok yang sudah terdaftar pada program
                if (! in_array($data[$i]['id'], $filter)) {
                    $hasil2[$j]['id']   = $data[$i]['id'];
                    $hasil2[$j]['nik']  = $data[$i]['nama_kelompok'];
                    $hasil2[$j]['nama'] = strtoupper($data[$i]['nama']) . ' [' . $data[$i]['nama_kelompok'] . ']';
                    $hasil2[$j]['info'] = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
                    $j++;
                }
            }
        } else {
            $hasil2 = false;
        }

        return $hasil2;
    }

    private function sasaran_sql()
    {
        if ($sasaran = $this->session->sasaran) {
            $this->db->where('p.sasaran', $sasaran);
        }
    }

    public function get_program($p, $slug)
    {
        if ($slug === false) {
            //Query untuk expiration status, jika end date sudah melebihi dari datenow maka status otomatis menjadi tidak aktif
            $expirySQL   = 'UPDATE program SET status = IF(edate < CURRENT_DATE(), 0, IF(edate > CURRENT_DATE(), 1, status)) WHERE status IS NOT NULL';
            $expiryQuery = $this->db->query($expirySQL);

            $response['paging'] = $this->paging_bantuan($p);

            $this->sasaran_sql();

            $data = $this->db
                ->select('COUNT(v.id) AS jml_peserta, p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.userid, p.status, p.asaldana')
                ->from('program p')
                ->join('program_peserta v', 'p.id = v.program_id', 'left')
                ->group_by('p.id')
                ->limit($response['paging']->per_page, $response['paging']->offset)
                ->get()->result_array();
            $response['program'] = $data;

            return $response;
        }

        // Untuk program bantuan, $slug berbentuk '50<program_id>'
        $slug   = preg_replace('/^50/', '', $slug);
        $hasil0 = $this->get_program_data($p, $slug);
        $hasil1 = $this->get_data_peserta($hasil0, $slug);
        $filter = array_column($hasil1, 'peserta');

        switch ($hasil0['sasaran']) {
            case 1:
                $hasil2 = $this->get_pilihan_penduduk($filter);
                break;

            case 2:
                $hasil2 = $this->get_pilihan_kk($filter);
                break;

            case 3:
                $hasil2 = $this->get_pilihan_rumah_tangga($filter);
                break;

            case 4:
                $hasil2 = $this->get_pilihan_kelompok($filter);
                break;

            default:
            }

        return [$hasil0, $hasil1, $hasil2];
    }

    // Ambil data program
    public function get_data_program($id)
    {
        // Untuk program bantuan, $id '50<program_id>'
        $program_id = preg_replace('/^50/', '', $id);

        return $this->db->select('*')->where('id', $program_id)->get('program')->row_array();
    }

    /*
     * Fungsi untuk menampilkan program bantuan yang sedang diterima peserta.
     * $id => id_peserta tergantung sasaran
     * $cat => sasaran program bantuan.
     *
     * */
    public function get_peserta_program($cat, $id)
    {
        $data_program = false;
        $strSQL       = "SELECT p.id AS id, o.peserta AS nik, o.id AS peserta_id,  p.nama AS nama, p.sdate, p.edate, p.ndesc, p.status
			FROM program_peserta o
			LEFT JOIN program p ON p.id = o.program_id
			WHERE ((o.peserta='" . $id . "') AND (p.sasaran='" . $cat . "'))";
        $query = $this->db->query($strSQL);
        if ($query->num_rows() > 0) {
            $data_program = $query->result_array();
        }

        switch ($cat) {
            case 1:
                // Rincian Penduduk
                $strSQL = "SELECT o.nama, o.foto, o.nik, w.rt, w.rw, w.dusun
					FROM tweb_penduduk o
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE o.nik='" . $id . "'";
                $query = $this->db->query($strSQL);
                if ($query->num_rows() > 0) {
                    $row         = $query->row_array();
                    $data_profil = [
                        'id'    => $id,
                        'nama'  => $row['nama'] . ' - ' . $row['nik'],
                        'ndesc' => 'Alamat: RT ' . strtoupper($row['rt']) . ' / RW ' . strtoupper($row['rw']) . ' ' . strtoupper($row['dusun']),
                        'foto'  => $row['foto'],
                    ];
                }

                break;

            case 2:
                // KK
                $strSQL = "SELECT o.nik_kepala, o.no_kk, p.nama, w.rt, w.rw, w.dusun FROM tweb_keluarga o
					LEFT JOIN tweb_penduduk p ON o.nik_kepala = p.id
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster WHERE o.no_kk = '" . $id . "'";
                $query = $this->db->query($strSQL);
                if ($query->num_rows() > 0) {
                    $row         = $query->row_array();
                    $data_profil = [
                        'id'    => $id,
                        'nama'  => 'Kepala KK : ' . $row['nama'] . ', NO KK: ' . $row['no_kk'],
                        'ndesc' => 'Alamat: RT ' . strtoupper($row['rt']) . ' / RW ' . strtoupper($row['rw']) . ' ' . strtoupper($row['dusun']),
                        'foto'  => '',
                    ];
                }
                break;

            case 3:
                // RTM
                $strSQL = "SELECT r.id, r.no_kk, o.nama, o.nik, w.rt, w.rw, w.dusun  FROM tweb_rtm r
					LEFT JOIN tweb_penduduk o ON o.id = r.nik_kepala
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE r.no_kk={$id}";
                $query = $this->db->query($strSQL);
                if ($query->num_rows() > 0) {
                    $row         = $query->row_array();
                    $data_profil = [
                        'id'    => $id,
                        'nama'  => 'Kepala RTM : ' . $row['nama'] . ', NIK: ' . $row['nik'],
                        'ndesc' => 'Alamat: RT ' . strtoupper($row['rt']) . ' / RW ' . strtoupper($row['rw']) . ' ' . strtoupper($row['dusun']),
                        'foto'  => '',
                    ];
                }
                break;

            case 4:
                // Kelompok
                $strSQL = "SELECT k.id as id, k.nama as nama, p.nama as ketua, p.nik as nik, w.rt, w.rw, w.dusun FROM kelompok k
				 LEFT JOIN tweb_penduduk p ON p.id = k.id_ketua
				 LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
				 WHERE k.id='" . $id . "'";
                $query = $this->db->query($strSQL);
                if ($query->num_rows() > 0) {
                    $row         = $query->row_array();
                    $data_profil = [
                        'id'    => $id,
                        'nama'  => $row['nama'],
                        'ndesc' => 'Ketua: ' . $row['ketua'] . ' [' . $row['nik'] . ']<br />Alamat: RT ' . strtoupper($row['rt']) . ' / RW ' . strtoupper($row['rw']) . ' ' . strtoupper($row['dusun']),
                        'foto'  => '',
                    ];
                }
                break;

            default:
            }
        if (! $data_program == false) {
            return ['programkerja' => $data_program, 'profil' => $data_profil];
        }

        return null;
    }

    public function set_program()
    {
        $data           = $this->validasi_bantuan($this->input->post());
        $data['userid'] = $this->session->user;

        return $this->db->insert('program', $data);
    }

    private function validasi_bantuan($post)
    {
        $data = [];
        // Ambil dan bersihkan data input
        $data['sasaran']  = $post['cid'];
        $data['nama']     = nomor_surat_keputusan($post['nama']);
        $data['ndesc']    = htmlentities($post['ndesc']);
        $data['asaldana'] = $post['asaldana'];
        $data['sdate']    = date('Y-m-d', strtotime($post['sdate']));
        $data['edate']    = date('Y-m-d', strtotime($post['edate']));
        $data['status']   = $post['status'];

        return $data;
    }

    public function add_peserta($program_id)
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';
        $data                     = $this->validasi_peserta($this->input->post());
        $data['program_id']       = $program_id;
        $data['peserta']          = $this->input->post('peserta');

        $file_gambar = $this->_upload_gambar();
        if ($file_gambar) {
            $data['kartu_peserta'] = $file_gambar;
        }
        $outp = $this->db->insert('program_peserta', $data);
        status_sukses($outp, true);
    }

    // $id = program_peserta.id
    public function edit_peserta($id)
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';
        $data                     = $this->validasi_peserta($this->input->post());

        if ($this->input->post('gambar_hapus')) {
            unlink(LOKASI_DOKUMEN . $this->input->post('gambar_hapus'));
            $data['kartu_peserta'] = '';
        }

        unset($data['gambar_hapus']);
        $file_gambar = $this->_upload_gambar($data['old_gambar']);
        if ($file_gambar) {
            $data['kartu_peserta'] = $file_gambar;
        }
        unset($data['old_gambar']);
        $this->db->where('id', $id);
        $outp = $this->db->update('program_peserta', $data);
        status_sukses($outp, true);
    }

    public function validasi_peserta($post)
    {
        $data['no_id_kartu']         = nama_terbatas($post['no_id_kartu']);
        $data['kartu_nik']           = bilangan($post['kartu_nik']);
        $data['kartu_nama']          = nama(htmlentities($post['kartu_nama']));
        $data['kartu_tempat_lahir']  = alamat(htmlentities($post['kartu_tempat_lahir']));
        $data['kartu_tanggal_lahir'] = date_is_empty($post['kartu_tanggal_lahir']) ? null : tgl_indo_in($post['kartu_tanggal_lahir']);
        $data['kartu_alamat']        = alamat(htmlentities($post['kartu_alamat']));
        if ($post['kartu_id_pend']) {
            $data['kartu_id_pend'] = bilangan($post['kartu_id_pend']);
        }

        return $data;
    }

    private function _upload_gambar($old_document = '')
    {
        if ($_FILES['satuan']['error'] == UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $error = periksa_file('satuan', unserialize(MIME_TYPE_GAMBAR), unserialize(EXT_GAMBAR));
        if ($error != '') {
            $this->session->set_userdata('success', -1);
            $this->session->set_userdata('error_msg', $error);

            return null;
        }
        $nama_file = $_FILES['satuan']['name'];
        $nama_file = time() . '-' . urlencode($nama_file);      // normalkan nama file
        UploadDocument($nama_file, $old_document);

        return $nama_file;
    }

    public function hapus_peserta_program($peserta_id, $program_id)
    {
        $this->db->where(['peserta' => $peserta_id, 'program_id' => $program_id]);
        $this->db->delete('program_peserta');
    }

    public function hapus_peserta($peserta_id = '', $semua = false)
    {
        $this->db->where('id', $peserta_id);
        $this->db->delete('program_peserta');
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $peserta_id) {
            $this->hapus_peserta($peserta_id, $semua = true);
        }
    }

    // Mengambil data individu peserta menggunakan id tabel program_peserta
    public function get_program_peserta_by_id($id)
    {
        $data = $this->db
            ->select('pp.*, p.sasaran')
            ->from('program_peserta pp')
            ->join('program p', 'pp.program_id = p.id')
            ->where('pp.id', $id)
            ->get()
            ->row_array();

        // Data tambahan untuk ditampilkan
        $peserta = $this->get_peserta($data['peserta'], $data['sasaran']);

        switch ($data['sasaran']) {
            case 1:
                $data['judul_peserta']      = 'NIK';
                $data['judul_peserta_info'] = 'Nama Peserta';
                $data['peserta_nama']       = $data['peserta'];
                $data['peserta_info']       = $peserta['nama'];
                break;

            case 2:
                // Data KK; $peserta_id adalah No KK
                $kk                         = $this->get_kk($data['peserta']);
                $data['judul_peserta']      = 'No. KK';
                $data['judul_peserta_info'] = 'Kepala Keluarga';
                $data['peserta_nama']       = $data['peserta'];
                $data['peserta_info']       = $kk['nama_kk'];
                break;

            case 3:
                $data['judul_peserta']      = 'No. Rumah Tangga';
                $data['judul_peserta_info'] = 'Kepala Rumah Tangga';
                $data['peserta_nama']       = $data['peserta'];
                $data['peserta_info']       = $peserta['nama'];
                break;

            case 4:
                $data['judul_peserta']      = 'Nama Kelompok';
                $data['judul_peserta_info'] = 'Ketua Kelompok';
                $data['peserta_nama']       = $peserta['nama_kelompok'];
                $data['peserta_info']       = $peserta['nama'];
                break;

            default:
            }

        return $data;
    }

    public function update_program($id)
    {
        $data  = $this->validasi_bantuan($this->input->post());
        $hasil = $this->db->where('id', $id)
            ->update('program', $data);

        if ($hasil) {
            $_SESSION['success'] = 1;
            $_SESSION['pesan']   = 'Data program telah diperbarui';
        } else {
            $_SESSION['success'] = -1;
        }
    }

    public function jml_peserta_program($id = null)
    {
        if ($id) {
            $jml_peserta = $this->db->select('count(v.program_id) as jml')->from('program p')->join('program_peserta v', 'p.id = v.program_id', 'left')->where('p.id', $id)->get()->row()->jml;
        } else {
            $jml_peserta = $this->db->get('program_peserta')->num_rows();
        }

        return $jml_peserta;
    }

    // Program yang sudah ada pesertanya tidak boleh dihapus
    public function hapus_program($id)
    {
        if ($this->jml_peserta_program($id) > 0) {
            $_SESSION['success'] = -1;

            return;
        }

        $hasil = $this->db->where('id', $id)->delete('program');
        if ($hasil) {
            $_SESSION['success'] = 1;
            $_SESSION['pesan']   = 'Data program telah dihapus';
        } else {
            $_SESSION['success'] = -1;
        }
    }

    /* Mendapatkan daftar bantuan yang diterima oleh penduduk
         parameter pencarian yang digunakan adalah nik ( data nik disimpan pada kolom peserta tabel program_peserta ).
         Saat ini terbatas pada program bantuan perorangan
    */
    public function daftar_bantuan_yang_diterima($nik)
    {
        return $this->db->select('p.*, pp.*')
            ->where(['peserta' => $nik])
            ->join('program p', 'p.id = pp.program_id', 'left')
            ->get('program_peserta pp')
            ->result_array();
    }

    /* ====================================
     * Untuk datatable #peserta_program di themes/nama_tema/partials/statistik.php
     * ==================================== */

    private function get_all_peserta_bantuan_query()
    {
        $this->db
            ->select('p.nama as program, pp.kartu_nama as peserta, pp.kartu_alamat AS alamat')
            ->from('program p')
            ->join('program_peserta pp', 'p.id = pp.program_id', 'left');

        // keluarga
        if ($this->input->post('stat') == 'bantuan_keluarga') {
            $this->db->where('p.sasaran', '2');
        }
        // penduduk
        else {
            $this->db->where('p.sasaran', '1');
        }
    }

    private function cari_query()
    {
        $cari = $this->input->post('search')['value'];
        if (! $cari || empty($this->column_search)) {
            return;
        }

        foreach ($this->column_search as $key => $item) {
            reset($this->column_search);
            if ($key === key($this->column_search)) {
                $this->db->group_start()
                    ->like($item, $cari);

                continue;
            }
            $this->db->or_like($item, $cari);
            end($this->column_search);
            if ($key === key($this->column_search)) {
                $this->db->group_end();
                break;
            }
        }
    }

    private function get_peserta_bantuan_query()
    {
        $this->column_search = ['p.nama', 'pp.kartu_nama', 'pp.kartu_alamat']; // Kolom yg dapat dicari
        $this->get_all_peserta_bantuan_query();
        $this->cari_query();

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_peserta_bantuan($filter = [])
    {
        if ($filter) {
            if ($status = $filter['status'] != '') {
                $this->db->where('p.status', $status);
            }

            $tahun = $this->session->tahun;
            if (isset($tahun)) {
                $this->db->where('YEAR(p.sdate)', $tahun);
                $this->db->or_where('YEAR(p.edate)', $tahun);
            }
        }

        $this->get_peserta_bantuan_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $data = $this->db->get()->result_array();

        return $data;
    }

    public function tahun_bantuan_pertama($sasaran = '')
    {
        if ($status = $this->session->status) {
            $this->db->where('status', $status);
        }

        if ($sasaran != '') {
            $this->db->where('sasaran', $sasaran);
        }

        return $this->db
            ->select('min(date_format(sdate, "%Y")) as thn')
            ->from('program')
            ->where('DAYNAME(sdate) IS NOT NULL')
            ->get()
            ->row()
            ->thn;
    }

    public function count_peserta_bantuan_filtered()
    {
        $this->get_peserta_bantuan_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function count_peserta_bantuan_all()
    {
        $this->get_all_peserta_bantuan_query();

        return $this->db->count_all_results();
    }

    //Ambil data yg dibutuhkan saja, ambil dr tabel penduduk_hidup
    public function get_penduduk($peserta_id)
    {
        $data = $this->db
            ->select('p.id as id, p.nama, p.nik, p.id_kk, p.id_rtm, p.rtm_level, x.nama AS sex, h.nama AS hubungan, p.tempatlahir, p.tanggallahir, a.nama AS agama, k.nama AS pendidikan, j.nama AS pekerjaan, w.nama AS warganegara, c.dusun, c.rw, c.rt')
            ->from('penduduk_hidup p')
            ->join('tweb_penduduk_sex x', 'x.id = p.sex', 'left')
            ->join('tweb_penduduk_hubungan h', 'h.id = p.kk_level', 'left')
            ->join('tweb_penduduk_agama a', 'a.id = p.agama_id', 'left')
            ->join('tweb_penduduk_pendidikan_kk k', 'k.id = p.pendidikan_kk_id', 'left')
            ->join('tweb_penduduk_pekerjaan j', 'j.id = p.pekerjaan_id', 'left')
            ->join('tweb_penduduk_warganegara w', 'w.id = p.warganegara_id', 'left')
            ->join('tweb_wil_clusterdesa c', 'c.id = p.id_cluster', 'left')
            ->group_start()
            ->where('p.nik', $peserta_id) // Hapus jika 'peserta' sudah fix menggunakan 'id' (sesuai sasaran) sebagai referensi parameter
            ->or_where('p.id', $peserta_id)
            ->group_end()
            ->get()
            ->row_array();

        $data['umur'] = umur($data['tanggallahir']);

        return $data;
    }

    public function get_kk($id_kk)
    {
        return $this->db
            ->select('k.no_kk, p.nik as nik_kk, p.nama as nama_kk, k.alamat, c.*')
            ->from('keluarga_aktif k')
            ->join('penduduk_hidup p', 'p.id = k.nik_kepala', 'left')
            ->join('tweb_wil_clusterdesa c', 'c.id = k.id_cluster', 'left')
            ->group_start()
            ->where('k.no_kk', $id_kk) // Hapus jika 'peserta' sudah fix menggunakan 'id' (sesuai sasaran) sebagai referensi parameter
            ->or_where('k.id', $id_kk)
            ->group_end()
            ->get()
            ->row_array();
    }

    public function impor_program($program_id = null, $data_program = [], $ganti_program = 0)
    {
        $this->session->success = 1;
        $sekarang               = $data_program['sdate'] ?? date('Y m d');
        $data_tambahan          = [
            'userid' => $this->session->user,
            'status' => ($data_program['edate'] < $sekarang) ? 0 : 1,
        ];

        $data_program = array_merge($data_program, $data_tambahan);

        if ($program_id == null) {
            $this->db->insert('program', $data_program);

            return $this->db->insert_id();
        }

        if ($ganti_program == 1) {
            $this->db->where('id', $program_id)->update('program', $data_program);
        }

        return $program_id;
    }

    public function impor_peserta($program_id = '', $data_peserta = [], $kosongkan_peserta = 0, $data_diubah = '')
    {
        $this->session->success = 1;

        if ($kosongkan_peserta == 1) {
            $this->db->where('program_id', $program_id)->delete('program_peserta');
        }

        if ($data_diubah) {
            $data_diubah = explode(', ', ltrim($data_diubah, ', '));

            $this->db->where_in('peserta', $data_diubah)->where('program_id', $program_id)->delete('program_peserta');
        }

        if ($data_peserta != null) {
            $outp = $this->db->insert_batch('program_peserta', $data_peserta);
        }
        status_sukses($outp, true);
    }

    // TODO: function ini terlalu panjang dan sebaiknya dipecah menjadi beberapa method
    public function cek_peserta($peserta = '', $sasaran = 1)
    {
        if (in_array($peserta, [null, '-', ' ', '0'])) {
            return false;
        }

        switch ($sasaran) {
            case 1:
                // Penduduk
                $sasaran_peserta = 'NIK';

                $data = $this->db
                    ->select('id, nik')
                    ->where('nik', $peserta)
                    ->get('penduduk_hidup')
                    ->result_array();
                break;

            case 2:
                // Keluarga
                $sasaran = 'No. KK';

                $data = $this->db
                    ->select('k.id, p.nik')
                    ->from('penduduk_hidup p')
                    ->join('keluarga_aktif k', 'k.id = p.id_kk', 'left')
                    ->where('k.no_kk', $peserta)
                    ->get()
                    ->result_array();
                break;

            case 3:
                // RTM
                // no_rtm = no_kk
                $sasaran_peserta = 'No. RTM';

                $data = $this->db
                    ->select('r.id, p.nik')
                    ->from('penduduk_hidup p')
                    ->join('tweb_rtm r', 'p.id = r.nik_kepala', 'left')
                    ->where('r.no_kk', $peserta)
                    ->get()
                    ->result_array();
                break;

            case 4:
                // Kelompok
                $sasaran_peserta = 'Kode Kelompok';

                $data = $this->db
                    ->select('kl.id, p.nik')
                    ->from('penduduk_hidup p')
                    ->join('kelompok kl', 'p.id = kl.id_ketua', 'left')
                    ->where('kl.kode', $peserta)
                    ->get()
                    ->result_array();
                break;

            default:
                // Lainnya
                break;
        }

        return [
            'id'              => $data[0]['id'], // untuk nik, no_kk, no_rtm, kode konversi menjadi id issue #3417
            'sasaran_peserta' => $sasaran_peserta,
            'valid'           => str_replace("'", '', explode(', ', sql_in_list(array_column($data, 'nik')))), // untuk daftar valid anggota keluarga
        ];
    }

    private function dusun($nama_dusun)
    {
        return ($this->setting->sebutan_dusun == '-') ? '' : ucwords(strtolower($this->setting->sebutan_dusun . ' ' . $nama_dusun));
    }

    // Jika sasaran (penduduk/keluarga/rumah-tangga/kelompok),
    // peserta terkait perlu dihapus juga dari program jenis sasaran itu
    public function hapus_peserta_dari_sasaran($peserta, $sasaran)
    {
        $peserta_hapus = $this->db
            ->select('pp.id')
            ->from('program_peserta pp')
            ->join('program p', 'p.id = pp.program_id')
            ->where('p.sasaran', $sasaran)
            ->where('pp.peserta', $peserta)
            ->get()->result_array();
        $peserta_hapus = array_column($peserta_hapus, 'id');
        if (empty($peserta_hapus)) {
            return true;
        }

        return $this->db
            ->where_in('id', $peserta_hapus)
            ->delete('program_peserta');
    }
}
