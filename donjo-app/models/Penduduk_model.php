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

class Penduduk_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('keluarga_model');
        $this->load->model('web_dokumen_model');
        $this->load->model('penduduk_log_model');
        $this->ktp_el             = array_flip(unserialize(KTP_EL));
        $this->status_rekam       = $this->referensi_model->list_status_rekam();
        $this->tempat_dilahirkan  = array_flip(unserialize(TEMPAT_DILAHIRKAN));
        $this->jenis_kelahiran    = array_flip(unserialize(JENIS_KELAHIRAN));
        $this->penolong_kelahiran = array_flip(unserialize(PENOLONG_KELAHIRAN));
    }

    public function autocomplete($cari = '')
    {
        return $this->autocomplete_str('nama', 'tweb_penduduk', $cari);
    }

    protected function search_sql()
    {
        if ($this->session->cari) {
            $cari = $this->session->cari;
            $this->db
                ->group_start()
                ->like('u.nama', $cari)
                ->or_like('u.nik', $cari)
                ->or_like('u.tag_id_card', $cari)
                ->group_end();
        }
    }

    protected function kumpulan_nik_sql()
    {
        if (empty($this->session->kumpulan_nik)) {
            return;
        }

        $kumpulan_nik                = preg_replace('/[^0-9\,]/', '', $this->session->kumpulan_nik);
        $kumpulan_nik                = array_filter(array_slice(explode(',', $kumpulan_nik), 0, 20)); // ambil 20 saja
        $kumpulan_nik                = implode(',', $kumpulan_nik);
        $this->session->kumpulan_nik = $kumpulan_nik;
        $this->db->where("u.nik in ({$kumpulan_nik})");
    }

    protected function keluarga_sql()
    {
        if ($this->session->layer_keluarga == 1) {
            $this->db->where('u.kk_level', 1);
        }
    }

    protected function dusun_sql()
    {
        if (! empty($this->session->dusun)) {
            $kf = $this->session->dusun;
            $this->db->where("((u.id_kk <> '0' AND a.dusun = '{$kf}') OR (u.id_kk = '0' AND a2.dusun = '{$kf}'))");
        }
    }

    protected function rw_sql()
    {
        if (! empty($this->session->rw)) {
            $kf = $this->session->rw;
            $this->db->where("((u.id_kk <> '0' AND a.rw = '{$kf}') OR (u.id_kk = '0' AND a2.rw = '{$kf}'))");
        }
    }

    protected function rt_sql()
    {
        if (! empty($this->session->rt)) {
            $kf = $this->session->rt;
            $this->db->where("((u.id_kk <> '0' AND a.rt = '{$kf}') OR (u.id_kk = '0' AND a2.rt = '{$kf}'))");
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
                ->where("{$kolom} !=", '')
                ->group_end();
        } elseif ($kf == BELUM_MENGISI) {
            $this->db
                ->group_start()
                ->where("{$kolom} IS NULL")
                ->or_where($kolom, '')
                ->group_end();
        } elseif ($kf == $this->session->status_dasar) {
            $this->db->where_in($kolom, $kf);
        } else {
            $this->db->where($kolom, $kf);
        }
    }

    protected function nik_sementara_sql()
    {
        if ($this->session->nik_sementara == '0') {
            $this->db->like('nik', '0', 'after');
        }
    }

    // Filter belum digunakan
    protected function hamil_sql()
    {
        $kf = $this->session->hamil;

        if ($kf) {
            switch (true) {
                case $kf == BELUM_MENGISI:
                    $this->db->where('(u.hamil IS NULL)');
                    break;

                case $kf == JUMLAH:
                    $this->db->where('u.hamil IS NOT NULL');
                    break;

                case $kf == TOTAL:
                    break;

                default:
                    $this->db->where('u.hamil', $kf);
                    break;
            }

            $this->db->where('u.sex', '2');
        }
    }

    // Filter belum digunakan
    protected function tag_id_card_sql()
    {
        $kf = $this->session->tag_id_card;
        if (isset($kf)) {
            if ($kf === '1') {
                $this->db->where('u.tag_id_card !=', null);
            } elseif ($kf === '2') {
                $this->db->where('u.tag_id_card', null);
            }
        }
    }

    protected function umur_max_sql()
    {
        $kf = $this->session->umur_max;
        if (isset($kf)) {
            $this->db->where(" DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0  <= {$kf}");
        }
    }

    protected function umur_min_sql()
    {
        $kf = $this->session->umur_min;
        if (isset($kf)) {
            $this->db->where(" DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= {$kf}");
        }
    }

    protected function umur_sql()
    {
        $kf = $this->session->umurx;
        if (isset($kf)) {
            if ($kf == JUMLAH) {
                $this->db->where("u.tanggallahir <> ''");
            } elseif ($kf == BELUM_MENGISI) {
                $this->db->where("(u.tanggallahir IS NULL OR u.tanggallahir = '')");
            } else {
                $this->db->where(" DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= (SELECT dari FROM tweb_penduduk_umur WHERE id={$kf} ) AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= (SELECT sampai FROM tweb_penduduk_umur WHERE id={$kf} ) ");
            }
        }
    }

    protected function akta_kelahiran_sql()
    {
        $kf = $this->session->akta_kelahiran;
        if (isset($kf)) {
            if (! in_array($kf, [JUMLAH, BELUM_MENGISI])) {
                $this->session->umurx = $kf;
                $this->db->where("u.akta_lahir <> '' ");
                $this->umur_sql();

                return;
            }

            if ($kf == JUMLAH) {
                $this->db->where("u.akta_lahir <> '' ");
            } elseif ($kf == BELUM_MENGISI) {
                $this->db->where("(u.akta_lahir IS NULL OR u.akta_lahir = '') ");
            }
        }
    }

    private function tahun_bulan()
    {
        $kt = $this->session->filter_tahun;
        $kb = $this->session->filter_bulan;

        switch (true) {
            case $kt && $kb:
                $kb_pad = str_pad($kb, 2, '0', STR_PAD_LEFT);
                $this->db->where("date_format(log.tgl_lapor, '%Y-%m') <= '{$kt}-{$kb_pad}'");
                break;

            case $kt:
                $this->db->where('YEAR(log.tgl_lapor) <=', $kt);
                break;

            case $kb:
                $this->db->where('MONTH(log.tgl_lapor) <=', $kb);
                break;

            default:
            }
    }

    protected function status_ktp_sql()
    {
        if (! $this->session->status_ktp) {
            return;
        }

        // Filter berdasarkan data eKTP
        $this->db->where("((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) ");

        $kf = $this->session->status_ktp;

        switch (true) {
            case $kf == BELUM_MENGISI:
                $this->db->where("(u.status_rekam IS NULL OR u.status_rekam = '')");
                break;

            case $kf == JUMLAH:
                $this->db->where("u.status_rekam IS NOT NULL AND u.status_rekam <> ''");
                break;

            case $kf == TOTAL:
                // TOTAL hanya yang wajib KTP
                break;

            case $kf != 0:
                // Tidak bisa pakai query builder, supaya tidak menghapus query utama
                $sql          = 'select * from tweb_status_ktp where id = ?';
                $status_rekam = $this->db->query($sql, $kf)->row()->status_rekam;
                $this->db->where('u.status_rekam', $status_rekam);
                break;

            default:
            }
    }

    public function get_alamat_wilayah($id)
    {
        // Alamat anggota keluarga diambil dari tabel keluarga
        $this->db->select('id_kk');
        $this->db->where('id', $id);
        $q        = $this->db->get('tweb_penduduk');
        $penduduk = $q->row_array();
        if ($penduduk['id_kk'] > 0) {
            return $this->keluarga_model->get_alamat_wilayah($penduduk['id_kk']);
        }
        // Alamat penduduk lepas diambil dari kolom alamat_sekarang
        $sql = 'SELECT a.dusun, a.rw, a.rt, u.alamat_sekarang as alamat
				FROM tweb_penduduk u
				LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
				WHERE u.id = ?';
        $query = $this->db->query($sql, $id);
        $data  = $query->row_array();

        return trim("{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . ikut_case($data['dusun'], $this->setting->sebutan_dusun) . " {$data['dusun']}");
    }

    private function bantuan_penduduk_sql()
    {
        // Yg berikut hanya untuk menampilkan peserta bantuan
        $bantuan_penduduk = $this->session->bantuan_penduduk;
        if (! in_array($bantuan_penduduk, [JUMLAH, BELUM_MENGISI, TOTAL])) {
            // Salin program_id
            $this->session->program_bantuan = $bantuan_penduduk;
        }
        if ($bantuan_penduduk && $bantuan_penduduk != BELUM_MENGISI) {
            if ($bantuan_penduduk != JUMLAH && $this->session->program_bantuan) {
                $this->db
                    ->join('program_peserta bt', 'bt.peserta = u.nik')
                    ->join('program rcb', 'bt.program_id = rcb.id', 'left');
            }
        }
        // Untuk BUKAN PESERTA program bantuan tertentu
        if ($bantuan_penduduk == BELUM_MENGISI) {
            if ($this->session->program_bantuan) {
                // Program bantuan tertentu
                $program_id = $this->session->program_bantuan;
                $this->db
                    ->join('program_peserta bt', "bt.peserta = u.nik and bt.program_id = {$program_id}", 'left')
                    ->where('bt.id is null');
            } else {
                // Bukan penerima bantuan apa pun
                $this->db
                    ->join('program_peserta bt', 'bt.peserta = u.nik', 'left')
                    ->where('bt.id is null');
            }
        } elseif ($bantuan_penduduk == JUMLAH && ! $this->session->program_bantuan) {
            // Penerima bantuan mana pun
            $this->db
                ->where('u.nik IN (select peserta from program_peserta)');
        }
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        // Join di sini untuk mendukung urut penduduk
        $this->db
            ->from('tweb_penduduk u')
            ->join('tweb_keluarga d', 'u.id_kk = d.id', 'left')
            ->join('tweb_wil_clusterdesa a', 'd.id_cluster = a.id', 'left')
            ->join('tweb_wil_clusterdesa a2', 'u.id_cluster = a2.id', 'left')
            // Ambil log yg terakhir saja
            ->join('(
              SELECT    MAX(id) max_id, id_pend
              FROM      log_penduduk
              GROUP BY  id_pend
          ) log_max', 'log_max.id_pend = u.id', 'left')
            ->join('log_penduduk log', 'log_max.max_id = log.id', 'left');
        if ($this->session->bantuan_penduduk) {
            $this->bantuan_penduduk_sql();
        }

        if ($this->session->status_covid) {
            $this->db
                ->join('covid19_pemudik c', 'c.id_terdata = u.id', 'left')
                ->join('ref_status_covid cv', 'cv.id = c.status_covid', 'left');
        }

        $this->search_sql();
        $this->kumpulan_nik_sql();
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();
        $this->tahun_bulan();

        // Filter data penduduk digunakan dibeberapa tempat, termasuk untuk laporan statistik kependudukan.
        // Filter untuk statistik kependudukan menggunakan kode yang ada di daftar STAT_PENDUDUK di referensi_model.php
        $kolom_kode = [
            ['filter', 'u.status'], //  Kode 6 Tetap, Tidak Tetap, Pendatang
            ['status_penduduk', 'u.status'], // Status Tetap, Tidak Tetap, Pendatang -> Hanya u/ Pencarian Spesifik
            ['status_dasar', 'u.status_dasar'], // Status : Hidup, Maati, Dll -> Hanya u/ Pencarian Spesifik
            ['sex', 'u.sex'], // Kode 4
            ['pendidikan_kk_id', 'u.pendidikan_kk_id'], // Kode 0
            ['cacat', 'u.cacat_id'], // Kode 9
            ['cara_kb_id', 'u.cara_kb_id'], // Kode 16
            ['menahun', 'u.sakit_menahun_id'], // Kode 10
            ['status', 'u.status_kawin'], // Kode 2
            ['pendidikan_sedang_id', 'u.pendidikan_sedang_id'], // Kode 14
            ['pekerjaan_id', 'u.pekerjaan_id'], // Kode 1
            ['agama', 'u.agama_id'], // Kode 3
            ['warganegara', 'u.warganegara_id'], // Kode 5
            ['golongan_darah', 'u.golongan_darah_id'], // Kode 7
            ['hubungan', 'u.kk_level'], // Kode hubungan_kk
            ['id_asuransi', 'u.id_asuransi'], // Kode 19
            ['status_covid', 'cv.id'],  // Kode covid
            ['suku', 'u.suku'], // Kode suku
            ['bpjs_ketenagakerjaan', 'u.bpjs_ketenagakerjaan'], // Kode bpjs_ketenagakerjaan
        ];

        if ($this->session->bantuan_penduduk && $this->session->bantuan_penduduk != BELUM_MENGISI) {
            if ($this->session->bantuan_penduduk != JUMLAH && $this->session->program_bantuan) {
                $kolom_kode[] = ['bantuan_penduduk', 'rcb.id'];
            }
        }

        foreach ($kolom_kode as $kolom) {
            // Gunakan cara ini u/ filter sederhana
            $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
        }

        $this->status_ktp_sql(); // Kode 18
        $this->umur_min_sql(); // Hanya u/ Pencarian Spesifik
        $this->umur_max_sql(); // Hanya u/ Pencarian Spesifik
        $this->umur_sql(); // Kode 13, 15
        $this->akta_kelahiran_sql(); // Kode 17
        $this->hamil_sql(); // Filter blum digunakan
        $this->tag_id_card_sql(); // Filter blum digunakan
        $this->nik_sementara_sql(); // NIK Sementara
    }

    // Perlu di urut sebelum paging dan sesudah paging
    private function order_by_list($order_by)
    {
        //Urut data
        switch ($order_by) {
            case 1:
                $this->db->order_by('u.nik');
                break;

            case 2:
                $this->db->order_by('u.nik', 'DESC');
                break;

            case 3:
                $this->db->order_by('u.nama');
                break;

            case 4:
                $this->db->order_by('u.nama', 'DESC');
                break;

            case 5:
                $this->db->order_by('CONCAT(d.no_kk, u.id_kk, u.kk_level)');
                break;

            case 6:
                $this->db->order_by('d.no_kk DESC, u.id_kk, u.kk_level');
                break;

            case 7:
                $this->db->order_by('umur');
                break;

            case 8:
                $this->db->order_by('umur', 'DESC');
                break;

            case 9:
                $this->db->order_by('u.created_at');
                break;

            case 10:
                $this->db->order_by('u.created_at', 'DESC');
                break;

            case 11:
                $this->db->order_by('log.tgl_peristiwa');
                break;

            case 12:
                $this->db->order_by('log.tgl_peristiwa', 'DESC');
                break;

            default:
                $this->db->order_by('CONCAT(d.no_kk, u.id_kk, u.kk_level)');
                break;
        }
    }

    // $page = 0 mengambil semua
    public function list_data($order_by = 1, $page = 1)
    {
        //Main Query
        $this->list_data_sql();
        $this->db->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur");
        $this->order_by_list($order_by);

        if ($page > 0) {
            $jumlah_pilahan = $this->db->count_all_results('', false);
            $paging         = $this->paginasi($page, $jumlah_pilahan);
            $this->db->limit($paging->per_page, $paging->offset);
        }

        $query_dasar = $this->db->select('u.*')->get_compiled_select();

        // Proses berikutnya dilakukan setelah paginasi, untuk mempercepat proses join di lookup_ref_penduduk
        // yang cukup banyak.
        $this->db->select("u.id, u.nik, u.tanggallahir, u.tempatlahir, u.foto, u.status, u.status_dasar, u.id_kk, u.nama, u.nama_ayah, u.nama_ibu, u.alamat_sebelumnya, u.suku, u.bpjs_ketenagakerjaan, a.dusun, a.rw, a.rt, d.alamat, d.no_kk AS no_kk, u.kk_level, u.tag_id_card, u.created_at, u.sex as id_sex, u.negara_asal, u.tempat_cetak_ktp, u.tanggal_cetak_ktp, v.nama AS warganegara, l.inisial as bahasa, l.nama as bahasa_nama, u.ket, log.tgl_peristiwa, log.maksud_tujuan_kedatangan, log.tgl_lapor,
			(CASE
				WHEN u.status_kawin IS NULL THEN ''
				WHEN u.status_kawin <> 2 THEN k.nama
				ELSE
					CASE
                    WHEN (u.akta_perkawinan IS NULL OR u.akta_perkawinan = '') AND u.tanggalperkawinan IS NULL THEN 'KAWIN BELUM TERCATAT'
                    ELSE 'KAWIN TERCATAT'
					END
			END) AS kawin,
			(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur,
			(DATE_FORMAT(FROM_DAYS(TO_DAYS(log.tgl_peristiwa)-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur_pada_peristiwa,
			x.nama AS sex, sd.nama AS pendidikan_sedang, n.nama AS pendidikan, p.nama AS pekerjaan, g.nama AS agama, m.nama AS gol_darah, hub.nama AS hubungan, b.no_kk AS no_rtm, b.id AS id_rtm
		");

        $this->db->from("({$query_dasar}) as u");
        $this->lookup_ref_penduduk();
        $this->order_by_list($order_by);

        $data = $this->db->get()->result_array();

        //Formating Output
        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            // Untuk penduduk mati atau hilang, gunakan umur pada tgl peristiwa
            if (in_array($data[$i]['status_dasar'], ['2', '4'])) {
                $data[$i]['umur'] = $data[$i]['umur_pada_peristiwa'];
            }
            // Ubah alamat penduduk lepas
            if (! $data[$i]['id_kk'] || $data[$i]['id_kk'] == 0) {
                // Ambil alamat penduduk
                $this->db
                    ->select('p.id_cluster, p.alamat_sekarang, c.dusun, c.rw, c.rt')
                    ->from('tweb_penduduk p')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
                    ->where('p.id', $data[$i]['id']);
                $penduduk           = $this->db->get()->row_array();
                $data[$i]['alamat'] = $penduduk['alamat_sekarang'];
                $data[$i]['dusun']  = $penduduk['dusun'];
                $data[$i]['rw']     = $penduduk['rw'];
                $data[$i]['rt']     = $penduduk['rt'];
            }

            // Tambah tanggal datang
            $kt     = $this->session->filter_tahun;
            $kb     = $this->session->filter_bulan;
            $kb_pad = str_pad($kb, 2, '0', STR_PAD_LEFT);

            // Ambil Log Datang Terakhir Penduduk
            $this->db
                ->select('lp.*')
                ->from('tweb_penduduk tp')
                ->join('log_penduduk lp', 'tp.id = lp.id_pend', 'left')
                ->where('tp.id', $data[$i]['id'])
                ->where('lp.kode_peristiwa', '5')
                ->where("date_format(lp.tgl_lapor, '%Y-%m') <= '{$kt}-{$kb_pad}'")
                ->order_by('lp.id', 'DESC');
            $log_datang = $this->db->get()->row_array();

            // Ambil Log Terakhir Penduduk
            $this->db
                ->select('lp.*')
                ->from('tweb_penduduk tp')
                ->join('log_penduduk lp', 'tp.id = lp.id_pend', 'left')
                ->where('tp.id', $data[$i]['id'])
                ->where("date_format(lp.tgl_lapor, '%Y-%m') <= '{$kt}-{$kb_pad}'")
                ->order_by('lp.id', 'DESC');
            $log_terakhir = $this->db->get()->row();

            $data[$i]['tanggal_datang'] = $log_datang['tgl_lapor'];

            // Tambah tanggal pergi untuk penduduk dengan status dasar pergi
            $data[$i]['tanggal_pergi'] = null;
            if ($log_terakhir->kode_peristiwa == '6') {
                // Ambil Log Pergi Terakhir Penduduk
                $this->db
                    ->select('lp.*')
                    ->from('tweb_penduduk tp')
                    ->join('log_penduduk lp', 'tp.id = lp.id_pend', 'left')
                    ->where('tp.id', $data[$i]['id'])
                    ->where('lp.kode_peristiwa', '6')
                    ->where("date_format(lp.tgl_lapor, '%Y-%m') <= '{$kt}-{$kb_pad}'")
                    ->order_by('lp.id', 'DESC');
                $log_pergi = $this->db->get()->row_array();

                $data[$i]['tanggal_pergi'] = $log_pergi['tgl_lapor'];
                $data[$i]['ket']           = $log_pergi['catatan'];
            }

            $data[$i]['no'] = $j + 1;
            $j++;
        }
        if ($page > 0) {
            return ['paging' => $paging, 'main' => $data];
        }

        return $data;
    }

    private function lookup_ref_penduduk()
    {
        $this->db
            ->join('tweb_keluarga d', 'u.id_kk = d.id', 'left')
            ->join('tweb_wil_clusterdesa a', 'd.id_cluster = a.id', 'left')
            ->join('tweb_wil_clusterdesa a2', 'u.id_cluster = a2.id', 'left')
            ->join('tweb_rtm b', 'u.id_rtm = b.no_kk', 'left')
            ->join('tweb_penduduk_pendidikan_kk n', 'u.pendidikan_kk_id = n.id', 'left')
            ->join('tweb_penduduk_pendidikan sd', 'u.pendidikan_sedang_id = sd.id', 'left')
            ->join('tweb_penduduk_pekerjaan p', 'u.pekerjaan_id = p.id', 'left')
            ->join('tweb_penduduk_kawin k', 'u.status_kawin = k.id', 'left')
            ->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
            ->join('tweb_penduduk_agama g', ' u.agama_id = g.id', 'left')
            ->join('tweb_penduduk_warganegara v', 'u.warganegara_id = v.id', 'left')
            ->join('ref_penduduk_bahasa l', 'u.bahasa_id = l.id', 'left')
            ->join('tweb_golongan_darah m', 'u.golongan_darah_id = m.id', 'left')
            ->join('tweb_cacat f', 'u.cacat_id = f.id', 'left')
            ->join('tweb_penduduk_hubungan hub', 'u.kk_level = hub.id', 'left')
            ->join('tweb_sakit_menahun j', 'u.sakit_menahun_id = j.id', 'left')
            // Ambil log yg terakhir saja
            ->join('(
              SELECT    MAX(id) max_id, id_pend
              FROM      log_penduduk
              GROUP BY  id_pend
          ) log_max', 'log_max.id_pend = u.id')
            ->join('log_penduduk log', 'log_max.max_id = log.id')
            ->join('ref_peristiwa ra', 'ra.id = log.kode_peristiwa', 'left')
            ->join('covid19_pemudik c', 'c.id_terdata = u.id', 'left')
            ->join('ref_status_covid cv', 'cv.id = c.status_covid', 'left');
    }

    // TODO : Apakah function ini masih digunakan?
    public function list_data_map()
    {
        //Main Query
        $this->db
            ->select("u.id, u.nik, u.nama, u.sex as id_sex, map.lat, map.lng, a.dusun, a.rw, a.rt, u.foto, d.no_kk AS no_kk,
					DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0	AS umur,
						x.nama AS sex, sd.nama AS pendidikan_sedang, n.nama AS pendidikan, p.nama AS pekerjaan, k.nama AS kawin, g.nama AS agama, m.nama AS gol_darah, hub.nama AS hubungan,
						@alamat:=trim(concat_ws('',
							case
								when a.rt != '-' then concat('RT-', a.rt)
								else ''
							end,
							case
								when a.rw != '-' then concat('RW-', a.rw)
								else ''
							end,
							case
								when a.dusun != '-' then concat('Dusun ', a.dusun)
								else ''
							end
						)),
						case
							when length(@alamat) > 0 then @alamat
							else 'Alamat penduduk belum valid'
						end as alamat")
            ->from('tweb_penduduk u')
            ->join('tweb_penduduk_map map', 'u.id = map.id')
            ->join('tweb_wil_clusterdesa a', 'u.id_cluster = a.id', 'left')
            ->join('tweb_wil_clusterdesa a2', 'u.id_cluster = a2.id', 'left')
            ->join('tweb_keluarga d', 'u.id_kk = d.id', 'left')
            ->join('tweb_penduduk_pendidikan_kk n', 'u.pendidikan_kk_id = n.id', 'left')
            ->join('tweb_penduduk_pendidikan sd', 'u.pendidikan_sedang_id = sd.id', 'left')
            ->join('tweb_penduduk_pekerjaan p', 'u.pekerjaan_id = p.id', 'left')
            ->join('tweb_penduduk_kawin k', 'u.status_kawin = k.id', 'left')
            ->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
            ->join('tweb_penduduk_agama g', 'u.agama_id = g.id', 'left')
            ->join('tweb_penduduk_warganegara v', 'u.warganegara_id = v.id', 'left')
            ->join('tweb_golongan_darah m', 'u.golongan_darah_id = m.id', 'left')
            ->join('tweb_cacat f', 'u.cacat_id = f.id', 'left')
            ->join('tweb_penduduk_hubungan hub', 'u.kk_level = hub.id', 'left')
            ->join('tweb_sakit_menahun j', 'u.sakit_menahun_id = j.id', 'left');

        $this->keluarga_sql();
        $this->search_sql();
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();

        // Filter data penduduk juga digunakan untuk laporan statistik kependudukan di peta.
        // Filter untuk statistik kependudukan menggunakan kode yang ada di daftar STAT_PENDUDUK di referensi_model.php
        $kolom_kode = [
            ['filter', 'u.status'], // Status : Hidup, Mati, Dll -> Load data awal (filtering combobox)
            ['status_penduduk', 'u.status'], // Status : Hidup, Mati, Dll -> Hanya u/ Pencarian Spesifik
            ['status_dasar', 'u.status_dasar'], // Kode 6
            ['sex', 'u.sex'], // Kode 4
            ['pendidikan_kk_id', 'u.pendidikan_kk_id'], // Kode 0
            ['cacat', 'u.cacat_id'], // Kode 9
            ['cara_kb_id', 'u.cara_kb_id'], // Kode 16
            ['menahun', 'u.sakit_menahun_id'], // Kode 10
            ['status', 'u.status_kawin'], // Kode 2
            ['pendidikan_sedang_id', 'u.pendidikan_sedang_id'], // Kode 14
            ['pekerjaan_id', 'u.pekerjaan_id'], // Kode 1
            ['agama', 'u.agama_id'], // Kode 3
            ['warganegara', 'u.warganegara_id'], // Kode 5
            ['golongan_darah', 'u.golongan_darah_id'], // Kode 7
            ['hubungan', 'u.kk_level'], // Kode 11
            ['id_asuransi', 'u.id_asuransi'], // Kode 19
            ['status_covid', 'cv.id'], // Kode covid
            ['suku', 'u.suku'], // Kode suku
            ['bpjs_ketenagakerjaan', 'u.bpjs_ketenagakerjaan'], // Kode bpjs_ketenagakerjaan
        ];

        foreach ($kolom_kode as $kolom) {
            // Gunakan cara ini u/ filter sederhana
            $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
        }

        $this->status_ktp_sql(); // Kode 18
        $this->umur_min_sql(); // Kode 13, 15
        $this->umur_max_sql(); // Kode 13, 15
        $this->umur_sql(); // Kode 13, 15
        $this->akta_kelahiran_sql(); // Kode 17
        $this->hamil_sql(); // Filter blum digunakan
        $this->tag_id_card_sql(); // Filter blum digunakan

        return $this->db->get()->result_array();
    }

    public function validasi_data_penduduk(&$data, $id = null)
    {
        $data['tanggallahir']         = empty($data['tanggallahir']) ? null : tgl_indo_in($data['tanggallahir']);
        $data['tanggal_akhir_paspor'] = empty($data['tanggal_akhir_paspor']) ? null : tgl_indo_in($data['tanggal_akhir_paspor']);
        $data['tanggalperkawinan']    = empty($data['tanggalperkawinan']) ? null : tgl_indo_in($data['tanggalperkawinan']);
        $data['tanggalperceraian']    = empty($data['tanggalperceraian']) ? null : tgl_indo_in($data['tanggalperceraian']);
        $data['tanggal_cetak_ktp']    = empty($data['tanggal_cetak_ktp']) ? null : tgl_indo_in($data['tanggal_cetak_ktp']);

        $data['pendidikan_kk_id']     = $data['pendidikan_kk_id'] ?: null;
        $data['pendidikan_sedang_id'] = $data['pendidikan_sedang_id'] ?: null;
        $data['pekerjaan_id']         = $data['pekerjaan_id'] ?: null;
        $data['status_kawin']         = $data['status_kawin'] ?: null;
        $data['id_asuransi']          = $data['id_asuransi'] ?: null;
        $data['hamil']                = $data['hamil'] ?: null;

        $data['ktp_el']             = $data['ktp_el'] ?: null;
        $data['tag_id_card']        = $data['tag_id_card'] ?: null;
        $data['status_rekam']       = $data['status_rekam'] ?: null;
        $data['berat_lahir']        = $data['berat_lahir'] ?: null;
        $data['tempat_dilahirkan']  = $data['tempat_dilahirkan'] ?: null;
        $data['jenis_kelahiran']    = $data['jenis_kelahiran'] ?: null;
        $data['penolong_kelahiran'] = $data['penolong_kelahiran'] ?: null;
        $data['panjang_lahir']      = $data['panjang_lahir'] ?: null;
        $data['cacat_id']           = $data['cacat_id'] ?: null;
        $data['sakit_menahun_id']   = $data['sakit_menahun_id'] ?: null;
        $data['kk_level']           = $data['kk_level'];
        if (empty($data['id_asuransi']) || $data['id_asuransi'] == 1) {
            $data['no_asuransi'] = null;
        }
        if (empty($data['warganegara_id'])) {
            $data['warganegara_id'] = 1;
        } //default WNI

        // Hanya status 'kawin' yang boleh jadi akseptor kb
        if ($data['status_kawin'] != 2 || ! in_array($data['cara_kb_id'], [1, 2, 3, 4, 5, 6, 7, 99])) {
            $data['cara_kb_id'] = null;
        }
        // Status hamil tidak berlaku bagi laki-laki
        if ($data['sex'] == 1) {
            $data['hamil'] = null;
        }
        if (empty($data['kelahiran_anak_ke'])) {
            $data['kelahiran_anak_ke'] = null;
        }
        if ($data['warganegara_id'] == 1 || empty($data['dokumen_kitas'])) {
            $data['dokumen_kitas'] = null;
        }
        // Tanggal cetak ktp harus <= tanggal input
        if ($data['tanggal_cetak_ktp'] > date('Y-m-d')) {
            $data['tanggal_cetak_ktp'] = date('Y-m-d');
        }

        switch ($data['status_kawin']) {
            case 1:
                // Status 'belum kawin' tidak berlaku akta perkawinan dan perceraian
                $data['akta_perkawinan']   = '';
                $data['akta_perceraian']   = '';
                $data['tanggalperkawinan'] = null;
                $data['tanggalperceraian'] = null;
                break;

            case 2:
                // Status 'kawin' tidak berlaku akta perceraian
                $data['akta_perceraian']   = '';
                $data['tanggalperceraian'] = null;
                break;

            case 3:
            case 4:
                break;
        }

        // Sterilkan data
        $data['no_kk_sebelumnya']     = preg_replace('/[^0-9\.]/', '', strip_tags($data['no_kk_sebelumnya']));
        $data['akta_lahir']           = nomor_surat_keputusan($data['akta_lahir']);
        $data['tempatlahir']          = strip_tags($data['tempatlahir']);
        $data['dokumen_pasport']      = nomor_surat_keputusan($data['dokumen_pasport']);
        $data['nama_ayah']            = nama($data['nama_ayah']);
        $data['nama_ibu']             = nama($data['nama_ibu']);
        $data['alamat_sebelumnya']    = strip_tags($data['alamat_sebelumnya']);
        $data['alamat_sekarang']      = strip_tags($data['alamat_sekarang']);
        $data['akta_perkawinan']      = nomor_surat_keputusan($data['akta_perkawinan']);
        $data['akta_perceraian']      = nomor_surat_keputusan($data['akta_perceraian']);
        $data['bpjs_ketenagakerjaan'] = nomor_surat_keputusan($data['bpjs_ketenagakerjaan']);
        $data['suku']                 = nama_terbatas($data['suku']);

        $data['telepon']  = empty($data['telepon']) ? null : bilangan($data['telepon']);
        $data['email']    = empty($data['email']) ? null : email($data['email']);
        $data['telegram'] = empty($data['telegram']) ? null : bilangan($data['telegram']);

        $valid = [];
        if (preg_match("/[^a-zA-Z '\\.,\\-]/", $data['nama'])) {
            $valid[] = 'Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }
        if (isset($data['nik'])) {
            if ($error_nik = $this->nik_error($data['nik'], 'NIK')) {
                $valid[] = $error_nik;
            } else {
                if ($id) {
                    $this->db->where('id <>', $id);
                } //Tidak termasuk penduduk yg diupdate
                $existing_data = $this->db
                    ->select('nik, status_dasar')
                    ->from('tweb_penduduk')
                    ->where('nik', $data['nik'])
                    ->where('nik <>', 0)
                    ->limit(1)->get()->row();

                if ($existing_data) {
                    if ($existing_data->status_dasar != 6) {
                        $valid[] = "NIK {$data['nik']} sudah digunakan";
                    } else {
                        $valid[] = "NIK {$data['nik']} terdaftar Penduduk PERGI. Ubah Status di Menu Log Penduduk";
                    }
                }
            }
        }
        if ($error_nik = $this->nik_error($data['ayah_nik'], 'NIK Ayah')) {
            $valid[] = $error_nik;
        }
        if ($error_nik = $this->nik_error($data['ibu_nik'], 'NIK Ibu')) {
            $valid[] = $error_nik;
        }

        //cek email duplikat
        if (isset($data['email'])) {
            $existing_data = $this->db
                ->select('email')
                ->from('tweb_penduduk')
                ->where('email', $data['email'])
                ->where_not_in('id', $id)
                ->limit(1)->get()->row();

            if ($existing_data) {
                $valid[] = "Email {$data['email']} sudah digunakan";
            }
        }

        //cek telegram duplikat
        if (isset($data['telegram'])) {
            $existing_data = $this->db
                ->select('telegram')
                ->from('tweb_penduduk')
                ->where('telegram', $data['telegram'])
                ->where_not_in('id', $id)
                ->limit(1)->get()->row();

            if ($existing_data) {
                $valid[] = "Telegram {$data['telegram']} sudah digunakan";
            }
        }
        if (! empty($valid)) {
            $_SESSION['validation_error'] = true;
        }

        // Cek duplikasi Tag ID Card
        if ($this->penduduk_model->cekTagIdCard($data['tag_id_card'], $id)) {
            $valid[] = 'Tag ID Card sudah digunakan';
        }

        return $valid;
    }

    private function nik_error($nilai, $judul)
    {
        if (empty($nilai)) {
            return false;
        }
        if (! ctype_digit($nilai)) {
            return $judul . ' hanya berisi angka';
        }
        if (strlen($nilai) != 16 && $nilai != '0') {
            return $judul . ' panjangnya harus 16 atau bernilai 0';
        }

        return false;
    }

    // Tambah penduduk domisili (tidak ada nomor KK)
    public function insert()
    {
        unset($_SESSION['validation_error'], $_SESSION['success']);

        $_SESSION['error_msg'] = '';

        $data = $_POST;

        $error_validasi = $this->validasi_data_penduduk($data);
        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $_SESSION['error_msg'] .= ': ' . $error . '\n';
            }
            // Form menggunakan kolom id_sex = sex dan id_status = status
            $_POST['id_sex']    = $_POST['sex'];
            $_POST['id_status'] = $_POST['status'];
            // Tampilkan tanda kutip dalam nama
            $_POST['nama']       = str_replace('"', '&quot;', $_POST['nama']);
            $_SESSION['post']    = $_POST;
            $_SESSION['success'] = -1;

            return;
        }

        unset($data['file_foto'], $data['old_foto'], $data['nik_lama'], $data['kk_level_lama'], $data['dusun'], $data['rw'], $data['no_kk']);

        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        unset($data['maksud_tujuan_kedatangan']);

        $tgl_lapor = rev_tgl($_POST['tgl_lapor'], date('Y-m-d H:i:s'));
        if ($_POST['tgl_peristiwa']) {
            $tgl_peristiwa = rev_tgl($_POST['tgl_peristiwa']);
        } else {
            $tgl_peristiwa = rev_tgl($_POST['tanggallahir']);
        }
        unset($data['tgl_lapor'], $data['tgl_peristiwa']);

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->user;
        if ($data['tanggallahir'] == '') {
            unset($data['tanggallahir']);
        }
        if ($data['tanggalperkawinan'] == '') {
            unset($data['tanggalperkawinan']);
        }
        if ($data['tanggalperceraian'] == '') {
            unset($data['tanggalperceraian']);
        }
        $outp = $this->db->insert('tweb_penduduk', $data);
        $idku = $this->db->insert_id();

        // Upload foto dilakukan setelah ada id, karena nama foto berisi id pend
        if ($foto = upload_foto_penduduk()) {
            $this->db->where('id', $idku)->update('tweb_penduduk', ['foto' => $foto]);
        }

        // Jenis peristiwa didapat dari form yang berbeda
        // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
        $log = [
            'tgl_peristiwa'            => $tgl_peristiwa,
            'kode_peristiwa'           => $this->session->jenis_peristiwa,
            'tgl_lapor'                => $tgl_lapor,
            'id_pend'                  => $idku,
            'created_by'               => $this->session->user,
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
        ];
        $this->tulis_log_penduduk_data($log);

        $log1['id_pend']    = $idku;
        $log1['id_cluster'] = 1;
        $log1['tanggal']    = date('d-m-y');

        $outp = $this->db->insert('log_perubahan_penduduk', $log1);

        status_sukses($outp); //Tampilkan Pesan

        return $idku;
    }

    public function update($id = 0)
    {
        unset($_SESSION['validation_error'], $_SESSION['success'], $_SESSION['error_msg']);

        $data           = $_POST;
        $error_validasi = $this->validasi_data_penduduk($data, $id);
        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $_SESSION['error_msg'] .= ': ' . $error . '\n';
            }
            // Form menggunakan kolom id_sex = sex dan id_status = status
            $_POST['id_sex']    = $_POST['sex'];
            $_POST['id_status'] = $_POST['status'];
            // Tampilkan tanda kutip dalam nama
            $_POST['nama']       = str_replace('"', '&quot;', $_POST['nama']);
            $_SESSION['post']    = $_POST;
            $_SESSION['success'] = -1;

            return;
        }

        $sql   = 'SELECT id_kk, id_cluster, status_dasar FROM tweb_penduduk WHERE id = ?';
        $query = $this->db->query($sql, $id);
        $pend  = $query->row_array();
        if ($pend['status_dasar'] != 1) {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = 'Data penduduk dengan status dasar MATI/HILANG/PINDAH tidak dapat diubah!';

            return;
        }

        $this->keluarga_model->update_kk_level($id, $pend['id_kk'], $data['kk_level'], $data['kk_level_lama']);
        unset($data['kk_level_lama']);

        // Untuk anggota keluarga
        if (! empty($data['no_kk'])) {
            // Ganti alamat KK
            $this->db->where('id', $pend['id_kk'])->update('tweb_keluarga', ['alamat' => $data['alamat']]);
            if ($pend['id_cluster'] != $data['id_cluster']) {
                $this->keluarga_model->pindah_keluarga($pend['id_kk'], $data['id_cluster']);
            }
            unset($data['alamat']);
        }

        if ($foto = upload_foto_penduduk()) {
            $data['foto'] = $foto;
        } else {
            unset($data['foto']);
        }

        unset($data['no_kk'], $data['dusun'], $data['rw'], $data['file_foto'], $data['old_foto']);

        $tgl_lapor = rev_tgl($_POST['tgl_lapor']);
        if ($_POST['tgl_peristiwa']) {
            $tgl_peristiwa = rev_tgl($_POST['tgl_peristiwa']);
        } else {
            $tgl_peristiwa = rev_tgl($_POST['tanggallahir']);
        }
        unset($data['tgl_lapor'], $data['tgl_peristiwa']);

        // Reset data terkait penduduk TIDAK TETAP saat status berubah menjadi TETAP
        $maksud_tujuan = $_POST['maksud_tujuan_kedatangan'];
        if ($data['status'] == 1) {
            $data['maksud_tujuan_kedatangan'] = null;
        }
        unset($data['maksud_tujuan_kedatangan']);

        // Perbarui data log, mengecek status dasar dari penduduk, jika status dasar adalah hidup
        // maka akan menupdate data dengan kode_peristiwa 1/5
        $get_pendudukId = $this->db->where('id', $id)->get('tweb_penduduk')->row();
        $log            = [
            'tgl_peristiwa'            => $tgl_peristiwa,
            'updated_at'               => date('Y-m-d H:i:s'),
            'updated_by'               => $this->session->user,
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
        ];

        if ($_POST['tgl_lapor']) {
            $log['tgl_lapor'] = $tgl_lapor;
        }

        if ($get_pendudukId->status_dasar == 1) {
            $this->db->where('id_pend', $id)->where_in('kode_peristiwa', [1, 5])->update('log_penduduk', $log);
        } else {
            $this->db->where('id_pend', $id)->where('kode_peristiwa', $get_pendudukId->status_dasar)->update('log_penduduk', $log);
        }

        // Reset data terkait kewarganegaarn dari WNA / Dua Kewarganegaraan menjadi WNI
        if ($data['warganegara_id'] == 1) {
            $data['negara_asal'] = null;
        }

        // Reset data terkait kepemilikan KTP dari Memiliki KTP-EL menjadi Belum Memiliki KTP-EL
        if ($data['ktp_el'] == 1) {
            $data['tempat_cetak_ktp']  = null;
            $data['tanggal_cetak_ktp'] = null;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->user;
        $this->db->where('id', $id);
        $outp = $this->db->update('tweb_penduduk', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_position($id = 0)
    {
        $sql   = 'SELECT m.id, p.status_dasar FROM tweb_penduduk_map m RIGHT JOIN tweb_penduduk p ON m.id = p.id WHERE p.id = ?';
        $query = $this->db->query($sql, $id);
        $cek   = $query->row_array();
        if ($cek['status_dasar'] != 1) {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = 'Data penduduk dengan status dasar MATI/HILANG/PINDAH tidak dapat diubah!';

            return;
        }
        $data = $_POST;
        unset($data['zoom'], $data['map_tipe']);

        if ($cek['id'] == $id) {
            if ($data['lat']) {
                $this->db->where('id', $id);
                $outp = $this->db->update('tweb_penduduk_map', $data);
            }
        } else {
            if ($data['lat']) {
                $data['id'] = $id;
                $outp       = $this->db->insert('tweb_penduduk_map', $data);
            }
        }
        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_penduduk_map($id = 0)
    {
        $sql   = 'SELECT m.*, p.nama, p.status_dasar FROM tweb_penduduk_map m RIGHT JOIN tweb_penduduk p ON m.id = p.id WHERE p.id = ? ';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    public function update_status_dasar($id = 0)
    {
        akun_demo($id);

        $data['kelahiran_anak_ke'] = (int) $this->input->post('anak_ke');
        $data['status_dasar']      = $this->input->post('status_dasar');
        $data['updated_at']        = date('Y-m-d H:i:s');
        $data['updated_by']        = $this->session->user;
        $this->db
            ->where('id', $id)
            ->update('tweb_penduduk', $data);
        $penduduk = $this->get_penduduk($id);

        // Tulis log_penduduk
        $log = [
            'id_pend'        => $id,
            'no_kk'          => $penduduk['no_kk'],
            'nama_kk'        => $penduduk['kepala_kk'],
            'tgl_peristiwa'  => rev_tgl($this->input->post('tgl_peristiwa')),
            'tgl_lapor'      => rev_tgl($this->input->post('tgl_lapor')),
            'kode_peristiwa' => $data['status_dasar'],
            'catatan'        => alfanumerik_spasi($this->input->post('catatan')),
            'meninggal_di'   => alfanumerik_spasi($this->input->post('meninggal_di')),
            'jam_mati'       => $this->input->post('jam_mati'),
            'sebab'          => (int) ($this->input->post('sebab')),
            'penolong_mati'  => (int) ($this->input->post('penolong_mati')),
            'akta_mati'      => $this->input->post('akta_mati'),
        ];

        if ($log['kode_peristiwa'] == 3) {
            $pindah               = $this->input->post('ref_pindah');
            $log['ref_pindah']    = ! empty($pindah) ? $pindah : 1;
            $log['alamat_tujuan'] = $this->input->post('alamat_tujuan');
        }
        $id_log_penduduk = $this->tulis_log_penduduk_data($log);

        // Tulis log_keluarga jika penduduk adalah kepala keluarga
        if ($penduduk['kk_level'] == 1) {
            $id_peristiwa = $penduduk['status_dasar_id']; // lihat kode di keluarga_model
            $this->keluarga_model->log_keluarga($penduduk['id_kk'], $penduduk['id'], $id_peristiwa, null, $id_log_penduduk);
        }
    }

    /**
     * Kembalikan status dasar penduduk ke hidup
     *
     * @param $id 			id penduduk
     *
     * @return void
     */
    public function kembalikan_status($id)
    {
        $_SESSION['success']  = 1;
        $data['status_dasar'] = 1; // status dasar hidup
        $data['updated_at']   = date('Y-m-d H:i:s');
        $data['updated_by']   = $this->session->user;
        if (! $this->db->where('id', $id)->update('tweb_penduduk', $data)) {
            $_SESSION['success'] = -1;
        }
    }

    public function tulis_log_hapus_penduduk($log)
    {
        $this->db->insert('log_hapus_penduduk', $log);
    }

    public function delete($id = '', $semua = false)
    {
        akun_demo($id);

        // Catat data penduduk yg di hapus di log_hapus_penduduk
        $penduduk_hapus = $this->get_penduduk($id);
        $log            = [
            'id_pend'    => $penduduk_hapus['id'],
            'nik'        => $penduduk_hapus['nik'],
            'foto'       => $penduduk_hapus['foto'],
            'deleted_by' => $this->session->user,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $this->tulis_log_hapus_penduduk($log);

        // Hapus file foto penduduk yg di hapus di folder desa/upload/user_pict
        $file_foto = LOKASI_USER_PICT . $log['foto'];
        if (is_file($file_foto)) {
            unlink($file_foto);
            //break;
        }

        // Hapus file foto kecil penduduk yg di hapus di folder desa/upload/user_pict
        $file_foto_kecil = LOKASI_USER_PICT . 'kecil_' . $log['foto'];
        if (is_file($file_foto_kecil)) {
            unlink($file_foto_kecil);
            //break;
        }

        $outp = $this->db->where('id', $id)->delete('tweb_penduduk');

        // Hapus peserta program bantuan sasaran penduduk, kalau ada
        $outp = $outp && $this->program_bantuan_model->hapus_peserta_dari_sasaran($penduduk_hapus['nik'], 1);

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function adv_search_proses()
    {
        unset($_POST['umur1'], $_POST['umur2'], $_POST['dusun'], $_POST['rt'], $_POST['rw']);

        $i = 0;

        while ($i++ < count($_POST)) {
            $col[$i] = key($_POST);
            next($_POST);
        }
        $i = 0;

        while ($i++ < count($col)) {
            if ($_POST[$col[$i]] == '') {
                unset($_POST[$col[$i]]);
            }
        }

        $data = $_POST;
        $this->db->where($data);

        return $this->db->get('tweb_penduduk');
    }

    public function get_id_kk($id = 0)
    {
        $sql = 'SELECT u.id_kk
				FROM tweb_penduduk u
				WHERE id = ? limit 1';
        $query = $this->db->query($sql, $id);
        $data  = $query->row_array();

        return $data['id_kk'];
    }

    public function get_penduduk($id = 0, $nik_sementara = false)
    {
        $sql = "SELECT bahasa.nama as bahasa_nama, u.sex as id_sex, u.*, a.dusun, a.rw, a.rt, t.id AS id_status, t.nama AS status, o.nama AS pendidikan_sedang, m.nama as golongan_darah, h.nama as hubungan,
			b.nama AS pendidikan_kk, d.no_kk AS no_kk, d.alamat, u.id_cluster as id_cluster, ux.nama as nama_pengubah, ucreate.nama as nama_pendaftar, polis.nama AS asuransi,
			(CASE
				WHEN u.status_kawin IS NULL THEN ''
				WHEN u.status_kawin <> 2 THEN k.nama
				ELSE
					case
                    WHEN (u.akta_perkawinan IS NULL OR u.akta_perkawinan = '') AND u.tanggalperkawinan IS NULL THEN 'KAWIN BELUM TERCATAT'
                            ELSE 'KAWIN TERCATAT'
					END
			END) AS kawin,
			DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0  AS umur,
			x.nama AS sex, w.nama AS warganegara, p.nama AS pekerjaan, g.nama AS agama, c.nama as cacat, kb.nama as cara_kb, sm.nama as sakit_menahun, sd.nama as status_dasar, u.status_dasar as status_dasar_id,
			(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = d.nik_kepala)) AS kepala_kk,
			log.no_kk as log_no_kk, log.tgl_lapor as tgl_lapor, log.tgl_peristiwa as tgl_peristiwa, log.maksud_tujuan_kedatangan as maksud_tujuan_kedatangan FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
			LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
			LEFT JOIN tweb_penduduk_pendidikan o ON u.pendidikan_sedang_id = o.id
			LEFT JOIN tweb_penduduk_pendidikan_kk b ON u.pendidikan_kk_id = b.id
			LEFT JOIN tweb_penduduk_warganegara w ON u.warganegara_id = w.id
			LEFT JOIN tweb_penduduk_status t ON u.status = t.id
			LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
			LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
			LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
			LEFT JOIN tweb_penduduk_hubungan h on u.kk_level = h.id
			LEFT JOIN tweb_cacat c ON u.cacat_id = c.id
			LEFT JOIN tweb_sakit_menahun sm ON u.sakit_menahun_id = sm.id
			LEFT JOIN tweb_cara_kb kb ON u.cara_kb_id = kb.id
			LEFT JOIN tweb_status_dasar sd ON u.status_dasar = sd.id
			LEFT JOIN log_penduduk log ON u.id = log.id_pend
			LEFT JOIN user ux ON u.updated_by = ux.id
			LEFT JOIN user ucreate ON u.created_by = ucreate.id
			LEFT JOIN tweb_penduduk_asuransi polis ON polis.id = u.id_asuransi
			LEFT JOIN ref_penduduk_bahasa bahasa ON bahasa.id = u.bahasa_id
			WHERE u.id=?";
        $query = $this->db->query($sql, $id);
        $data  = $query->row_array();
        if ($data) {
            $data['tanggallahir']         = tgl_indo_out($data['tanggallahir']);
            $data['tanggal_akhir_paspor'] = tgl_indo_out($data['tanggal_akhir_paspor']);
            $data['tanggalperkawinan']    = tgl_indo_out($data['tanggalperkawinan']);
            $data['tanggalperceraian']    = tgl_indo_out($data['tanggalperceraian']);
            $data['tanggal_cetak_ktp']    = tgl_indo_out($data['tanggal_cetak_ktp']);
            // Penduduk lepas, pakai alamat penduduk
            if ($data['id_kk'] == 0 || $data['id_kk'] == '') {
                $data['alamat'] = $data['alamat_sekarang'];
                $this->db->where('id', $data['id_cluster']);
                $query         = $this->db->get('tweb_wil_clusterdesa');
                $cluster       = $query->row_array();
                $data['dusun'] = $cluster['dusun'];
                $data['rw']    = $cluster['rw'];
                $data['rt']    = $cluster['rt'];
            }
            // Data ektp: cari tulisan untuk kode
            $wajib_ktp = $this->is_wajib_ktp($data);
            if ($wajib_ktp !== null) {
                $data['wajib_ktp'] = $wajib_ktp ? 'WAJIB' : 'BELUM';
            }
            $data['ktp_el']                  = strtoupper($this->ktp_el[$data['ktp_el']]);
            $data['status_rekam']            = strtoupper($this->status_rekam[$data['status_rekam']]);
            $data['tempat_dilahirkan_nama']  = strtoupper($this->tempat_dilahirkan[$data['tempat_dilahirkan']]);
            $data['jenis_kelahiran_nama']    = strtoupper($this->jenis_kelahiran[$data['jenis_kelahiran']]);
            $data['penolong_kelahiran_nama'] = strtoupper($this->penolong_kelahiran[$data['penolong_kelahiran']]);
            // Tampilkan tanda kutip dalam nama
            $data['nama'] = str_replace('"', '&quot;', $data['nama']);

            if ($nik_sementara) {
                $data['nik'] = get_nik($data['nik']);
            }
        }

        return $data;
    }

    public function get_penduduk_by_nik($nik = 0)
    {
        $sql = "SELECT u.id AS id, u.nama AS nama, x.nama AS sex, u.id_kk AS id_kk,
		u.tempatlahir AS tempatlahir, u.tanggallahir AS tanggallahir, u.kk_level,
		(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
		w.nama AS status_kawin, f.nama AS warganegara, a.nama AS agama, h.nama as hubungan, d.nama AS pendidikan, j.nama AS pekerjaan, u.nik AS nik, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.no_kk AS no_kk, k.alamat,
		(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
		from tweb_penduduk u
		left join tweb_penduduk_sex x on u.sex = x.id
		left join tweb_penduduk_kawin w on u.status_kawin = w.id
		left join tweb_penduduk_agama a on u.agama_id = a.id
		left join tweb_penduduk_hubungan h on u.kk_level = h.id
		left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
		left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
		left join tweb_wil_clusterdesa c on u.id_cluster = c.id
		left join tweb_keluarga k on u.id_kk = k.id
		left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
		WHERE u.nik = ?";
        $query                  = $this->db->query($sql, $nik);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = trim("{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} {$data['dusun']}");

        return $data;
    }

    /**
     * $status_kawin_kk adalah status kawin dari kepala keluarga.
     * Digunakan pada saat menambah anggota keluarga, supaya yang ditampilkan hanya
     * hubungan yang berlaku
     *
     * @param mixed|null $status_kawin_kk
     * @param mixed      $sex
     */
    public function list_hubungan($status_kawin_kk = null, $sex = 1)
    {
        if (! empty($status_kawin_kk)) {
            /*
                Untuk Kepala Keluarga yang belum kawin, hubungan berikut tidak berlaku:
                    menantu, cucu, mertua, suami, istri; anak hanya berlaku untuk kk perempuan
                Untuk semua Kepala Keluarga, hubungan 'kepala keluarga' tidak berlaku
            */

            if ($status_kawin_kk == 1) {
                ($sex == 2) ? $this->db->where("id NOT IN ('1', '2', '3', '5', '6', '8') ")
                    : $this->db->where("id NOT IN ('1', '2', '3', '4', '5', '6', '8') ");
            } else {
                $this->db->where('id <> 1');
            }
        }

        return $this->db
            ->get('tweb_penduduk_hubungan')
            ->result_array();
    }

    // Hapus jika tdk ada modul yg gunakan, untuk selanjutnya penanganan wilayah terdapat pd wilayah_model.php
    public function list_pendidikan()
    {
        $sql   = 'SELECT * FROM tweb_penduduk_pendidikan WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    // TODO: tinjau apakah bisa digunakan atau perlu dihapus
    public function list_pendidikan_telah()
    {
        $sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama, 6) <> 'SEDANG' ";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function list_pendidikan_sedang()
    {
        $sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama, 5) <> 'TAMAT' ";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    // Hapus jika tdk ada modul yg gunakan, untuk selanjutnya penanganan wilayah terdapat pd wilayah_model.php
    public function list_pendidikan_kk()
    {
        $sql   = 'SELECT * FROM tweb_penduduk_pendidikan_kk WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    // Untuk pekerjaan, ubah bentuk seperti 'Belum/tidak Bekerja' menjadi 'Belum/Tidak Bekerja'
    private function ubah_ke_huruf_besar($matches)
    {
        $matches[0][1] = strtoupper($matches[0][1]);

        return $matches[0];
    }

    public function normalkanPekerjaan($nama)
    {
        $nama_pekerjaan = [
            '(pns)'   => '(PNS)',
            '(tni)'   => '(TNI)',
            '(polri)' => '(POLRI)',
            ' Ri '    => ' RI ',
            'Dpr-ri'  => 'DPR-RI',
            'Dpd'     => 'DPD',
            'Bpk'     => 'BPK',
            'Dprd'    => 'DPRD',
        ];
        $nama = ucwords(strtolower($nama));

        foreach ($nama_pekerjaan as $key => $value) {
            $nama = str_replace($key, $value, $nama);
        }
        if (strpos($nama, '/')) {
            $nama = $nama;
            $nama = preg_replace_callback('/\/\S{1}/', 'Penduduk_Model::ubah_ke_huruf_besar', $nama);
        }

        return $nama;
    }

    public function list_pekerjaan($case = '')
    {
        $sql   = 'SELECT * FROM tweb_penduduk_pekerjaan WHERE 1';
        $query = $this->db->query($sql);
        $data  = $query->result_array();
        if ($case == 'ucwords') {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['nama'] = $this->normalkanPekerjaan($data[$i]['nama']);
            }
        }

        return $data;
    }

    public function list_warganegara()
    {
        $sql   = 'SELECT * FROM tweb_penduduk_warganegara WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function list_status_kawin()
    {
        $sql   = 'SELECT * FROM tweb_penduduk_kawin WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function list_golongan_darah()
    {
        $sql   = 'SELECT * FROM tweb_golongan_darah WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function list_sex()
    {
        return $this->db->select('*')->get('tweb_penduduk_sex')->result_array();
    }

    public function list_cacat()
    {
        $sql   = 'SELECT * FROM tweb_cacat WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function list_cara_kb($sex = '')
    {
        if ($sex != 1 && $sex != 2) {
            $sql = 'SELECT * FROM tweb_cara_kb WHERE 1';
        } else {
            $sql = 'SELECT * FROM tweb_cara_kb WHERE sex = ? OR sex = 3';
        }
        $query = $this->db->query($sql, $sex);

        return $query->result_array();
    }

    public function is_anggota_keluarga($id)
    {
        $this->db->select('id_kk');
        $this->db->where('id', $id);
        $q        = $this->db->get('tweb_penduduk');
        $penduduk = $q->row_array();

        return (bool) ($penduduk['id_kk'] > 0);
    }

    public function tulis_log_penduduk_data($log)
    {
        $this->session->unset_userdata('jenis_peristiwa');
        $sql = $this->db->insert_string('log_penduduk', $log) . duplicate_key_update_str($log);
        $this->db->query($sql);

        return $this->db->insert_id();
    }

    public function tulis_log_penduduk($id_pend, $kode_peristiwa, $bulan, $tahun)
    {
        $data = [
            'id_pend'        => $id_pend,
            'kode_peristiwa' => $kode_peristiwa,
            'tgl_peristiwa'  => date('d-m-y'),
        ];
        $query = $this->db->insert_string('log_penduduk', $data) .
            'ON DUPLICATE KEY UPDATE
				id_pend = VALUES(id_pend),
				kode_peristiwa = VALUES(kode_peristiwa),
				tgl_peristiwa = VALUES(tgl_peristiwa)
				;
		';
        $this->db->query($query);
    }

    public function get_judul_statistik($tipe = '0', $nomor = 0, $sex = null)
    {
        if ($nomor == JUMLAH) {
            $judul = ['nama' => 'JUMLAH'];
        } elseif ($nomor == BELUM_MENGISI) {
            $judul = ['nama' => 'BELUM MENGISI'];
        } elseif ($nomor == TOTAL) {
            $judul = ['nama' => 'TOTAL'];
        } else {
            switch ($tipe) {
                case '0':
                    $table = 'tweb_penduduk_pendidikan_kk';
                    break;

                case 1:
                    $table = 'tweb_penduduk_pekerjaan';
                    break;

                case 2:
                    $table = 'tweb_penduduk_kawin';
                    break;

                case 3:
                    $table = 'tweb_penduduk_agama';
                    break;

                case 4:
                    $table = 'tweb_penduduk_sex';
                    break;

                case 5:
                    $table = 'tweb_penduduk_warganegara';
                    break;

                case 6:
                    $table = 'tweb_penduduk_status';
                    break;

                case 7:
                    $table = 'tweb_golongan_darah';
                    break;

                case 9:
                    $table = 'tweb_cacat';
                    break;

                case 10:
                    $table = 'tweb_sakit_menahun';
                    break;

                case 14:
                    $table = 'tweb_penduduk_pendidikan';
                    break;

                case 16:
                    $table = 'tweb_cara_kb';
                    break;

                case 13: // = 17
                case 15: // = 17
                case 17:
                    $table = 'tweb_penduduk_umur';
                    break;

                case 18:
                    $table = 'tweb_status_ktp';
                    break;

                case 19:
                    $table = 'tweb_penduduk_asuransi';
                    break;

                case 'bpjs-tenagakerja':
                    $table = 'tweb_penduduk_pekerjaan';
                    break;

                case 'covid':
                    $table = 'ref_status_covid';
                    break;

                case 'bantuan_penduduk':
                    $table = 'program';
                    break;

                case 'hubungan_kk':
                    $table = 'tweb_penduduk_hubungan';
                    break;

                case 'suku':
                    $table = 'tweb_penduduk';
                    break;

                case 'hamil':
                    $table = 'ref_penduduk_hamil';
                    break;
            }

            if ($tipe == 13 || $tipe == 17) {
                $this->db->where('STATUS', 1);
            }
            if ($tipe == 15) {
                $this->db->where('STATUS', 0);
            }

            $judul = $this->db->get_where($table, ['id' => $nomor])->row_array();

            if ($tipe == 'suku') {
                $judul['nama'] = rawurldecode($nomor);
            }
        }

        if ($sex == 1) {
            $judul['nama'] .= ' - LAKI-LAKI';
        } elseif ($sex == 2) {
            $judul['nama'] .= ' - PEREMPUAN';
        }

        return $judul;
    }

    // Untuk form surat
    public function list_penduduk_status_dasar($status_dasar = 1)
    {
        $sql = "SELECT u.id, nik, nama,
			CONCAT('Alamat : RT-', w.rt, ', RW-', w.rw, '', w.dusun) AS alamat,
			CONCAT('NIK: ', nik, ' - ', nama, '\nAlamat : RT-', w.rt, ', RW-', w.rw, '', w.dusun) AS info_pilihan_penduduk,
			w.rt, w.rw, w.dusun, u.sex FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id WHERE u.status_dasar = ?";

        return $this->db->query($sql, [$status_dasar])->result_array();
    }

    public function get_cluster($id_cluster = 0)
    {
        $sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE id = {$id_cluster} ";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    public function list_dokumen($id = '')
    {
        $sql   = 'SELECT * FROM dokumen_hidup WHERE id_pend = ? AND deleted = 0';
        $query = $this->db->query($sql, $id);
        $data  = null;
        if ($query) {
            $data = $query->result_array();
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']     = $i + 1;
            $data[$i]['hidden'] = false;

            // jika dokumen berelasi dengan dokumen kepala kk
            if (isset($data[$i]['id_parent'])) {
                $data[$i]['hidden'] = true;
            }
        }

        return $data;
    }

    public function list_kelompok($id = '')
    {
        $sql = 'SELECT k.nama, m.kelompok AS kategori
			FROM kelompok_anggota a
			LEFT JOIN kelompok k ON a.id_kelompok = k.id
			LEFT JOIN kelompok_master m ON k.id_master = m.id
			WHERE a.id_penduduk = ? ';
        $query = $this->db->query($sql, $id);
        $data  = null;
        if ($query) {
            $data = $query->result_array();
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    public function get_dokumen($id = 0)
    {
        $sql   = 'SELECT * FROM dokumen WHERE id = ?';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    public function is_wajib_ktp($data)
    {
        // Wajib KTP = sudah umur 17 atau pernah kawin
        $umur = umur($data['tanggallahir']);
        if ($umur === null) {
            return null;
        }

        return ($umur > 16) || (! empty($data['status_kawin']) && $data['status_kawin'] != 1);
    }

    public function get_suku()
    {
        $suku = [];
        // ref pendduduk
        $suku['ref'] = $this->db->select('suku')
            ->order_by('suku')
            ->get('ref_penduduk_suku')
            ->result_array();

        // dari penduduk
        $suku['penduduk'] = $this->db
            ->distinct()
            ->select('suku')
            ->where('suku IS NOT NULL')
            ->where('suku <>', '')
            ->order_by('suku')
            ->get('tweb_penduduk')->result_array();

        return $suku;
    }

    public function nik_sementara()
    {
        $digit = $this->db
            ->select('RIGHT(nik, 5) as digit')
            ->order_by('RIGHT(nik, 5) DESC')
            ->like('nik', '0', 'after')
            ->where('nik !=', '0')
            ->get('tweb_penduduk')
            ->row()->digit ?? 0;

        // NIK Sementara menggunakan format 0[kode-desa][nomor-urut]
        $desa = $this->config_model->get_data();

        return '0' . $desa['kode_desa'] . sprintf('%05d', $digit + 1);
    }

    public function cekTagIdCard($cek = null, $kecuali = null)
    {
        // Cek duplikasi Tag ID Card
        if ($kecuali) {
            $this->db->where('id !=', $kecuali);
        }

        $tag_id_card = $this->db->select('tag_id_card')->get_where('tweb_penduduk', ['tag_id_card !=' => null])->result_array();

        return (bool) (in_array($cek, array_column($tag_id_card, 'tag_id_card')));
    }
}
