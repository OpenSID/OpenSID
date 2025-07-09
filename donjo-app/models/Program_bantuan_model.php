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

use App\Libraries\Paging;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;

defined('BASEPATH') || exit('No direct script access allowed');

class Program_bantuan_model extends MY_Model
{
    private $tahun;

    // Untuk datatables peserta bantuan di themes/nama_tema/partials/statistik.php (web)
    public $column_order  = [null, 'program', 'peserta', null]; //set column field database for datatable orderable
    public $column_search = []; // Daftar kolom yg bisa dicari
    public $order         = ['peserta' => 'asc']; // default order

    public function __construct()
    {
        parent::__construct();
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
            $this->db->where('p.sasaran', $sasaran);
        } else {
            $this->db->select("CONCAT('50',p.id) as lap");
        }

        return $this->config_id('p', true)
            ->select('p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.status')
            ->get('program p')
            ->result_array();
    }

    public function list_program_keluarga($kk_id)
    {
        $this->load->model('keluarga_model'); // Di-load di sini karena tidak bisa diload di constructor, karena keluarga_model juga load program_bantuan_model
        $no_kk   = $this->keluarga_model->get_nokk($kk_id);
        $sasaran = 2;
        $strSQL  = "
            SELECT p.id, p.nama, p.sasaran, p.ndesc, p.sdate, p.edate, p.status, CONCAT('50',p.id) as lap, pp.peserta
            FROM program p
            LEFT OUTER JOIN program_peserta pp ON p.id = pp.program_id AND pp.peserta = '{$no_kk}'
            WHERE p.sasaran = {$sasaran} AND p.config_id = {$this->config_id}";
        $query = $this->db->query($strSQL);

        return $query->result_array();
    }

    public function paging_peserta($p, $slug, $sasaran)
    {
        $sql      = $this->get_peserta_sql($slug, $sasaran, true);
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['jumlah'];

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    public function paging_bantuan($p)
    {
        $this->sasaran_sql();

        $jml_data = $this->config_id('p', true)
            ->select('count(p.id) as jumlah')
            ->from('program p')
            ->get()
            ->row()
            ->jumlah;

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
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
                $data['kartu_nik']      = $data['id_peserta'] = $data['nik']; /// NIK Penduduk digunakan sebagai peserta
                $data['judul_nik']      = 'NIK Penduduk';
                $data['judul']          = 'Penduduk';
                break;

            case 2:
                // Data Penduduk; $peserta_id adalah NIK
                // NIK bisa untuk anggota keluarga, belum tentu kepala KK
                $data = $this->get_penduduk($peserta_id);
                // Data KK
                $kk                     = $this->get_kk($data['id_kk']);
                $data['no_kk']          = $data['id_peserta'] = $kk['no_kk']; // No KK digunakan sebagai peserta
                $data['nik_kk']         = $kk['nik_kk'];
                $data['nama_kk']        = $kk['nama_kk'];
                $data['alamat_wilayah'] = $this->wilayah_model->get_alamat_wilayah($kk);
                $data['kartu_nik']      = $data['nik'];
                $data['judul_nik']      = 'NIK Penduduk';
                $data['judul']          = 'Peserta';
                break;

            case 3:
                // Data Penduduk; $peserta_id adalah No RTM (kolom no_kk)
                $data                    = $this->rtm_model->get_kepala_rtm($peserta_id, $is_no_kk = true);
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
    private function get_peserta_sql(string $slug, $sasaran, bool $jumlah = false): string
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

        $strSQL .= " AND p.config_id = {$this->config_id}";

        return $strSQL . $this->search_peserta_sql();
    }

    public function get_sasaran($id)
    {
        $data = $this->config_id(null, true)
            ->select('sasaran, nama')
            ->where('id', $id)
            ->get('program')
            ->row_array();

        switch ($data['sasaran']) {
            case 1:
            default:
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
        }

        return $data;
    }

    private function get_program_data($p, $slug)
    {
        $hasil0 = $this->config_id(null, true)
            ->where('id', $slug)
            ->get('program')
            ->row_array();

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

    private function get_data_peserta(array $hasil0, $slug)
    {
        $paging_sql = ' LIMIT ' . $hasil0['paging']->offset . ',' . $hasil0['paging']->per_page;
        $strSQL     = $this->get_peserta_sql($slug, $hasil0['sasaran']);
        $strSQL .= $paging_sql;
        $query = $this->db->query($strSQL);

        switch ($hasil0['sasaran']) {
            case 1:
                return $this->get_data_peserta_penduduk($query);

            case 2:
                return $this->get_data_peserta_kk($query);

            case 3:
                return $this->get_data_peserta_rumah_tangga($query);

            case 4:
                return $this->get_data_peserta_kelompok($query);
        }
    }

    private function get_data_peserta_penduduk($query)
    {
        // Data penduduk
        if ($query->num_rows() > 0) {
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['nik']          = $data[$i]['peserta'];
                $data[$i]['peserta_plus'] = $data[$i]['no_kk'] ?: '-';
                $data[$i]['peserta_nama'] = $data[$i]['peserta'];
                $data[$i]['peserta_info'] = $data[$i]['nama'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']);
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }

            return $data;
        }

        return false;
    }

    private function get_data_peserta_kk($query)
    {
        // Data KK
        if ($query->num_rows() > 0) {
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['nik']          = $data[$i]['peserta'];
                $data[$i]['peserta_plus'] = $data[$i]['nik_kk'];
                $data[$i]['peserta_nama'] = $data[$i]['peserta'];
                $data[$i]['peserta_info'] = $data[$i]['nama_kk'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']);
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }

            return $data;
        }

        return false;
    }

    private function get_data_peserta_rumah_tangga($query)
    {
        // Data RTM
        if ($query->num_rows() > 0) {
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['nik']          = $data[$i]['peserta'];
                $data[$i]['peserta_nama'] = $data[$i]['no_kk'];
                $data[$i]['peserta_info'] = $data[$i]['nama'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']) . ' [' . $data[$i]['nik'] . ' - ' . $data[$i]['no_kk'] . ']';
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }

            return $data;
        }

        return false;
    }

    private function get_data_peserta_kelompok($query)
    {
        // Data Kelompok
        if ($query->num_rows() > 0) {
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['nik']          = $data[$i]['nama_kelompok'];
                $data[$i]['peserta_nama'] = $data[$i]['nama_kelompok'];
                $data[$i]['peserta_info'] = $data[$i]['nama'];
                $data[$i]['nama']         = strtoupper($data[$i]['nama']);
                $data[$i]['info']         = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
            }

            return $data;
        }

        return false;
    }

    private function get_pilihan_penduduk(array $filter)
    {
        $query = $this->config_id('p')
            ->select('p.nik, p.nama, w.rt, w.rw, w.dusun')
            ->from('penduduk_hidup p')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->order_by('nama')
            ->get();

        $data = $query->result_array();

        if ($query->num_rows() > 0) {
            $j       = 0;
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                // Abaikan penduduk yang sudah terdaftar pada program
                if (! in_array($data[$i]['nik'], $filter) && $data[$i]['nik'] != '') {
                    $data1[$j]['id']   = $data[$i]['nik'];
                    $data1[$j]['nik']  = $data[$i]['nik'];
                    $data1[$j]['nama'] = strtoupper($data[$i]['nama']) . ' [' . $data[$i]['nik'] . ']';
                    $data1[$j]['info'] = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . '  ' . $this->dusun($data[$i]['dusun']);
                    $j++;
                }
            }

            return $data1;
        }

        return false;
    }

    private function get_pilihan_kk(array $filter)
    {
        // Daftar keluarga, tidak termasuk keluarga yang sudah menjadi peserta
        $hasil2 = [];
        $query  = $this->config_id('p')
            ->select('k.no_kk, p.nama, p.nik, h.nama as kk_level, w.dusun, w.rw, w.rt')
            ->from('penduduk_hidup p')
            ->join('tweb_penduduk_hubungan h', 'h.id = p.kk_level', 'LEFT')
            ->join('keluarga_aktif k', 'k.id = p.id_kk', 'OUTER JOIN')
            ->join('tweb_wil_clusterdesa w', 'w.id = k.id_cluster', 'LEFT')
            ->where_in('p.kk_level', ['1', '2', '3', '4'])
            ->order_by('p.id_kk')
            ->get();

        $data = $query->result_array();

        if ($query->num_rows() > 0) {
            $j       = 0;
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
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

    private function get_pilihan_rumah_tangga(array $filter)
    {
        // Data RTM
        $hasil2 = [];
        $query  = $this->config_id('r')
            ->select('r.no_kk as id, o.nama, w.rt, w.rw, w.dusun')
            ->from('tweb_rtm r')
            ->join('tweb_penduduk o', 'o.id = r.nik_kepala', 'LEFT')
            ->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'LEFT')
            ->get();

        $data = $query->result_array();

        if ($query->num_rows() > 0) {
            $j       = 0;
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
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

    private function get_pilihan_kelompok(array $filter)
    {
        // Data Kelompok
        $hasil2 = [];
        $query  = $this->config_id('k')
            ->select('k.id,k.nama as nama_kelompok, o.nama, w.rt, w.rw, w.dusun')
            ->from('kelompok k')
            ->join('tweb_penduduk o', 'o.id = k.id_ketua', 'LEFT')
            ->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'LEFT')
            ->get();

        $data = $query->result_array();

        if ($query->num_rows() > 0) {
            $j       = 0;
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
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

    private function sasaran_sql(): void
    {
        if ($sasaran = $this->session->sasaran) {
            $this->db->where('p.sasaran', $sasaran);
        }
    }

    public function get_program($p, $slug)
    {
        if ($slug === false) {
            //Query untuk expiration status, jika end date sudah melebihi dari datenow maka status otomatis menjadi tidak aktif
            $expirySQL = 'UPDATE program SET status = IF(edate < CURRENT_DATE(), 0, IF(edate > CURRENT_DATE(), 1, status)) WHERE status IS NOT NULL AND config_id = ' . $this->config_id;
            $this->db->query($expirySQL);

            $response['paging'] = $this->paging_bantuan($p);

            $this->sasaran_sql();

            $data = $this->config_id('p', true)
                ->from('program p')
                ->group_by('p.id')
                ->limit($response['paging']->per_page, $response['paging']->offset)
                ->get()
                ->result_array();

            $response['program'] = collect($data)->map(function (array $item, $key): array {
                $item['jml_peserta'] = $this->db->query($this->get_peserta_sql($item['id'], (int) $item['sasaran'], true))->row_array()['jumlah'];

                return $item;
            });

            return $response;
        }

        // Untuk program bantuan, $slug berbentuk '50<program_id>'
        $slug   = preg_replace('/^50/', '', $slug);
        $hasil0 = $this->get_program_data($p, $slug);
        $hasil1 = $this->get_data_peserta($hasil0, $slug);
        $filter = array_column(is_array($hasil1) ? $hasil1 : [], 'peserta') ?? [];

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

        return $this->config_id(null, true)->where('id', $program_id)->get('program')->row_array();
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
        $query        = $this->config_id('o')
            ->select('p.id AS id, o.peserta AS nik, o.id AS peserta_id,  p.nama AS nama, p.sdate, p.edate, p.ndesc, p.status')
            ->from('program_peserta o')
            ->join('program p', 'p.id = o.program_id')
            ->where('o.peserta', $id)
            ->where('p.sasaran', $cat)
            ->get();

        if ($query->num_rows() > 0) {
            $data_program = $query->result_array();
        }

        switch ($cat) {
            case 1:
                // Rincian Penduduk
                $query = $this->config_id('o')
                    ->select('o.nama, o.foto, o.nik, w.rt, w.rw, w.dusun')
                    ->from('tweb_penduduk o')
                    ->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster')
                    ->where('o.nik', $id)
                    ->get();

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
                $query = $this->config_id('o')
                    ->select('o.nik_kepala, o.no_kk, p.nama, w.rt, w.rw, w.dusun')
                    ->from('tweb_keluarga o')
                    ->join('tweb_penduduk p', 'o.nik_kepala = p.id')
                    ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster')
                    ->where('o.no_kk', $id)
                    ->get();

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
                $query = $this->config_id('r')
                    ->select('r.id, r.no_kk, o.nama, o.nik, w.rt, w.rw, w.dusun')
                    ->from('tweb_rtm r')
                    ->join('tweb_penduduk o', 'o.id = r.nik_kepala')
                    ->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster')
                    ->where('r.no_kk', $id)
                    ->get();

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
                $query = $this->config_id('k')
                    ->select('k.id as id, k.nama as nama, p.nama as ketua, p.nik as nik, w.rt, w.rw, w.dusun')
                    ->from('kelompok k')
                    ->join('tweb_penduduk p', 'p.id = k.id_ketua')
                    ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster')
                    ->where('k.id', $id)
                    ->get();

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

    public function set_program(): void
    {
        $data              = $this->validasi_bantuan($this->input->post());
        $data['config_id'] = $this->config_id;

        $outp = $this->db->insert('program', $data);
        shortcut_cache();
        status_sukses($outp);
    }

    private function validasi_bantuan(array $post): array
    {
        $kk_level = json_encode($post['kk_level']);
        if ($post['cid'] != 2) {
            $kk_level = null;
        }

        return [
            // Ambil dan bersihkan data input
            'sasaran'  => $post['cid'],
            'nama'     => nomor_surat_keputusan($post['nama']),
            'ndesc'    => htmlentities($post['ndesc']),
            'asaldana' => $post['asaldana'],
            'sdate'    => date('Y-m-d', strtotime($post['sdate'])),
            'edate'    => date('Y-m-d', strtotime($post['edate'])),
            'kk_level' => $kk_level,
        ];
    }

    public function add_peserta($program_id): void
    {
        $data               = $this->validasi_peserta($this->input->post());
        $data['program_id'] = $program_id;
        $data['peserta']    = $this->input->post('peserta');
        if ($_FILES['file']['name']) {
            $data['kartu_peserta'] = unggah_file(['upload_path' => LOKASI_DOKUMEN, 'allowed_types' => 'jpg|jpeg|png']);
        }

        $outp = BantuanPeserta::create($data);
        status_sukses($outp);
    }

    // $id = program_peserta.id
    public function edit_peserta($id): void
    {
        $peserta = BantuanPeserta::findOrFail($id);
        if ($_FILES['file']['name']) {
            $peserta->kartu_peserta = unggah_file(['upload_path' => LOKASI_DOKUMEN, 'allowed_types' => 'jpg|jpeg|png'], $peserta->kartu_peserta);
        } elseif ($this->input->post('gambar_hapus')) {
            unlink(LOKASI_DOKUMEN . $peserta->kartu_peserta);
            $peserta->kartu_peserta = null;
        }
        $outp = $peserta->update($this->validasi_peserta($this->request));

        status_sukses($outp);
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

    public function hapus_peserta_program($peserta_id, $program_id): void
    {
        $this->config_id()->where(['peserta' => $peserta_id, 'program_id' => $program_id])->delete('program_peserta');
    }

    public function hapus_peserta($peserta_id = ''): void
    {
        $peserta = BantuanPeserta::findOrFail($peserta_id);
        $outp    = $peserta->delete();

        if ($outp && $peserta->kartu_peserta) {
            unlink(LOKASI_DOKUMEN . $peserta->kartu_peserta);
        }

        status_sukses($outp);
    }

    public function delete_all(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $peserta_id) {
            $this->hapus_peserta($peserta_id);
        }
    }

    // Mengambil data individu peserta menggunakan id tabel program_peserta
    public function get_program_peserta_by_id($id)
    {
        $data = $this->config_id('pp')
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

    public function update_program($id): void
    {
        $data  = $this->validasi_bantuan($this->input->post());
        $hasil = $this->config_id()
            ->where('id', $id)
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
            return $this->config_id('p', true)
                ->select('count(v.program_id) as jml')
                ->from('program p')
                ->join('program_peserta v', 'p.id = v.program_id', 'left')
                ->where('p.id', $id)
                ->get()
                ->row()
                ->jml;
        }

        return $this->config_id()->get('program_peserta')->num_rows();
    }

    // Program yang sudah ada pesertanya tidak boleh dihapus
    public function hapus_program($id): void
    {
        if ($this->jml_peserta_program($id) > 0) {
            $_SESSION['success'] = -1;

            return;
        }

        $this->config_id()->where('id', $id)->delete('program');
        shortcut_cache();
        status_sukses($this->db->affected_rows());
    }

    /* Mendapatkan daftar bantuan yang diterima oleh penduduk
     * parameter pencarian yang digunakan adalah nik ( data nik disimpan pada kolom peserta tabel program_peserta ).
     * Saat ini terbatas pada program bantuan perorangan
     */
    public function daftar_bantuan_yang_diterima($nik)
    {
        return $this->config_id('pp')
            ->select('p.*, pp.*')
            ->join('program p', 'p.id = pp.program_id', 'left')
            ->where(['peserta' => $nik])
            ->get('program_peserta pp')
            ->result_array();
    }

    /* ====================================
     * Untuk datatable #peserta_program di themes/nama_tema/partials/statistik.php
     * ==================================== */

    private function get_all_peserta_bantuan_query(): void
    {
        $dusun = $this->session->dusun;
        $rw    = $this->session->rw;
        $rt    = $this->session->rt;
        if ($dusun = $this->session->dusun) {
            $this->db->group_start();
            $this->db->where('a.dusun', $dusun);
            if ($rw = $this->session->rw) {
                $this->db->where('a.rw', $rw);
                if ($rt = $this->session->rt) {
                    $this->db->where('a.rt', $rt);
                }
            }
            $this->db->group_end();
        }
        $selectedTahun = $this->getTahun();
        if ($selectedTahun) {
            $this->db->where('YEAR(p.sdate)', $selectedTahun);
        }
        $this->config_id('p', true)
            ->select('p.nama as program, pp.kartu_nama as peserta, pp.kartu_alamat AS alamat')
            ->from('program p')
            ->join('program_peserta pp', 'p.id = pp.program_id', 'left');

        $tipe = $this->input->post('stat');
        $this->jenis_sasaran($tipe);
    }

    private function jenis_sasaran($sasaran): void
    {
        // keluarga
        if ($sasaran == 'bantuan_keluarga') {
            $this->db->where('p.sasaran', 2);
            $sasaran = '1';
        }
        // penduduk
        elseif ($sasaran == 'bantuan_penduduk') {
            $this->db->where('p.sasaran', 1);
            $sasaran = '2';
        } else {
            $id = substr($sasaran, 2);
            $this->db->where('p.id', $id);
            $sasaran = Bantuan::find($id)->sasaran;
        }

        switch ($sasaran) {
            case '1':
                $this->db
                    ->join('tweb_penduduk pd', 'pp.peserta = pd.nik', 'left')
                    ->join('tweb_wil_clusterdesa a', 'pd.id_cluster = a.id', 'left');
                break;

            case '2':
                $this->db
                    ->join('tweb_keluarga k', 'pp.peserta = k.no_kk', 'left')
                    ->join('tweb_penduduk pd', 'k.nik_kepala = pd.id', 'left')
                    ->join('tweb_wil_clusterdesa a', 'pd.id_cluster = a.id', 'left');
                break;

            case '3':
                $this->db->join('tweb_rtm s', 's.no_kk = pp.peserta', 'left');
                break;

            case '4':
                $this->db->join('kelompok s', 's.kode = pp.peserta', 'left');
                break;

            default:
                break;
        }
    }

    private function cari_query(): void
    {
        $cari = $this->input->post('search')['value'];
        if (! $cari || empty($this->column_search)) {
            return;
        }

        foreach ($this->column_search as $key => $item) {
            if ($key === array_key_first($this->column_search)) {
                $this->db->group_start()
                    ->like($item, $cari);

                continue;
            }
            $this->db->or_like($item, $cari);
            if ($key === array_key_last($this->column_search)) {
                $this->db->group_end();
                break;
            }
        }
    }

    private function get_peserta_bantuan_query(): void
    {
        $this->column_search = ['p.nama', 'pp.kartu_nama', 'pp.kartu_alamat']; // Kolom yg dapat dicari
        $this->get_all_peserta_bantuan_query();
        $this->cari_query();

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif ($this->order !== null) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_peserta_bantuan($filter = [])
    {
        if ($filter) {
            if ($filter['status'] != '') {
                $this->db->where('p.status', $filter['status']);
            }

            if ($filter['tahun'] != '') {
                $this->db
                    ->group_start()
                    ->where('YEAR(p.sdate) <=', $filter['tahun'])
                    ->where('YEAR(p.edate) >=', $filter['tahun'])
                    ->group_end();
            }
        }

        $this->get_peserta_bantuan_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        return $this->db->get()->result_array();
    }

    public function tahun_bantuan_pertama($sasaran = '')
    {
        if ($status = $this->session->status) {
            $this->db->where('status', $status);
        }

        if ($sasaran != '') {
            $this->db->where('sasaran', $sasaran);
        }

        return $this->config_id(null, true)
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
        $data = $this->config_id('p')
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
        return $this->config_id('k')
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
            'status'    => ($data_program['edate'] < $sekarang) ? 0 : 1,
            'config_id' => $this->config_id,
        ];

        $data_program = array_merge($data_program, $data_tambahan);

        if ($program_id == null) {
            $this->db->insert('program', $data_program);
            shortcut_cache();

            return $this->db->insert_id();
        }

        if ($ganti_program == 1) {
            $this->db->where('id', $program_id)->update('program', $data_program);
        }

        return $program_id;
    }

    public function impor_peserta($program_id = '', $data_peserta = [], $kosongkan_peserta = 0, $data_diubah = ''): void
    {
        $this->session->success = 1;

        if ($kosongkan_peserta == 1) {
            $this->config_id()->where('program_id', $program_id)->delete('program_peserta');
        }

        if ($data_diubah) {
            $data_diubah = explode(', ', ltrim($data_diubah, ', '));

            $this->config_id()->where_in('peserta', $data_diubah)->where('program_id', $program_id)->delete('program_peserta');
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

                $data = $this->config_id()
                    ->select('id, nik')
                    ->where('nik', $peserta)
                    ->get('penduduk_hidup')
                    ->result_array();
                break;

            case 2:
                // Keluarga
                $sasaran_peserta = 'No. KK';

                $data = $this->config_id('p')
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

                $data = $this->config_id('p')
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

                $data = $this->config_id('p')
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

    private function dusun(string $nama_dusun): string
    {
        return (setting('sebutan_dusun') == '-') ? '' : ucwords(strtolower(setting('sebutan_dusun') . ' ' . $nama_dusun));
    }

    // Jika sasaran (penduduk/keluarga/rumah-tangga/kelompok),
    // peserta terkait perlu dihapus juga dari program jenis sasaran itu
    public function hapus_peserta_dari_sasaran($peserta, $sasaran)
    {
        $peserta_hapus = $this->config_id('pp')
            ->select('pp.id')
            ->from('program_peserta pp')
            ->join('program p', 'p.id = pp.program_id')
            ->where('p.sasaran', $sasaran)
            ->where('pp.peserta', $peserta)
            ->get()
            ->result_array();
        $peserta_hapus = array_column($peserta_hapus, 'id');
        if ($peserta_hapus === []) {
            return true;
        }

        return $this->config_id()
            ->where_in('id', $peserta_hapus)
            ->delete('program_peserta');
    }

    public function peserta_duplikat($program)
    {
        return $this->config_id('pp')
            ->select('pp.peserta, COUNT(peserta) as jumlah, MAX(pp.id) as id, MAX(p.nama) as nama, MAX(p.sasaran) as sasaran, MAX(pp.kartu_nama) as kartu_nama')
            ->from('program_peserta pp')
            ->join('program p', 'pp.program_id = p.id')
            ->where('pp.program_id', $program['id'])
            ->group_by('pp.peserta')
            ->having('COUNT(peserta) > 1')
            ->get()
            ->result_array() ?? [];
    }

    public function peserta_tidak_valid($sasaran)
    {
        $query = $this->config_id('pp')
            ->select('pp.id, p.nama, p.sasaran, pp.peserta, pp.kartu_nama')
            ->from('program_peserta pp')
            ->join('program p', 'p.id = pp.program_id')
            ->where('p.sasaran', $sasaran)
            ->where('s.id is NULL')
            ->order_by('p.sasaran', 'pp.peserta');

        switch ($sasaran) {
            case '1':
                $query->join('tweb_penduduk s', 's.nik = pp.peserta', 'left');
                break;

            case '2':
                $query->join('tweb_keluarga s', 's.no_kk = pp.peserta', 'left');
                break;

            case '3':
                $query->join('tweb_rtm s', 's.no_kk = pp.peserta', 'left');
                break;

            case '4':
                $query->join('kelompok s', 's.kode = pp.peserta', 'left');
                break;

            default:
                break;
        }

        return $query->get()->result_array() ?? [];
    }

    /**
     * Get the value of tahun
     */
    public function getTahun()
    {
        return $this->tahun;
    }

    /**
     * Set the value of tahun
     *
     * @param mixed $tahun
     *
     * @return self
     */
    public function setTahun($tahun)
    {
        $this->tahun = $tahun;

        return $this;
    }
}
