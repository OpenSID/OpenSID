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

use App\Enums\SHDKEnum;
use App\Models\LogKeluarga;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluarga_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['program_bantuan_model', 'penduduk_model', 'web_dokumen_model']);
    }

    public function autocomplete($cari = '')
    {
        if ($cari) {
            $this->db->like('t.nama', $cari);
        }
        $data = $this->config_id('u')
            ->select('t.nama')
            ->distinct()
            ->from('tweb_keluarga u')
            ->join('tweb_penduduk t', 'u.nik_kepala = t.id', 'left')
            ->order_by('t.nama')
            ->get()
            ->result_array();

        return autocomplete_data_ke_str($data);
    }

    /*
        1 - tampilkan keluarga di mana KK mempunyai status dasar 'hidup'
        2 - tampilkan keluarga di mana KK mempunyai status dasar 'hilang/pindah/mati'
        3 - tampilkan keluarga di mana KK tidak ada
        4 - tampilkan keluarga dengan nomor KK sementara
    */
    private function status_dasar_sql(): void
    {
        if (empty($value = $this->session->status_dasar)) {
            return;
        }

        if ($value == '1') {
            $this->db
                ->where('t.status_dasar', 1)
                ->where('t.kk_level', SHDKEnum::KEPALA_KELUARGA);
        } elseif ($value == '2') {
            $this->db->where('t.status_dasar <>', 1);
        } elseif ($value == '3') {
            $this->db
                ->group_start()
                ->where('t.status_dasar IS NULL')
                ->or_where(' t.kk_level <>', SHDKEnum::KEPALA_KELUARGA)
                ->group_end();
        } elseif ($value == '4') {
            $this->db
                ->like('u.no_kk', '0', 'after');
        }
    }

    private function search_sql(): void
    {
        if (empty($value = $this->session->cari)) {
            return;
        }

        $this->db
            ->group_start()
            ->like('t.nama', $value)
            ->or_like('u.no_kk ', $value)
            ->or_like('t.tag_id_card', $value)
            ->group_end();
    }

    private function kumpulan_kk_sql(): void
    {
        if (empty($this->session->kumpulan_kk)) {
            return;
        }

        $kumpulan_kk = preg_replace('/[^0-9\,]/', '', $this->session->kumpulan_kk);
        if (! is_array($kumpulan_kk)) {
            $kumpulan_kk                = explode(',', $kumpulan_kk);
            $this->session->kumpulan_kk = $kumpulan_kk;
        }
        $this->db->where_in('u.no_kk ', $kumpulan_kk);
    }

    private function filter_bantuan(): void
    {
        $status = (string) $this->session->filter_global['status'];
        if ($status != '') {
            $this->db->where('rcb.status', $status);
        }

        $tahun = $this->session->filter_global['tahun'];
        if ($tahun != '') {
            $this->db
                ->group_start()
                ->where('YEAR(rcb.sdate) <=', $tahun)
                ->where('YEAR(rcb.edate) >=', $tahun)
                ->group_end();
        }
    }

    private function bantuan_keluarga_sql(): void
    {
        // Yg berikut hanya untuk menampilkan peserta bantuan
        $bantuan_keluarga = $this->session->bantuan_keluarga;
        if (! in_array($bantuan_keluarga, [JUMLAH, BELUM_MENGISI, TOTAL])) {
            // Salin program_id
            $this->session->program_bantuan = $bantuan_keluarga;
        }
        if ($bantuan_keluarga && $bantuan_keluarga != BELUM_MENGISI && ($bantuan_keluarga != JUMLAH && $this->session->program_bantuan)) {
            $this->db
                ->join('program_peserta bt', 'bt.peserta = u.no_kk')
                ->join('program rcb', 'bt.program_id = rcb.id', 'left');
            $this->filter_bantuan();
        }
        // Untuk BUKAN PESERTA program bantuan tertentu
        if ($bantuan_keluarga == BELUM_MENGISI) {
            if ($this->session->program_bantuan) {
                // Program bantuan tertentu
                $program_id = $this->session->program_bantuan;
                $this->db
                    ->join('program_peserta bt', "bt.peserta = u.no_kk and bt.program_id = {$program_id}", 'left')
                    ->where('bt.id is null');
            } else {
                if (isset($this->session->status)) {
                    $status = $this->session->status;
                    $this->db->join('program_peserta bt', "bt.peserta = u.no_kk AND bt.program_id in (SELECT pro.id from program AS pro WHERE pro.`status` = {$status} and pro.sasaran = 2)", 'left');
                } else {
                    $this->db->join('program_peserta bt', 'bt.peserta = u.no_kk', 'left');
                }

                // Bukan penerima bantuan apa pun
                $this->db->where('bt.id is null');
            }
        } elseif ($bantuan_keluarga == JUMLAH && ! $this->session->program_bantuan) {
            if (isset($this->session->status)) {
                $status = $this->session->status;
                $this->db->join('program_peserta bt', "bt.peserta = u.no_kk AND bt.program_id in (SELECT pro.id from program AS pro WHERE pro.`status` = {$status} and pro.sasaran = 2)", 'left')->where('bt.id is not null');
            } else {
                // Penerima bantuan mana pun
                $this->db->where('u.no_kk IN (select peserta from program_peserta)');
            }
        }
    }

    private function filter_id(): void
    {
        if ($id = $this->input->get('id_cb')) {
            $this->db->where_in('u.id', explode(',', $id));
        }
    }

    private function list_data_sql(): void
    {
        $this->config_id('u')
            ->from('tweb_keluarga u')
            ->join('tweb_penduduk t', 'u.nik_kepala = t.id', 'left')
            ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');

        if ($this->session->bantuan_keluarga) {
            $this->bantuan_keluarga_sql();
        }

        $this->filter_id();
        $this->search_sql();
        $this->kumpulan_kk_sql();
        $this->status_dasar_sql();

        $kolom_kode = [
            ['dusun', 'c.dusun'],
            ['rw', 'c.rw'],
            ['rt', 'c.rt'],
            ['sex', 't.sex'],
            ['kelas', 'u.kelas_sosial'],
            ['id_bos', 'id_bos'],
        ];

        if ($this->session->bantuan_keluarga && $this->session->bantuan_keluarga != BELUM_MENGISI && ($this->session->bantuan_keluarga != JUMLAH && $this->session->program_bantuan)) {
            $kolom_kode[] = ['bantuan_keluarga', 'rcb.id'];
        }

        foreach ($kolom_kode as $kolom) {
            $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
        }
    }

    protected function get_sql_kolom_kode($session, $kolom)
    {
        if (empty($kf = $this->session->{$session})) {
            return;
        }

        if ($kf == JUMLAH) {
            $this->db
                ->group_start()
                ->where("{$kolom} IS NOT NULL")
                ->or_where("{$kolom} <>", '')
                ->group_end();
        } elseif ($kf == BELUM_MENGISI) {
            $this->db
                ->group_start()
                ->where("{$kolom} IS NULL")
                ->or_where($kolom, '')
                ->group_end();
        } else {
            $this->db->where($kolom, $kf);
        }
    }

    // $page = -1 mengambil semua
    public function list_data($o = 0, $page = 1)
    {
        $this->db
            ->distinct()
            ->select('u.*, t.nama AS kepala_kk, t.nik, t.tag_id_card, t.sex, t.sex as id_sex, t.status_dasar, t.foto, t.id as id_pend, c.dusun, c.rw, c.rt')
            ->select('(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id AND status_dasar = 1) AS jumlah_anggota');
        $this->list_data_sql();

        $this->db->order_by("CASE
            WHEN CHAR_LENGTH(u.no_kk) < 16 THEN 1
            WHEN u.no_kk LIKE '0%' AND CHAR_LENGTH(u.no_kk) = 16 THEN 2
            ELSE 3
        END");

        switch ($o) {
            case 1:
                $this->db->order_by('u.no_kk');
                break;

            case 2:
            default:
                $this->db->order_by('u.no_kk DESC');
                break;

            case 3:
                $this->db->order_by('kepala_kk');
                break;

            case 4:
                $this->db->order_by('kepala_kk DESC');
                break;

            case 5:
                $this->db->order_by('u.tgl_daftar');
                break;

            case 6:
                $this->db->order_by('u.tgl_daftar DESC');
                break;

            case 7:
                $this->db->order_by('u.tgl_cetak_kk');
                break;

            case 8:
                $this->db->order_by('u.tgl_cetak_kk DESC');
                break;

            case 9:
                $this->db->order_by('jumlah_anggota');
                break;

            case 10:
                $this->db->order_by('jumlah_anggota DESC');
                break;
        }
        $query_dasar = $this->db->get_compiled_select();

        /** Lakukan pencarian jumlah anggota setelah data diperoleh supaya lebih cepat */
        $this->db->from('(' . $query_dasar . ') u');

        if (! $this->input->get('id_cb') && $this->session->per_page > 0) {
            $jumlah_pilahan = $this->db->count_all_results('', false);
            $paging         = $this->paginasi($page, $jumlah_pilahan);
            $this->db->limit($paging->per_page, $paging->offset);
        }
        $data = $this->db->get()->result_array();
        //Formating Output
        $j       = $paging->offset ?: 0;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            if ($data[$i]['jumlah_anggota'] == 0) {
                $data[$i]['jumlah_anggota'] = '-';
            }

            switch ($data[$i]['sex']) {
                case 1:
                    $data[$i]['sex'] = 'LAKI-LAKI';
                    break;

                case 2:
                    $data[$i]['sex'] = 'PEREMPUAN';
                    break;

                default:
                    $data[$i]['sex'] = '-';
                    break;
            }

            $data[$i]['boleh_hapus'] = $this->cek_boleh_hapus($data[$i]['id']);
            $j++;
        }

        if ($page > 0) {
            return ['paging' => $paging, 'main' => $data];
        }

        return $data;
    }

    // Tambah keluarga baru dari penduduk lepas (status tetap atau pendatang)
    public function insert(): void
    {
        $this->session->unset_userdata('error_msg');
        $data = $_POST;

        if (! $this->validasi_data_keluarga($data)) {
            return;
        }

        $pend = $this->config_id()->select('alamat_sekarang, id_cluster')->where('id', $data['nik_kepala'])->get('tweb_penduduk')->row_array();

        // Gunakan alamat penduduk sebagai alamat keluarga
        $data['alamat']     = $pend['alamat_sekarang'];
        $data['id_cluster'] = $pend['id_cluster'];
        $data['updated_by'] = $this->session->user;
        $data['config_id']  = $this->config_id;

        $outp  = $this->db->insert('tweb_keluarga', $data);
        $kk_id = $this->db->insert_id();

        $default['id_kk']      = $kk_id;
        $default['kk_level']   = SHDKEnum::KEPALA_KELUARGA;
        $default['status']     = 1; // statusnya menjadi tetap
        $default['updated_at'] = date('Y-m-d H:i:s');
        $default['updated_by'] = $this->session->user;
        $default['config_id']  = $this->config_id;
        $outp                  = $outp && $this->config_id()->where('id', $data['nik_kepala'])->update('tweb_penduduk', $default);

        $log['id_pend']    = $data['nik_kepala'];
        $log['id_cluster'] = $pend['id_cluster'];
        $log['tanggal']    = date('Y-m-d H:i:s');
        $log['config_id']  = $this->config_id;
        $outp              = $outp && $this->db->insert('log_perubahan_penduduk', $log);

        // Untuk statistik perkembangan keluarga
        $this->log_keluarga($kk_id, LogKeluarga::KELUARGA_BARU);

        status_sukses($outp); //Tampilkan Pesan
    }

    private function validasi_data_keluarga(&$data)
    {
        // Sterilkan data
        $data['alamat'] = strip_tags($data['alamat']);

        if (! empty($data['id'])) {
            $nokk_lama = $this->get_nokk($data['id']);
            if ($data['no_kk'] == $nokk_lama) {
                return true;
            } // Tidak berubah
        }
        $valid = [];
        if (isset($data['no_kk'])) {
            if (! ctype_digit($data['no_kk'])) {
                $valid[] = 'Nomor KK hanya berisi angka';
            }
            if (strlen($data['no_kk']) != 16 && $data['no_kk'] != '0') {
                $valid[] = 'Nomor KK panjangnya harus 16 atau 0';
            }
            if ($this->config_id()->select('no_kk')->from('tweb_keluarga')->where(['no_kk' => $data['no_kk']])->limit(1)->get()->row()->no_kk) {
                $valid[] = "Nomor KK {$data['no_kk']} sudah digunakan";
            }
        }

        if ($valid !== []) {
            $_SESSION['validation_error'] = true;

            foreach ($valid as $error) {
                $_SESSION['error_msg'] .= ': ' . $error . '\n';
            }
            $_SESSION['post']    = $_POST;
            $_SESSION['success'] = -1;

            return false;
        }

        return true;
    }

    // Tambah KK dengan mengisi form penduduk kepala keluarga baru pindah datang
    public function insert_new(): void
    {
        unset($_SESSION['validation_error'], $_SESSION['success'], $_SESSION['error_msg']);

        $data = $_POST;

        if (! $this->validasi_data_keluarga($data)) {
            return;
        }

        $error_validasi = $this->penduduk_model->validasi_data_penduduk($data);
        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $_SESSION['error_msg'] .= ': ' . $error . '\n';
            }
            $_SESSION['post']    = $_POST;
            $_SESSION['success'] = -1;

            return;
        }

        $lokasi_file = $_FILES['foto']['tmp_name'];
        $tipe_file   = $_FILES['foto']['type'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file);      // normalkan nama file
        $old_foto    = '';
        if (! empty($lokasi_file)) {
            if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/pjpeg' && $tipe_file != 'image/png') {
                unset($data['foto']);
            } else {
                UploadFoto($nama_file, $old_foto);
                $data['foto'] = $nama_file;
            }
        } else {
            unset($data['foto']);
        }

        unset($data['file_foto'], $data['old_foto'], $data['nik_lama'], $data['dusun'], $data['rw'], $data['no_kk']);

        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        unset($data['maksud_tujuan_kedatangan']);

        $tgl_lapor = rev_tgl($_POST['tgl_lapor'], null);
        if ($_POST['tgl_peristiwa']) {
            $tgl_peristiwa = rev_tgl($_POST['tgl_peristiwa'], null);
        } else {
            $tgl_peristiwa = rev_tgl($_POST['tanggallahir'], null);
        }
        unset($data['tgl_lapor'], $data['tgl_peristiwa']);

        // Simpan alamat keluarga sebelum menulis penduduk
        $data2['alamat'] = $data['alamat'];
        unset($data['alamat']);

        // Tulis penduduk baru sebagai kepala keluarga
        $data['kk_level']   = SHDKEnum::KEPALA_KELUARGA;
        $data['created_by'] = $this->session->user;
        $data['config_id']  = $this->config_id;
        $outp               = $this->db->insert('tweb_penduduk', $data);
        $id_pend            = $this->db->insert_id();
        status_sukses($outp); //Tampilkan Pesan

        // Tulis keluarga baru
        $data2['nik_kepala'] = $id_pend;
        $data2['no_kk']      = $_POST['no_kk'];
        $data2['id_cluster'] = $data['id_cluster'];
        $data2['updated_by'] = $this->session->user;
        $data2['config_id']  = $this->config_id;
        $outp                = $this->db->insert('tweb_keluarga', $data2);
        $kk_id               = $this->db->insert_id();

        // Update penduduk kaitkan dengan KK
        $default['updated_at'] = date('Y-m-d H:i:s');
        $default['updated_by'] = $this->session->user;
        $default['id_kk']      = $kk_id;
        $this->config_id()->where('id', $id_pend)->update('tweb_penduduk', $default);

        // Jenis peristiwa didapat dari form yang berbeda
        // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
        $x = [
            'tgl_peristiwa'            => $tgl_peristiwa,
            'kode_peristiwa'           => $this->session->jenis_peristiwa,
            'tgl_lapor'                => $tgl_lapor,
            'id_pend'                  => $id_pend,
            'created_by'               => $this->session->user,
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
            'config_id'                => $this->config_id,
        ];
        $this->penduduk_model->tulis_log_penduduk_data($x);

        $log['id_pend']    = 1;
        $log['id_cluster'] = 1;
        $log['tanggal']    = date('Y-m-d H:i:s');
        $log['config_id']  = $this->config_id;
        $outp              = $this->db->insert('log_perubahan_penduduk', $log);

        // Untuk statistik perkembangan keluarga
        $this->log_keluarga($kk_id, LogKeluarga::KELUARGA_BARU_DATANG);

        status_sukses($outp); //Tampilkan Pesan
    }

    /* Keluarga tidak boleh dihapus, jika:
        (1) masih ada anggota keluarga
        (2) kepala keluarga sebelumnya mati/pindah/hilang
        (3) digunakan di bantuan, data suplemen atau analisis
    */
    public function cek_boleh_hapus($id)
    {
        $jml_anggota = $this->config_id()
            ->from('tweb_penduduk')
            ->where('id_kk', $id)
            ->count_all_results();
        if ($jml_anggota > 0) {
            return false;
        }
        $kepala_keluarga = $this->keluarga_model->get_kepala_a($id);
        if ($kepala_keluarga['id'] && $kepala_keluarga['status_dasar'] != 1) {
            return false;
        }

        $bantuan = $this->config_id('pp')
            ->from('program_peserta pp')
            ->join('tweb_keluarga k', 'k.no_kk = pp.peserta')
            ->join('program p', 'pp.program_id = p.id')
            ->where('p.sasaran', 2)
            ->where('k.id', $id)
            ->count_all_results();
        if ($bantuan > 0) {
            return false;
        }
        $suplemen = $this->config_id()
            ->from('suplemen_terdata')
            ->where('id_terdata', $id)
            ->where('sasaran', 2)
            ->count_all_results();
        if ($suplemen > 0) {
            return false;
        }
        $analisis = $this->db
            ->from('analisis_respon r')
            ->join('analisis_indikator i', 'i.id = r.id_indikator')
            ->join('analisis_master m', 'm.id = i.id_master')
            ->where('r.id_subjek', $id)
            ->where('m.jenis', 2)
            ->count_all_results();

        return $analisis <= 0;
    }

    /* 	Hapus keluarga:
            (1) Untuk setiap anggota keluarga lakukan rem_anggota (pecah kk).
            (2) Hapus keluarga
            $id adalah id tweb_keluarga
    */
    public function delete($id = 0, $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        if (! $this->cek_boleh_hapus($id)) {
            $this->session->success   = -1;
            $this->session->error_msg = "Keluarga ini (id = {$id} ) tidak diperbolehkan dihapus";

            return;
        }

        $keluarga = $this->config_id()->select('*')->where('id', $id)->get('tweb_keluarga')->row();

        $list_anggota = $this->config_id()->select('id')->where('id_kk', $id)->get('tweb_penduduk')->result_array();

        foreach ($list_anggota as $anggota) {
            $this->rem_anggota($id, $anggota['id']);
        }
        $outp = $this->config_id()->where('id', $id)->delete('tweb_keluarga');

        // Hapus peserta program bantuan sasaran keluarga, kalau ada
        $outp = $outp && $this->program_bantuan_model->hapus_peserta_dari_sasaran($keluarga->no_kk, 2);

        // Untuk statistik perkembangan keluarga
        $this->log_keluarga($id, LogKeluarga::KELUARGA_HAPUS);

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function log_keluarga($id = null, $id_peristiwa = 1, $id_pend = null, $id_log_penduduk = null): void
    {
        $log_keluarga = [
            'id_kk'           => $id,
            'id_peristiwa'    => $id_peristiwa,
            'tgl_peristiwa'   => date('Y-m-d H:i:s'),
            'id_pend'         => $id_pend,
            'id_log_penduduk' => $id_log_penduduk,
            'updated_by'      => $this->session->user,
        ];

        $outp = LogKeluarga::create($log_keluarga);

        status_sukses($outp);
    }

    public function add_anggota($id = 0): void
    {
        $data = $this->input->post();
        $this->update_kk_level($data['nik'], $id, $data['kk_level']);

        $temp['id_kk']      = $id;
        $temp['kk_level']   = $data['kk_level'];
        $temp['updated_at'] = date('Y-m-d H:i:s');
        $temp['updated_by'] = $this->session->user;

        $outp = $this->config_id()->where('id', $data['nik'])->update('tweb_penduduk', $temp);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_kk_level($id, $id_kk, $kk_level)
    {
        $outp              = true;
        $nik['updated_by'] = auth()->id;
        if ($kk_level == SHDKEnum::KEPALA_KELUARGA) {
            // Kalau ada penduduk lain yg juga Kepala Keluarga, ubah menjadi hubungan Lainnya
            $lvl['kk_level']   = SHDKEnum::LAINNYA;
            $lvl['updated_at'] = Carbon::now();
            $lvl['updated_by'] = auth()->id;
            $this->config_id()
                ->where('id_kk', $id_kk)
                ->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                ->update('tweb_penduduk', $lvl);

            $nik['nik_kepala'] = $id;
            $outp              = $this->config_id()->where('id', $id_kk)->update('tweb_keluarga', $nik);
        }

        return $outp;
    }

    public function update_anggota($id = 0): void
    {
        $data = $this->input->post();

        $pend = $this->config_id()
            ->select('id_kk')
            ->where('id', $id)
            ->get('tweb_penduduk')
            ->row_array();

        $this->update_kk_level($id, $pend['id_kk'], $data['kk_level']);

        $data['updated_at'] = Carbon::now();
        $data['updated_by'] = auth()->id;
        $outp               = $this->config_id()->where('id', $id)->update('tweb_penduduk', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function rem_anggota($kk = 0, $id = 0): void
    {
        $pend = $this->keluarga_model->get_anggota($id);
        $kel  = $this->config_id('k')
            ->select('id, no_kk, nik_kepala')
            ->where('id', $pend['id_kk'])
            ->from('tweb_keluarga k')
            ->get()
            ->row();

        $temp['no_kk_sebelumnya'] = $kk ? $kel->no_kk : null; // Tidak simpan no kk kalau keluar dari keluarga
        $temp['id_kk']            = null;
        $temp['kk_level']         = null;
        $temp['updated_at']       = date('Y-m-d H:i:s');
        $temp['updated_by']       = $this->session->user;
        $outp                     = $this->config_id()->where('id', $id)->update('tweb_penduduk', $temp);
        if ($pend['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            $temp2['updated_by'] = $this->session->user;
            $temp2['nik_kepala'] = null;
            $outp                = $this->config_id()->where('id', $pend['id_kk'])->update('tweb_keluarga', $temp2);
        }

        // hapus dokumen bersama dengan kepala KK sebelumnya
        $this->web_dokumen_model->hard_delete_dokumen_bersama($id);
        // catat peristiwa keluar/pecah di log_keluarga
        $this->log_keluarga($kel->id, LogKeluarga::ANGGOTA_KELUARGA_PECAH, $id);
    }

    public function rem_all_anggota($kk): void
    {
        $id_cb = $_POST['id_cb'];
        if (count($id_cb) > 0) {
            foreach ($id_cb as $id) {
                $this->rem_anggota($kk, $id);
            }
        }
    }

    public function get_keluarga($id = 0)
    {
        $data = $this->config_id('k')
            ->select('k.*, p.nama, p.nik, p.status_dasar, b.dusun as dusun, b.rw as rw, b.rt as rt')
            ->from('tweb_keluarga k')
            ->join('tweb_penduduk p', 'k.nik_kepala = p.id')
            ->join('tweb_wil_clusterdesa b', 'k.id_cluster = b.id', 'left')
            ->where('k.id', $id)
            ->get()
            ->row_array();

        if ($data) {
            $data['alamat_plus_dusun'] = $data['alamat'];
            $data['tgl_cetak_kk']      = tgl_indo_out($data['tgl_cetak_kk']);
            if (! isset($data['alamat'])) {
                $data['alamat'] = '';
            }
            if (! isset($data['rt'])) {
                $data['rt'] = '';
            }
            if (! isset($data['rw'])) {
                $data['rw'] = '';
            }
            $str_dusun              = (empty($data['dusun']) || $data['dusun'] == '-') ? '' : ikut_case($data['dusun'], $this->setting->sebutan_dusun . ' ' . $data['dusun']);
            $data['alamat_wilayah'] = trim("{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . $str_dusun);
        }

        return $data;
    }

    public function get_data_cetak_kk($id = 0)
    {
        $kk['id_kk']      = $id;
        $kk['main']       = $this->keluarga_model->list_anggota($id);
        $kk['kepala_kk']  = $this->keluarga_model->get_kepala_kk($id);
        $kk['desa']       = identitas();
        $data['all_kk'][] = $kk;

        return $data;
    }

    public function get_data_cetak_kk_all()
    {
        $data  = [];
        $id_cb = $this->input->post('id_cb');
        if (count($id_cb) > 0) {
            foreach ($id_cb as $id) {
                $kk               = $this->get_data_cetak_kk($id);
                $data['all_kk'][] = $kk['all_kk'][0]; //Kumpulkan semua kk
            }
        }

        return $data;
    }

    public function get_anggota($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('tweb_penduduk')
            ->row_array();
    }

    /**
     * List penduduk lepas.
     *
     * @param bool $shdk Pilih berdasarkan kepala keluarga atau bukan.
     *
     * @return array
     */
    public function list_penduduk_lepas($shdk = false)
    {
        $this->config_id('u')
            ->select('u.id, u.nik, u.nama, u.alamat_sekarang as alamat, w.rt, w.rw, w.dusun')
            ->from('penduduk_hidup u')
            ->join('tweb_wil_clusterdesa w', 'u.id_cluster = w.id', 'left')
            ->where('id_kk is null')
            ->where('status', 1);

        if ($shdk) {
            $this->db->group_start()
                ->where('u.kk_level !=', SHDKEnum::KEPALA_KELUARGA)
                ->or_where('u.kk_level is null')
                ->group_end();
        } else {
            $this->db->group_start()
                ->where('u.kk_level', SHDKEnum::KEPALA_KELUARGA)
                ->or_where('u.kk_level is null')
                ->group_end();
        }

        return $this->db->get()->result_array();
    }

    // $options['dengan_kk'] = false jika hanya perlu tanggungan keluarga tanpa kepala keluarga
    // $options['pilih'] untuk membatasi ke nik tertentu saja
    public function list_anggota($id = 0, $options = ['dengan_kk' => true], $nik_sementara = false)
    {
        $this->config_id('u')
            ->select('u.*')
            ->select('u.sex as sex_id')
            ->select('u.status_kawin as status_kawin_id')
            ->select("DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(`tanggallahir`)), '%Y')+0 as umur")
            ->select("
                (CASE
                    WHEN u.status_kawin IS NULL THEN ''
                    WHEN u.status_kawin <> 2 THEN w.nama
                    ELSE
                        CASE
                            WHEN (u.akta_perkawinan IS NULL OR u.akta_perkawinan = '') AND u.tanggalperkawinan IS NULL THEN 'KAWIN BELUM TERCATAT'
                            ELSE 'KAWIN TERCATAT'
                        END
                END) as status_kawin
            ")
            ->select(['b.dusun', 'b.rw', 'b.rt', 'x.nama as sex', 'u.kk_level', 'a.nama as agama', 'd.nama as pendidikan', 'd.id as pendidikan_id', 'j.nama as pekerjaan', 'f.nama as warganegara', 'g.nama as golongan_darah', 'h.nama AS hubungan', 'h.id AS hubungan_id', 'k.alamat', 'tc.nama AS cacat'])
            ->from('tweb_penduduk u')
            ->join('tweb_penduduk_agama a', 'u.agama_id = a.id', 'left')
            ->join('tweb_penduduk_pekerjaan j', 'u.pekerjaan_id = j.id', 'left')
            ->join('tweb_penduduk_pendidikan_kk d', 'u.pendidikan_kk_id = d.id', 'left')
            ->join('tweb_penduduk_warganegara f', 'u.warganegara_id = f.id', 'left')
            ->join('tweb_golongan_darah g', 'u.golongan_darah_id = g.id', 'left')
            ->join('tweb_penduduk_kawin w', 'u.status_kawin = w.id', 'left')
            ->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
            ->join('tweb_cacat tc', 'u.cacat_id = tc.id', 'left')
            ->join('tweb_penduduk_hubungan h', 'u.kk_level = h.id', 'left')
            ->join('tweb_wil_clusterdesa b', 'u.id_cluster = b.id', 'left')
            ->join('tweb_keluarga k', 'u.id_kk = k.id', 'left')
            ->where(['status_dasar' => 1, 'id_kk' => $id]);

        if ($options['dengan_kk'] !== null && ! $options['dengan_kk']) {
            $this->db->where('kk_level !=', SHDKEnum::KEPALA_KELUARGA);
        }

        if (! empty($options['pilih'])) {
            $this->db->where_in('u.nik', $options['pilih']);
        }

        $data = $this->db->order_by('kk_level, tanggallahir')->get()->result_array();

        if ($nik_sementara) {
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['nik'] = get_nik($data[$i]['nik']);
            }
        }

        return $data;
    }

    // $id adalah id_kk : id dari tabel tweb_keluarga, kecuali
    // apabila $is_no_kk == true maka $id adalah no_kk
    public function get_kepala_kk($id, $is_no_kk = false)
    {
        $kolom_id = ($is_no_kk) ? 'no_kk' : 'id';
        // Buat subquery untuk setiap kolom yg diperlukan dari tweb_keluarga
        $list_kk = array_map(function ($a) use ($kolom_id, $id) {
            $this->config_id()
                ->select($a)
                ->from('tweb_keluarga')
                ->where($kolom_id, $id);

            return $this->db->get_compiled_select();
        }, ['no_kk', 'alamat', 'id', 'id_cluster', 'nik_kepala']);

        foreach (['no_kk', 'alamat', 'id_kk', 'id_cluster', 'nik_kepala'] as $key => $a) {
            ${$a} = $list_kk[$key]; // Hasilkan variabel dgn nama dari string
        }

        $this->config_id('u')
            ->select('nik, u.id, u.nama, u.tanggalperkawinan, u.status_kawin as status_kawin_id, u.sex as sex_id, tempatlahir, tanggallahir, u.status_dasar')
            ->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0) AS umur")
            ->select('a.nama as agama, d.nama as pendidikan, j.nama as pekerjaan, x.nama as sex, w.nama as status_kawin')
            ->select('h.nama as hubungan, f.nama as warganegara, warganegara_id, nama_ayah, nama_ibu, g.nama as golongan_darah')
            ->select('c.rt as rt, c.rw as rw, c.dusun as dusun')
            ->select('(' . $no_kk . ') AS no_kk')
            ->select('(' . $alamat . ') AS alamat')
            ->select('(' . $id_kk . ') AS id_kk')
            ->from('tweb_penduduk u')
            ->join('tweb_penduduk_pekerjaan j', 'u.pekerjaan_id = j.id', 'left')
            ->join('tweb_golongan_darah g', 'u.golongan_darah_id = g.id', 'left')
            ->join('tweb_penduduk_pendidikan_kk d', 'u.pendidikan_kk_id = d.id', 'left')
            ->join('tweb_penduduk_warganegara f', 'u.warganegara_id = f.id', 'left')
            ->join('tweb_penduduk_agama a', 'u.agama_id = a.id', 'left')
            ->join('tweb_penduduk_kawin w', 'u.status_kawin = w.id', 'left')
            ->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
            ->join('tweb_penduduk_hubungan h', 'u.kk_level = h.id', 'left')
            ->join('tweb_wil_clusterdesa c', '(' . $id_cluster . ') = c.id', 'left')
            ->where('u.id = (' . $nik_kepala . ')');

        $data = $this->db->get()->row_array();

        // Untuk keluarga kosong
        if (empty($data)) {
            $no_kk = $this->config_id()
                ->select('no_kk')
                ->from('tweb_keluarga')
                ->where($kolom_id, $id)
                ->get()->row()->no_kk;
            $data['no_kk'] = $no_kk;
            $data['id_kk'] = $id;
        }

        if ($data['dusun'] != '-' && $data['dusun'] != '') {
            $data['alamat_plus_dusun'] = trim($data['alamat'] . ' ' . ucwords($this->setting->sebutan_dusun) . ' ' . $data['dusun']);
        } elseif ($data['alamat']) {
            $data['alamat_plus_dusun'] = $data['alamat'];
        }
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data['id_kk']);

        return $data;
    }

    public function get_kepala_a($id)
    {
        return $this->config_id('k')
            ->select('u.*, c.*, u.id as id, k.no_kk, k.alamat, k.id_cluster as id_cluster')
            ->from('tweb_keluarga k')
            ->join('tweb_penduduk u', 'k.nik_kepala = u.id', 'left')
            ->join('tweb_wil_clusterdesa c', 'k.id_cluster = c.id', 'left')
            ->where('k.id', $id)
            ->get()
            ->row_array();
    }

    // TODO: Ganti fuction ini jika sudah tdk lg digunakan di modul lain, gunakan referensi_model
    public function list_hubungan()
    {
        return $this->db
            ->select('*, nama as hubungan')
            ->get('tweb_penduduk_hubungan')
            ->result_array();
    }

    // Tambah anggota keluarga, penduduk baru
    public function insert_a(): void
    {
        unset($_SESSION['validation_error']);
        $_SESSION['success'] = 1;
        unset($_SESSION['error_msg']);

        $data        = $_POST;
        $lokasi_file = $_FILES['foto']['tmp_name'];
        $tipe_file   = $_FILES['foto']['type'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/pjpeg' && $tipe_file != 'image/png') {
                unset($data['foto']);
            } else {
                UploadFoto($nama_file, '');
                $data['foto'] = $nama_file;
            }
        } else {
            unset($data['foto']);
        }

        unset($data['file_foto'], $data['old_foto'], $data['nik_lama']);

        $tgl_lapor     = rev_tgl($_POST['tgl_lapor']);
        $tgl_peristiwa = $_POST['tgl_peristiwa'] ? rev_tgl($_POST['tgl_peristiwa']) : rev_tgl($_POST['tanggallahir']);
        unset($data['tgl_lapor'], $data['tgl_peristiwa']);

        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        unset($data['maksud_tujuan_kedatangan']);

        if ($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            $tambah_kk = true;
            $kel       = $this->get_raw_keluarga($data['id_kk']);
            if ($kel['nik_kepala']) {
                $_SESSION['success'] = -1;
                $_SESSION['error_msg'] .= 'Tidak bisa tambah kepala keluarga';

                return;
            }
        }

        if (! $this->validasi_data_keluarga($data)) {
            return;
        }
        unset($data['alamat'], $data['no_kk'], $data['id']);

        $error_validasi = $this->penduduk_model->validasi_data_penduduk($data);
        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $_SESSION['error_msg'] .= ': ' . $error . '\n';
            }
            $_SESSION['post']    = $_POST;
            $_SESSION['success'] = -1;

            return;
        }

        $this->db->trans_begin();

        try {
            $data['created_by'] = $this->session->user;
            $data['config_id']  = $this->config_id;
            $outp               = $this->db->insert('tweb_penduduk', $data);

            if (! $outp) {
                $_SESSION = -1;
            }

            $id_pend = $this->db->insert_id();

            if ($foto = upload_foto_penduduk(time() . '-' . $id_pend . '-' . random_int(10000, 999999))) {
                $this->config_id()->where('id', $id_pend)->update('tweb_penduduk', ['foto' => $foto]);
            }

            // Jika anggota yang ditambah adalah kepala keluarga untuk kk kosong
            $this->config_id()
                ->set('nik_kepala', $id_pend)
                ->set('updated_by', $this->session->user)
                ->where('id', $kel['id'])
                ->update('tweb_keluarga');

            // Jenis peristiwa didapat dari form yang berbeda
            // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
            $x = [
                'tgl_peristiwa'            => $tgl_peristiwa,
                'kode_peristiwa'           => $this->session->jenis_peristiwa,
                'tgl_lapor'                => $tgl_lapor,
                'id_pend'                  => $id_pend,
                'created_by'               => $this->session->user,
                'maksud_tujuan_kedatangan' => $maksud_tujuan,
                'config_id'                => $this->config_id,
            ];

            $this->penduduk_model->tulis_log_penduduk_data($x);
            $this->db->trans_commit();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            $this->db->trans_rollback();
        }
    }

    public function get_raw_keluarga($id)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('tweb_keluarga')
            ->row_array();
    }

    public function get_nokk($id)
    {
        return $this->config_id()
            ->select('no_kk')
            ->where('id', $id)
            ->get('tweb_keluarga')
            ->row_array()['no_kk'];
    }

    public function update_nokk($id = 0): void
    {
        unset($_SESSION['error_msg']);
        $data = $_POST;

        if (! $this->validasi_data_keluarga($data)) {
            return;
        }

        // Pindah dusun/rw/rt anggota keluarga kalau berubah
        if ($data['id_cluster'] != $data['id_cluster_lama']) {
            $this->keluarga_model->pindah_anggota_keluarga($id, $data['id_cluster']);
        }
        unset($data['dusun'], $data['rw'], $data['id_cluster_lama'], $data['id_program']);

        $data['tgl_cetak_kk'] = empty($data['tgl_cetak_kk']) ? null : date('Y-m-d H:i:s', strtotime($data['tgl_cetak_kk']));
        if (empty($data['kelas_sosial'])) {
            $data['kelas_sosial'] = null;
        }
        $data['updated_by'] = $this->session->user;
        $outp               = $this->config_id()->where('id', $id)->update('tweb_keluarga', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function pindah_keluarga($id_kk, $id_cluster): void
    {
        $this->config_id()->where('id', $id_kk)->update('tweb_keluarga', ['id_cluster' => $id_cluster, 'updated_by' => $this->session->user]);
        $this->pindah_anggota_keluarga($id_kk, $id_cluster);
    }

    private function pindah_anggota_keluarga($id_kk, $id_cluster): void
    {
        // Ubah dusun/rw/rt untuk semua anggota keluarga
        if (! empty($id_cluster)) {
            $data['id_cluster'] = $id_cluster;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->session->user;
            $outp               = $this->config_id()->where('id_kk', $id_kk)->update('tweb_penduduk', $data);

            // Tulis log pindah untuk setiap anggota keluarga
            $data2 = $this->config_id()
                ->select('id')
                ->where('id_kk', $id_kk)
                ->get('tweb_penduduk')
                ->result_array();

            foreach ($data2 as $datanya) {
                $this->penduduk_model->tulis_log_penduduk($datanya['id'], '6', date('m'), date('Y'));
            }
        }
    }

    public function get_alamat_wilayah($id_kk)
    {
        $data = $this->config_id('k')
            ->select('a.dusun, a.rw, a.rt, k.alamat')
            ->from('tweb_keluarga k')
            ->join('tweb_wil_clusterdesa a', 'k.id_cluster = a.id', 'left')
            ->where('k.id', $id_kk)
            ->get()
            ->row_array();

        if (! isset($data['alamat'])) {
            $data['alamat'] = '';
        }
        if (! isset($data['rt'])) {
            $data['rt'] = '';
        }
        if (! isset($data['rw'])) {
            $data['rw'] = '';
        }
        $str_dusun = (empty($data['dusun']) || $data['dusun'] == '-') ? '' : ikut_case($data['dusun'], $this->setting->sebutan_dusun . ' ' . $data['dusun']);

        return trim("{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . $str_dusun);
    }

    public function get_judul_statistik($tipe = 0, $nomor = 1, $sex = 0)
    {
        if ($nomor == JUMLAH) {
            $judul = ['nama' => 'JUMLAH'];
        } elseif ($nomor == BELUM_MENGISI) {
            $judul = ['nama' => 'BELUM MENGISI'];
        } elseif ($nomor == TOTAL) {
            $judul = ['nama' => 'TOTAL'];
        } else {
            switch ($tipe) {
                case 'kelas_sosial':
                    $tabel = 'tweb_keluarga_sejahtera';
                    break;

                case 'bantuan_keluarga':
                    $tabel = 'program';
                    break;
            }
            $judul = $query = $this->db->where('id', $nomor)->get($tabel)->row_array();
        }
        if ($sex == 1) {
            $judul['nama'] .= ' - LAKI-LAKI';
        } elseif ($sex == 2) {
            $judul['nama'] .= ' - PEREMPUAN';
        }

        return $judul;
    }

    public function get_data_unduh_kk($id)
    {
        return ['desa' => identitas(), 'id_kk' => $id, 'main' => $this->list_anggota($id), 'kepala_kk' => $this->get_kepala_kk($id)];
    }

    public function unduh_kk($id = 0): void
    {
        $id_cb = $_POST['id_cb'];
        if (empty($id) && count($id_cb) == 1) {
            // Aksi borongan dengan satu KK saja
            $id = $id_cb[0];
        }
        if (empty($id)) {
            // Aksi borongan lebih dari satu KK
            $berkas_kk = [];
            if (count($id_cb) > 0) {
                foreach ($id_cb as $id) {
                    $data        = $this->get_data_unduh_kk($id);
                    $berkas_kk[] = $this->buat_berkas_kk($data);
                }
            }
            // Masukkan semua berkas ke dalam zip
            $berkas_kk = masukkan_zip($berkas_kk);
            // Unduh berkas zip
            header('Content-disposition: attachment; filename=berkas_kk_' . date('d-m-Y') . '.zip');
            header('Content-type: application/zip');
            readfile($berkas_kk);
        } else {
            // Satu kk
            $data      = $this->get_data_unduh_kk($id);
            $berkas_kk = $this->buat_berkas_kk($data);
            ambilBerkas(basename($berkas_kk));
        }
    }

    private function buat_berkas_kk($data = '')
    {
        $path_arsip = LOKASI_ARSIP;
        $file       = DEFAULT_LOKASI_EKSPOR . 'kk.rtf';
        if (! is_file($file)) {
            return;
        }
        $nama = '';

        $handle = fopen($file, 'rb');
        $buffer = stream_get_contents($handle);
        $i      = 0;

        foreach ($data['main'] as $ranggota) {
            $i++;
            $nama              .= $ranggota['nama'] . '\\line ';
            $no                .= $i . '\\line ';
            $hubungan          .= $ranggota['hubungan'] . '\\line ';
            $nik               .= $ranggota['nik'] . '\\line ';
            $sex               .= $ranggota['sex'] . '\\line ';
            $tempatlahir       .= $ranggota['tempatlahir'] . '\\line ';
            $tanggallahir      .= tgl_indo($ranggota['tanggallahir']) . '\\line ';
            $agama             .= $ranggota['agama'] . '\\line ';
            $pendidikan        .= $ranggota['pendidikan'] . '\\line ';
            $pekerjaan         .= $ranggota['pekerjaan'] . '\\line ';
            $status_kawin      .= $ranggota['status_kawin'] . '\\line ';
            $warganegara       .= $ranggota['warganegara'] . '\\line ';
            $dokumen_pasport   .= $ranggota['dokumen_pasport'] . '\\line ';
            $dokumen_kitas     .= $ranggota['dokumen_kitas'] . '\\line ';
            $nama_ayah         .= $ranggota['nama_ayah'] . '\\line ';
            $nama_ibu          .= $ranggota['nama_ibu'] . '\\line ';
            $golongan_darah    .= $ranggota['golongan_darah'] . '\\line ';
            $tanggalperkawinan .= isset($ranggota['tanggalperkawinan']) ? tgl_indo($ranggota['tanggalperkawinan']) . '\\line ' : '- \\line ';
            $tanggalperceraian .= isset($ranggota['tanggalperceraian']) ? tgl_indo($ranggota['tanggalperceraian']) . '\\line ' : '- \\line ';
        }

        $buffer = str_replace('[no]', "{$no}", $buffer);
        $buffer = str_replace('[nama]', "\\caps {$nama}", $buffer);
        $buffer = str_replace('[hubungan]', "{$hubungan}", $buffer);
        $buffer = str_replace('[nik]', "{$nik}", $buffer);
        $buffer = str_replace('[sex]', "{$sex}", $buffer);
        $buffer = str_replace('[agama]', "{$agama}", $buffer);
        $buffer = str_replace('[pendidikan]', "{$pendidikan}", $buffer);
        $buffer = str_replace('[pekerjaan]', "{$pekerjaan}", $buffer);
        $buffer = str_replace('[tempatlahir]', "\\caps {$tempatlahir}", $buffer);
        $buffer = str_replace('[tanggallahir]', "\\caps {$tanggallahir}", $buffer);
        $buffer = str_replace('[kawin]', "{$status_kawin}", $buffer);
        $buffer = str_replace('[warganegara]', "{$warganegara}", $buffer);
        $buffer = str_replace('[pasport]', "{$dokumen_pasport}", $buffer);
        $buffer = str_replace('[kitas]', "{$dokumen_kitas}", $buffer);
        $buffer = str_replace('[ayah]', "\\caps {$nama_ayah}", $buffer);
        $buffer = str_replace('[ibu]', "\\caps {$nama_ibu}", $buffer);
        $buffer = str_replace('[darah]', "\\caps {$golongan_darah}", $buffer);
        $buffer = str_replace('[tanggalperkawinan]', "\\caps {$tanggalperkawinan}", $buffer);
        $buffer = str_replace('[tanggalperceraian]', "\\caps {$tanggalperceraian}", $buffer);

        $h              = $data['desa'];
        $k              = $data['kepala_kk'];
        $sebutan_kepala = setting('sebutan_kepala_desa');
        $tertanda       = tgl_indo(date('Y m d'));
        $tertanda       = $h['nama_desa'] . ', ' . $tertanda;
        $buffer         = str_replace('[sebutan_kepala_desa]', "\\caps {$sebutan_kepala}", $buffer);
        $buffer         = str_replace('desa', "\\caps {$h['nama_desa']}", $buffer);
        $buffer         = str_replace('alamat_plus_dusun', "\\caps {$k['alamat_plus_dusun']}", $buffer);
        $buffer         = str_replace('prop', "\\caps {$h['nama_propinsi']}", $buffer);
        $buffer         = str_replace('kab', "\\caps {$h['nama_kabupaten']}", $buffer);
        $buffer         = str_replace('kec', "\\caps {$h['nama_kecamatan']}", $buffer);
        $buffer         = str_replace('*camat', "\\caps {$h['nama_kepala_camat']}", $buffer);
        $buffer         = str_replace('*kades', "\\caps {$h['nama_kepala_desa']}", $buffer);
        $buffer         = str_replace('*rt', "{$k['rt']}", $buffer);
        $buffer         = str_replace('*rw', "{$k['rw']}", $buffer);
        $buffer         = str_replace('*kk', "\\caps {$k['nama']}", $buffer);
        $buffer         = str_replace('no_kk', "{$k['no_kk']}", $buffer);
        $buffer         = str_replace('pos', "{$h['kode_pos']}", $buffer);
        $buffer         = str_replace('*tertanda', "\\caps {$tertanda}", $buffer);
        $buffer         = str_replace('*nip_camat', "{$h['nip_kepala_camat']}", $buffer);

        $berkas_arsip = $path_arsip . "kk_{$k['no_kk']}.rtf";
        $handle       = fopen($berkas_arsip, 'w+b');
        fwrite($handle, $buffer);
        fclose($handle);

        return $berkas_arsip;
    }

    public function get_keluarga_by_no_kk($no_kk)
    {
        return $this->config_id()
            ->where('no_kk', $no_kk)
            ->get('tweb_keluarga')
            ->row_array();
    }

    public function nokk_sementara()
    {
        $digit = $this->config_id()
            ->select('RIGHT(no_kk, 5) as digit')
            ->order_by('RIGHT(no_kk, 5) DESC')
            ->like('no_kk', '0', 'after')
            ->where('no_kk !=', '0')
            ->get('tweb_keluarga')
            ->row()->digit ?? 0;

        // No_kk Sementara menggunakan format 0[kode-desa][nomor-urut]
        return '0' . identitas()->kode_desa . sprintf('%05d', $digit + 1);
    }

    public function pecah_semua($id, $post): void
    {
        $this->session->unset_userdata(['success', 'error_msg']);
        // Buat keluarga baru
        $kel = $this->db
            ->where('id', $id)
            ->get('tweb_keluarga')->row_array();
        unset($kel['id']);
        $no_kk_lama        = $kel['no_kk'];
        $kel['config_id']  = $this->config_id;
        $kel['nik_kepala'] = bilangan($post['nik_kepala']);
        $kel['no_kk']      = bilangan($post['no_kk']);
        $kel['updated_at'] = date('Y-m-d H:i:s');
        $kel['updated_by'] = $this->session->user;
        $hasil             = $this->db->insert('tweb_keluarga', $kel);
        $kk_id             = $this->db->insert_id();
        // Untuk statistik perkembangan keluarga
        $this->log_keluarga($kk_id, LogKeluarga::KELUARGA_BARU);

        // Masukkan semua anggota lama
        $list_anggota = $this->config_id()
            ->select('id')
            ->where('id_kk', $id)
            ->get('tweb_penduduk')->result_array();

        foreach ($list_anggota as $anggota) {
            $data = [
                'id_kk'            => $kk_id,
                'no_kk_sebelumnya' => $no_kk_lama,
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => $this->session->user,
            ];
            if ($anggota['id'] == $post['nik_kepala']) {
                $data['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
            }
            $hasil = $hasil && $this->config_id()
                ->where('id', $anggota['id'])
                ->update('tweb_penduduk', $data);
        }

        // Hapus dokumen bersama dengan kepala KK sebelumnya
        $hasil = $hasil && $this->web_dokumen_model->hard_delete_dokumen_bersama($id);

        status_sukses($hasil, true); //Tampilkan Pesan
    }

    public function proses_pindah($post)
    {
        $id_kk = explode(',', $post['id_kk']);

        $data = ['id_cluster' => $post['id_cluster']];

        $outp_keluarga = $this->db
            ->where_in('id', $id_kk)
            ->update('tweb_keluarga', $data);

        $outp_penduduk = $this->db
            ->where_in('id_kk', $id_kk)
            ->update('tweb_penduduk', $data);

        status_sukses($outp_keluarga && $outp_penduduk);
    }
}
