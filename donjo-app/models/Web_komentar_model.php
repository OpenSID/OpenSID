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

class Web_komentar_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('komentar', 'komentar');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('komentar', $cari)
                ->or_like('subjek', $cari);
        }
    }

    private function filter_status_sql(): void
    {
        if ($filter = $this->session->filter_status) {
            $this->db->like('k.status', $filter);
        }
    }

    private function filter_nik_sql(): void
    {
        if ($filter_nik = $this->session->filter_nik) {
            $this->db->where('k.email', $filter_nik);
        }
    }

    private function filter_archived_sql(): void
    {
        $archive = $this->session->filter_archived ?? 0;
        $this->db->where('k.is_archived', $archive);
    }

    public function paging($p = 1, $o = 0, $kat = 0)
    {
        $this->list_data_sql($kat);
        $row      = $this->db->select('count(*) as jml')->get()->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql($kat = 0): void
    {
        $this->config_id('k')
            ->from('komentar k')
            ->join('artikel a', 'k.id_artikel = a.id', 'left');

        if ($kat != 0) {
            $this->db
                ->where('k.tipe', $kat);
            $this->filter_nik_sql();
            $this->filter_archived_sql();
        }

        $this->search_sql();
        $this->filter_status_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500, $kat = 0)
    {
        switch ($o) {
            case 1: $order = 'owner DESC';
                break;

            case 2: $order = 'owner';
                break;

            case 3: $order = 'email DESC';
                break;

            case 4: $order = 'email';
                break;

            case 5: $order = 'komentar DESC';
                break;

            case 6: $order = 'komentar';
                break;

            case 7: $order = 'status DESC';
                break;

            case 8: $order = 'status';
                break;

            case 9: $order = 'tgl_upload DESC';
                break;

            case 10: $order = 'tgl_upload';
                break;

            default: $order = 'tgl_upload DESC';
        }
        $this->list_data_sql($kat);
        $data = $this->db
            ->select('k.*, a.judul as artikel, YEAR(a.tgl_upload) AS thn, MONTH(a.tgl_upload) AS bln, DAY(a.tgl_upload) AS hri, a.slug AS slug')
            ->order_by($order)
            ->limit($limit, $offset)
            ->get()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']    = $j + 1;
            $data[$i]['aktif'] = $data[$i]['status'] == 1 ? 'Ya' : 'Tidak';
            $j++;
        }

        return $data;
    }

    public function list_kategori($tipe = 1)
    {
        return $this->db->get_where('kategori', ['tipe' => $tipe])->result_array();
    }

    private function bersihkan_data($post)
    {
        $data['owner']    = htmlentities($post['owner']);
        $data['no_hp']    = bilangan($post['no_hp']);
        $data['email']    = email($post['email']);
        $data['komentar'] = htmlentities($post['komentar']);
        $data['status']   = bilangan($post['status']);

        return $data;
    }

    public function insert(): void
    {
        $data              = $this->bersihkan_data($this->input->post());
        $data['config_id'] = identitas('id');
        $data['id_user']   = $this->session->user;
        $outp              = $this->db->insert('komentar', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data               = $this->bersihkan_data($this->input->post());
        $data['updated_at'] = date('Y-m-d H:i:s');
        $outp               = $this->config_id()
            ->where('id', $id)
            ->update('komentar', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function archive($id): void
    {
        $archive = [
            'is_archived' => 1,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $outp = $this->config_id()->where('id', $id)->update('komentar', $archive);

        $this->session->success = $outp ? 1 : -1;
    }

    public function archive_all(): void
    {
        $id_cb = $this->input->post('id_cb');

        if (count($id_cb) > 0) {
            foreach ($id_cb as $id) {
                $archive = [
                    'is_archived' => 1,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
                $outp = $this->config_id()->where('id', $id)->update('komentar', $archive);
            }
        } else {
            $outp = false;
        }

        $this->session->success = $outp ? 1 : -1;
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()
            ->where('id', $id)
            ->delete('komentar');

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

    public function komentar_lock($id = '', $val = 0): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('komentar', [
                'status'     => $val,
                'updated_at' => date('Y-m-d H:i:s'), ]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_komentar($id = 0)
    {
        return $this->config_id('a')->where('a.id', $id)->get('komentar a')->row_array();
    }
}
