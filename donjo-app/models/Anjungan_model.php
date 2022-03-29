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

class Anjungan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cek_anjungan($mac_address = null)
    {
        $ip          = $this->input->ip_address();
        $mac_address = $mac_address ?: $this->session->mac_address;

        $this->db
            ->group_start()
            ->where('ip_address', $ip);
        if ($mac_address) {
            $this->db->or_where('mac_address', $mac_address);
        }
        $data = $this->db
            ->group_end()
            ->where('status', 1)
            ->get('anjungan');

        return ($data->num_rows() > 0) ? $data->row_array() : null;
    }

    public function list_data()
    {
        return $this->db
            ->order_by('ip_address')
            ->get('anjungan')
            ->result_array();
    }

    public function insert()
    {
        $data               = $this->validasi($this->input->post());
        $data['created_by'] = $this->session->user;
        $data['created_at'] = date('Y-m-d H:i:s');
        $outp               = $this->db->insert('anjungan', $data);
        status_sukses($outp);
    }

    private function validasi($post)
    {
        $data['ip_address']   = bilangan_titik($post['ip_address']);
        $data['printer_ip']   = bilangan_titik($post['printer_ip']);
        $data['printer_port'] = bilangan($post['printer_port']);
        $data['mac_address']  = alfanumerik_kolon($post['mac_address']);
        $data['keterangan']   = htmlentities($post['keterangan']);
        $data['keyboard']     = bilangan($post['keyboard']);
        $data['status']       = bilangan($post['status']);
        $data['updated_by']   = $this->session->user;

        return $data;
    }

    public function delete($id)
    {
        $outp = $this->db->where('id', $id)->delete('anjungan');
        status_sukses($outp);
    }

    public function update($id)
    {
        $data               = $this->validasi($this->input->post());
        $data['updated_at'] = date('Y-m-d H:i:s');
        $outp               = $this->db->where('id', $id)->update('anjungan', $data);

        status_sukses($outp);
    }

    public function get_anjungan($id)
    {
        return $this->db
            ->where('id', $id)
            ->get('anjungan')->row_array();
    }

    /**
     * @param $id id
     * @param $val status : 1 = Unlock, 2 = Lock
     */
    public function lock($id, $val)
    {
        $outp = $this->db
            ->where('id', $id)
            ->update('anjungan', ['status' => $val]);
        status_sukses($outp);
    }
}
