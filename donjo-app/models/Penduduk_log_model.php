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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Penduduk_log_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('penduduk_model');
    }

    /**
     * Ambil data log penduduk
     *
     * @param $id_log id log penduduk
     *
     * @return array(data log)
     */
    public function get_log($id_log)
    {
        $log = $this->config_id('l')
            ->select("s.nama as status, s.id as status_id, date_format(tgl_peristiwa, '%d-%m-%Y') as tgl_peristiwa, kode_peristiwa, ref_pindah, catatan, date_format(tgl_lapor, '%d-%m-%Y') as tgl_lapor, alamat_tujuan, meninggal_di, p.alamat_sebelumnya, p.kelahiran_anak_ke AS anak_ke, l.jam_mati, l.sebab, l.penolong_mati, l.akta_mati")
            ->where('l.id', $id_log)
            ->join('tweb_penduduk p', 'l.id_pend = p.id', 'left')
            ->join('ref_peristiwa s', 's.id = l.kode_peristiwa', 'left')
            ->get('log_penduduk l')
            ->row_array();
        if (empty($log['tgl_peristiwa'])) {
            $log['tgl_peristiwa'] = date('d-m-Y');
        }

        return $log;
    }

    /**
     * Update log penduduk
     *
     * @param $id_log id log penduduk
     *
     * @return void
     */
    public function update($id_log)
    {
        unset($_SESSION['success']);
        $data['catatan'] = htmlentities($this->input->post('catatan'));
        if ($this->input->post('alamat_tujuan')) {
            $data['alamat_tujuan'] = htmlentities($this->input->post('alamat_tujuan'));
        }

        if ($this->input->post('meninggal_di')) {
            $data['meninggal_di'] = htmlentities($this->input->post('meninggal_di'));
        }

        if ($this->input->post('jam_mati')) {
            $data['jam_mati'] = htmlentities($this->input->post('jam_mati'));
        }

        if ($this->input->post('sebab')) {
            $data['sebab'] = (int) $this->input->post('sebab');
        }

        if ($this->input->post('penolong_mati')) {
            $data['penolong_mati'] = (int) $this->input->post('penolong_mati');
        }

        if ($this->input->post('akta_mati')) {
            $data['akta_mati'] = $this->input->post('akta_mati');
        }

        $penduduk = [];
        if ($this->input->post('anak_ke')) {
            $penduduk['kelahiran_anak_ke'] = (int) $this->input->post('anak_ke');
        }

        if ($this->input->post('alamat_sebelumnya')) {
            $penduduk['alamat_sebelumnya'] = htmlentities($this->input->post('alamat_sebelumnya'));
        }

        if ($penduduk) {
            $get_pendudukId = $this->config_id()->where('id', $id_log)->get('log_penduduk')->row()->id_pend;
            $this->config_id()->where('id', $get_pendudukId)->update('tweb_penduduk', $penduduk);
        }
        $data['tgl_peristiwa'] = rev_tgl($this->input->post('tgl_peristiwa'));
        $data['tgl_lapor']     = rev_tgl($this->input->post('tgl_lapor'), null);
        $data['updated_at']    = date('Y-m-d H:i:s');
        $data['updated_by']    = $this->session->user;

        $outp = $this->config_id()->where('id', $id_log)->update('log_penduduk', $data);
        status_sukses($outp);
    }

    /**
     * Kembalikan status dasar penduduk ke hidup
     *
     * @param $id_log id log penduduk
     *
     * @return void
     */
    public function kembalikan_status($id_log)
    {
        $log = LogPenduduk::findOrFail($id_log);

        // Kembalikan status selain lahir dan masuk
        if (! in_array($log->kode_peristiwa, [LogPenduduk::BARU_LAHIR, LogPenduduk::BARU_PINDAH_MASUK])) {
            $outp = Penduduk::where('id', $log->id_pend)
                ->update([
                    'status_dasar' => StatusDasarEnum::HIDUP,
                ]);

            // Hapus log_keluarga, jika terkait
            $logKeluarga = LogKeluarga::where('id_log_penduduk', $log->id)->first();
            if ($logKeluarga) {
                $outp = $outp && $logKeluarga->delete();
            }

            // Hapus log penduduk
            $outp = $outp && LogPenduduk::find($id_log)->delete();

            return status_sukses($outp);
        }

        return session_error(', tidak dapat mengubah status dasar.');
    }

    /**
     * Kembalikan status dasar penduduk dari PERGI ke HIDUP
     *
     * @param $id_log id log penduduk
     *
     * @return void
     */
    public function kembalikan_status_pergi($id_log)
    {
        $log = LogPenduduk::findOrFail($id_log);

        // Cek tgl lapor
        // tampilkan hanya jika beda tanggal lapor
        $tgl_lapor    = Carbon::parse($log['tgl_lapor'])->format('m-Y');
        $tgl_sekarang = Carbon::now()->format('m-Y');
        if ($tgl_lapor >= $tgl_sekarang) {
            session_error('Tidak dapat mengubah status dasar penduduk, karena tanggal lapor masih sama dengan tanggal sekarang.');

            return;
        }

        // Kembalikan status_dasar hanya jika penduduk pindah keluar (3) atau tidak tetap pergi (6)
        if (in_array($log->kode_peristiwa, [LogPenduduk::PINDAH_KELUAR, LogPenduduk::TIDAK_TETAP_PERGI])) {
            $outp = Penduduk::find($log->id_pend)
                ->updated([
                    'status_dasar' => StatusDasarEnum::HIDUP,
                ]);

            if (! $outp) {
                $this->session->success = -1;
            }

            // Log Penduduk
            $logPenduduk = [
                'tgl_peristiwa'            => rev_tgl($this->input->post('tgl_peristiwa')),
                'kode_peristiwa'           => LogPenduduk::BARU_PINDAH_MASUK,
                'tgl_lapor'                => rev_tgl($this->input->post('tgl_lapor'), null),
                'id_pend'                  => $log->id_pend,
                'created_by'               => auth()->id,
                'maksud_tujuan_kedatangan' => $this->input->post('maksud_tujuan'),
                'config_id'                => $this->config_id,
            ];

            $sql = $this->db->insert_string('log_penduduk', $logPenduduk) . duplicate_key_update_str($logPenduduk);
            $this->db->query($sql);

            // Log Keluarga jika kepala keluarga
            $penduduk = Penduduk::select(['id', 'id_kk', 'kk_level'])->find($log->id_pend);
            if ($penduduk->kk_level == SHDKEnum::KEPALA_KELUARGA) {
                $logKeluarga = [
                    'id_kk'         => $penduduk->id_kk,
                    'id_peristiwa'  => LogKeluarga::KELUARGA_BARU_DATANG,
                    'tgl_peristiwa' => rev_tgl($this->input->post('tgl_lapor'), null),
                    'updated_by'    => auth()->id,
                    'config_id'     => $this->config_id,
                ];

                $sql = $this->db->insert_string('log_keluarga', $logKeluarga) . duplicate_key_update_str($logKeluarga);
                $this->db->query($sql);
            }

            session_success();
        }
    }

    /**
     * Kembalikan status dasar sekumpulan penduduk ke hidup
     *
     * @return void
     */
    public function kembalikan_status_all()
    {
        unset($_SESSION['success']);
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->kembalikan_status($id);
        }
    }

    private function search_sql()
    {
        if ($kw = $this->session->cari) {
            $this->db
                ->group_start()
                ->or_like('u.nama', $kw, 'both', false)
                ->or_like('u.nik', $kw, 'both', false)
                ->group_end();
        }
    }

    private function sex_sql()
    {
        if ($kf = $this->session->sex) {
            $this->db->where('u.sex', $kf);
        }
    }

    private function agama_sql()
    {
        if ($kf = $this->session->agama) {
            $this->db->where('u.agama_id', $kf);
        }
    }

    private function dusun_sql()
    {
        if ($kf = $this->session->dusun) {
            $this->db->where('a.dusun', $kf);
        }
    }

    private function rw_sql()
    {
        if ($kf = $this->session->rw) {
            $this->db->where('a.rw', $kf);
        }
    }

    private function rt_sql()
    {
        if ($kf = $this->session->rt) {
            $this->db->where('a.rt', $kf);
        }
    }

    private function kode_peristiwa()
    {
        if ($kf = $this->session->kode_peristiwa) {
            $this->db->where_in('log.kode_peristiwa', $kf);
        }
    }

    private function status_penduduk()
    {
        if ($kf = $this->session->status_penduduk) {
            $this->db->where('u.status', $kf);
        }
    }

    private function tahun_bulan()
    {
        $kt = $this->session->filter_tahun;
        $kb = $this->session->filter_bulan;

        if ($kt) {
            $this->db->where('YEAR(log.tgl_lapor)', $kt);
        }
        if ($kb) {
            $this->db->where('MONTH(log.tgl_lapor)', $kb);
        }
    }

    private function tgl_lengkap()
    {
        if ($kf = $this->session->tgl_lengkap) {
            $this->db->where('log.tgl_lapor >=', $kf);
        }
    }

    // Menampilkan list tahun dari tabel log_penduduk,
    // Mengambil tahun terkecil dari database, kemudian ditambahkan sampai tahun skrg
    public function list_tahun()
    {
        $list_tahun = [];

        $list_tahun = $this->config_id()
            ->select('MIN(YEAR(tgl_lapor)) as tahun')
            ->from('log_penduduk')
            ->order_by('tahun DESC')
            ->limit(5)
            ->get()->row()->tahun;

        $data_tahun = [];

        for ($nYear = date('Y'); $nYear >= (int) $list_tahun; $nYear--) {
            $data_tahun[]['tahun'] = $nYear;
        }

        return $data_tahun;
    }

    public function paging($p = 1)
    {
        $this->db->select('COUNT(log.id) AS jml');
        $this->list_data_sql();
        $jml_data = $this->db->get()->row()->jml;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_data_hapus()
    {
        $this->config_id('log')
            ->select('log.tgl_peristiwa, log.tgl_lapor, h.nik, h.deleted_at')
            ->from('log_penduduk log')
            ->join('tweb_penduduk u', 'u.id = log.id_pend', 'left')
            ->join('log_hapus_penduduk h', 'h.id_pend = log.id_pend', 'left')
            ->where('u.created_at IS NULL')
            ->where('h.deleted_at > log.created_at');

        $this->tgl_lengkap();
        $this->tahun_bulan();

        $data['list_hapus'] = $this->db->get()->result_array();
        $data['total']      = count($data['list_hapus']);

        return $data;
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        $this->config_id('log')
            ->from('log_penduduk log')
            ->join('tweb_penduduk u', 'u.id = log.id_pend', 'left')
            ->join('log_hapus_penduduk h', 'h.id_pend = log.id_pend', 'left')
            ->join('tweb_keluarga d', 'u.id_kk = d.id', 'left')
            ->join('tweb_wil_clusterdesa a', 'd.id_cluster = a.id', 'left')
            ->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
            ->join('tweb_penduduk_agama g', 'u.agama_id = g.id', 'left')
            ->join('tweb_penduduk_warganegara v', 'v.id = u.warganegara_id', 'left')
            ->join('ref_pindah rp', 'rp.id = log.ref_pindah', 'left')
            ->join('ref_peristiwa ra', 'ra.id = log.kode_peristiwa', 'left');

        $this->kode_peristiwa();
        $this->search_sql();
        $this->sex_sql();
        $this->agama_sql();
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();
        $this->status_penduduk();
        $this->tahun_bulan();
    }

    // $limit = 0 mengambil semua
    public function list_data($o = 0, $offset = 0, $limit = 0)
    {
        //Main Query
        $this->db
            ->select('u.id, u.nik, u.sex as id_sex, u.tempatlahir, u.tanggallahir, u.id_kk, u.nama, u.foto, u.status_dasar, a.dusun, a.rw, a.rt, d.alamat, log.id as id_log, log.no_kk AS no_kk, log.catatan as catatan, log.nama_kk as nama_kk, v.nama AS warganegara, u.created_at, log.meninggal_di, u.alamat_sebelumnya, log.alamat_tujuan,')
            ->select('(CASE when log.kode_peristiwa = 3 then rp.nama else ra.nama end) as nama_peristiwa')
            ->select("(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(log.tgl_peristiwa)-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur_pada_peristiwa")
            ->select('x.nama AS sex, g.nama AS agama, log.tgl_lapor, log.tgl_peristiwa, log.kode_peristiwa, h.nik as nik_hapus');

        $this->list_data_sql();

        switch ($o) {
            case 1:
                $this->db->order_by('u.nik', 'ASC');
                break;

            case 2:
                $this->db->order_by('u.nik', 'DESC');
                break;

            case 3:
                $this->db->order_by('u.nama', 'ASC');
                break;

            case 4:
                $this->db->order_by('u.nama', 'DESC');
                break;

            case 5:
                $this->db->order_by('d.no_kk', 'ASC');
                break;

            case 6:
                $this->db->order_by('d.no_kk', 'DESC');
                break;

            case 7:
                $this->db->order_by('umur_pada_peristiwa', 'ASC');
                break;

            case 8:
                $this->db->order_by('umur_pada_peristiwa', 'DESC');
                break;

                // Untuk Log Penduduk
            case 9:
                $this->db->order_by('log.tgl_peristiwa', 'ASC');
                break;

            case 10:
                $this->db->order_by('log.tgl_peristiwa', 'DESC');
                break;

            default:
                $this->db->order_by('log.tgl_lapor', 'DESC');
                break;
        }

        //Paging SQL
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $data = $this->db->get()->result_array();

        //Formating Output
        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            // Ubah alamat penduduk lepas
            if (! $data[$i]['id_kk'] || $data[$i]['id_kk'] == 0) {
                // Ambil alamat penduduk
                $query = $this->db->select('p.id_cluster, p.alamat_sekarang, c.dusun, c.rw, c.rt')
                    ->from('tweb_penduduk p')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
                    ->where('p.id', $data[$i]['id']);
                $penduduk           = $query->get()->row_array();
                $data[$i]['alamat'] = $penduduk['alamat_sekarang'];
                $data[$i]['dusun']  = $penduduk['dusun'];
                $data[$i]['rw']     = $penduduk['rw'];
                $data[$i]['rt']     = $penduduk['rt'];
            }

            // Ambil Log Pergi Terakhir Penduduk
            $log_pergi_terakhir = LogPenduduk::select('id')
                ->where('id_pend', $data[$i]['id'])
                ->whereIn('kode_peristiwa', [LogPenduduk::PINDAH_KELUAR, LogPenduduk::TIDAK_TETAP_PERGI])
                ->orderBy('id', 'desc')
                ->first();

            $data[$i]['is_log_pergi_terakhir'] = ($log_pergi_terakhir->id == $data[$i]['id_log']);
            $data[$i]['no']                    = $j + 1;

            // tampilkan hanya jika beda tanggal lapor
            $tgl_lapor                  = Carbon::parse($data[$i]['tgl_lapor'])->format('m-Y');
            $tgl_sekarang               = Carbon::now()->format('m-Y');
            $data[$i]['kembali_datang'] = $tgl_lapor >= $tgl_sekarang ? false : true;
            $j++;
        }

        return $data;
    }

    public function tahun_log_pertama()
    {
        return $this->config_id()
            ->select('min(date_format(tgl_lapor, "%Y")) as thn')
            ->from('log_penduduk')
            ->where('DAYNAME(tgl_lapor) IS NOT NULL')
            ->get()->row()->thn;
    }

    public function get_log_penduduk($id_penduduk, $status_dasar = null)
    {
        if ($status_dasar !== null) {
            $this->db->where('kode_peristiwa', $status_dasar);
        }

        return $this->config_id('l')
            ->select("s.nama as status, s.id as status_id, date_format(tgl_peristiwa, '%d-%m-%Y') as tgl_peristiwa, kode_peristiwa, ref_pindah, catatan, date_format(tgl_lapor, '%d-%m-%Y') as tgl_lapor, alamat_tujuan, meninggal_di, p.alamat_sebelumnya, p.kelahiran_anak_ke AS anak_ke, l.jam_mati, l.sebab, l.penolong_mati, l.akta_mati")
            ->where('p.id', $id_penduduk)
            ->join('tweb_penduduk p', 'l.id_pend = p.id', 'left')
            ->join('ref_peristiwa s', 's.id = l.kode_peristiwa', 'left')
            ->order_by('l.id', 'desc')
            ->get('log_penduduk l')
            ->row_array();
    }
}
