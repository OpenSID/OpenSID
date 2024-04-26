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

class First_artikel_m extends MY_Model
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
        return $this->config_id('a')
            ->select('a.*, u.nama AS owner, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->where('a.enabled', 1)
            ->where('headline', 1)
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'))
            ->order_by('tgl_upload DESC')
            ->get()
            ->row_array();
    }

    public function get_feed()
    {
        $sumber_feed = setting('link_feed');
        if (! cek_bisa_akses_site($sumber_feed)) {
            return null;
        }

        $this->load->library('Feed_Reader');
        $feed = new Feed_Reader($sumber_feed);

        return array_slice($feed->items, 0, 2);
    }

    public function get_widget()
    {
        return $this->config_id()->get('widget', 1)->result_array();
    }

    public function paging($p = 1)
    {
        $this->db->select('COUNT(a.id) AS jml');
        $this->paging_artikel_sql();
        $cari = trim($this->input->get('cari', true));
        if ($cari !== '') {
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

    private function paging_artikel_sql(): void
    {
        $this->config_id('a')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->join('kategori k', 'a.id_kategori = k.id', 'LEFT')
            ->where('a.enabled', 1)
            ->where('(a.headline != 1)')
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'));

        if ($statis = json_decode($this->setting->artikel_statis, true)) {
            $tipe = array_merge(['dinamis'], $statis);
            $this->db->where_in('a.tipe', $tipe);
        }

        $cari = trim($this->input->get('cari', true));
        if ($cari !== '') {
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
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $this->sterilkan_artikel($data[$i]);
            $this->icon_keuangan($data[$i]);
        }

        return $data;
    }

    private function sterilkan_artikel(&$data): void
    {
        $data['judul'] = htmlspecialchars_decode($this->security->xss_clean($data['judul']));
        $data['slug']  = $this->security->xss_clean($data['slug']);
    }

    private function icon_keuangan(&$data): void
    {
        // ganti shortcode menjadi icon
        $data['isi'] = $this->shortcode_model->convert_sc_list($data['isi']);
    }

    public function arsip_show($type = '')
    {
        // Artikel agenda (kategori=1000) tidak ditampilkan
        $this->config_id('a')
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
        $data    = $this->db->get('artikel a')->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['judul'] = htmlspecialchars_decode($this->security->xss_clean($data[$i]['judul']));
        }

        return $data;
    }

    public function paging_arsip($p = 1)
    {
        $row = $this->config_id('a')
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
        $query = $this->config_id('a')
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
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
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

    private function sql_gambar_slide_show(string $gambar)
    {
        $this->config_id()
            ->select('id, judul, gambar, slug, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->from('artikel')
            ->where('enabled', 1)
            ->where('slider', 1)
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
        $limit  = $this->setting->jumlah_gambar_slider ?? 10;

        $slider_gambar = [];

        switch ($sumber) {
            case '1':
                // 10 gambar utama semua artikel terbaru
                $slider_gambar['gambar'] = $this->config_id()
                    ->select('id, judul, gambar, slug, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
                    ->where('enabled', 1)
                    ->where('gambar !=', '')
                    ->where('tgl_upload <', date('Y-m-d H:i:s'))
                    ->order_by('tgl_upload DESC')
                    ->limit($limit)
                    ->get('artikel')
                    ->result_array();
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
        $slider_gambar['gambar'] = array_slice($slider_gambar['gambar'] ?? [], 0, $limit);

        return $slider_gambar;
    }

    public function agenda_show($type = '')
    {
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

        return $this->config_id('g')
            ->select('a.*, g.*, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
            ->join('artikel a', 'a.id = g.id_artikel', 'LEFT')
            ->where('a.enabled', 1)
            ->where('a.tipe', AGENDA)
            ->get('agenda g')
            ->result_array();
    }

    public function komentar_show()
    {
        $this->for_slug();

        return $this->config_id('k')
            ->select('k.*')
            ->from('komentar k')
            ->join('artikel a', 'k.id_artikel = a.id')
            ->where('k.status', 1)
            ->where('k.id_artikel <>', 775)
            ->where('parent_id', null)
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
        $data = $this->config_id(null, true)
            ->group_start()
            ->where('id', $id)
            ->or_where('slug', $id)
            ->group_end()
            ->get('kategori')
            ->row_array();

        if (empty($data)) {
            $judul = [
                'statis'   => 'Halaman Statis',
                'agenda'   => 'Agenda',
                'keuangan' => 'Artikel Keuangan',
                $id        => "Artikel Kategori {$id}",
            ];

            $data['kategori'] = $judul[$id];
        }

        return $data;
    }

    public function get_artikel($thn, $bln, $hr, $url)
    {
        if (is_numeric($url)) {
            $this->db->where('a.id', $url);
        } else {
            $this->db->where('a.slug', $url);
        }

        $query = $this->config_id('a')
            ->select('a.*, u.nama AS owner, k.kategori, k.slug AS kat_slug, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'left')
            ->join('kategori k', 'a.id_kategori = k.id', 'left')
            ->where('a.enabled', 1)
            ->where('a.tgl_upload <', date('Y-m-d H:i:s'))
            ->where('YEAR(a.tgl_upload)', $thn)
            ->where('MONTH(a.tgl_upload)', $bln)
            ->where('DAY(a.tgl_upload)', $hr)
            ->get();

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
        return $this->config_id()
            ->select('dokumen')
            ->where('id', $id)
            ->get('artikel')
            ->row()
            ->dokumen;
    }

    public function get_agenda($id)
    {
        return $this->config_id()
            ->where('id_artikel', $id)
            ->get('agenda')
            ->row_array();
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
    private function list_artikel_sql($id): void
    {
        $this->config_id('a')
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
        $data    = $this->db->get()->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['judul'] = htmlspecialchars_decode($this->security->xss_clean($data[$i]['judul']));
            if (empty($this->setting->user_admin) || $data[$i]['id_user'] != $this->setting->user_admin) {
                $data[$i]['isi'] = htmlspecialchars_decode($this->security->xss_clean($data[$i]['isi']));
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
        $data['config_id'] = identitas('id');
        $this->db->insert('komentar', $data);

        return $this->db->affected_rows();
    }

    public function list_komentar($id_artikel = 0, $parent = null, $order = 'DESC')
    {
        $komentar = $this->config_id()
            ->from('komentar')
            ->where('id_artikel', $id_artikel)
            ->where('status', 1)
            ->where('parent_id', $parent)
            ->order_by("tgl_upload {$order}")
            ->get()
            ->result_array();

        return collect($komentar)->map(function (array $item) use ($id_artikel): array {
            $item['owner']    = 's';
            $item['children'] = $this->list_komentar($id_artikel, $item['id'], 'ASC');

            return $item;
        })->toArray();
    }

    // Tampilan di widget sosmed
    public function list_sosmed()
    {
        $query = $this->config_id()->where('enabled', 1)->get('media_sosial');

        if ($query->num_rows() > 0) {
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['link'] = $this->web_sosmed_model->link_sosmed($data[$i]['id'], $data[$i]['link'], $data[$i]['tipe']);
            }
        }

        return $data;
    }

    public function hit($url): void
    {
        $this->load->library('user_agent');

        $id = $this->config_id()
            ->select('id')
            ->where('slug', $url)
            ->or_where('id', $url)
            ->get('artikel')
            ->row()->id;

        //membatasi hit hanya satu kali dalam setiap session
        if (in_array($id, $_SESSION['artikel']) || $this->agent->is_robot() || crawler()) {
            return;
        }

        $this->config_id()
            ->set('hit', 'hit + 1', false)
            ->where('id', $id)
            ->update('artikel');
        $_SESSION['artikel'][] = $id;
    }

    public function get_artikel_by_id($id)
    {
        return $this->config_id()
            ->select('slug, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri, judul, tgl_upload')
            ->where(['id' => $id])
            ->get('artikel')
            ->row_array();
    }
}
