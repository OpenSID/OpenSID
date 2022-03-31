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

require_once 'vendor/simplehtmldom/simplehtmldom/simple_html_dom.php';

class Surat_master_model extends MY_Model
{
    protected $table = 'tweb_surat_format';

    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'tweb_surat_format');
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari       = $_SESSION['cari'];
            $kw         = $this->db->escape_like_str($cari);
            $kw         = '%' . $kw . '%';
            $search_sql = " AND (u.nama LIKE '{$kw}' OR u.nama LIKE '{$kw}')";

            return $search_sql;
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf         = $_SESSION['filter'];
            $filter_sql = " AND u.jenis = {$kf}";

            return $filter_sql;
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $sql      = 'SELECT COUNT(*) AS jml ' . $this->list_data_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $sql = ' FROM tweb_surat_format u WHERE 1 ';
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();

        return $sql;
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        //Ordering SQL
        switch ($o) {
            case 1:
                $order_sql = ' ORDER BY u.nomor';
                break;

            case 2:
                $order_sql = ' ORDER BY u.nomor DESC';
                break;

            case 3:
                $order_sql = ' ORDER BY u.nama';
                break;

            case 4:
                $order_sql = ' ORDER BY u.nama DESC';
                break;

            case 5:
                $order_sql = ' ORDER BY u.kode_surat';
                break;

            case 6:
                $order_sql = ' ORDER BY u.kode_surat DESC';
                break;

            default:
                $order_sql = ' ORDER BY u.id';
        }

        //Paging SQL
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        //Main Query
        $sql = 'SELECT u.* ' . $this->list_data_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        //Formating Output
        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    public function insert()
    {
        $data = $_POST;
        $this->validasi_surat($data);

        $pemohon_surat = $data['pemohon_surat'];
        unset($data['pemohon_surat']);
        $data['url_surat'] = str_replace(' ', '_', $data['nama']);
        $data['url_surat'] = 'surat_' . strtolower($data['url_surat']);
        /** pastikan belum ada url suratnya */
        if ($this->isExist($data['url_surat'])) {
            $_SESSION['success'] = -2;

            return;
        }
        $outp     = $this->db->insert('tweb_surat_format', $data);
        $raw_path = 'template-surat/raw/';

        // Folder untuk surat ini
        $folder_surat = LOKASI_SURAT_DESA . $data['url_surat'] . '/';
        if (! file_exists($folder_surat)) {
            mkdir($folder_surat, 0777, true);
        }

        if ($pemohon_surat == 'warga') {
            $template = 'template.rtf';
            $form     = 'form.raw';
        } else {
            $template = 'template_non_warga.rtf';
            $form     = 'form_non_warga.raw';
        }

        // index.html untuk menutup akses ke folder melalui browser
        copy($raw_path . 'index.html', $folder_surat . 'index.html');

        //doc
        copy($raw_path . $template, $folder_surat . $data['url_surat'] . '.rtf');

        //form
        $file   = $raw_path . $form;
        $handle = fopen($file, 'rb');
        $buffer = stream_get_contents($handle);
        $berkas = $folder_surat . $data['url_surat'] . '.php';
        $handle = fopen($berkas, 'w+b');
        $buffer = str_replace('[nama_surat]', "Surat {$data['nama']}", $buffer);
        fwrite($handle, $buffer);
        fclose($handle);

        if ($pemohon_surat == 'warga') {
            // cetak
            $file       = $raw_path . 'print.raw';
            $handle     = fopen($file, 'rb');
            $buffer     = stream_get_contents($handle);
            $berkas     = $folder_surat . 'print_' . $data['url_surat'] . '.php';
            $handle     = fopen($berkas, 'w+b');
            $nama_surat = strtoupper($data['nama']);
            $buffer     = str_replace('[nama_surat]', "SURAT {$nama_surat}", $buffer);
            fwrite($handle, $buffer);
            fclose($handle);
        } else {
            // data untuk form
            copy($raw_path . 'data_form_non_warga.raw', $folder_surat . 'data_form_' . $data['url_surat'] . '.php');
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    private function validasi_surat(&$data)
    {
        $data['nama'] = alfanumerik_spasi($data['nama']);
    }

    public function update($id = 0)
    {
        $data = $_POST;
        $this->validasi_surat($data);

        $before = $this->get_surat_format($id);

        $outp = $this->db
            ->where('id', $id)
            ->update($this->table, $data);

        if ($outp) {
            $surat_baru  = 'surat_' . str_replace(' ', '_', strtolower($data['nama']));
            $lokasi_baru = LOKASI_SURAT_DESA . $surat_baru;

            // Ubah nama folder penyimpanan template surat
            rename($before['lokasi_surat'], $lokasi_baru);

            // Ubah nama file surat
            rename($lokasi_baru . '/' . $before['url_surat'] . '.rtf', $lokasi_baru . '/' . $surat_baru . '.rtf');
            rename($lokasi_baru . '/' . $before['url_surat'] . '.php', $lokasi_baru . '/' . $surat_baru . '.php');
            rename($lokasi_baru . '/data_rtf_' . $before['url_surat'] . '.php', $lokasi_baru . '/data_rtf_' . $surat_baru . '.php');
            rename($lokasi_baru . '/data_form_' . $before['url_surat'] . '.php', $lokasi_baru . '/data_form_' . $surat_baru . '.php');
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function upload($url = '')
    {
        $_SESSION['success']   = 1;
        $_SESSION['error_msg'] = '';

        // Folder desa untuk surat ini
        $folder_surat = LOKASI_SURAT_DESA . $url . '/';
        if (! file_exists($folder_surat)) {
            mkdir($folder_surat, 0755, true);
        }
        // index.html untuk menutup akses ke folder melalui browser
        copy('template-surat/raw/' . 'index.html', $folder_surat . 'index.html');

        $nama_file_rtf = $url . '.rtf';
        $this->uploadBerkas('rtf', $folder_surat, 'foto', 'surat_master', $nama_file_rtf);
        $this->salin_lampiran($url, $folder_surat);
    }

    // Lampiran surat perlu disalin ke folder surata di LOKASI_SURAT_DESA, karena
    // file lampiran surat dianggap ada di folder yang sama dengan tempat template surat RTF
    private function salin_lampiran($url, $folder_surat)
    {
        $this->load->model('surat_model');
        $surat = $this->surat_model->get_surat($url);
        if (! $surat['lampiran']) {
            return;
        }

        // $lampiran_surat dalam bentuk seperti "f-1.08.php, f-1.25.php, f-1.27.php"
        $daftar_lampiran = explode(',', $surat['lampiran']);

        foreach ($daftar_lampiran as $lampiran) {
            if (! file_exists($folder_surat . $lampiran)) {
                copy('template-surat/' . $url . '/' . $lampiran, $folder_surat . $lampiran);
            }
        }
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        // ambil data surat sebelum dihapus
        $before = $this->get_surat_format($id);

        // Surat jenis sistem (nilai 1) tidak bisa dihapus
        $outp = $this->db->where('id', $id)->where('jenis !=', '1')->delete($this->table);

        if ($outp) {
            //hapus file dan folder penyimpanan template surat
            delete_files($before['lokasi_surat'], true, false, 1);
        }

        status_sukses($outp, true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, true);
        }
    }

    public function get_surat_format($id = 0)
    {
        $surat = $this->db
            ->get_where($this->table, ['id' => $id])
            ->row_array();

        $surat['lokasi_surat'] = LOKASI_SURAT_DESA . $surat['url_surat'];

        return $surat;
    }

    public function get_kode_isian($surat)
    {
        // Lokasi instalasi SID mungkin di sub-folder
        include FCPATH . '/vendor/simple_html_dom.php';
        $path_bawaan = FCPATH . '/template-surat/' . $surat['url_surat'] . '/' . $surat['url_surat'] . '.php';
        $path_lokal  = FCPATH . LOKASI_SURAT_DESA . $surat['url_surat'] . '/' . $surat['url_surat'] . '.php';
        if (file_exists($path_lokal)) {
            $html = file_get_html($path_lokal);
        } elseif (file_exists($path_bawaan)) {
            $html = file_get_html($path_bawaan);
        } else {
            return [];
        }
        // Kumpulkan semua isian (tag input) di form surat
        // Asumsi di form surat, struktur input seperti ini
        // <tr>
        // 		<th>Keterangan Isian</th>
        // 		<td><input><td>
        // </tr>
        $inputs = [];

        foreach ($html->find('input') as $input) {
            if ($input->type == 'hidden') {
                continue;
            }
            if ($input->title == 'Pilih Tanggal') {
                $inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;

                continue;
            }
            if ($input->type == 'radio') {
                $inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;

                continue;
            }
            if ($input->id == 'jam_1') {
                $inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;

                continue;
            }
            if ($input->id == 'input_group') {
                $inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;

                continue;
            }
            $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
        }

        foreach ($html->find('textarea') as $input) {
            if ($input->type == 'hidden') {
                continue;
            }
            $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
        }

        foreach ($html->find('select') as $input) {
            if ($input->type == 'hidden') {
                continue;
            }
            $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
        }

        $html->clear();
        unset($html);

        return $inputs;
    }

    public function favorit($id = 0, $k = 0)
    {
        if ($k == 1) {
            $sql = 'UPDATE tweb_surat_format SET favorit = 0 WHERE id = ?';
        } else {
            $sql = 'UPDATE tweb_surat_format SET favorit = 1 WHERE id = ?';
        }

        $outp = $this->db->query($sql, $id);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function lock($id = 0, $k = 0)
    {
        if ($k == 1) {
            $sql = 'UPDATE tweb_surat_format SET kunci = 0 WHERE id = ?';
        } else {
            $sql = 'UPDATE tweb_surat_format SET kunci = 1 WHERE id = ?';
        }

        $outp = $this->db->query($sql, $id);

        status_sukses($outp); //Tampilkan Pesan
    }

    // Tambahkan surat desa jika folder surat tidak ada di surat master
    public function perbaharui_surat_desa()
    {
        $folder_surat_desa = glob(LOKASI_SURAT_DESA . '*', GLOB_ONLYDIR);
        $daftar_surat      = [];

        if ($folder_surat_desa) {
            foreach ($folder_surat_desa as $surat) {
                $surat = str_replace(LOKASI_SURAT_DESA, '', $surat);

                if (! $this->isExist($surat)) {
                    $data              = [];
                    $data['jenis']     = 2;
                    $data['url_surat'] = $surat;
                    $data['nama']      = strtolower(trim(str_replace(['surat', '-', '_'], ' ', $surat)));

                    $this->db->insert('tweb_surat_format', $data);
                }

                $daftar_surat[] = $surat;
            }

            // Hapus surat ubahan desa yg sudah tidak ada
            $this->db
                ->where('jenis', 2)
                ->where_not_in('url_surat', $daftar_surat)
                ->delete($this->table);
        }

        status_sukses(true);
    }

    /**
     * - success: nama berkas yang diunggah
     * - fail: NULL
     *
     * @param mixed $allowed_types
     * @param mixed $upload_path
     * @param mixed $lokasi
     * @param mixed $redirect
     * @param mixed $nama_file
     *
     * @return
     */
    private function uploadBerkas($allowed_types, $upload_path, $lokasi, $redirect, $nama_file)
    {
        // Untuk dapat menggunakan library upload
        $this->load->library('upload');
        // Untuk dapat menggunakan fungsi generator()
        $this->load->helper('donjolib');
        $this->upload_config = [
            'upload_path'   => $upload_path,
            'allowed_types' => $allowed_types,
            'max_size'      => max_upload() * 1024,
            'file_name'     => $nama_file,
            'overwrite'     => true,
        ];
        // Adakah berkas yang disertakan?
        $ada_berkas = ! empty($_FILES[$lokasi]['name']);
        if ($ada_berkas !== true) {
            return null;
        }
        // Tes tidak berisi script PHP
        if (isPHP($_FILES[$lokasi]['tmp_name'], $_FILES[$lokasi]['name'])) {
            $_SESSION['error_msg'] .= ' -> Jenis file ini tidak diperbolehkan ';
            $_SESSION['success'] = -1;
            redirect($redirect);
        }

        $upload_data = null;
        // Inisialisasi library 'upload'
        $this->upload->initialize($this->upload_config);
        // Upload sukses
        if ($this->upload->do_upload($lokasi)) {
            $upload_data = $this->upload->data();
        }
        // Upload gagal
        else {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = $this->upload->display_errors(null, null);
        }

        return (! empty($upload_data)) ? $upload_data['file_name'] : null;
    }

    private function isExist($url_surat)
    {
        $sudahAda = $this->db->select('count(*) ada')
            ->where(['url_surat' => $url_surat])
            ->get('tweb_surat_format')->row_array();

        return $sudahAda['ada'];
    }

    public function get_syarat_surat($id = 1)
    {
        return $this->db->select('r.ref_syarat_id, r.ref_syarat_nama')
            ->where('surat_format_id', $id)
            ->from('syarat_surat s')
            ->join('ref_syarat_surat r', 's.ref_syarat_id = r.ref_syarat_id')
            ->order_by('ref_syarat_id')
            ->get()
            ->result_array();
    }
}
