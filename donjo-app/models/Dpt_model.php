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

use App\Models\Pemilihan;

class Dpt_model extends Penduduk_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function tanggal_pemilihan()
    {
        if ($tanggal_pemilihan = $this->input->post('tanggal_pemilihan')) {
            $_SESSION['tanggal_pemilihan'] = $tanggal_pemilihan;
        } elseif (! isset($_SESSION['tanggal_pemilihan'])) {
            $tanggal_pemilihan             = Pemilihan::tanggalPemilihan();
            $_SESSION['tanggal_pemilihan'] = $tanggal_pemilihan ?: date('d-m-Y');
        }

        return $_SESSION['tanggal_pemilihan'];
    }

    /**
     * Syarat calon pemilih:
     *
     * 1. Status dasar = HIDUP
     * 2. Status penduduk = TETAP
     * 3. Warganegara = WNI
     * 4. Umur >= 17 tahun pada tanggal pemilihan ATAU sudah/pernah kawin (status kawin = KAWIN, CERAI HIDUP atau CERAI MATI)
     * 5. Pekerjaan bukan TNI atau POLRI
     */
    private function syarat_dpt_sql(): void
    {
        $tanggal_pemilihan = $this->tanggal_pemilihan();
        $this->db
            ->where('u.status_dasar = 1 AND u.status = 1 AND u.warganegara_id = 1')
            ->where("(((SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(STR_TO_DATE('{$tanggal_pemilihan}','%d-%m-%Y'))-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= 17) OR u.status_kawin IN (2,3,4))")
            ->where("u.pekerjaan_id NOT IN ('6', '7')");
    }

    private function cacatx_sql(): void
    {
        $kf = $this->session->cacatx;
        if (isset($kf)) {
            $this->db->where("u.cacat_id <> {$kf} AND u.cacat_id is not null and u.cacat_id<>''");
        }
    }

    private function menahunx_sql(): void
    {
        $kf = $this->session->menahunx;
        if (isset($_kf)) {
            $this->db->where("u.sakit_menahun_id <> {$kf} and u.sakit_menahun_id is not null and u.sakit_menahun_id<>'0'");
        }
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        $this->config_id('u')
            ->from('tweb_penduduk u')
            ->join('tweb_keluarga d', 'u.id_kk = d.id', 'left')
            ->join('tweb_wil_clusterdesa a', 'd.id_cluster = a.id', 'left')
            ->join('tweb_wil_clusterdesa a2', 'u.id_cluster = a2.id', 'left')
            // Ambil log yg terakhir saja
            ->join(
                '
                (
                    SELECT    MAX(id) max_id, id_pend
                    FROM      log_penduduk
                    GROUP BY  id_pend
                ) log_max',
                'log_max.id_pend = u.id',
                'left'
            )
            ->join('log_penduduk log', 'log_max.max_id = log.id', 'left');

        $this->syarat_dpt_sql();
        $this->search_sql();
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();

        $kolom_kode = [
            ['filter', 'u.status'], // Status : Hidup, Mati, Dll -> Load data awal (filtering combobox)
            ['status_penduduk', 'u.status'], // Status : Hidup, Mati, Dll -> Hanya u/ Pencarian Spesifik
            ['status_dasar', 'u.status_dasar'], // Kode 6
            ['sex', 'u.sex'], // Kode 4
            ['cacat', 'cacat_id'],
            ['cara_kb_id', 'cara_kb_id'],
            ['menahun', 'sakit_menahun_id'],
            ['status', 'status_kawin'],
            ['pendidikan_kk_id', 'pendidikan_kk_id'],
            ['pendidikan_sedang_id', 'pendidikan_sedang_id'],
            ['status_penduduk', 'status'],
            ['pekerjaan_id', 'pekerjaan_id'],
            ['agama', 'agama_id'],
            ['warganegara', 'warganegara_id'],
            ['golongan_darah', 'golongan_darah_id'],
        ];

        foreach ($kolom_kode as $kolom) {
            $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
        }

        $this->cacatx_sql();
        $this->akta_kelahiran_sql();
        $this->menahunx_sql();
        $this->umur_min_sql();
        $this->umur_max_sql();
        $this->umur_sql();
        $this->hamil_sql();
        $this->tag_id_card_sql();
    }

    private function order_by_list($order_by)
    {
        //Ordering SQL
        switch ($order_by) {
            case 1: $this->db->order_by('u.nik');
                break;

            case 2: $this->db->order_by('u.nik DESC');
                break;

            case 3: $this->db->order_by('u.nama');
                break;

            case 4: $this->db->order_by('u.nama DESC');
                break;

            case 5: $this->db->order_by('d.no_kk');
                break;

            case 6: $this->db->order_by('d.no_kk DESC');
                break;

            case 7: $this->db->order_by('umur');
                break;

            case 8: $this->db->order_by('umur DESC');
                break;

                // Untuk Log Penduduk
            case 9: $this->db->order_by('log.tgl_peristiwa');
                break;

            case 10: $this->db->order_by('log.tgl_peristiwa DESC');
                break;

            default: break;
        }
    }

    // $limit = 0 mengambil semua
    public function list_data($o = 0, $page = 1)
    {
        $tanggal_pemilihan = $this->tanggal_pemilihan();

        //Main Query
        $this->list_data_sql();
        $this->db->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur");
        $this->db->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(STR_TO_DATE('{$tanggal_pemilihan}','%d-%m-%Y'))-TO_DAYS(`tanggallahir`)), '%Y')+0) AS umur_pada_pemilihan");
        $this->order_by_list($o);

        //Paging SQL
        if ($page > 0 && $this->session->per_page > 0) {
            $jumlah_pilahan = $this->db->count_all_results('', false);
            $paging         = $this->paginasi($page, $jumlah_pilahan);
            $this->db->limit($paging->per_page, $paging->offset);
        }
        $query_dasar = $this->db->select('u.*')->get_compiled_select();

        $this->db->distinct()
            ->select('u.id,u.nik,u.tag_id_card,u.tanggallahir,u.tempatlahir,u.status,u.status_dasar,u.id_kk,u.nama,u.nama_ayah,u.nama_ibu,a.dusun,a.rw,a.rt,d.alamat,d.no_kk AS no_kk')
            ->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur")
            ->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(STR_TO_DATE('{$tanggal_pemilihan}','%d-%m-%Y'))-TO_DAYS(`tanggallahir`)), '%Y')+0) AS umur_pada_pemilihan")
            ->select('x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan');

        $this->db->from("#({$query_dasar}) AS u#");
        $this->lookup_ref_penduduk();
        $this->order_by_list($o);
        $sql = str_replace(['(#', '#)'], '', $this->db->get_compiled_select());

        $data = $this->db->query($sql)->result_array();

        //Formating Output
        $j       = $paging->offset ?? $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            // Ubah alamat penduduk lepas
            if (! $data[$i]['id_kk'] || $data[$i]['id_kk'] == 0) {
                $penduduk = $this->config_id('p')
                    ->select('p.id_cluster, p.alamat_sekarang, c.dusun, c.rw, c.rt')
                    ->from('tweb_penduduk p')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
                    ->where('p.id', $data[$i]['id'])
                    ->get()
                    ->row_array();

                $data[$i]['alamat'] = $penduduk['alamat_sekarang'];
                $data[$i]['dusun']  = $penduduk['dusun'];
                $data[$i]['rw']     = $penduduk['rw'];
                $data[$i]['rt']     = $penduduk['rt'];
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
            ->join('tweb_sakit_menahun j', 'u.sakit_menahun_id = j.id', 'left')
            ->join('log_penduduk log', 'u.id = log.id_pend', 'left');
    }

    public function statistik_wilayah()
    {
        $this->config_id('u')
            ->select([
                'dusun', 'rw', "count('*') as jumlah_warga",
                'SUM(CASE WHEN sex = 1 THEN 1 ELSE 0 END) as jumlah_warga_l',
                'SUM(CASE WHEN sex = 2 THEN 1 ELSE 0 END) as jumlah_warga_p',
            ])
            ->from('tweb_penduduk u')
            ->join('tweb_wil_clusterdesa w', 'u.id_cluster = w.id', 'left');

        $this->syarat_dpt_sql();
        $this->db->group_by(['dusun', 'rw']);

        $data = $this->db->get()->result_array();
        //Formating Output
        $counter = count($data);

        //Formating Output
        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    public function statistik_total()
    {
        $this->config_id('u')
            ->select([
                "count('*') as total_warga",
                'SUM(CASE WHEN sex = 1 THEN 1 ELSE 0 END) as total_warga_l',
                'SUM(CASE WHEN sex = 2 THEN 1 ELSE 0 END) as total_warga_p',
            ])
            ->from('tweb_penduduk u');

        $this->syarat_dpt_sql();

        return $this->db->get()->row_array();
    }
}
