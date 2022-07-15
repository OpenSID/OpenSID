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

class Teks_berjalan_model extends MY_Model
{
    private $urut_model;

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . '/models/Urut_model.php';
        $this->urut_model = new Urut_Model('teks_berjalan');
    }

    public function get_teks($id = '')
    {
        $this->sql();

        return $this->db->where('t.id', $id)->get()->row_array();
    }

    /**
     * @param Nilai TRUE untuk Data Ditampilkan Ke Halaman Website/Depan
     * @param mixed $web
     */
    public function list_data($web = false)
    {
        $this->sql();

        if ($web === true) {
            $this->db->where('status', 1);
        }

        $data = $this->db->get()->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']     = $i + 1;
            $data[$i]['tautan'] = $this->menu_slug('artikel/' . $data[$i]['tautan']);
        }

        return $data;
    }

    private function sql()
    {
        $this->db
            ->select('t.*, a.judul, a.tgl_upload')
            ->from('teks_berjalan t')
            ->join('artikel a', 't.tautan = a.id', 'left')
            ->order_by('urut');
    }

    /**
     * @param $id Id teks
     * @param $arah Arah untuk menukar dengan teks: 1) bawah, 2) atas
     *
     * @return int Nomer urut teks lain yang ditukar
     */
    public function urut($id, $arah)
    {
        return $this->urut_model->urut($id, $arah);
    }

    /**
     * @param $id id
     * @param $val status : 1 = Unlock, 2 = Lock
     */
    public function lock($id, $val)
    {
        $this->db->where('id', $id)->update('teks_berjalan', ['status' => $val]);
    }

    public function insert()
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';

        $data           = $this->input->post();
        $data['status'] = 2;
        // insert baru diberi urutan terakhir
        $data['urut']       = $this->urut_model->urut_max() + 1;
        $data               = $this->sanitise_data($data);
        $data['created_by'] = $this->session->user;

        $outp = $this->db->insert('teks_berjalan', $data);

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    private function sanitise_data($data)
    {
        $data['teks']         = htmlentities($data['teks']);
        $data['judul_tautan'] = $data['tautan'] ? htmlentities($data['judul_tautan']) : '';

        return $data;
    }

    public function update($id = 0)
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';

        $data               = $this->input->post();
        $data               = $this->sanitise_data($data);
        $data['updated_by'] = $this->session->user;
        $data['updated_at'] = date('Y-m-d H:i:s');

        $outp = $this->db->where('id', $id)->update('teks_berjalan', $data);

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('teks_berjalan');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }
}
