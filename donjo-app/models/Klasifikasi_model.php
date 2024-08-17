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

class Klasifikasi_model extends MY_model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'klasifikasi_surat');
    }

    public function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('u.nama', $cari)
                ->or_like('u.uraian', $cari)
                ->group_end();
        }
    }

    public function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('enabled', $filter);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $jml_data = $this->db
            ->select('COUNT(*) as jml')
            ->get()->row()->jml;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql(): void
    {
        $this->db
            ->from('klasifikasi_surat u')
            ->where('config_id', identitas('id'));

        $this->search_sql();
        $this->filter_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        //Main Query
        $this->list_data_sql();

        //Ordering SQL
        switch ($o) {
            case 1: $order = 'u.kode';
                break;

            case 2: $order = 'u.kode DESC';
                break;

            case 3: $order = 'u.nama';
                break;

            case 4: $order = 'u.nama DESC';
                break;

            default:$order = 'u.kode';
        }

        $data = $this->db
            ->select('*')
            ->order_by($order)
            ->limit($limit, $offset)
            ->get()
            ->result_array();
        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    // Ambil kode yang aktif untuk ditampilkan di form surat_masuk
    public function list_kode()
    {
        return $this->config_id()
            ->select('kode, nama')
            ->where('enabled', '1')
            ->order_by('kode')
            ->get('klasifikasi_surat')
            ->result_array();
    }

    public function insert()
    {
        $data = $this->input->post();
        $data = $this->sterilkan_data($data);

        $data['config_id'] = identitas('id');

        return $this->db->insert('klasifikasi_surat', $data);
    }

    private function sterilkan_data($data)
    {
        return [
            'kode'   => alfanumerik_titik($data['kode']),
            'nama'   => alfa_spasi($data['nama']),
            'uraian' => strip_tags($data['uraian']),
        ];
    }

    public function update($id = 0)
    {
        $data = $this->input->post();
        $data = $this->sterilkan_data($data);

        return $this->config_id()->where('id', $id)->update('klasifikasi_surat', $data);
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('klasifikasi_surat');

        status_sukses($outp, true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function lock($id = '', $val = 0): void
    {
        $outp                = $this->config_id()->where('id', $id)->update('klasifikasi_surat', ['enabled' => $val]);
        $_SESSION['success'] = $outp ? 1 : -1;
    }

    public function get_klasifikasi($id = 0)
    {
        return $this->config_id()->where('id', $id)->get('klasifikasi_surat')->row_array();
    }

    /**
     * Hapus tabel klasifikasi_surat dan ganti isinya
     * dengan data dari berkas csv.
     * Baris pertama berisi nama kolom tabel.
     *
     * @param mixed $file
     */
    public function impor($file): void
    {
        ini_set('auto_detect_line_endings', '1');
        if (($handle = fopen($file, 'rb')) == false) {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = 'Berkas tidak ada atau bermasalah';

            return;
        }
        $this->db->trans_start();
        $this->config_id()->delete('klasifikasi_surat');
        $header    = fgetcsv($handle);
        $jml_kolom = count($header);

        while (($csv = fgetcsv($handle)) !== false) {
            $data = [];

            for ($c = 0; $c < $jml_kolom; $c++) {
                $data[$header[$c]] = $csv[$c];
                $data['config_id'] = identitas('id');
            }
            $this->db->insert('klasifikasi_surat', $data);
        }
        $this->db->trans_complete();
        fclose($handle);
        $_SESSION['success'] = 1;
    }
}
