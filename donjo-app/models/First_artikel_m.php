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

class First_artikel_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('web_sosmed_model');
        $this->load->model('shortcode_model');
        if (! isset($_SESSION['artikel'])) {
            $_SESSION['artikel'] = [];
        }
    }

    public function get_headline()
    {
        $data = $this->db
            ->select('a.*, u.nama AS owner, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->where('headline = 1')
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'))
            ->order_by('tgl_upload DESC')
            ->get()
            ->row_array();
        if (empty($data)) {
            $data = null;
        }

        return $data;
    }

    public function get_feed()
    {
        $sumber_feed = 'https://www.covid19.go.id/feed/';
        if (! cek_bisa_akses_site($sumber_feed)) {
            return null;
        }

        $this->load->library('Feed_Reader');
        $feed = new Feed_Reader($sumber_feed);

        return array_slice($feed->items, 0, 2);
    }

    public function get_widget()
    {
        $sql   = 'SELECT * FROM widget LIMIT 1 ';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function paging($p = 1)
    {
        $this->db->select('COUNT(a.id) AS jml');
        $this->paging_artikel_sql();
        $cari = trim($this->input->get('cari', true));
        if (! empty($cari)) {
            $cari          = $this->db->escape_like_str($cari);
            $cfg['suffix'] = "?cari={$cari}";
        }
        $jml = $this->db->get()
            ->row()->jml;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->setting->web_artikel_per_page;
        $cfg['num_rows'] = $jml;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function paging_artikel_sql()
    {
        $this->db
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->join('kategori k', 'a.id_kategori = k.id', 'LEFT')
            ->where('a.enabled', 1)
            ->where('a.headline <>', 1)
            ->where('a.id_kategori NOT IN (1000)')
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'));

        $cari = trim($this->input->get('cari', true));
        if (! empty($cari)) {
            $this->db
                ->group_start()
                ->like('a.judul', $cari)
                ->or_like('a.isi', $cari)
                ->group_end();
        }
    }

    public function artikel_show($offset, $limit)
    {
        $this->paging_artikel_sql();
        $data = $this->db
            ->select('a.*, u.nama AS owner, k.kategori, k.slug AS kat_slug, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->order_by('a.tgl_upload DESC')
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $this->sterilkan_artikel($data[$i]);
            $this->icon_keuangan($data[$i]);
        }

        return $data;
    }

    private function sterilkan_artikel(&$data)
    {
        $data['judul'] = $this->security->xss_clean($data['judul']);
        $data['slug']  = $this->security->xss_clean($data['slug']);
        // User terpecaya boleh menampilkan <iframe> dsbnya
        if (empty($this->setting->user_admin) || $data['id_user'] != $this->setting->user_admin) {
            $data['isi'] = $this->security->xss_clean($data['isi']);
        }
    }

    private function icon_keuangan(&$data)
    {
        // ganti shortcode menjadi icon
        $data['isi'] = $this->shortcode_model->convert_sc_list($data['isi']);
    }

    public function arsip_show($type = '')
    {
        // Artikel agenda (kategori=1000) tidak ditampilkan
        $this->db
            ->select('a.*, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
            ->where('a.enabled', 1)
            ->where('a.id_kategori NOT IN (1000)')
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'));

        switch ($type) {
            case 'acak':
                $this->db->order_by('rand()');
                break;

            case 'populer':
                $this->db->order_by('a.hit', 'DESC');
                break;

            default:
                $this->db->order_by('a.tgl_upload', 'DESC');
                break;
        }

        $this->db->limit(7);
        $data = $this->db->get('artikel a')->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['judul'] = $this->security->xss_clean($data[$i]['judul']);
        }

        return $data;
    }

    public function paging_arsip($p = 1)
    {
        $row = $this->db
            ->select('COUNT(a.id) AS id')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->join('kategori k', 'a.id_kategori = k.id', 'left')
            ->where('a.enabled=1')
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'))
            ->order_by('tgl_upload DESC')
            ->get()
            ->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = 20;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function full_arsip($offset = 0, $limit = 50)
    {
        $query = $this->db
            ->select('a.*,u.nama AS owner,k.kategori, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->join('kategori k', 'a.id_kategori = k.id', 'left')
            ->where('a.enabled=1')
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'))
            ->order_by('a.tgl_upload', 'DESC')
            ->limit($limit, $offset)
            ->get();
        $data = $query->result_array();
        if ($query->num_rows() > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $nomer           = $offset + $i + 1;
                $id              = $data[$i]['id'];
                $tgl             = date('d/m/Y', strtotime($data[$i]['tgl_upload']));
                $data[$i]['no']  = $nomer;
                $data[$i]['tgl'] = $tgl;
                $data[$i]['isi'] = "<a href='" . site_url("artikel/{$id}") . "'>" . $data[$i]['judul'] . '</a>, <i class="fa fa-user"></i> ' . $data[$i]['owner'];
            }
        } else {
            $data = false;
        }

        return $data;
    }

    private function sql_gambar_slide_show($gambar)
    {
        $this->db
            ->select('id, judul, gambar, slug, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->from('artikel')
            ->where('enabled', 1)
            ->where('headline', 3)
            ->where($gambar . ' !=', '')
            ->where('tgl_upload <', date('Y-m-d H:i:s'));

        return $this->db->get_compiled_select();
    }

    // Jika $gambar_utama, hanya tampilkan gambar utama masing2 artikel terbaru
    public function slide_show($gambar_utama = false)
    {
        $sql   = [];
        $sql[] = $this->sql_gambar_slide_show('gambar');
        if (! $gambar_utama) {
            $sql[] = $this->sql_gambar_slide_show('gambar1');
            $sql[] = '(' . $this->sql_gambar_slide_show('gambar2') . ')';
            $sql[] = '(' . $this->sql_gambar_slide_show('gambar3') . ')';
        }
        $sql = implode('
		UNION
		', $sql);

        $sql .= ($gambar_utama) ? 'ORDER BY tgl_upload DESC LIMIT 10' : 'ORDER BY RAND() LIMIT 10';

        return $this->db->query($sql)->result_array();
    }

    // Ambil gambar slider besar tergantung dari settingnya.
    public function slider_gambar()
    {
        $sumber = $this->setting->sumber_gambar_slider;

        $slider_gambar = [];

        switch ($sumber) {
            case '1':
                // 10 gambar utama semua artikel terbaru
                $slider_gambar['gambar'] = $this->db
                    ->select('id, judul, gambar, slug, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
                    ->where('enabled', 1)
                    ->where('gambar !=', '')
                    ->where('tgl_upload <', date('Y-m-d H:i:s'))
                    ->order_by('tgl_upload DESC')
                    ->limit(10)->get('artikel')->result_array();
                $slider_gambar['lokasi'] = LOKASI_FOTO_ARTIKEL;
                break;

            case '2':
                // 10 gambar utama artikel terbaru yang masuk ke slider atas
                $slider_gambar['gambar'] = $this->slide_show(true);
                $slider_gambar['lokasi'] = LOKASI_FOTO_ARTIKEL;
                break;

            case '3':
                // 10 gambar dari galeri yang masuk ke slider besar
                $this->load->model('web_gallery_model');
                $slider_gambar['gambar'] = $this->web_gallery_model->list_slide_galeri();
                $slider_gambar['lokasi'] = LOKASI_GALERI;
                break;

            default:
                // code...
                break;
        }

        $slider_gambar['sumber'] = $sumber;

        return $slider_gambar;
    }

    public function agenda_show($type = '')
    {
        $this->db
            ->select('a.*, g.*, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
            ->join('artikel a', 'a.id = g.id_artikel', 'LEFT')
            ->where('a.enabled', 1)
            ->where('a.id_kategori', '1000');

        switch ($type) {
            case 'yad':
                $this->db->where('DATE(g.tgl_agenda) > CURDATE()')
                    ->order_by('g.tgl_agenda');
                break;

            case 'lama':
                $this->db->where('DATE(g.tgl_agenda) < CURDATE()');
                break;

            default:
                $this->db->where('DATE(g.tgl_agenda) = CURDATE()');
                break;
        }

        return $this->db
            ->get('agenda g')
            ->result_array();
    }

    public function komentar_show()
    {
        $this->for_slug();

        return $this->db
            ->select('k.*')
            ->from('komentar k')
            ->join('artikel a', 'k.id_artikel = a.id')
            ->where('k.status', 1)
            ->where('k.id_artikel <>', 775)
            ->order_by('k.tgl_upload', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();
    }

    public function for_slug()
    {
        return $this->db->select('YEAR(a.tgl_upload) AS thn, MONTH(a.tgl_upload) AS bln, DAY(a.tgl_upload) AS hri, a.slug as slug');
    }

    public function get_kategori($id = 0)
    {
        $data = $this->db
            ->group_start()
            ->where('id', $id)
            ->or_where('slug', $id)
            ->group_end()
            ->get('kategori')
            ->row_array();

        if (empty($data)) {
            $judul = [
                999  => 'Halaman Statis',
                1000 => 'Agenda',
                1001 => 'Artikel Keuangan',
                $id  => "Artikel Kategori {$id}",
            ];

            $data['kategori'] = $judul[$id];
        }

        return $data;
    }

    public function get_artikel($url)
    {
        $this->db->select('a.*, u.nama AS owner, k.kategori, k.slug AS kat_slug, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'left')
            ->join('kategori k', 'a.id_kategori = k.id', 'left')
            ->where('a.enabled', 1)
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'));

        if (is_numeric($url)) {
            $this->db->where('a.id', $url);
        } else {
            $this->db->where('a.slug', $url);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $this->sterilkan_artikel($data);
        } else {
            $data = false;
        }

        return $data;
    }

    public function get_dokumen_artikel($id)
    {
        return $this->db->select('dokumen')
            ->where('id', $id)
            ->get('artikel')
            ->row()->dokumen;
    }

    public function get_agenda($id)
    {
        return $this->db->where('id_artikel', $id)
            ->get('agenda')->row_array();
    }

    public function paging_kat($p = 1, $id = 0)
    {
        $this->list_artikel_sql($id);
        $this->db->select('COUNT(a.id) AS jml');
        $jml_data = $this->db->get()->row()->jml;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->setting->web_artikel_per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // Query sama untuk paging and ambil daftar artikel menurut kategori
    private function list_artikel_sql($id)
    {
        $this->db
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'left')
            ->join('kategori k', 'a.id_kategori = k.id', 'left')
            ->where('a.enabled', 1)
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'));

        if (! empty($id)) {
            $this->db
                ->group_start()
                ->where('k.slug', $id)
                ->or_where('k.id', $id)
                ->group_end();
        }
    }

    public function list_artikel($offset = 0, $limit = 50, $id = 0)
    {
        $this->list_artikel_sql($id);
        $this->db->select('a.*, u.nama AS owner, k.kategori, k.slug AS kat_slug, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri');
        $this->db->order_by('a.tgl_upload', 'DESC');
        $this->db->limit($limit, $offset);
        $data = $this->db->get()->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['judul'] = $this->security->xss_clean($data[$i]['judul']);
            if (empty($this->setting->user_admin) || $data[$i]['id_user'] != $this->setting->user_admin) {
                $data[$i]['isi'] = $this->security->xss_clean($data[$i]['isi']);
            }
            // ganti shortcode menjadi icon
            $data[$i]['isi'] = $this->shortcode_model->convert_sc_list($data[$i]['isi']);
        }

        return $data;
    }

    /**
     * Simpan komentar yang dikirim oleh pengunjung
     *
     * @param mixed $data
     */
    public function insert_comment($data = [])
    {
        $this->db->insert('komentar', $data);

        return $this->db->affected_rows();
    }

    public function list_komentar($id = 0)
    {
        return $this->db->from('komentar')
            ->where('id_artikel', $id)
            ->where('status', 1)
            ->order_by('tgl_upload DESC')
            ->get()->result_array();
    }

    // Tampilan di widget sosmed
    public function list_sosmed()
    {
        $query = $this->db->where('enabled', 1)->get('media_sosial');

        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['link'] = $this->web_sosmed_model->link_sosmed($data[$i]['id'], $data[$i]['link'], $data[$i]['tipe']);
            }
        }

        return $data;
    }

    public function hit($url)
    {
        $this->load->library('user_agent');

        $id = $this->db->select('id')
            ->where('slug', $url)
            ->or_where('id', $url)
            ->get('artikel')
            ->row()->id;

        //membatasi hit hanya satu kali dalam setiap session
        if (in_array($id, $_SESSION['artikel']) || $this->agent->is_robot() || crawler() === true) {
            return;
        }

        $this->db->set('hit', 'hit + 1', false)
            ->where('id', $id)
            ->update('artikel');
        $_SESSION['artikel'][] = $id;
    }

    public function get_artikel_by_id($id)
    {
        return $this->db->select('slug, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')->where(['id' => $id])->get('artikel')->row_array();
    }
}
