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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan_penduduk_model extends MY_Model
{
    private $lap;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('program_bantuan_model');
    }

    public function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari = $_SESSION['cari'];
            $kw   = $this->db->escape_like_str($cari);
            $kw   = '%' . $kw . '%';

            return " AND u.nama LIKE '{$kw}'";
        }
    }

    private function dusun_sql()
    {
        if ($dusun = $this->session->userdata('dusun')) {
            return "AND a.dusun = '{$dusun}' ";
        }
    }

    private function rw_sql()
    {
        if ($rw = $this->session->userdata('rw')) {
            return "AND a.rw = '{$rw}' ";
        }
    }

    private function rt_sql()
    {
        if ($rt = $this->session->userdata('rt')) {
            return "AND a.rt = '{$rt}' ";
        }
    }

    // TODO: Digunakan dimana?
    protected function get_jumlah_sql($fk = false, $delimiter = false, $where = 0)
    {
        $sql = '(SELECT COUNT(b.id) FROM penduduk_hidup b
		LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
		WHERE 1 ';
        $sql .= $fk ? "AND {$fk} = u.id " : '';
        $sql .= $where ?: '';
        $sql .= $this->dusun_sql();
        $sql .= $this->rw_sql();
        $sql .= $this->rt_sql();
        $sql .= ') AS jumlah';

        return $sql . ($delimiter ? ',' : '');
    }

    // TODO: Digunakan dimana?
    protected function get_laki_sql($fk = false, $delimiter = false, $where = 0)
    {
        $sql = '(SELECT COUNT(b.id) FROM penduduk_hidup b
		LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
		WHERE sex = 1 ';
        $sql .= $fk ? "AND {$fk} = u.id " : '';
        $sql .= $where ?: '';
        $sql .= $this->dusun_sql();
        $sql .= $this->rw_sql();
        $sql .= $this->rt_sql();
        $sql .= ') AS laki';

        return $sql . ($delimiter ? ',' : '');
    }

    protected function get_perempuan_sql($fk = false, $delimiter = false, $where = 0)
    {
        $sql = '(SELECT COUNT(b.id) FROM penduduk_hidup b
		LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
		WHERE sex = 2 ';
        $sql .= $fk ? "AND {$fk} = u.id " : '';
        $sql .= $where ?: '';
        $sql .= $this->dusun_sql();
        $sql .= $this->rw_sql();
        $sql .= $this->rt_sql();
        $sql .= ') AS perempuan';

        return $sql . ($delimiter ? ',' : '');
    }

    public function judul_statistik($lap)
    {
        // Program bantuan berbentuk '50<program_id>'
        if ((int) $lap > 50) {
            $program_id = preg_replace('/^50/', '', $lap);

            $program = $this->config_id(null, true)
                ->select('nama')
                ->where('id', $program_id)
                ->get('program')
                ->row_array();

            return $program['nama'];
        }

        $list_judul = unserialize(STAT_PENDUDUK) + unserialize(STAT_KELUARGA) + unserialize(STAT_RTM) + unserialize(STAT_BANTUAN);

        return $list_judul[$lap];
    }

    // -------------------- Siapkan data untuk statistik kependudukan -------------------

    protected function hitung_total(&$data)
    {
        $total['no']        = '';
        $total['id']        = TOTAL;
        $total['jumlah']    = 0;
        $total['laki']      = 0;
        $total['perempuan'] = 0;
        $counter            = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
            $total['jumlah'] += $data[$i]['jumlah'];
            $total['laki'] += $data[$i]['laki'];
            $total['perempuan'] += $data[$i]['perempuan'];
        }

        return $total;
    }

    protected function isi_nomor(&$data)
    {
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }
    }

    protected function hitung_persentase(&$data, $semua)
    {
        // Hitung semua presentase
        $counter = count($data);

        // Hitung semua presentase
        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['persen']  = persen2($data[$i]['jumlah'], $semua['jumlah']);
            $data[$i]['persen1'] = persen2($data[$i]['laki'], $semua['jumlah']);
            $data[$i]['persen2'] = persen2($data[$i]['perempuan'], $semua['jumlah']);
        }

        $data['total'] = $semua;
    }

    protected function baris_jumlah($total, $nama)
    {
        // Isi Total
        return [
            'no'        => '',
            'id'        => JUMLAH,
            'nama'      => $nama,
            'jumlah'    => $total['jumlah'],
            'perempuan' => $total['perempuan'],
            'laki'      => $total['laki'],
        ];
    }

    protected function baris_belum($semua, $total, $nama)
    {
        // Isi data jml belum mengisi
        $baris_belum = [
            'no'        => '',
            'id'        => BELUM_MENGISI,
            'nama'      => $nama,
            'jumlah'    => $semua['jumlah'] - $total['jumlah'],
            'perempuan' => $semua['perempuan'] - $total['perempuan'],
            'laki'      => $semua['laki'] - $total['laki'],
        ];
        if (isset($total['jumlah_nonaktif'])) {
            $baris_belum['jumlah'] += $total['jumlah_nonaktif'];
            $baris_belum['perempuan'] += $total['jumlah_nonaktif_perempuan'];
            $baris_belum['laki'] += $total['jumlah_nonaktif_laki'];
        }

        return $baris_belum;
    }

    private function select_jml_penduduk_per_kategori(string $id_referensi, string $tabel_referensi): void
    {
        $this->filter_wilayah();

        $this->db
            ->select('u.*, COUNT(p.id) AS jumlah')
            ->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
            ->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
            ->from("{$tabel_referensi} u")
            ->join('penduduk_hidup p', "u.id = p.{$id_referensi} AND p.config_id = {$this->config_id} ", 'left')
            ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left')
            ->group_by('u.id');
    }

    protected function data_jml_semua_penduduk($status_dasar = '1')
    {
        $this->filter_wilayah();

        return $this->config_id('b')
            ->select('COUNT(b.id) AS jumlah')
            ->select('COUNT(CASE WHEN b.sex = 1 THEN b.id END) AS laki')
            ->select('COUNT(CASE WHEN b.sex = 2 THEN b.id END) AS perempuan')
            ->from('tweb_penduduk b')
            ->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id', 'left')
            ->where('b.status_dasar', $status_dasar)
            ->get()
            ->row_array();
    }

    protected function data_jml_semua_keluarga()
    {
        $this->filter_wilayah();

        return $this->config_id('k')
            ->select('COUNT(k.id) as jumlah')
            ->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
            ->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
            ->from('keluarga_aktif k')
            ->join('tweb_penduduk p', 'p.id = k.nik_kepala', 'left')
            ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left')
            ->get()
            ->row_array();
    }

    protected function data_jml_semua_rtm()
    {
        $this->filter_wilayah();

        return $this->config_id('r')
            ->select('COUNT(r.id) as jumlah')
            ->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
            ->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
            ->from('tweb_rtm r')
            ->join('tweb_penduduk p', 'p.id = r.nik_kepala', 'left') //TODO : Ganti kolom no_kk jadi no_rtm
            ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left')
            ->where('r.nik_kepala !=', null)
            ->get()
            ->row_array();
    }

    protected function persentase_semua($semua)
    {
        // Hitung persentase
        $semua['no']      = '';
        $semua['id']      = TOTAL;
        $semua['nama']    = 'TOTAL';
        $semua['persen']  = persen2(($semua['laki'] + $semua['perempuan']), $semua['jumlah']);
        $semua['persen1'] = persen2($semua['laki'], $semua['jumlah']);
        $semua['persen2'] = persen2($semua['perempuan'], $semua['jumlah']);

        return $semua;
    }

    protected function order_by($o, $lap)
    {
        //Ordering SQL
        switch (true) {
            case $o == 1 && $lap == 'suku':
                $this->db->order_by($lap);
                break;

            case $o == 2 && $lap == 'suku':
                $this->db->order_by($lap . ' DESC');
                break;

            case $lap == 'bdt':
                break;

            case $o == 1:

            case $o == 1:
                $this->db->order_by('u.id');
                break;

            case $o == 2:
                $this->db->order_by('u.id DESC');
                break;

            case $o == 3:
                $this->db->order_by('laki');
                break;

            case $o == 4:
                $this->db->order_by('laki DESC');
                break;

            case $o == 5:
                $this->db->order_by('jumlah');
                break;

            case $o == 6:
                $this->db->order_by('jumlah DESC');
                break;

            case $o == 7:
                $this->db->order_by('perempuan');
                break;

            case $o == 8:
                $this->db->order_by('perempuan DESC');
                break;
        }
    }

    private function select_jml(string $where, string $status_dasar = '1'): void
    {
        $str_jml_penduduk  = $this->str_jml_penduduk($where, '', $status_dasar);
        $str_jml_laki      = $this->str_jml_penduduk($where, '1', $status_dasar);
        $str_jml_perempuan = $this->str_jml_penduduk($where, '2', $status_dasar);
        $this->db
            ->select("({$str_jml_penduduk}) as jumlah")
            ->select("({$str_jml_laki}) as laki")
            ->select("({$str_jml_perempuan}) as perempuan");
    }

    private function str_jml_penduduk(string $where, string $sex = '', string $status_dasar = '1')
    {
        $this->filter_wilayah();

        if ($sex !== '' && $sex !== '0') {
            $this->db->where('b.sex', $sex);
        }

        return $this->config_id('b')
            ->select('COUNT(b.id)')
            ->from('tweb_penduduk b')
            ->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id', 'left')
            ->join('log_penduduk l', 'l.id_pend = b.id', 'left')
            ->where('b.status_dasar', $status_dasar)
            ->where($where)
            ->get_compiled_select();
    }

    protected function select_per_kategori()
    {
        $lap = $this->lap;

        // Bagian Penduduk
        $statistik_penduduk = [
            '0'           => ['id_referensi' => 'pendidikan_kk_id', 'tabel_referensi' => 'tweb_penduduk_pendidikan_kk'],
            '1'           => ['id_referensi' => 'pekerjaan_id', 'tabel_referensi' => 'tweb_penduduk_pekerjaan'],
            '2'           => ['id_referensi' => 'status_kawin', 'tabel_referensi' => 'tweb_penduduk_kawin'],
            '3'           => ['id_referensi' => 'agama_id', 'tabel_referensi' => 'tweb_penduduk_agama'],
            '4'           => ['id_referensi' => 'sex', 'tabel_referensi' => 'tweb_penduduk_sex'],
            'hubungan_kk' => ['id_referensi' => 'kk_level', 'tabel_referensi' => 'tweb_penduduk_hubungan'],
            '5'           => ['id_referensi' => 'warganegara_id', 'tabel_referensi' => 'tweb_penduduk_warganegara'],
            '6'           => ['id_referensi' => 'status', 'tabel_referensi' => 'tweb_penduduk_status'],
            '7'           => ['id_referensi' => 'golongan_darah_id', 'tabel_referensi' => 'tweb_golongan_darah'],
            '9'           => ['id_referensi' => 'cacat_id', 'tabel_referensi' => 'tweb_cacat'],
            '10'          => ['id_referensi' => 'sakit_menahun_id', 'tabel_referensi' => 'tweb_sakit_menahun'],
            '14'          => ['id_referensi' => 'pendidikan_sedang_id', 'tabel_referensi' => 'tweb_penduduk_pendidikan'],
            '16'          => ['id_referensi' => 'cara_kb_id', 'tabel_referensi' => 'tweb_cara_kb'],
            '19'          => ['id_referensi' => 'id_asuransi', 'tabel_referensi' => 'tweb_penduduk_asuransi'],
        ];

        switch ("{$lap}") {
            case 'akta-kematian':
                // Akta Kematian
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai AND l.akta_mati IS NOT NULL ";
                $this->select_jml($where, '2');
                $this->db
                    ->select("u.*, concat('UMUR ', u.dari, ' S/D ', u.sampai, ' TAHUN') as nama")
                    ->from('tweb_penduduk_umur u')
                    ->where('u.status', '1');
                break;

            // KELUARGA
            case 'kelas_sosial':
                // Kelas Sosial
                $this->db
                    ->select('u.*, COUNT(k.id) as jumlah')
                    ->select('COUNT(CASE WHEN kelas_sosial = u.id AND p.sex = 1 THEN p.id END) AS laki')
                    ->select('COUNT(CASE WHEN kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS perempuan')
                    ->from('tweb_keluarga_sejahtera u')
                    ->join('keluarga_aktif k', "k.kelas_sosial = u.id AND k.config_id = {$this->config_id}", 'left')
                    ->join('tweb_penduduk p', 'p.id=k.nik_kepala', 'left')
                    ->group_by('u.id');
                break;

                // RTM
            case 'bdt':
                // BDT
                $this->config_id('u')
                    ->select('COUNT(u.id) as jumlah')
                    ->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
                    ->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
                    ->from('tweb_rtm u')
                    ->join('tweb_penduduk p', 'p.id = u.nik_kepala')
                    ->group_by('u.id')
                    ->where('u.bdt !=', null);
                break;

                // BANTUAN
            case 'bantuan_penduduk':
                $sql = 'SELECT u.*,
                    (SELECT COUNT(kartu_nik) FROM program_peserta WHERE program_id = u.id AND config_id = u.config_id) AS jumlah,
                    (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 1 AND config_id = u.config_id) AS laki,
                    (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 2 AND config_id = u.config_id) AS perempuan
                    FROM program u WHERE (u.config_id = ' . $this->config_id . ' OR u.config_id IS NULL)';
                break;

                // PENDUDUK
            case 'hamil':
                // Kehamilan
                $this->db->where('p.sex', 2);
                $this->select_jml_penduduk_per_kategori('hamil', 'ref_penduduk_hamil');
                break;

            case 'buku-nikah':
                // kepemilikan buku nikah
                $this->db->where('p.akta_perkawinan !=', null)->where('p.akta_perkawinan !=', '');
                $this->db->where('p.status_kawin !=', 1);
                $this->select_jml_penduduk_per_kategori('status_kawin', 'tweb_penduduk_kawin');
                break;

            case 'kia':
                // Kepemilikan kia
                $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)<=17) AND u.status_rekam = status_rekam AND b.ktp_el = '3'";
                $this->select_jml($where);
                $this->db
                    ->select('u.*')
                    ->from('tweb_status_ktp u');
                break;

            case 'covid':
                // Covid
                $this->db
                    ->select('u.*, COUNT(k.id) as jumlah')
                    ->select('COUNT(CASE WHEN k.status_covid = u.id AND p.sex = 1 THEN k.id_terdata END) AS laki')
                    ->select('COUNT(CASE WHEN k.status_covid = u.id AND p.sex = 2 THEN k.id_terdata END) AS perempuan')
                    ->from('ref_status_covid u')
                    ->join('covid19_pemudik k', "k.status_covid = u.id and k.config_id = {$this->config_id}", 'left')
                    ->join('tweb_penduduk p', 'p.id=k.id_terdata', 'left')
                    ->group_by('u.id');
                break;

            case 'suku':
                // Suku
                $this->config_id('u')
                    ->select('u.suku AS nama, u.suku AS id')
                    ->select('COUNT(u.sex) AS jumlah')
                    ->select('COUNT(CASE WHEN u.sex = 1 THEN 1 END) AS laki')
                    ->select('COUNT(CASE WHEN u.sex = 2 THEN 1 END) AS perempuan')
                    ->from('penduduk_hidup AS u')
                    ->group_by('u.suku')
                    ->where('u.suku IS NOT NULL')
                    ->where('u.suku != ""')
                    ->join('tweb_wil_clusterdesa a', 'u.id_cluster = a.id', 'left');

                $this->filter_wilayah();

                break;

            case 'bpjs-tenagakerja':
                // BPJS Tenaga Kerja
                $this->select_jml_penduduk_per_kategori('pekerjaan_id', 'tweb_penduduk_pekerjaan');
                $this->db->where("(p.bpjs_ketenagakerjaan IS NOT NULL && p.bpjs_ketenagakerjaan != '')");
                break;

            case in_array($lap, array_keys($statistik_penduduk)):
                // Dengan tabel referensi
                $this->select_jml_penduduk_per_kategori($statistik_penduduk["{$lap}"]['id_referensi'], $statistik_penduduk["{$lap}"]['tabel_referensi']);
                break;

            case '13':
                // Umur rentang
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai";
                $this->select_jml($where);
                $this->config_id('u')
                    ->select('u.*')
                    ->from('tweb_penduduk_umur u')
                    ->where('u.status', '1');
                break;

            case '15':
                // Umur kategori
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai ";
                $this->select_jml($where);
                $this->config_id('u')
                    ->select("u.*, concat(u.nama, ' (', u.dari, ' - ', u.sampai, ')') as nama")
                    ->from('tweb_penduduk_umur u')
                    ->where('u.status', '0');
                break;

            case '17':
                // Akta kelahiran
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai AND akta_lahir <> '' ";
                $this->select_jml($where);
                $this->config_id('u')
                    ->select("u.*, concat('UMUR ', u.dari, ' S/D ', u.sampai, ' TAHUN') as nama")
                    ->from('tweb_penduduk_umur u')
                    ->where('u.status', '1');
                break;

            case '18':
                // Kepemilikan ktp
                $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam AND b.ktp_el != '3'";
                $this->select_jml($where);
                $this->db
                    ->select('u.*')
                    ->from('tweb_status_ktp u');
                break;

            default:
                $this->select_jml_penduduk_per_kategori($statistik_penduduk['0']['id_referensi'], $statistik_penduduk['0']['tabel_referensi']);
        }

        return true;
    }

    protected function get_data_jml()
    {
        $lap          = $this->lap;
        $status_dasar = '1';
        //Siapkan data baris rekap
        if ((int) $lap == 18) {
            $this->db->where("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1))");
            $semua = $this->data_jml_semua_penduduk();
        } elseif ($lap == 'kia') {
            $this->db->where("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)<=17)");
            $semua = $this->data_jml_semua_penduduk();
        } elseif (in_array($lap, ['kelas_sosial', 'bantuan_keluarga'])) {
            $semua = $this->data_jml_semua_keluarga();
        } elseif ($lap == 'bdt') {
            $semua = $this->data_jml_semua_rtm();
        } else {
            if ($lap == 'hamil') {
                $this->db->where('b.sex', 2);
            } elseif ($lap == 'buku-nikah') {
                $this->db->where('b.status_kawin !=', 1);
            } elseif ($lap == 'akta-kematian') {
                $status_dasar = '2';
            }
            $semua = $this->data_jml_semua_penduduk($status_dasar);
        }

        return $semua;
    }

    public function list_data($lap = 0, $o = 0)
    {
        $this->lap = $lap;

        $this->load->model('statistik_penduduk_model');
        if ($statistik = $this->statistik_penduduk_model->statistik($lap)) {
            // Statistik yg sudah di-refactor
            $namespace    = $statistik;
            $judul_belum  = $statistik->judul_belum;
            $judul_jumlah = $statistik->judul_jumlah;
        } else {
            $namespace    = $this;
            $judul_jumlah = 'JUMLAH';
            $judul_belum  = 'BELUM MENGISI';
        }

        if ($namespace->select_per_kategori()) {
            $this->order_by($o, $lap);
            $data = $this->db->get()->result_array();
            $this->isi_nomor($data);
        } else {
            $data = [];
        }
        $semua = $namespace->get_data_jml();
        $semua = $this->persentase_semua($semua);
        $total = $namespace->hitung_total($data);

        // Statistik tanpa tabel referensi
        if ($lap === 'bdt') {
            $data = [];
        }

        $data[] = $this->baris_jumlah($total, $judul_jumlah);
        $data[] = $this->baris_belum($semua, $total, $judul_belum);
        $this->hitung_persentase($data, $semua);

        return $data;
    }

    // -------------------- Akhir siapkan data untuk statistik kependudukan -------------------

    public function list_data_rentang()
    {
        return $this->config_id()
            ->where('status', 1)
            ->order_by('dari')
            ->get('tweb_penduduk_umur')
            ->result_array();
    }

    public function get_rentang($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->where('status', 1)
            ->get('tweb_penduduk_umur')
            ->row_array();
    }

    public function get_rentang_terakhir()
    {
        return $this->config_id()
            ->select('case when max(sampai) is null then 0 else (max(sampai)+1) end as dari')
            ->where('status', 1)
            ->get('tweb_penduduk_umur')
            ->row_array();
    }

    public function insert_rentang(): void
    {
        $data           = $this->input->post();
        $data['status'] = 1;
        if ($data['sampai'] != '99999') {
            $data['nama'] = $data['dari'] . ' s/d ' . $data['sampai'] . ' Tahun';
        } else {
            $data['nama'] = 'Di atas ' . $data['dari'] . ' Tahun';
        }
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('tweb_penduduk_umur', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_rentang($id = 0): void
    {
        $data = $this->input->post();
        if ($data['sampai'] != '99999') {
            $data['nama'] = $data['dari'] . ' s/d ' . $data['sampai'] . ' Tahun';
        } else {
            $data['nama'] = 'Di atas ' . $data['dari'] . ' Tahun';
        }
        $outp = $this->config_id()->where('id', $id)->update('tweb_penduduk_umur', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_rentang($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('tweb_penduduk_umur');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all_rentang(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete_rentang($id, $semua = true);
        }
    }

    public function filter(): void
    {
        $this->filter_status();
        $this->filter_tahun();
        $this->filter_wilayah();
    }

    public function filter_status(): void
    {
        $status = (string) $this->session->status;
        if ($status !== '') {
            $this->db->where('u.status', $status);
        }
    }

    public function filter_tahun(): void
    {
        $tahun = $this->session->tahun;
        if ($tahun != '') {
            $this->db
                ->group_start()
                ->where('YEAR(u.sdate) <=', $tahun)
                ->where('YEAR(u.edate) >=', $tahun)
                ->group_end();
        }
    }

    public function filter_wilayah(): void
    {
        $dusun = $this->session->dusun;
        $rw    = $this->session->rw;
        $rt    = $this->session->rt;

        if ($dusun) {
            $this->db->group_start();
            $this->db->where('a.dusun', $dusun);
            if ($rw) {
                $this->db->where('a.rw', $rw);
                if ($rt) {
                    $this->db->where('a.rt', $rt);
                }
            }
            $this->db->group_end();
        }
    }
}
