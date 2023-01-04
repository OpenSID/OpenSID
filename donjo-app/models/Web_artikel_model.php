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

class Web_artikel_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('agenda_model');
    }

    public function autocomplete($cat)
    {
        $this->group_akses();

        $this->db->where('a.id_kategori', $cat);

        return $this->autocomplete_str('a.judul', 'artikel a');
    }

    private function search_sql()
    {
        $cari = $this->session->cari;

        if (isset($cari)) {
            $kw = $this->db->escape_like_str($cari);
            $kw = '%' . $kw . '%';

            return " AND (judul LIKE '{$kw}' OR isi LIKE '{$kw}')";
        }
    }

    private function filter_sql()
    {
        $status = $this->session->status;

        if (isset($status)) {
            return " AND a.enabled = {$status}";
        }
    }

    // TODO : Gunakan $this->group_akses(); jika sudah menggunakan query builder
    private function grup_sql()
    {
        // Kontributor dan lainnya (group yg dibuat sendiri) hanya dapat melihat artikel yg dibuatnya sendiri
        if (! in_array($this->session->grup, [1, 2, 3, 4])) {
            return " AND a.id_user = {$this->session->user}";
        }
    }

    public function paging($cat = 0, $p = 1, $o = 0)
    {
        $sql      = 'SELECT COUNT(a.id) AS id ' . $this->list_data_sql($cat);
        $query    = $this->db->query($sql, $cat);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql($cat)
    {
        if ($cat > 0) {
            $sql = 'FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE id_kategori = ? ';
        } elseif ($cat == -1) {
            // Semua artikel dinamis (tidak termasuk artikel statis)
            $sql = "FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE 1 AND id_kategori NOT IN ('999', '1000', '1001')";
        } else { // Artikel dinamis tidak berkategori
            $sql = 'FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE a.id_kategori <> 999 AND a.id_kategori <> 1000 AND a.id_kategori <> 1001 AND k.id IS NULL ';
        }
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->grup_sql();

        return $sql;
    }

    public function list_data($cat = 0, $o = 0, $offset = 0, $limit = 500)
    {
        switch ($o) {
            case 1: $order_sql = ' ORDER BY judul';
                break;

            case 2: $order_sql = ' ORDER BY judul DESC';
                break;

            case 3: $order_sql = ' ORDER BY hit';
                break;

            case 4: $order_sql = ' ORDER BY hit DESC';
                break;

            case 5: $order_sql = ' ORDER BY tgl_upload';
                break;

            case 6: $order_sql = ' ORDER BY tgl_upload DESC';
                break;

            default:$order_sql = ' ORDER BY id DESC';
        }

        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        $sql = 'SELECT a.*, k.kategori AS kategori, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri ' . $this->list_data_sql($cat);
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql, $cat);
        $data  = $query->result_array();

        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']         = $j + 1;
            $data[$i]['boleh_ubah'] = $this->boleh_ubah($data[$i]['id'], $this->session->user);
            $j++;
        }

        return $data;
    }

    // TODO: pindahkan dan gunakan web_kategori_model
    private function kategori($id)
    {
        return $this->db
            ->where('parrent', $id)
            ->order_by('urut')
            ->get('kategori')
            ->result_array();
    }

    // TODO: pindahkan dan gunakan web_kategori_model
    public function list_kategori()
    {
        $data = $this->kategori(0);

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['submenu'] = $this->kategori($data[$i]['id']);
        }

        $data[] = [
            'id'       => '0',
            'kategori' => '[Tidak Berkategori]',
        ];

        return $data;
    }

    // TODO: pindahkan dan gunakan web_kategori_model
    public function get_kategori_artikel($id)
    {
        return $this->db->select('id_kategori')->where('id', $id)->get('artikel')->row_array();
    }

    // TODO: pindahkan dan gunakan web_kategori_model
    public function get_kategori($cat = 0)
    {
        $sql   = 'SELECT kategori FROM kategori WHERE id = ?';
        $query = $this->db->query($sql, $cat);

        return $query->row_array();
    }

    public function insert($cat = 1)
    {
        $_SESSION['success']   = 1;
        $_SESSION['error_msg'] = '';
        $data                  = $_POST;
        if (empty($data['judul']) || empty($data['isi'])) {
            $_SESSION['error_msg'] .= ' -> Data harus diisi';
            $_SESSION['success'] = -1;

            return;
        }
        // Batasi judul menggunakan teks polos
        $data['judul'] = strip_tags($data['judul']);

        $fp          = time();
        $list_gambar = ['gambar', 'gambar1', 'gambar2', 'gambar3'];

        foreach ($list_gambar as $gambar) {
            $lokasi_file = $_FILES[$gambar]['tmp_name'];
            $nama_file   = $fp . '_' . $_FILES[$gambar]['name'];
            if (! empty($lokasi_file)) {
                $tipe_file = TipeFile($_FILES[$gambar]);
                $hasil     = UploadArtikel($nama_file, $gambar, $fp, $tipe_file);
                if ($hasil) {
                    $data[$gambar] = $nama_file;
                }
            }
        }
        $data['id_kategori'] = $cat;
        $data['id_user']     = $_SESSION['user'];

        // Kontributor tidak dapat mengaktifkan artikel
        if ($_SESSION['grup'] == 4) {
            $data['enabled'] = 2;
        }

        // Upload dokumen lampiran

        $lokasi_file = $_FILES['dokumen']['tmp_name'];
        $tipe_file   = TipeFile($_FILES['dokumen']);
        $nama_file   = $_FILES['dokumen']['name'];
        $ext         = get_extension($nama_file);
        $nama_file   = str_replace(' ', '-', $nama_file); // normalkan nama file

        if ($nama_file && ! empty($lokasi_file)) {
            if (! in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN), true) || ! in_array($ext, unserialize(EXT_DOKUMEN))) {
                unset($data['link_dokumen']);
                $_SESSION['error_msg'] .= ' -> Jenis file salah: ' . $tipe_file;
                $_SESSION['success'] = -1;
            } else {
                $data['dokumen'] = $nama_file;
                if ($data['link_dokumen'] == '') {
                    $data['link_dokumen'] = $data['judul'];
                }
                UploadDocument2($nama_file);
            }
        }

        foreach ($list_gambar as $gambar) {
            unset($data['old_' . $gambar]);
        }
        if ($data['tgl_upload'] == '') {
            $data['tgl_upload'] = date('Y-m-d H:i:s');
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
            $data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
        }
        if ($data['tgl_agenda'] == '') {
            unset($data['tgl_agenda']);
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_agenda']);
            $data['tgl_agenda'] = $tempTgl->format('Y-m-d H:i:s');
        }

        $data['slug'] = unique_slug('artikel', $data['judul']);

        if ($cat == AGENDA) {
            $outp = $this->insert_agenda($data);
        } else {
            $outp = $this->db->insert('artikel', $data);
        }
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
    }

    private function ambil_data_agenda(&$data)
    {
        $agenda               = [];
        $agenda['tgl_agenda'] = $data['tgl_agenda'];
        unset($data['tgl_agenda']);
        $agenda['koordinator_kegiatan'] = $data['koordinator_kegiatan'];
        unset($data['koordinator_kegiatan']);
        $agenda['lokasi_kegiatan'] = $data['lokasi_kegiatan'];
        unset($data['lokasi_kegiatan']);

        return $agenda;
    }

    private function insert_agenda($data)
    {
        $agenda = $this->ambil_data_agenda($data);
        unset($data['id_agenda']);
        $outp = $this->db->insert('artikel', $data);
        if ($outp) {
            $insert_id            = $this->db->insert_id();
            $agenda['id_artikel'] = $insert_id;
            $this->agenda_model->insert($agenda);
        }

        return $outp;
    }

    public function update($cat, $id = 0)
    {
        session_error_clear();

        $data = $_POST;
        if (empty($data['judul']) || empty($data['isi'])) {
            $_SESSION['error_msg'] .= ' -> Data harus diisi';
            $_SESSION['success'] = -1;

            return;
        }
        // Batasi judul menggunakan teks polos
        $data['judul'] = strip_tags($data['judul']);

        $fp          = time();
        $list_gambar = ['gambar', 'gambar1', 'gambar2', 'gambar3'];

        foreach ($list_gambar as $gambar) {
            $lokasi_file = $_FILES[$gambar]['tmp_name'];
            $nama_file   = $fp . '_' . $_FILES[$gambar]['name'];

            if (! empty($lokasi_file)) {
                $tipe_file = TipeFile($_FILES[$gambar]);
                $hasil     = UploadArtikel($nama_file, $gambar, $fp, $tipe_file);
                if ($hasil) {
                    $data[$gambar] = $nama_file;
                    HapusArtikel($data['old_' . $gambar]);
                } else {
                    unset($data[$gambar]);
                }
            } else {
                unset($data[$gambar]);
            }
        }

        foreach ($list_gambar as $gambar) {
            if (isset($data[$gambar . '_hapus'])) {
                HapusArtikel($data[$gambar . '_hapus']);
                $data[$gambar] = '';
                unset($data[$gambar . '_hapus']);
            }
        }

        // Upload dokumen lampiran
        $lokasi_file = $_FILES['dokumen']['tmp_name'];
        $tipe_file   = TipeFile($_FILES['dokumen']);
        $nama_file   = $_FILES['dokumen']['name'];
        $ext         = get_extension($nama_file);
        $nama_file   = str_replace(' ', '-', $nama_file); // normalkan nama file

        if ($nama_file && ! empty($lokasi_file)) {
            if (! in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN)) || ! in_array($ext, unserialize(EXT_DOKUMEN))) {
                unset($data['link_dokumen']);
                $_SESSION['error_msg'] .= ' -> Jenis file salah: ' . $tipe_file;
                $_SESSION['success'] = -1;
            } else {
                $data['dokumen'] = $nama_file;
                if ($data['link_dokumen'] == '') {
                    $data['link_dokumen'] = $data['judul'];
                }
                UploadDocument2($nama_file);
            }
        }

        foreach ($list_gambar as $gambar) {
            unset($data['old_' . $gambar]);
        }
        if ($data['tgl_upload'] == '') {
            $data['tgl_upload'] = date('Y-m-d H:i:s');
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
            $data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
        }
        if ($data['tgl_agenda'] == '') {
            unset($data['tgl_agenda']);
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_agenda']);
            $data['tgl_agenda'] = $tempTgl->format('Y-m-d H:i:s');
        }

        $data['slug'] = unique_slug('artikel', $data['judul'], $id);

        $this->group_akses();

        if ($cat == AGENDA) {
            $outp = $this->update_agenda($id, $data);
        } else {
            $this->db->where('a.id', $id);
            $outp = $this->db->update('artikel a', $data);
        }

        status_sukses($outp);
    }

    private function update_agenda($id_artikel, $data)
    {
        $agenda = $this->ambil_data_agenda($data);
        $id     = $data['id_agenda'];
        unset($data['id_agenda']);
        $outp = $this->db->where('a.id', $id_artikel)->update('artikel a', $data);
        if ($outp) {
            if (empty($id)) {
                $agenda['id_artikel'] = $id_artikel;
                $this->agenda_model->insert($agenda);
            } else {
                $this->agenda_model->update($id, $agenda);
            }
        }

        return $outp;
    }

    public function update_kategori($id, $id_kategori)
    {
        $this->db->where('id', $id)->update('artikel', ['id_kategori' => $id_kategori]);
    }

    public function delete($id = 0, $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $this->group_akses();

        $list_gambar = $this->db
            ->select('a.gambar, a.gambar1, a.gambar2, a.gambar3')
            ->from('artikel a')
            ->where('a.id', $id)
            ->get()
            ->row_array();

        if ($list_gambar) {
            foreach ($list_gambar as $key => $gambar) {
                HapusArtikel($gambar);
            }
        }

        if (! in_array($this->session->grup, [1, 2, 3, 4])) {
            $this->db->where('id_user', $this->session->user);
        }

        $this->db->from('artikel')->where('id', $id)->delete();
        $outp = $this->db->affected_rows();

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            if ($this->boleh_ubah($id, $this->session->user)) {
                $this->delete($id, true);
            }
        }
    }

    // TODO: pindahkan dan gunakan web_kategori_model
    public function hapus($id = 0, $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }
        $outp = $this->db->where('id', $id)->delete('kategori');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function artikel_lock($id = 0, $val = 1)
    {
        $this->group_akses();

        $outp = $this->db->where('id', $id)->update('artikel a', ['a.enabled' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function komentar_lock($id = 0, $val = 1)
    {
        $outp = $this->db->where('id', $id)->update('artikel', ['boleh_komentar' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_artikel($id = 0)
    {
        $this->group_akses();

        $data = $this->db
            ->select('a.*, g.*, g.id as id_agenda, u.nama AS owner')
            ->select('YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
            ->from('artikel a')
            ->join('user u', 'a.id_user = u.id', 'LEFT')
            ->join('agenda g', 'g.id_artikel = a.id', 'LEFT')
            ->where('a.id', $id)
            ->get()
            ->row_array();

        // Jika artikel tdk ditemukan
        if (! $data) {
            return false;
        }

        $data['judul'] = $this->security->xss_clean($data['judul']);
        if (empty($this->setting->user_admin) || $data['id_user'] != $this->setting->user_admin) {
            $data['isi'] = $this->security->xss_clean($data['isi']);
        }

        // Digunakan untuk timepicker
        $tempTgl            = date_create_from_format('Y-m-d H:i:s', $data['tgl_upload']);
        $data['tgl_upload'] = $tempTgl->format('d-m-Y H:i:s');
        // Data artikel terkait agenda
        if (! empty($data['tgl_agenda'])) {
            $tempTgl            = date_create_from_format('Y-m-d H:i:s', $data['tgl_agenda']);
            $data['tgl_agenda'] = $tempTgl->format('d-m-Y H:i:s');
        } else {
            $data['tgl_agenda'] = date('d-m-Y H:i:s');
        }

        return $data;
    }

    public function get_headline()
    {
        $sql = 'SELECT a.*, u.nama AS owner
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			WHERE headline = 1
			ORDER BY tgl_upload DESC LIMIT 1 ';
        $query = $this->db->query($sql);
        $data  = $query->row_array();

        if (empty($data)) {
            $data = null;
        } else {
            $id          = $data['id'];
            $panjang     = str_split($data['isi'], 300);
            $data['isi'] = '<label>' . $panjang[0] . "...</label><a href='" . site_url("artikel/{$id}") . "'>Baca Selengkapnya</a>";
        }

        return $data;
    }

    // TODO: pindahkan dan gunakan web_kategori_model
    public function insert_kategori()
    {
        $data['kategori'] = $_POST['kategori'];
        $data['tipe']     = '2';
        $outp             = $this->db->insert('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function list_komentar($id = 0)
    {
        $sql   = 'SELECT * FROM komentar WHERE id_artikel = ? ORDER BY tgl_upload DESC';
        $query = $this->db->query($sql, $id);

        return $query->result_array();
    }

    public function headline($id = 0)
    {
        $sql1 = 'UPDATE artikel SET headline = 0 WHERE headline = 1';
        $this->db->query($sql1);

        $sql  = 'UPDATE artikel SET headline = 1 WHERE id = ?';
        $outp = $this->db->query($sql, $id);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function slide($id = 0)
    {
        $sql   = 'SELECT * FROM artikel WHERE id = ?';
        $query = $this->db->query($sql, $id);
        $data  = $query->row_array();

        if ($data['headline'] == '3') {
            $sql  = 'UPDATE artikel SET headline = 0 WHERE id = ?';
            $outp = $this->db->query($sql, $id);
        } else {
            $sql  = 'UPDATE artikel SET headline = 3 WHERE id = ?';
            $outp = $this->db->query($sql, $id);
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function boleh_ubah($id, $user)
    {
        // Kontributor hanya boleh mengubah artikel yg ditulisnya sendiri
        $id_user = $this->db->select('id_user')->where('id', $id)->get('artikel')->row()->id_user;

        return $user == $id_user || $this->session->grup != 4;
    }

    public function reset($cat)
    {
        // Normalkan kembali hit artikel kategori 999 (yg ditampilkan di menu) akibat robot (crawler)
        $persen    = $this->input->post('hit');
        $list_menu = $this->db
            ->distinct()
            ->select('link')
            ->like('link', 'artikel/')
            ->where('enabled', 1)
            ->get('menu')
            ->result_array();

        foreach ($list_menu as $list) {
            $id      = str_replace('artikel/', '', $list['link']);
            $artikel = $this->db->where('id', $id)->get('artikel')->row_array();
            $hit     = $artikel['hit'] * ($persen / 100);
            if ($artikel) {
                $this->db->where('id', $id)->update('artikel', ['hit' => $hit]);
            }
        }
    }

    public function list_artikel_statis()
    {
        // '999' adalah id_kategori untuk artikel statis
        $this->group_akses();

        return $this->db
            ->select('a.id, judul')
            ->where('a.id_kategori', '999')
            ->get('artikel a')
            ->result_array();
    }

    private function group_akses()
    {
        // Kontributor dan lainnya (group yg dibuat sendiri) hanya dapat melihat artikel yg dibuatnya sendiri
        if (! in_array($this->session->grup, [1, 2, 3, 4])) {
            $this->db->where('a.id_user', $this->session->user);
        }
    }
}
