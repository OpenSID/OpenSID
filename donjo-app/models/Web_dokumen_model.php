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

use App\Libraries\Paging;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_dokumen_model extends MY_Model
{
    // Untuk datatables informasi publik
    public $table         = 'dokumen_hidup';
    public $column_order  = [null, 'nama', 'tahun', 'kategori_info_publik', 'tgl_upload']; //set column field database for datatable orderable
    public $column_search = ['nama']; //set column field database for datatable searchable
    public $order         = ['id' => 'asc']; // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('referensi_model');
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'dokumen_hidup');
    }

    // Ambil semua peraturan
    public function all_peraturan($kategori = '', $tahun = '', $isi = '')
    {
        $kategori_peraturan = ['2', '3'];
        $this->db->select('dokumen.id, satuan, dokumen.nama, tahun, ref_dokumen.nama as kategori');
        $this->db->join('ref_dokumen', 'ref_dokumen.id = dokumen.kategori', 'left');
        $this->db->where('dokumen.enabled', 1);
        $this->db->where_in('ref_dokumen.id', $kategori_peraturan);

        if ($kategori) {
            $this->db->where('dokumen.kategori', $kategori);
        }
        if ($tahun) {
            $this->db->where('tahun', $tahun);
        }
        if ($isi) {
            $this->db->like('dokumen.nama', $isi);
        }
        $this->db->order_by('dokumen.tahun DESC', 'dokumen.kategori ASC', 'dokumen.nama ASC');
        $this->config_id('dokumen');

        return $this->db->from('dokumen_hidup as dokumen')->get()->result_array();
    }

    // ================= informasi publik ===================
    // https://mbahcoding.com/tutorial/php/codeigniter/codeigniter-simple-server-side-datatable-example.html

    private function get_all_informasi_publik_query(): void
    {
        $this->config_id()
            ->from($this->table)
            ->where('id_pend', '0')
            ->where('enabled', '1')
            ->where('id_pend', '0');
    }

    private function get_informasi_publik_query(): void
    {
        $this->get_all_informasi_publik_query();

        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end();
                } //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif ($this->order !== null) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_informasi_publik()
    {
        $this->get_informasi_publik_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    public function count_informasi_publik_filtered()
    {
        $this->get_informasi_publik_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function count_informasi_publik_all()
    {
        $this->get_all_informasi_publik_query();

        return $this->db->count_all_results();
    }

    // ============== akhir informasi publik ===================

    // Lists Tahun Dokumen untuk web first
    public function tahun_dokumen()
    {
        $this->db->select('tahun');
        $this->db->group_by('tahun');
        $this->config_id();

        return $this->db->from($this->table)->get()->result_array();
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('satuan', $cari)
                ->or_like('nama', $cari)
                ->group_end();
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('enabled', $filter);
        }
    }

    private function jenis_peraturan_sql($kat): void
    {
        // Jenis peraturan ada di kolom attr dalam bentuk json
        if ($kat == 3 && ($jenis = $this->session->jenis_peraturan)) {
            $attr = '"jenis_peraturan":"' . $jenis . '"';
            $this->db->like('attr', $attr, 'both', false);
        }
    }

    private function filter_tahun($kat): void
    {
        if ($tahun = $this->session->tahun) {
            switch ($kat) {
                case '1':
                    // Informasi publik, termasuk kategori lainnya
                    $this->db->where('tahun', $tahun);
                    break;

                case '2':
                    // SK KADES
                    $attr_str = '"tgl_kep_kades":';
                    $this->db->like("SUBSTR(attr FROM LOCATE('{$attr_str}', attr)+LENGTH('{$attr_str}')+7 FOR 4)", $tahun, 'both')
                        ->where('kategori', $kat);
                    break;

                case '3':
                    // PERDES
                    $attr_str = '"tgl_ditetapkan":';
                    $this->db->like("SUBSTR(attr FROM LOCATE('{$attr_str}', attr)+LENGTH('{$attr_str}')+7 FOR 4)", $tahun, 'both')
                        ->where('kategori', $kat);
                    break;
            }
        }
    }

    private function list_data_sql($kat): void
    {
        $this->config_id()
            ->from('dokumen_hidup')
            ->where('id_pend', 0);

        // $kat == 1 adalah informasi publik dan mencakup juga jenis dokumen lain termasuk SK Kades dan Perdes
        if ($kat != '1') {
            $this->db->where('kategori', $kat);
        }
        $this->search_sql();
        $this->filter_sql();
        $this->jenis_peraturan_sql($kat);
        $this->filter_tahun($kat);
    }

    public function paging($kat, $p = 1, $o = 0)
    {
        $this->list_data_sql($kat);
        $jml_data = $this->db
            ->select('COUNT(*) as jml')
            ->get()->row()->jml;

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    public function list_data($kat, $o = 0, $offset = 0, $limit = 500)
    {
        $this->list_data_sql($kat);

        switch ($o) {
            case 1:
                $order = ' nama';
                break;

            case 2:
                $order = ' nama DESC';
                break;

            case 3:
                $order = ' enabled';
                break;

            case 4:
                $order = ' enabled DESC';
                break;

            case 5:
                $order = ' tgl_upload';
                break;

            case 6:
                $order = ' tgl_upload DESC';
                break;

            default:
                $order = ' id';
        }
        $data = $this->db
            ->select('*')
            ->order_by($order)
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']   = $j + 1;
            $data[$i]['attr'] = json_decode($data[$i]['attr'], true);
            // Ambil keterangan kategori publik
            if ($data[$i]['kategori_info_publik']) {
                $data[$i]['kategori_info_publik'] = $this->referensi_model->list_ref_flip(KATEGORI_PUBLIK)[$data[$i]['kategori_info_publik']];
            }

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';

            $j++;
        }

        return $data;
    }

    private function upload_dokumen()
    {
        $old_file                = $this->input->post('old_file', true);
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name']     = namafile($this->input->post('nama', true));

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('satuan')) {
            session_error($this->upload->display_errors(null, null));

            return false;
        }

        if (empty($old_file)) {
            unlink(LOKASI_DOKUMEN . $old_file);
        }

        return $this->upload->data()['file_name'];
    }

    public function insert($mandiri = false)
    {
        $retval = true;
        $post   = $this->input->post();
        $data   = $this->validasi($post);

        $data['config_id'] = $this->config_id;

        if (! empty($post['satuan'])) {
            $data['satuan'] = $result = $this->upload_dokumen();
            if ($result == false) {
                return false;
            }
        }

        if ($result === null && $data['tipe'] == 1) {
            return false;
        }

        $data['attr']      = json_encode($data['attr'], JSON_THROW_ON_ERROR);
        $data['dok_warga'] = isset($post['dok_warga']);
        // Dari layanan mandiri gunakan NIK penduduk
        $data['created_by'] = $mandiri ? $this->session->nik : $this->session->user;

        unset($data['anggota_kk']);
        $retval &= $this->db->insert('dokumen', $data);
        $insert_id = $this->db->insert_id();

        if ($retval !== 0) {
            $data['id_parent'] = $insert_id;

            foreach ($post['anggota_kk'] as $value) {
                $data['id_pend'] = $value;
                $retval &= $this->db->insert('dokumen', $data);
            }
        }

        return $retval;
    }

    public function validasi($post)
    {
        $data                         = [];
        $data['nama']                 = nomor_surat_keputusan($post['nama']);
        $data['kategori']             = (int) $post['kategori'] ?: 1;
        $data['kategori_info_publik'] = (int) $post['kategori_info_publik'] ?: null;
        $data['id_syarat']            = (int) $post['id_syarat'] ?: null;
        $data['id_pend']              = (int) $post['id_pend'] ?: 0;
        $data['tipe']                 = (int) $post['tipe'];
        $data['url']                  = $this->security->xss_clean($post['url']) ?: null;

        if ($data['tipe'] == 1) {
            $data['url'] = null;
        }

        switch ($data['kategori']) {
            case 1: //Informsi Publik
                $data['tahun'] = $post['tahun'];
                break;

            case 2: //SK Kades
                $data['tahun']                 = date('Y', strtotime($post['attr']['tgl_kep_kades']));
                $data['kategori_info_publik']  = '3';
                $data['attr']['tgl_kep_kades'] = $post['attr']['tgl_kep_kades'];
                $data['attr']['uraian']        = $this->security->xss_clean($post['attr']['uraian']);
                $data['attr']['no_kep_kades']  = nomor_surat_keputusan($post['attr']['no_kep_kades']);
                $data['attr']['no_lapor']      = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['tgl_lapor']     = $post['attr']['tgl_lapor'];
                $data['attr']['keterangan']    = $this->security->xss_clean($post['attr']['keterangan']);
                break;

            case 3: //Perdes
                $data['tahun']                     = date('Y', strtotime($post['attr']['tgl_ditetapkan']));
                $data['kategori_info_publik']      = '3';
                $data['attr']['tgl_ditetapkan']    = $post['attr']['tgl_ditetapkan'];
                $data['attr']['tgl_lapor']         = $post['attr']['tgl_lapor'];
                $data['attr']['tgl_kesepakatan']   = $post['attr']['tgl_kesepakatan'];
                $data['attr']['uraian']            = $this->security->xss_clean($post['attr']['uraian']);
                $data['attr']['jenis_peraturan']   = htmlentities($post['attr']['jenis_peraturan']);
                $data['attr']['no_ditetapkan']     = nomor_surat_keputusan($post['attr']['no_ditetapkan']);
                $data['attr']['no_lapor']          = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['no_lembaran_desa']  = nomor_surat_keputusan($post['attr']['no_lembaran_desa']);
                $data['attr']['no_berita_desa']    = nomor_surat_keputusan($post['attr']['no_berita_desa']);
                $data['attr']['tgl_lembaran_desa'] = $post['attr']['tgl_lembaran_desa'];
                $data['attr']['tgl_berita_desa']   = $post['attr']['tgl_berita_desa'];
                $data['attr']['keterangan']        = htmlentities($post['attr']['keterangan']);
                break;

            default:
                $data['tahun'] = date('Y');
                break;
        }

        return $data;
    }

    // $mandiri = true kalau dipanggil dari layanan mandiri
    public function update($id = 0, $id_pend = null, $mandiri = false)
    {
        $retval = true;

        $post = $this->input->post();
        $data = $this->validasi($post);
        // Jangan simpan dok_warga kalau dari Layanan Mandiri
        if (! $mandiri) {
            $data['dok_warga'] = isset($post['dok_warga']);
        }
        $old_file = $this->config_id()
            ->select('satuan')
            ->where('id', $id)
            ->get('dokumen')
            ->row()
            ->satuan;
        $data['satuan'] = $old_file;
        if (! empty($post['satuan'])) {
            $data['satuan'] = $this->upload_dokumen();
            $retval &= ! (empty($data['satuan']));
            if ($retval === 0) {
                return $retval;
            }
        }
        $data['attr']       = json_encode($data['attr'], JSON_THROW_ON_ERROR);
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Dari layanan mandiri gunakan NIK penduduk
        $data['updated_by'] = $mandiri ? $this->session->nik : $this->session->user;

        unset($data['anggota_kk']);

        if ($id_pend) {
            $this->db->where('id_pend', $id_pend);
        }
        $retval &= $this->config_id()->where('id', $id)->update('dokumen', $data);

        $retval &= $this->update_dok_anggota($id, $post, $data);

        status_sukses($retval);

        return $retval;
    }

    private function update_dok_anggota($id, $post, $data)
    {
        $retval = true;

        // cek jika dokumen ini juga ada di anggota yang lain
        $anggota_kk   = $post['anggota_kk'] ?? [];
        $anggota_lain = array_column($this->get_dokumen_di_anggota_lain($id), 'id_pend') ?? [];

        // cari intersect anggota
        unset($data['id_pend']);
        $intersect_id_pend = array_intersect($anggota_kk, $anggota_lain);

        foreach ($intersect_id_pend as $value) {
            $retval &= $this->config_id()->where('id_pend', $value)->where('id_parent', $id)->update('dokumen', $data);
        }

        // cari diff anggota (jika ada anggota yang diuncheck - delete)
        if (isset($anggota_kk)) {
            $diff_id_pend = array_diff($anggota_lain, $anggota_kk);

            foreach ($diff_id_pend as $value) {
                $retval &= $this->config_id()->delete('dokumen', ['id_pend' => $value, 'id_parent' => $id]);
            }  // hard delete
        } else {
            foreach ($anggota_lain as $value) {
                $retval &= $this->config_id()->delete('dokumen', ['id_pend' => $value, 'id_parent' => $id]);
            }  // hard delete
        }

        // cari diff anggota (jika ada anggota tambahan yang dicheck -> insert)
        $diff_id_pend = array_diff($anggota_kk, $anggota_lain);
        unset($data['updated_at']);

        foreach ($diff_id_pend as $value) {
            $data['id_pend']   = $value;
            $data['id_parent'] = $id;
            $data['config_id'] = $this->config_id;
            $retval &= $this->db->insert('dokumen', $data);    // insert new data
        }

        return $retval;
    }

    // Soft delete, tapi hapus berkas dokumen
    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $old_dokumen = $this->config_id()
            ->select('satuan')
            ->where('id', $id)
            ->get('dokumen')
            ->row()
            ->satuan;

        $data = [
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted'    => 1,
        ];
        $outp = $this->config_id()->where('id', $id)->update('dokumen', $data);
        if ($outp) {
            unlink(LOKASI_DOKUMEN . $old_dokumen);
        } else {
            $_SESSION['success'] = -1;
        }

        // cek jika dokumen ini juga ada di anggota yang lain
        $anggota_lain = $this->get_dokumen_di_anggota_lain($id);

        // soft delete dokumen anggota lain jika ada
        foreach ($anggota_lain as $item) {
            $this->config_id()->where('id', $item['id'])->update('dokumen', $data);
        }

        return $outp;
    }

    public function hard_delete_dokumen_bersama($id_pend)
    {
        return $this->config_id()->delete('dokumen', ['id_pend' => $id_pend, 'id_parent >' => '0']);
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function dokumen_lock($id = '', $val = 0): void
    {
        $outp = $this->config_id()
            ->set('enabled', $val)
            ->where('id', $id)
            ->update('dokumen');

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_dokumen($id = 0, $id_pend = null)
    {
        if ($id_pend) {
            $this->db->where('id_pend', $id_pend);
        }
        $data = $this->config_id()
            ->from($this->table)
            ->where('id', $id)
            ->get()
            ->row_array();

        if ($data) {
            $data['attr'] = json_decode($data['attr'], true);

            return array_filter($data);
        }

        return null;
    }

    public function get_dokumen_di_anggota_lain($id_dokumen = 0)
    {
        $data = $this->config_id()
            ->from($this->table)
            ->where('id_parent', $id_dokumen)
            ->get()->result_array();

        foreach ($data as $key => $value) {
            $data[$key]['attr'] = json_decode($data[$key]['attr'], true);
            $data[$key]         = array_filter($data[$key]);
        }

        return $data;
    }

    /**
     * Ambil nama berkas dari database berdasarkan id dokumen
     *
     * @param string $id      Id pada tabel dokumen
     * @param mixed  $id_pend
     *
     * @return string|null
     */
    public function get_nama_berkas($id, $id_pend = 0)
    {
        // Ambil nama berkas dari database untuk dokumen yg aktif
        if ($id_pend) {
            $this->db->where('id_pend', $id_pend);
        }

        return $this->config_id()
            ->select('satuan')
            ->where('id', $id)
            ->where('enabled', 1)
            ->get('dokumen')->row()->satuan;
    }

    public function kat_nama($kat = 1)
    {
        $kategori = $this->list_kategori();
        $kat_nama = $kategori[$kat];
        if (empty($kat_nama)) {
            return $kategori[0];
        }

        return $kat_nama;
    }

    public function list_kategori()
    {
        return $this->referensi_model->list_nama('ref_dokumen');
    }

    public function list_tahun($kat = 1)
    {
        // Data tanggal berbeda menurut kategori dokumen
        // Informasi masing2 kategori dokumen tersimpan dalam format json di kolom attr
        // MySQL baru memiliki fitur query json mulai dari 5.7; jadi di sini dilakukan secara manual

        switch ($kat) {
            case '1':
                // Informasi publik, termasuk kategori lainnya
                $this->db->select('tahun');
                break;

            case '2':
                // SK KADES
                $attr_str = '"tgl_kep_kades":';
                $this->db->select("SUBSTR(attr FROM LOCATE('{$attr_str}', attr)+LENGTH('{$attr_str}')+7 FOR 4) AS tahun")
                    ->where('kategori', $kat);
                break;

            case '3':
                // PERDES
                $attr_str = '"tgl_ditetapkan":';
                $this->db->select("SUBSTR(attr FROM LOCATE('{$attr_str}', attr)+LENGTH('{$attr_str}')+7 FOR 4) AS tahun")
                    ->where('kategori', $kat);
                break;
        }

        return $this->config_id()
            ->distinct()
            ->from($this->table)
            ->order_by('tahun DESC')
            ->get()
            ->result_array();
    }

    public function data_cetak($kat = 1, $tahun = '', $jenis_peraturan = '')
    {
        if (! empty($tahun)) {
            switch ($kat) {
                case '1':
                    // Informasi publik
                    $this->db->where('tahun', $tahun);
                    break;

                    // Data tanggal berbeda menurut kategori dokumen
                    // Informasi masing2 kategori dokumen tersimpan dalam format json di kolom attr
                    // MySQL baru memiliki fitur query json mulai dari 5.7; jadi di sini dilakukan secara manual
                case '2':
                    // SK KADES
                    $regex = '"tgl_kep_kades":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
                    $this->db->where("attr REGEXP '" . $regex . "'");
                    break;

                case '3':
                    // PERDES
                    $regex = '"tgl_ditetapkan":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
                    $this->db->where("attr REGEXP '" . $regex . "'");
                    break;
            }
        }
        // Filter jenis peraturan hanya untuk peraturan desa
        if ($kat == 3 && $jenis_peraturan) {
            $like = '"jenis_peraturan":"' . $jenis_peraturan . '"';
            // $this->db->where("attr REGEXP '" . $regex . "'");
            $this->db->like('attr', $like, 'both', false);
        }
        // Informasi publik termasuk kategori lainnya
        if ($kat != '1') {
            $this->db->where('kategori', $kat);
        }
        $data = $this->config_id()
            ->select('*')
            ->from($this->table)
            ->where('id_pend', '0')
            ->where('enabled', '1')
            ->get()
            ->result_array();

        foreach ($data as $i => $dok) {
            $data[$i]['no']   = $i + 1;
            $data[$i]['attr'] = json_decode($dok['attr'], true);
        }

        return $data;
    }

    public function data_ppid($tgl_dari = null)
    {
        $kode_desa = setting('kode_desa');

        $lokasi_dokumen = base_url('dokumen_web/unduh_berkas/');
        $this->db->select("id, '{$kode_desa}' as kode_desa, CONCAT('{$lokasi_dokumen}', id) as dokumen, nama, tgl_upload, updated_at, enabled, kategori_info_publik as kategori, tahun");

        return empty($tgl_dari) ? $this->ekspor_semua_data() : $this->ekspor_perubahan_data($tgl_dari);
    }

    public function ekspor_informasi_publik($data_ekspor, $tgl_dari = null)
    {
        $kode_desa = identitas('kode_desa');
        $this->db->select("id, '{$kode_desa}' as kode_desa, satuan, nama, tgl_upload, updated_at, enabled, kategori_info_publik as kategori, tahun");

        return $data_ekspor == 1 ? $this->ekspor_semua_data() : $this->ekspor_perubahan_data($tgl_dari);
    }

    // Semua informasi publik diekspor termasuk yg tidak aktif dan yg telah dihapus
    private function ekspor_semua_data()
    {
        // Hanya data yg 'hidup'
        return $this->config_id()
            ->select("'0' as aksi")
            ->from($this->table)
            ->where('id_pend', '0')
            ->order_by('id')
            ->get()->result_array();
    }

    /*
        aksi:
        1 - tambah baru
        2 - berubah
        3 - dihapus
    */
    private function ekspor_perubahan_data($tgl_dari)
    {
        $this->db->select("
			(CASE when deleted = 1
				then '3'
				else
					case when DATE(tgl_upload) > STR_TO_DATE('{$tgl_dari}', '%d-%m-%Y')
						then '1'
						else '2'
					end
				end) as aksi
		");

        // Termasuk data yg sudah dihapus
        return $this->config_id()
            ->from('dokumen')
            ->where('id_pend', '0')
            ->where("DATE(updated_at) > STR_TO_DATE('{$tgl_dari}', '%d-%m-%Y')")
            ->order_by('id')
            ->get()->result_array();
    }
}
