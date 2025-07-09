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

use App\Enums\SHDKEnum;
use App\Models\LogPenduduk;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\Penduduk;

class Surat_model extends MY_Model
{
    protected $awalan_qr = '89504e470d0a1a0a0000000d4948445200000084000000840802000000de';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penomoran_surat_model', 'url_shortener_model']);
    }

    private function list_penduduk_ajax_sql($cari = '', $filter = []): void
    {
        $this->config_id('u')
            ->from('tweb_penduduk u')
            ->join('tweb_wil_clusterdesa w', 'u.id_cluster = w.id', 'left');

        if ($filter['sex']) {
            $this->db->where('sex', $filter['sex']);
        }

        if ((is_array($filter['status_dasar']) && $filter['status_dasar'])) {
            $this->db->where_in('status_dasar', $filter['status_dasar']);
        }

        if ((is_array($filter['kk_level']) && $filter['kk_level'])) {
            $this->db->where_in('kk_level', $filter['kk_level']);
        }

        // batasi ambil data dari keluarga yang sama saja
        if ($filter['hubungan']) {
            $this->db->where('id_kk in (select id_kk from tweb_penduduk where id = ' . $filter['hubungan'] . ') and u.id != ' . $filter['hubungan']);
        }

        // ambil data selain yang dikecualikan
        if ($filter['kecuali']) {
            $this->db->where_not_in('u.id', $filter['kecuali']);
        }

        if ($filter['bersurat']) {
            $this->db->join('log_surat h', 'u.id = h.id_pend');
        }
        if ($cari) {
            $this->db
                ->group_start()
                ->like('nik', $cari)
                ->or_like('nama', $cari)
                ->or_like('tag_id_card', $cari)
                ->group_end();
        }
    }

    // Mengambil semua data penduduk untuk pilihan di form surat
    public function list_penduduk_ajax($cari = '', $filter = [], $page = 1)
    {
        // Hitung jumlah total
        $this->list_penduduk_ajax_sql($cari, $filter);
        $jml = $this->db
            ->select('count(u.id) as jml')
            ->get()
            ->row()
            ->jml;

        // Ambil penduduk sebatas paginasi
        $resultCount = 25;
        $offset      = ($page - 1) * $resultCount;

        $this->list_penduduk_ajax_sql($cari, $filter);
        $this->db
            ->distinct()
            ->select('u.id, nik, u.tag_id_card, nama, w.dusun, w.rw, w.rt, u.sex')
            ->limit($resultCount, $offset);
        $data = $this->db->get()->result_array();

        //Format untuk daftar pilihan select2 di form surat
        $penduduk = [];

        foreach ($data as $row) {
            $nama                  = $row['nama'];
            $alamat                = addslashes("Alamat: RT-{$row['rt']}, RW-{$row['rw']} {$row['dusun']}");
            $tag_id                = empty($row['tag_id_card']) ? '' : '/' . $row['tag_id_card'];
            $info_pilihan_penduduk = "NIK/Tag ID Card : {$row['nik']}{$tag_id} - {$nama}\n{$alamat}";
            $penduduk[]            = ['id' => $row['id'], 'text' => $info_pilihan_penduduk];
        }

        $endCount  = $offset + $resultCount;
        $morePages = $endCount < $jml;

        return [
            'results'    => $penduduk,
            'pagination' => [
                'more' => $morePages,
            ],
        ];
    }

    // Mengambil data penduduk yang telah bersurat untuk pilihan di form arsip layanan (rekam surat perseorangan)
    public function list_penduduk_bersurat_ajax($cari = '', $page = '1')
    {
        $filter = ['bersurat' => true];

        return $this->list_penduduk_ajax($cari, $filter, $page);
    }

    /*
     * Mengambil semua data penduduk untuk pilihan di form surat
     * Digunakan juga oleh method lain dengan tambahan kriteria penduduk
     */
    public function list_penduduk()
    {
        $data = $this->config_id('u')
            ->select('u.id, nik, u.tag_id_card, nama, w.dusun, w.rw, w.rt, u.sex')
            ->from('tweb_penduduk u')
            ->join('tweb_wil_clusterdesa w', 'u.id_cluster = w.id', 'left')
            ->where('status_dasar', '1')
            ->get()
            ->result_array();

        //Formating Output untuk nilai variabel di javascript, di form surat
        foreach ($data as $i => $row) {
            $data[$i]['nama']                  = addslashes($row['nama']);
            $data[$i]['alamat']                = addslashes("Alamat: RT-{$row['rt']}, RW-{$row['rw']} {$row['dusun']}");
            $data[$i]['info_pilihan_penduduk'] = "NIK/Tag ID Card : {$data[$i]['nik']}/{$data[$i]['tag_id_card']} - {$data[$i]['nama']}\n{$data[$i]['alamat']}";
        }

        return $data;
    }

    public function get_alamat_wilayah($data)
    {
        $alamat_wilayah = "{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . set_ucwords(setting('sebutan_dusun')) . ' ' . set_ucwords($data['dusun']);

        return trim($alamat_wilayah);
    }

    public function get_penduduk($id = 0)
    {
        $sql = "SELECT u.id AS id, u.nama AS nama, u.nik, u.sex as sex_id, x.nama AS sex, u.id_kk AS id_kk, u.tempatlahir AS tempatlahir, u.tanggallahir AS tanggallahir, u.no_kk_sebelumnya, s.nama as status, u.waktu_lahir, u.tempat_dilahirkan, u.jenis_kelahiran, u.kelahiran_anak_ke, u.penolong_kelahiran, u.berat_lahir, u.panjang_lahir, u.id_cluster,
		(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
		w.nama AS status_kawin, f.nama AS warganegara,a.nama AS agama, d.nama AS pendidikan, j.nama AS pekerjaan, u.nik AS nik, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.no_kk AS no_kk, k.alamat,
		(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
		from tweb_penduduk u
		left join tweb_penduduk_sex x on u.sex = x.id
		left join tweb_penduduk_kawin w on u.status_kawin = w.id
		left join tweb_penduduk_agama a on u.agama_id = a.id
		left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
		left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
		left join tweb_wil_clusterdesa c on u.id_cluster = c.id
		left join tweb_keluarga k on u.id_kk = k.id
		left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
		left join tweb_penduduk_status s on u.status = s.id
		WHERE u.id = ? AND u.config_id = {$this->config_id}";
        $query                  = $this->db->query($sql, $id);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);

        return $data;
    }

    public function pengikut()
    {
        $id_cb = $_POST['id_cb'];
        $outp  = '';
        if (count($id_cb) > 0) {
            foreach ($id_cb as $id) {
                //$id = '''."$id".''';
                $outp = $outp . $id . ',';
            }
            $outp .= '7070';

            $sql = "SELECT u.id AS id, u.nama AS nama, x.nama AS sex, u.tempatlahir AS tempatlahir, u.tanggallahir AS tanggallahir,
			(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
			w.nama AS status_kawin, f.nama AS warganegara, a.nama AS agama, d.nama AS pendidikan, h.nama AS hubungan, j.nama AS pekerjaan, u.nik AS nik, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.no_kk AS no_kk,
			(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_sex x on u.sex = x.id
			LEFT JOIN tweb_penduduk_kawin w on u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_hubungan h on u.kk_level = h.id
			LEFT JOIN tweb_penduduk_agama a on u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
			LEFT JOIN tweb_wil_clusterdesa c on u.id_cluster = c.id
			LEFT JOIN tweb_keluarga k on u.id_kk = k.id
			LEFT JOIN tweb_penduduk_warganegara f on u.warganegara_id = f.id
			WHERE u.nik IN({$outp}) AND u.config_id = {$this->config_id}";
            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        return $data;
    }

    // TODO: Ganti cara mengambil data kk, pisahkan dalam variabel lain
    public function get_data_surat($id = 0)
    {
        $sql = "SELECT u.*,
            case when substring(u.nik, 1, 1) = 0 then 0 ELSE u.nik END as nik,
            case when substring(k.no_kk, 1, 1) = 0 then 0 ELSE k.no_kk END as no_kk,
            g.nama AS gol_darah, x.nama AS sex, u.sex as sex_id,
            (select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
            w.nama AS status_kawin, u.status_kawin as status_kawin_id, f.nama AS warganegara, a.nama AS agama, d.nama AS pendidikan, h.nama AS hubungan, j.nama AS pekerjaan, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.alamat, m.nama as cacat,
            (select tweb_penduduk.nik from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS nik_kk,
            (select tweb_penduduk.telepon from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS telepon_kk,
            (select tweb_penduduk.email from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS email_kk,
            (select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk,
            r.bdt
            from tweb_penduduk u
            left join tweb_penduduk_sex x on u.sex = x.id
            left join tweb_penduduk_kawin w on u.status_kawin = w.id
            left join tweb_penduduk_hubungan h on u.kk_level = h.id
            left join tweb_penduduk_agama a on u.agama_id = a.id
            left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
            left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
            left join tweb_cacat m on u.cacat_id = m.id
            left join tweb_wil_clusterdesa c on u.id_cluster = c.id
            left join tweb_keluarga k on u.id_kk = k.id
            left join tweb_rtm r on u.id_rtm = r.no_kk # TODO : ganti nilai tweb_penduduk id_rtm = id pd tweb_rtm dan ganti kolom no_kk menjadi no_rtm
            left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
            left join tweb_golongan_darah g on u.golongan_darah_id = g.id
            WHERE u.id = ? AND u.config_id = {$this->config_id}";
        $query                  = $this->db->query($sql, $id);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);
        $this->format_data_surat($data);

        return $data;
    }

    public function format_data_surat(&$data): void
    {
        // Asumsi kolom "alamat_wilayah" sdh dalam format ucwords
        $kolomUpper = ['tanggallahir', 'tempatlahir', 'dusun', 'pekerjaan', 'gol_darah', 'agama', 'sex',
            'status_kawin', 'pendidikan', 'hubungan', 'nama_ayah', 'nama_ibu', 'alamat', 'alamat_sebelumnya',
            'cacat', ];

        foreach ($kolomUpper as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = set_ucwords($data[$kolom]);
            }
        }
        if (isset($data['pendidikan'])) {
            $data['pendidikan'] = kasus_lain('pendidikan', $data['pendidikan']);
        }

        if (isset($data['pekerjaan'])) {
            $data['pekerjaan'] = kasus_lain('pekerjaan', $data['pekerjaan']);
        }
    }

    public function get_pamong($id = 0)
    {
        return $this->config_id()
            ->where('pamong_id', $id)
            ->get('tweb_desa_pamong')
            ->row_array();
    }

    public function get_data_pribadi($id = 0)
    {
        $sql = "SELECT u.*, h.nama as hubungan, p.nama as kepala_kk, g.nama as gol_darah, d.nama as pendidikan, s.nama as status, r.nama as pek, m.nama as men, w.nama as wn, n.nama as agama, c.rw, c.rt, c.dusun, (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur, sex.nama as sex, k.alamat,
        CONCAT('NIK: ', u.nik, ' - ', u.nama, '\nAlamat : RT-', c.rt, ', RW-', c.rw, ' ', c.dusun) AS info_pilihan_penduduk
            FROM tweb_penduduk u
            left join tweb_penduduk_hubungan h on u.kk_level = h.id
            left join tweb_keluarga k on u.id_kk = k.id
            left join tweb_penduduk p on k.nik_kepala = p.id
            left join tweb_golongan_darah g on u.golongan_darah_id = g.id
            left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
            left join tweb_penduduk_pekerjaan r on u.pekerjaan_id = r.id
            left join tweb_cacat m on u.cacat_id = m.id
            left join tweb_wil_clusterdesa c on u.id_cluster = c.id
            left join tweb_penduduk_warganegara w on u.warganegara_id = w.id
            left join tweb_penduduk_agama n on u.agama_id = n.id
            LEFT JOIN tweb_penduduk_sex sex ON u.sex = sex.id
            left join tweb_penduduk_status s on u.status = s.id
            WHERE u.id = ? AND u.config_id = {$this->config_id}";
        $query                  = $this->db->query($sql, $id);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);
        $this->format_data_surat($data);

        return $data;
    }

    public function get_data_kk($id = 0)
    {
        return $this->config_id()
            ->select('b.nik_kepala, b.no_kk,b.id AS id_kk, c.nama as kepala_kk, d.*')
            ->from('tweb_penduduk a')
            ->join('tweb_keluarga b', 'a.id_kk = b.id')
            ->join('tweb_penduduk c', 'b.nik_kepala = c.id')
            ->join('tweb_wil_clusterdesa d', 'c.id_cluster = d.id')
            ->where('a.id', $id)
            ->get()
            ->row_array();
    }

    public function get_data_penduduk($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('tweb_penduduk')
            ->row_array();
    }

    public function get_data_istri($id = 0)
    {
        $id_kk    = Penduduk::where('id', $id)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->first('id_kk')->id_kk;
        $penduduk = Penduduk::where('id_kk', $id_kk)->where('kk_level', SHDKEnum::ISTRI)->first('id')->id;
        $data     = Penduduk::where('id', $penduduk)->first('id');

        $istri_id = $data['id'];
        if ($istri_id) {
            return $this->get_data_pribadi($istri_id);
        }
    }

    public function get_data_suami($id = 0)
    {
        $id_kk    = Penduduk::where('id', $id)->where('kk_level', SHDKEnum::ISTRI)->first('id_kk')->id_kk;
        $penduduk = Penduduk::where('id_kk', $id_kk)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->first('id')->id;
        $data     = Penduduk::where('id', $penduduk)->first('id');

        $suami_id = $data['id'];
        if ($suami_id) {
            return $this->get_data_pribadi($suami_id);
        }
    }

    public function get_data_suami_atau_istri($individu = [])
    {
        if (strtolower($individu['sex']) == 'laki-laki') {
            return $this->get_data_istri($individu['id']);
        }

        return $this->get_data_suami($individu['id']);
    }

    public function get_data_ayah($id = 0)
    {
        $penduduk = $this->get_data_penduduk($id);
        //cari kepala keluarga pria kalau penduduknya seorang anak dalam keluarga
        if ($penduduk['kk_level'] == 4) {
            $id_kk = $penduduk['id_kk'];
            $data  = $this->config_id('u')
                ->select('u.id')
                ->from('tweb_penduduk u')
                ->where('u.id_kk', $id_kk)
                ->where('u.sex', 1)
                ->group_start()
                    // Kepala Keluarga
                ->where('u.kk_level', 1)
                    // Suami dari ibu
                ->or_group_start()
                ->where('u.kk_level', 2)
                ->group_end()
                ->group_end()
                ->limit(1)->get()
                ->row_array();
        }

        // jika tidak ada Cari berdasarkan ayah_nik
        if (empty($data['id']) && ! empty($penduduk['ayah_nik'])) {
            $data = $this->config_id('u')
                ->select('u.id')
                ->from('tweb_penduduk u')
                ->where('u.nik', $penduduk['ayah_nik'])
                ->limit(1)
                ->get()
                ->row_array();
        }
        if (isset($data['id'])) {
            $ayah_id = $data['id'];

            return $this->get_data_pribadi($ayah_id);
        }

        // Ambil data sebisanya dari data ayah penduduk
        $ayah['nik']  = $penduduk['ayah_nik'];
        $ayah['nama'] = $penduduk['nama_ayah'];

        return $ayah;
    }

    public function get_data_ibu($id = 0)
    {
        $penduduk = $this->get_data_penduduk($id);

        // Cari istri keluarga kalau penduduknya seorang anak dalam keluarga
        // atau kepala keluarga perempuan
        if ($penduduk['kk_level'] == 4) {
            $id_kk = $penduduk['id_kk'];
            $data  = $this->config_id('u')
                ->select('u.id')
                ->from('tweb_penduduk u')
                ->where('u.id_kk', $id_kk)
                ->group_start()
                    // istri
                ->where('u.kk_level', 3)
                    // kepala keluarga perempuan
                ->or_group_start()
                ->where('u.kk_level', 1)
                ->where('u.sex', 2)
                ->group_end()
                ->group_end()
                ->limit(1)
                ->get()
                ->row_array();
        }

        // Cari berdasarkan ibu_nik
        if (empty($data['id']) && ! empty($penduduk['ibu_nik'])) {
            $data = $this->config_id('u')
                ->select('u.id')
                ->from('tweb_penduduk u')
                ->where('nik', $penduduk['ibu_nik'])
                ->limit(1)
                ->get()
                ->row_array();
        }
        if (isset($data['id'])) {
            $ibu_id = $data['id'];

            return $this->get_data_pribadi($ibu_id);
        }

        // Ambil data sebisanya dari data ibu penduduk
        $ibu['nik']  = $penduduk['ibu_nik'];
        $ibu['nama'] = $penduduk['nama_ibu'];

        return $ibu;
    }

    public function atas_nama($data, $buffer = null)
    {
        //Data penandatangan
        $input     = $data['input'];
        $nama_desa = identitas()->nama_desa;

        //Data penandatangan
        $kades = Pamong::kepalaDesa()->first();

        $ttd         = $input['pilih_atas_nama'];
        $atas_nama   = $kades->pamong_jabatan . ' ' . $nama_desa;
        $jabatan     = $kades->pamong_jabatan;
        $nama_pamong = $kades->pamong_nama;
        $nip_pamong  = $kades->pamong_nip;
        $niap_pamong = $kades->pamong_niap;

        $sekdes = Pamong::ttd('a.n')->first();
        if (preg_match('/a.n/i', $ttd)) {
            $atas_nama   = 'a.n ' . $atas_nama . ' \par ' . $sekdes->pamong_jabatan;
            $jabatan     = $sekdes->pamong_jabatan;
            $nama_pamong = $sekdes->pamong_nama;
            $nip_pamong  = $sekdes->pamong_nip;
            $niap_pamong = $sekdes->pamong_niap;
        }

        if (preg_match('/u.b/i', $ttd)) {
            $pamong      = Pamong::ttd('u.b')->find($input['pamong_id']);
            $atas_nama   = 'a.n ' . $atas_nama . ' \par ' . $sekdes->pamong_jabatan . ' \par  u.b  \par ' . $pamong->jabatan->nama;
            $jabatan     = $pamong->pamong_jabatan;
            $nama_pamong = $pamong->pamong_nama;
            $nip_pamong  = $pamong->pamong_nip;
            $niap_pamong = $pamong->pamong_niap;
        }

        // Untuk lampiran
        if (null === $buffer) {
            return [
                'atas_nama' => str_replace('\par', '<br>', $atas_nama),
                'jabatan'   => $jabatan,
                'nama'      => $nama_pamong,
                'nip'       => $nip_pamong,
                'niap'      => $niap_pamong,
            ];
        }

        $buffer = str_replace('[penandatangan]', $atas_nama, $buffer);
        $buffer = str_replace('[jabatan]', "{$jabatan}", $buffer);
        $buffer = str_replace('[nama_pamong]', $nama_pamong, $buffer);

        if (strlen($nip_pamong) > 10) {
            $sebutan_nip_desa = 'NIP';
            $nip              = $nip_pamong;
            $pamong_nip       = $sebutan_nip_desa . ' : ' . $nip;
        } else {
            $sebutan_nip_desa = setting('sebutan_nip_desa');
            if (! empty($niap_pamong)) {
                $nip        = $niap_pamong;
                $pamong_nip = $sebutan_nip_desa . ' : ' . $niap_pamong;
            } else {
                $pamong_nip = '';
            }
        }

        $buffer = str_replace('[sebutan_nip_desa]', $sebutan_nip_desa, $buffer);
        $buffer = str_replace('[pamong_nip]', $nip, $buffer);

        return str_replace('[form_pamong_nip]', $pamong_nip, $buffer);
    }

    // Kode isian nomor_surat bisa ditentukan panjangnya, diisi dengan '0' di sebelah kiri
    // Misalnya [nomor_surat, 3] akan menghasilkan seperti '012'
    public function substitusi_nomor_surat($nomor, &$buffer): void
    {
        $buffer = str_replace('[nomor_surat]', "{$nomor}", $buffer);
        if (preg_match_all('/\[nomor_surat,\s*\d+\]/', $buffer, $matches)) {
            foreach ($matches[0] as $match) {
                $parts         = explode(',', $match);
                $panjang       = (int) trim(rtrim($parts[1], ']'));
                $nomor_panjang = str_pad("{$nomor}", $panjang, '0', STR_PAD_LEFT);
                $buffer        = str_replace($match, $nomor_panjang, $buffer);
            }
        }
    }

    public function buatQrCode($nama_surat)
    {
        $log_surat = LogSurat::select(['id', 'urls_id'])->where('nama_surat', $nama_surat)->first();

        //redirect link tidak ke path aslinya dan encode ID surat
        $urls = $this->url_shortener_model->url_pendek($log_surat);

        $qrCode = [
            'isiqr'   => $urls['isiqr'],
            'urls_id' => $urls['urls_id'],
            'logoqr'  => gambar_desa($this->header['desa']['logo'], false, true),
            'sizeqr'  => 6,
            'foreqr'  => '#000000',
        ];

        $qrCode['viewqr'] = qrcode_generate($qrCode);

        return $qrCode;
    }

    public function getQrCode($id)
    {
        //redirect link tidak ke path aslinya dan encode ID surat
        $urls = $this->url_shortener_model->getUrlById($id);

        $qrCode = [
            'isiqr'  => site_url('v/' . $urls->alias),
            'logoqr' => gambar_desa($this->header['desa']['logo'], false, true),
            'sizeqr' => 6,
            'foreqr' => '#000000',
        ];

        $qrCode['viewqr'] = qrcode_generate($qrCode, true);

        return $qrCode;
    }

    public function get_data_mati($id = 0)
    {
        return LogPenduduk::where('id_pend', $id)->where('kode_peristiwa', '2')->first();
    }
}
