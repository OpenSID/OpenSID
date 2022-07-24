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

use App\Libraries\DateConv;
use App\Models\LogSurat;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class Surat_model extends CI_Model
{
    protected $awalan_qr = '89504e470d0a1a0a0000000d4948445200000084000000840802000000de';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model', 'penomoran_surat_model', 'url_shortener_model']);
    }

    public function list_surat()
    {
        $sql   = 'SELECT * FROM tweb_surat_format WHERE kunci = 0';
        $query = $this->db->query($sql);
        $data  = $query->result_array();
        //Formating Output
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['nama'] = ($i + 1) . ') ' . $data[$i]['nama'];
        }

        return $data;
    }

    public function list_surat2()
    {
        return $this->db
            ->where('kunci', 0)
            ->get('tweb_surat_format')
            ->result_array();
    }

    public function list_surat_mandiri()
    {
        return $this->db
            ->where('kunci', 0)
            ->where('mandiri', 1)
            ->get('tweb_surat_format')
            ->result_array();
    }

    public function list_surat_fav()
    {
        return $this->db
            ->where('kunci', 0)
            ->where('favorit', 1)
            ->get('tweb_surat_format')
            ->result_array();
    }

    private function list_penduduk_ajax_sql($cari = '', $filter = [])
    {
        $this->db
            ->from('tweb_penduduk u')
            ->join('tweb_wil_clusterdesa w', 'u.id_cluster = w.id', 'left')
            ->where('status_dasar', 1);
        if ($filter['sex']) {
            $this->db->where('sex', $filter['sex']);
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
        $jml = $this->db->select('count(u.id) as jml')
            ->get()->row()->jml;

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
        $this->db
            ->select('u.id, nik, u.tag_id_card, nama, w.dusun, w.rw, w.rt, u.sex')
            ->from('tweb_penduduk u')
            ->join('tweb_wil_clusterdesa w', 'u.id_cluster = w.id', 'left')
            ->where('status_dasar', '1');
        $data = $this->db->get()->result_array();

        //Formating Output untuk nilai variabel di javascript, di form surat
        foreach ($data as $i => $row) {
            $data[$i]['nama']                  = addslashes($row['nama']);
            $data[$i]['alamat']                = addslashes("Alamat: RT-{$row['rt']}, RW-{$row['rw']} {$row['dusun']}");
            $data[$i]['info_pilihan_penduduk'] = "NIK/Tag ID Card : {$data[$i]['nik']}/{$data[$i]['tag_id_card']} - {$data[$i]['nama']}\n{$data[$i]['alamat']}";
        }

        return $data;
    }

    public function list_kepala_keluarga()
    {
        // Setting kriteria, gunakan list_penduduk untuk mengambil data
        $this->db->where('kk_level', '1');

        return $this->list_penduduk();
    }

    public function list_penduduk_perempuan()
    {
        // Setting kriteria, gunakan list_penduduk untuk mengambil data
        $this->db->where('status = 1 AND sex = 2');

        return $this->list_penduduk();
    }

    public function list_penduduk_laki()
    {
        // Setting kriteria, gunakan list_penduduk untuk mengambil data
        $this->db->where('status = 1 AND sex = 1');

        return $this->list_penduduk();
    }

    public function list_anak($id)
    {
        // Setting kriteria, gunakan list_penduduk untuk mengambil data
        $escaped_id = $this->db->escape($id);
        $this->db->where('
			id_kk = (SELECT id_kk FROM tweb_penduduk WHERE id=' . $escaped_id . 'AND (kk_level=1 OR kk_level=2 OR kk_level=3))
			AND kk_level = 4');

        return $this->list_penduduk();
    }

    public function get_alamat_wilayah($data)
    {
        $alamat_wilayah = "{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . set_ucwords($this->setting->sebutan_dusun) . ' ' . set_ucwords($data['dusun']);

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
		WHERE u.id = ?";
        $query                  = $this->db->query($sql, $id);
        $data                   = $query->row_array();
        $data['nama']           = $data['nama'];
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);

        return $data;
    }

    public function pengikut()
    {
        $id_cb = $_POST['id_cb'];
        $outp  = '';
        if (count($id_cb)) {
            foreach ($id_cb as $id) {
                //$id = '''."$id".''';
                $outp = $outp . $id . ',';
            }
            $outp = $outp . '7070';

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
			WHERE u.nik IN({$outp})";
            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        return $data;
    }

    // TODO: ganti menggunakan pamong_model->list_data()
    public function list_pamong()
    {
        $sql = 'SELECT u.*, p.nama as nama
			FROM tweb_desa_pamong u
			LEFT JOIN tweb_penduduk p ON u.id_pend = p.id
			WHERE pamong_status = 1';
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        for ($i = 0; $i < count($data); $i++) {
            if (! empty($data[$i]['id_pend'])) {
                // Dari database penduduk
                $data[$i]['pamong_nama'] = $data[$i]['nama'];
            }
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

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
			WHERE u.id = ?";
        $query                  = $this->db->query($sql, $id);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);
        $this->format_data_surat($data);

        return $data;
    }

    public function format_data_surat(&$data)
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
            $namaPendidikan = ['Tk' => 'TK', 'Sd' => 'SD', 'Sltp' => 'SLTP', 'Slta' => 'SLTA', 'Slb' => 'SLB', 'Iii/s' => 'III/S', 'Iii' => 'III', 'Ii' => 'II', 'Iv' => 'IV'];

            foreach ($namaPendidikan as $key => $value) {
                $data['pendidikan'] = str_replace($key, $value, $data['pendidikan']);
            }
        }
        if (isset($data['pekerjaan'])) {
            $data['pekerjaan'] = $this->penduduk_model->normalkanPekerjaan($data['pekerjaan']);
        }
    }

    public function get_pamong($id = 0)
    {
        $sql   = 'SELECT u.* FROM tweb_desa_pamong u WHERE pamong_id = ?';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
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
			WHERE u.id = ?";
        $query                  = $this->db->query($sql, $id);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = $this->get_alamat_wilayah($data);
        $this->format_data_surat($data);

        return $data;
    }

    public function get_data_kk($id = 0)
    {
        $sql = 'SELECT b.nik_kepala, b.no_kk,b.id AS id_kk, c.nama as kepala_kk, d.*
			FROM tweb_penduduk a
			LEFT JOIN tweb_keluarga b ON a.id_kk = b.id
			LEFT JOIN tweb_penduduk c ON b.nik_kepala = c.id
			LEFT JOIN tweb_wil_clusterdesa d ON c.id_cluster = d.id
			WHERE a.id = ? ';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    public function get_data_penduduk($id = 0)
    {
        $sql   = 'SELECT u.* FROM tweb_penduduk u WHERE id = ?';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    public function get_data_istri($id = 0)
    {
        $sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.id = (SELECT id FROM tweb_penduduk WHERE id_kk = (SELECT id_kk FROM tweb_penduduk WHERE id = {$id} AND kk_level = 1) AND kk_level = 3 limit 1)";
        $query = $this->db->query($sql);
        $data  = $query->row_array();

        $istri_id = $data['id'];
        if ($istri_id) {
            return $this->get_data_pribadi($istri_id);
        }
    }

    public function get_data_suami($id = 0)
    {
        $sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.id = (SELECT id FROM tweb_penduduk WHERE id_kk = (SELECT id_kk FROM tweb_penduduk WHERE id = {$id} AND kk_level = 3) AND kk_level = 1 limit 1 )";
        $query = $this->db->query($sql);
        $data  = $query->row_array();

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
            $data  = $this->db
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
            $sql = 'SELECT u.id
				FROM tweb_penduduk u
				WHERE u.nik = ? limit 1';
            $query = $this->db->query($sql, $penduduk['ayah_nik']);
            $data  = $query->row_array();
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
            $data  = $this->db
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
                ->limit(1)->get()
                ->row_array();
        }

        // Cari berdasarkan ibu_nik
        if (empty($data['id']) && ! empty($penduduk['ibu_nik'])) {
            $data = $this->db
                ->select('u.id')
                ->from('tweb_penduduk u')
                ->where('nik', $penduduk['ibu_nik'])
                ->limit(1)->get()
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

    public function get_surat($url = '')
    {
        $sql   = 'SELECT * FROM tweb_surat_format WHERE url_surat = ?';
        $query = $this->db->query($sql, $url);
        $data  = $query->row_array();
        // Isi lokasi template surat
        // Pakai surat ubahan desa apabila ada
        $file = SuratExportDesa($url);
        if ($file == '') {
            $data['lokasi_rtf'] = "template-surat/{$url}/";
        } else {
            $data['lokasi_rtf'] = dirname($file) . '/';
        }
        $this->surat = $data;

        return $data;
    }

    public function bersihkan_kode_isian($buffer_in)
    {
        $buffer_out = '';
        $in         = 0;

        while ($in < strlen($buffer_in)) {
            switch ($buffer_in[$in]) {
                case '[':
                    // Ambil kode isian, hilangkan karakter bukan alpha
                    $kode_isian = $buffer_in[$in];
                    $in++;

                    while ($buffer_in[$in] != ']' && $in < strlen($buffer_in)) {
                        $kode_isian .= $buffer_in[$in];
                        $in++;
                    }
                    if ($in < strlen($buffer_in)) {
                        $kode_isian .= $buffer_in[$in];
                        $in++;
                    }
                    // Ganti karakter non-alphanumerik supaya bisa di-cek
                    $kode_isian = preg_replace('/[^a-zA-Z0-9,_\{\}\[\]\-]/', '#', $kode_isian);
                    // Regex ini untuk membersihkan kode isian dari karakter yang dimasukkan oleh Word
                    // Regex ini disusun berdasarkan RTF yang dihasilkan oleh Word 2011 di Mac.
                    // Perlu diverifikasi regex ini berlaku juga untuk RTF yang dihasilkan oleh versi Word lain.
                    $regex      = '/(\\}.?#)|rtlch.?#|cf\\d#|fcs.?#+|afs.?\\d#+|f\\d*?\\d#|fs\\d*?\\d#|af\\d*?\\d#+|ltrch#+|insrsid\\d*?\\d#+|alang\\d+#+|lang\\d+|langfe\\d+|langnp\\d+|langfenp\\d+|b#+|ul#+|hich#+|dbch#+|loch#+|charrsid\\d*?\\d#+|#+/';
                    $kode_isian = preg_replace($regex, '', $kode_isian);
                    $buffer_out .= $kode_isian;
                    break;

                default:
                    // Ambil isi yang bukan bagian dari kode isian
                    $buffer_out .= $buffer_in[$in];
                    $in++;
                    break;
            }
        }

        return $buffer_out;
    }

    private function sisipkan_kop_surat($buffer)
    {
        $kop_surat = file_get_contents('template-surat/raw/kop_surat_auto.rtf');

        return str_replace('[kop_surat]', $kop_surat, $buffer);
    }

    private function sisipkan_logo($nama_logo, $logo_garuda, $buffer)
    {
        $file_logo = FCPATH . (($logo_garuda) ? 'assets/images/garuda.png' : LOKASI_LOGO_DESA . $nama_logo);

        if (! is_file($file_logo)) {
            return $buffer;
        }

        // Akhiran dan awalan agak panjang supaya unik
        $akhiran_logo      = 'e33874670000000049454e44ae426082';
        $awalan_logo       = '89504e470d0a1a0a0000000d4948445200000040000000400806000000aa';
        $akhiran_sementara = 'akhiran_logo';
        $jml_logo          = substr_count($buffer, $akhiran_logo);
        if ($jml_logo <= 0) {
            return $buffer;
        } // tidak ada logo placeholder

        // Ganti logo placeholder dengan logo desa kalau ada, satu per satu
        $logo_bytes = file_get_contents($file_logo);
        $logo_hex   = implode('', unpack('H*', $logo_bytes));

        for ($i = 0; $i < $jml_logo; $i++) {
            // Ganti akhiran logo supaya preg_replace hanya memproses logo yg ditemukan
            // Cari logo berikutnya, kalau ada
            $pos              = strpos($buffer, $akhiran_logo);
            $buffer           = substr_replace($buffer, $akhiran_sementara, $pos, strlen($akhiran_logo));
            $placeholder_logo = '/' . $awalan_logo . '.*' . $akhiran_sementara . '/s';
            // Ganti logo yang ditemukan
            $buffer = preg_replace($placeholder_logo, $logo_hex, $buffer);
        }

        return $buffer;
    }

    private function sisipkan_foto($data, $nama_foto, $buffer)
    {
        $input       = $data['input'];
        $tampil_foto = $input['tampil_foto'];
        if ($tampil_foto) {
            $file_foto = APPPATH . '../' . LOKASI_USER_PICT . $nama_foto;
        } else {
            $file_foto = APPPATH . '../' . LOKASI_SISIPAN_DOKUMEN . $nama_foto;
        }
        if (! is_file($file_foto)) {
            return $buffer;
        }
        $akhiran_foto      = 'afbe45630000000049454e44ae426082';
        $awalan_foto       = '89504e470d0a1a0a0000000d4948445200000080000000800806000000c3';
        $akhiran_sementara = 'akhiran_foto';
        $jml_foto          = substr_count($buffer, $akhiran_foto);
        if ($jml_foto <= 0) {
            return $buffer;
        }

        $foto_bytes = file_get_contents($file_foto);
        $foto_hex   = implode('', unpack('H*', $foto_bytes));

        for ($i = 0; $i < $jml_foto; $i++) {
            $pos              = strpos($buffer, $akhiran_foto);
            $buffer           = substr_replace($buffer, $akhiran_sementara, $pos, strlen($akhiran_foto));
            $placeholder_foto = '/' . $awalan_foto . '.*' . $akhiran_sementara . '/s';
            $buffer           = preg_replace($placeholder_foto, $foto_hex, $buffer);
        }

        return $buffer;
    }

    public function get_data_form($surat)
    {
        $data_form = LOKASI_SURAT_DESA . $surat . '/data_form_' . $surat . '.php';
        if (is_file($data_form)) {
            return $data_form;
        }

        $data_form = "template-surat/{$surat}/data_form_{$surat}.php";
        if (is_file($data_form)) {
            return $data_form;
        }
    }

    public function get_data_rtf($surat)
    {
        $data_rtf = LOKASI_SURAT_DESA . $surat . '/data_rtf_' . $surat . '.php';
        if (is_file($data_rtf)) {
            return $data_rtf;
        }

        $data_rtf = "template-surat/{$surat}/data_rtf_{$surat}.php";
        if (is_file($data_rtf)) {
            return $data_rtf;
        }
    }

    // Untuk surat sistem, cek apakah komponen surat sudah disesuaikan oleh desa
    private function lokasi_komponen($nama_surat, $komponen)
    {
        $lokasi = LOKASI_SURAT_DESA . $nama_surat . '/' . $komponen;
        if ($this->surat['jenis'] == 1 && ! is_file($lokasi)) {
            $lokasi = "template-surat/{$nama_surat}/{$komponen}";
        }

        return $lokasi;
    }

    public function surat_rtf_khusus($url, $input, &$buffer, $config, &$individu, $ayah, $ibu)
    {
        $alamat_desa = ucwords($this->setting->sebutan_desa) . ' ' . $config['nama_desa'] . ', Kecamatan ' . $config['nama_kecamatan'] . ', ' . ucwords($this->setting->sebutan_kabupaten) . ' ' . $config['nama_kabupaten'];
        // Proses surat yang membutuhkan pengambilan data khusus

        $data_rtf = $this->surat_model->get_data_rtf($url);
        if (is_file($data_rtf)) {
            include $data_rtf;
        }
    }

    /* Dipanggil untuk setiap kode isian ditemukan,
       dan diganti dengan kata pengganti yang huruf besar/kecil mengikuti huruf kode isian.
         Berdasarkan contoh di http://stackoverflow.com/questions/19317493/php-preg-replace-case-insensitive-match-with-case-sensitive-replacement

         Huruf pertama dan kedua huruf besar --> ganti dengan huruf besar semua:
                 [SEbutan_desa] ==> KAMPUNG
         Huruf pertama besar dan kedua kecil --> ganti dengan huruf besar pertama saja:
                 [Sebutan_desa] ==> Kampung
         Huruf pertama kecil --> ganti dengan huruf kecil semua:
                 [sebutan_desa] ==> kampung
    */
    public function case_replace($dari, $ke, $str)
    {
        $replacer    = static function ($matches) use ($ke) {
            $matches = array_map(static function ($match) {
                return preg_replace('/[\\[\\]]/', '', $match);
            }, $matches);
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][1])) {
                return strtoupper($ke);
            }
            if (ctype_upper($matches[0][0])) {
                return ucwords($ke);
            }

            return strtolower($ke);
        };
        $dari = str_replace('[', '\\[', $dari);
        $str  = preg_replace_callback('/(' . $dari . ')/i', $replacer, $str);

        return $str;
    }

    private function atas_nama($data)
    {
        //Data penandatangan
        $input  = $data['input'];
        $config = $data['config'];
        $this->load->model('pamong_model');
        $pamong_ttd = $this->pamong_model->get_ttd();
        $atas_nama  = '';
        if (! empty($input['pilih_atas_nama'])) {
            $atas_nama = 'a.n ' . ucwords($pamong_ttd['jabatan'] . ' ' . $config['nama_desa']);
            if (strpos($input['pilih_atas_nama'], 'u.b') !== false) {
                $pamong_ub = $this->pamong_model->get_ub();
                $atas_nama .= ' \par ' . $pamong_ub['jabatan'] . ' \par' . ' u.b';
            }
            $atas_nama .= ' \par ';
            $atas_nama .= $input['jabatan'];
        } else {
            $atas_nama .= $input['jabatan'] . ' ' . $config['nama_desa'];
        }

        return $atas_nama;
    }

    private function penandatangan_lampiran($data)
    {
        return str_replace('\par', '<br>', $this->atas_nama($data));
    }

    public function surat_rtf($data)
    {
        $DateConv = new DateConv();

        // Ambil data
        $input       = $data['input'];
        $individu    = $data['individu'];
        $ayah        = $data['ayah'];
        $ibu         = $data['ibu'];
        $config      = $data['config'];
        $surat       = $data['surat'];
        $id          = $input['nik'];
        $url         = $surat['url_surat'];
        $logo_garuda = $surat['logo_garuda'];
        $tgl         = tgl_indo(date('Y m d'));
        $tgl_hijri   = $DateConv->HijriDateId('j F Y');
        $thn         = date('Y');
        $tampil_foto = $input['tampil_foto'];

        $tgllhr           = ucwords(tgl_indo($individu['tanggallahir']));
        $individu['nama'] = strtoupper($individu['nama']);

        // Pakai surat ubahan desa apabila ada
        $file = SuratExportDesa($url);
        if ($file == '') {
            $file = "template-surat/{$url}/{$url}.rtf";
        }

        if (is_file($file)) {
            $handle = fopen($file, 'rb');
            $buffer = stream_get_contents($handle);
            $buffer = $this->bersihkan_kode_isian($buffer);
            $buffer = $this->sisipkan_kop_surat($buffer);
            $buffer = $this->sisipkan_logo($config['logo'], $logo_garuda, $buffer);
            $buffer = $this->sisipkan_foto($data, $tampil_foto ? $individu['foto'] : 'empty.png', $buffer);
            $buffer = $this->sisipkan_qr($data['qrCode']['viewqr'] ?? FCPATH . LOKASI_SISIPAN_DOKUMEN . 'empty.png', $buffer);

            // SURAT PROPERTI
            $array_replace = [
                '/\{\\\\title\s.+?\}/'    => '{\title ' . $surat['nama'] . '}',
                '/\{\\\\author\s.+?\}/'   => '{\author ' . ucwords($this->setting->sebutan_desa) . ' ' . $config['nama_desa'] . '}',
                '/\{\\\\operator\s.+?\}/' => '{\operator ' . $config['website'] . '}',
            ];

            $buffer = preg_replace(array_keys($array_replace), array_values($array_replace), $buffer);

            // PRINSIP FUNGSI
            // -> [kata_template] -> akan digantikan dengan data di bawah ini (sebelah kanan)

            // Proses surat yang membutuhkan pengambilan data khusus
            $this->surat_rtf_khusus($url, $input, $buffer, $config, $individu, $ayah, $ibu);

            //DATA SURAT
            $array_replace = [
                '[kode_surat]'         => $surat['kode_surat'],
                '[judul_surat]'        => strtoupper('surat ' . $surat['nama']),
                '[tgl_surat]'          => "{$tgl}",
                '[tgl_surat_hijri]'    => $tgl_hijri,
                '[tahun]'              => "{$thn}",
                '[bulan_romawi]'       => bulan_romawi((int) date('m')),
                '[format_nomor_surat]' => $surat['format_nomor_surat'],
            ];
            $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);

            //Data penandatangan
            $buffer = str_replace('[penandatangan]', $this->atas_nama($data), $buffer);

            //DATA DARI KONFIGURASI DESA
            $buffer = $this->case_replace('[sebutan_kabupaten]', $this->setting->sebutan_kabupaten, $buffer);
            $buffer = $this->case_replace('[sebutan_kecamatan]', $this->setting->sebutan_kecamatan, $buffer);
            $buffer = $this->case_replace('[sebutan_desa]', $this->setting->sebutan_desa, $buffer);
            $buffer = $this->case_replace('[sebutan_dusun]', $this->setting->sebutan_dusun, $buffer);
            $buffer = $this->case_replace('[sebutan_camat]', $this->setting->sebutan_camat, $buffer);
            if (! empty($config['email_desa'])) {
                $alamat_desa  = "{$config['alamat_kantor']} Email: {$config['email_desa']} Kode Pos: {$config['kode_pos']}";
                $alamat_surat = "{$config['alamat_kantor']} Telp. {$config['telepon']} Kode Pos: {$config['kode_pos']} \\par Website: {$config['website']} Email: {$config['email_desa']}";
            } else {
                $alamat_desa  = "{$config['alamat_kantor']} Kode Pos: {$config['kode_pos']}";
                $alamat_surat = "{$config['alamat_kantor']} Telp. {$config['telepon']} Kode Pos: {$config['kode_pos']}";
            }
            $array_replace = [
                '[alamat_des]'        => $alamat_desa,
                '[alamat_desa]'       => $alamat_desa,
                '[alamat_surat]'      => $alamat_surat,
                '[alamat_kantor]'     => $config['alamat_kantor'],
                '[email_desa]'        => $config['email_desa'],
                '[kode_desa]'         => $config['kode_desa'],
                '[kode_kecamatan]'    => $config['kode_kecamatan'],
                '[kode_kabupaten]'    => $config['kode_kabupaten'],
                '[kode_pos]'          => $config['kode_pos'],
                '[kode_provinsi]'     => $config['kode_propinsi'],
                '[NAMA_DES]'          => strtoupper($config['nama_desa']),
                '[nama_des]'          => $config['nama_desa'],
                '[NAMA_KAB]'          => strtoupper($config['nama_kabupaten']),
                '[nama_kab]'          => ucwords(strtolower($config['nama_kabupaten'])),
                '[nama_kabupaten]'    => $config['nama_kabupaten'],
                '[NAMA_KEC]'          => strtoupper($config['nama_kecamatan']),
                '[nama_kec]'          => $config['nama_kecamatan'],
                '[nama_kecamatan]'    => $config['nama_kecamatan'],
                '[NAMA_PROV]'         => strtoupper($config['nama_propinsi']),
                '[nama_provinsi]'     => ucwords(strtolower($config['nama_propinsi'])),
                '[nama_kepala_camat]' => $config['nama_kepala_camat'],
                '[nama_kepala_desa]'  => $config['nama_kepala_desa'],
                '[nip_kepala_camat]'  => $config['nip_kepala_camat'],
                '[nip_kepala_desa]'   => $config['nip_kepala_desa'],
                '[pos]'               => $config['kode_pos'],
                '[telepon_desa]'      => $config['telepon'],
                '[website_desa]'      => $config['website'],
            ];
            $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);

            //DATA DARI TABEL PENDUDUK
            //jika data kurang lengkap bisa di tambahkan dari fungsi "get_data_surat" pada file ini
            $array_replace = [
                '[agama]'                => $individu['agama'],
                '[akta_lahir]'           => $individu['akta_lahir'],
                '[akta_perceraian]'      => $individu['akta_perceraian'],
                '[akta_perkawinan]'      => $individu['akta_perkawinan'],
                '[alamat]'               => $individu['alamat_wilayah'],
                '[alamat_jalan]'         => $individu['alamat'],
                '[alamat_sebelumnya]'    => $individu['alamat_sebelumnya'],
                '[ayah_nik]'             => $individu['ayah_nik'],
                '[cacat]'                => $individu['cacat'],
                '[dokumen_pasport]'      => $individu['dokumen_pasport'],
                '[dusun]'                => $individu['dusun'],
                '[gol_darah]'            => $individu['gol_darah'],
                '[hubungan]'             => $individu['hubungan'],
                '[ibu_nik]'              => $individu['ibu_nik'],
                '[kepala_kk]'            => $individu['kepala_kk'],
                '[nama]'                 => $individu['nama'],
                '[nama_ayah]'            => $individu['nama_ayah'],
                '[nama_ibu]'             => $individu['nama_ibu'],
                '[no_kk]'                => $individu['no_kk'],
                '[no_ktp]'               => get_nik($individu['nik']),
                '[pendidikan]'           => $individu['pendidikan'],
                '[pekerjaan]'            => $individu['pekerjaan'],
                '[rw]'                   => $individu['rw'],
                '[rt]'                   => $individu['rt'],
                '[sex]'                  => $individu['sex'],
                '[status]'               => $individu['status_kawin'],
                '[tanggallahir]'         => $tgllhr,
                '[tanggalperceraian]'    => ucwords(tgl_indo($individu['tanggalperceraian'])),
                '[tanggalperkawinan]'    => ucwords(tgl_indo($individu['tanggalperkawinan'])),
                '[tanggal_akhir_paspor]' => ucwords(tgl_indo($individu['tanggal_akhir_paspor'])),
                '[tempatlahir]'          => $individu['tempatlahir'],
                '[tempat_tgl_lahir]'     => "{$individu['tempatlahir']}/{$tgllhr}",
                '[ttl]'                  => "{$individu['tempatlahir']}/{$tgllhr}",
                '[usia]'                 => "{$individu['umur']} Tahun",
                '*usia'                  => "{$individu['umur']} Tahun",
                '[warga_negara]'         => $individu['warganegara'],

                // Data RTM
                '[bdt]' => $individu['bdt'] ?? '-',
            ];
            $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);

            // DATA AYAH dan IBU
            $array_replace = [
                '[d_nama_ibu]'          => $ibu['nama'],
                '[d_nik_ibu]'           => $ibu['nik'] ?: '-',
                '[d_tempatlahir_ibu]'   => $ibu['tempatlahir'] ?: '-',
                '[d_tanggallahir_ibu]'  => $ibu['tanggallahir'] ? tgl_indo_dari_str($ibu['tanggallahir']) : '-',
                '[d_warganegara_ibu]'   => $ibu['wn'],
                '[d_agama_ibu]'         => $ibu['agama'] ?: '-',
                '[d_pekerjaan_ibu]'     => $ibu['pek'] ?: '-',
                '[d_alamat_ibu]'        => "RT {$ibu['rt']} / RW {$ibu['rw']} {$ibu['dusun']}",
                '[d_nama_ayah]'         => $ayah['nama'],
                '[d_nik_ayah]'          => $ayah['nik'],
                '[d_tempatlahir_ayah]'  => $ayah['tempatlahir'],
                '[d_tanggallahir_ayah]' => tgl_indo_dari_str($ayah['tanggallahir']),
                '[d_warganegara_ayah]'  => $ayah['wn'],
                '[d_agama_ayah]'        => $ayah['agama'],
                '[d_pekerjaan_ayah]'    => $ayah['pek'],
                '[d_alamat_ayah]'       => "RT {$ayah['rt']} / RW {$ayah['rw']} {$ayah['dusun']}",
            ];
            $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
            //DATA DARI FORM INPUT SURAT
            // Kode isian yang disediakan pada SID CRI
            $this->substitusi_nomor_surat($input['nomor'], $buffer);
            $buffer = str_replace('[nomor_sorat]', "{$input['nomor']}", $buffer);
            if (isset($input['berlaku_dari'])) {
                $buffer = str_replace('[mulai_berlaku]', tgl_indo(date('Y m d', strtotime($input['berlaku_dari']))), $buffer);
            }
            if (isset($input['berlaku_sampai'])) {
                $buffer = str_replace('[tgl_akhir]', tgl_indo(date('Y m d', strtotime($input['berlaku_sampai']))), $buffer);
            }
            $buffer = str_replace('[jabatan]', "{$input['jabatan']}", $buffer);
            $buffer = str_replace('[nama_pamong]', "{$input['pamong']}", $buffer);
            $nip    = "{$input['pamong_nip']}";
            if (strlen($nip) > 10) {
                $pamong_nip = 'NIP: ' . $nip;
            } else {
                $sebutan_nip_desa = $this->setting->sebutan_nip_desa;
                $pamong_niap      = "{$input['pamong_niap']}";
                if (! empty($pamong_niap)) {
                    $pamong_nip = $sebutan_nip_desa . ': ' . $pamong_niap;
                } else {
                    $pamong_nip = '';
                }
            }
            $buffer = str_replace('NIP: [pamong_nip]', $pamong_nip, $buffer);
            $buffer = str_replace('[keterangan]', "{$input['keterangan']}", $buffer);
            if (isset($input['keperluan'])) {
                $buffer = str_replace('[keperluan]', "{$input['keperluan']}", $buffer);
            }
            // $input adalah isian form surat. Kode isian dari form bisa berbentuk [form_isian]
            // sesuai dengan panduan, atau boleh juga langsung [isian] saja
            $isian_tanggal = ['berlaku_dari', 'berlaku_sampai', 'tanggal', 'tgl_meninggal',
                'tanggal_lahir', 'tanggallahir_istri', 'tanggallahir_suami', 'tanggal_mati',
                'tanggallahir_pasangan', 'tgl_lahir_ayah', 'tgl_lahir_ibu', 'tgl_berakhir_paspor',
                'tgl_akte_perkawinan', 'tgl_perceraian', 'tanggallahir', 'tanggallahir_pelapor', 'tgl_lahir',
                'tanggallahir_ayah', 'tanggallahir_ibu', 'tgl_lahir_wali', 'tgl_nikah',
                'tanggal_pindah', 'tanggal_nikah', 'tanggallahir_wali', 'tanggallahir_suami_dulu', 'tanggallahir_istri_dulu', 'tanggallahir_ayah_pria', 'tanggallahir_ibu_pria',
            ];

            foreach ($input as $key => $entry) {
                // Isian tanggal diganti dengan format tanggal standar
                if (in_array($key, $isian_tanggal)) {
                    if (is_array($entry)) {
                        for ($i = 1; $i <= count($entry); $i++) {
                            $str = $key . $i;
                            //Jika format tanggal adalah 31-12-2018 atau terdapat 10 karakter, maka jalankan tgl_indo_dari_str($waktu)
                            //Jika format tanggal adalah 31-12-2018 23:59 atau terdapat lebih dari 10 karakter, maka jalankan tgl_indo2(tgl_indo_in($waktu))
                            $buffer = preg_replace("/\\[{$str}\\]|\\[form_{$str}\\]/", (strlen($entry[$i - 1]) > 10 ? tgl_indo2(tgl_indo_in($entry[$i - 1])) : tgl_indo_dari_str($entry[$i - 1])), $buffer);
                        }
                    } else {
                        $buffer = preg_replace("/\\[{$key}\\]|\\[form_{$key}\\]/", (strlen($entry) > 10 ? tgl_indo2(tgl_indo_in($entry)) : tgl_indo_dari_str($entry)), $buffer);
                    }
                }
                if (! is_array($entry)) {
                    $buffer = str_replace("[form_{$key}]", $entry, $buffer);
                    // Diletakkan di bagian akhir karena bisa sama dengan kode isian sebelumnya
                    // dan kalau masih ada dianggap sebagai kode dari form isian
                    $buffer = str_replace("[{$key}]", $entry, $buffer);
                }
            }
        }

        return $buffer;
    }

    // Kode isian nomor_surat bisa ditentukan panjangnya, diisi dengan '0' di sebelah kiri
    // Misalnya [nomor_surat, 3] akan menghasilkan seperti '012'
    public function substitusi_nomor_surat($nomor, &$buffer)
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

    private function get_file_data_lampiran($url_surat, $lokasi_rtf)
    {
        $file = FCPATH . $lokasi_rtf . 'get_data_lampiran.php';
        if (! file_exists($file)) {
            $file = FCPATH . 'template-surat/' . $url_surat . '/get_data_lampiran.php';
        }

        return $file;
    }

    private function get_file_lampiran($url_surat, $lokasi_rtf, $format_lampiran)
    {
        $file = FCPATH . $lokasi_rtf . $format_lampiran;
        if (! file_exists($file)) {
            $file = FCPATH . 'template-surat/' . $url_surat . '/' . $format_lampiran;
        }

        return $file;
    }

    public function lampiran($data, $nama_surat, &$lampiran)
    {
        $surat    = $data['surat'];
        $config   = $data['config'];
        $individu = $data['individu'];
        $input    = $data['input'];

        if (! $surat['lampiran']) {
            return;
        }

        // $lampiran_surat dalam bentuk seperti "f-1.08.php, f-1.25.php, f-1.27.php"
        $daftar_lampiran = explode(',', $surat['lampiran']);
        include $this->get_file_data_lampiran($surat['url_surat'], $surat['lokasi_rtf']);
        $lampiran = pathinfo($nama_surat, PATHINFO_FILENAME) . '_lampiran.pdf';

        // convert in PDF
        try {
            // get the HTML using output buffer
            ob_start();

            foreach ($daftar_lampiran as $format_lampiran) {
                include $this->get_file_lampiran($surat['url_surat'], $surat['lokasi_rtf'], $format_lampiran);
            }
            $content = ob_get_clean();

            $html2pdf = new Html2Pdf();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content);
            $html2pdf->output(FCPATH . LOKASI_ARSIP . $lampiran, 'F');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            log_message('error', $formatter->getHtmlMessage());
        }
    }

    public function get_data_untuk_surat($url)
    {
        $data['input'] = $_POST;

        // Ambil data
        $data['config']                      = $this->header['desa'];
        $data['surat']                       = $this->get_surat($url);
        $data['surat']['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);

        switch ($url) {
            default:
                $id               = $data['input']['nik'];
                $data['individu'] = $this->get_data_surat($id);
                $data['ayah']     = $this->get_data_ayah($id);
                $data['ibu']      = $this->get_data_ibu($id);
                break;
        }

        return $data;
    }

    public function buat_surat($url, &$nama_surat, &$lampiran)
    {
        $data           = $this->get_data_untuk_surat($url);
        $data['qrCode'] = null;
        if ($data['surat']['qr_code'] == 1) {
            $data['qrCode'] = $this->buatQrCode($nama_surat);
        }

        $this->lampiran($data, $nama_surat, $lampiran);

        return [
            'namaSurat' => $this->surat_utama($data, $nama_surat),
            'qrCode'    => $data['qrCode'],
        ];
    }

    public function surat_utama($data, &$nama_surat)
    {
        $rtf = $this->surat_rtf($data);
        // Simpan surat di folder arsip dan download
        $path_arsip   = LOKASI_ARSIP;
        $berkas_arsip = $path_arsip . $nama_surat;
        $handle       = fopen($berkas_arsip, 'w+b');
        fwrite($handle, $rtf);
        fclose($handle);

        return $nama_surat;
    }

    public function rtf_to_pdf($nama_surat)
    {
        $lokasi_arsip = FCPATH . LOKASI_ARSIP;
        $berkas_arsip = $lokasi_arsip . $nama_surat;

        if (! empty($this->setting->libreoffice_path)) {
            // Untuk konversi rtf ke pdf, libreoffice harus terinstall
            if (strpos(strtoupper(php_uname('s')), 'WIN') !== false) {
                // Windows O/S
                $berkas_arsip_win = str_replace('/', '\\', $berkas_arsip);
                $outdir           = rtrim(str_replace('/', '\\', $lokasi_arsip), '/\\');
                $cmd              = '"' . $this->setting->libreoffice_path . '\\soffice.exe"';
                $cmd              = $cmd . ' --headless --convert-to pdf:writer_pdf_Export --outdir "' . $outdir . '" "' . $berkas_arsip_win . '"';
            } elseif ($this->setting->libreoffice_path == '/') {
                // Linux menggunakan stand-alone LibreOffice
                $cmd = '' . FCPATH . 'vendor/libreoffice/opt/libreoffice/program/soffice --headless --norestore --convert-to pdf --outdir ' . $lokasi_arsip . ' ' . $berkas_arsip;
            } else {
                // Linux menggunakan LibreOffice yg dipasang menggunakan 'sudo apt-get'
                $cmd = 'libreoffice --headless --norestore --convert-to pdf --outdir ' . $lokasi_arsip . ' ' . $berkas_arsip;
            }
            exec($cmd, $output, $return);
            // Kalau berhasil, pakai pdf
            if ($return == 0) {
                return pathinfo($nama_surat, PATHINFO_FILENAME) . '.pdf';
            }
        }

        // Kembalikan surat .rtf
        return $nama_surat;
    }

    public function get_last_nosurat_log($url)
    {
        $data = $this->penomoran_surat_model->get_surat_terakhir('log_surat', $url);
        if ($this->setting->penomoran_surat == 2 && empty($data['nama'])) {
            $surat        = $this->get_surat($url);
            $data['nama'] = $surat['nama'];
        }
        $ket = [
            1 => 'Terakhir untuk semua surat layanan: ',
            2 => "Terakhir untuk jenis surat {$data['nama']}: ",
            3 => 'Terakhir untuk semua surat layanan, keluar dan masuk: ',
        ];
        $data['no_surat_berikutnya'] = $data['no_surat'] + 1;
        $data['no_surat_berikutnya'] = str_pad((string) $data['no_surat_berikutnya'], (int) $this->setting->panjang_nomor_surat, '0', STR_PAD_LEFT);
        $data['ket_nomor']           = $ket[$this->setting->penomoran_surat];

        return $data;
    }

    public function surat_total()
    {
        return $this->db->select('COUNT(id) as jml')
            ->get('log_surat')
            ->row()->jml;
    }

    private function sisipkan_qr($file_qr, $buffer)
    {
        if (! is_file($file_qr)) {
            return $buffer;
        }
        $akhiran_qr        = '04c5cd360000000049454e44ae426082';
        $akhiran_sementara = 'akhiran_qr';
        $jml_qr            = substr_count($buffer, $akhiran_qr);
        if ($jml_qr <= 0) {
            return $buffer;
        }

        $qr_bytes = file_get_contents($file_qr);
        $qr_hex   = implode('', unpack('H*', $qr_bytes));

        for ($i = 0; $i < $jml_qr; $i++) {
            $pos            = strpos($buffer, $akhiran_qr);
            $buffer         = substr_replace($buffer, $akhiran_sementara, $pos, strlen($akhiran_qr));
            $placeholder_qr = '/' . $this->awalan_qr . '.*' . $akhiran_sementara . '/s';
            $buffer         = preg_replace($placeholder_qr, $qr_hex, $buffer);
        }

        return $buffer;
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

    public function cek_surat_mandiri($id)
    {
        return $this->db
            ->get_where('tweb_surat_format', ['id' => $id])
            ->row_array();
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
}
