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

class Mailbox_model extends CI_Model
{
    protected $table = 'komentar';

    /**
     * Gunakan model ini untuk memindahkan semua method terkait mailbox layanan mandiri.
     * Dimana layanan mailbox memiliki perlakuan yang sepenuhnya berbeda dengan komentar web
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('referensi_model');
    }

    public function list_menu()
    {
        return $this->referensi_model->list_ref_flip(KATEGORI_MAILBOX);
    }

    public function get_kat_nama($kat)
    {
        $sub_menu = $this->list_menu();

        return $sub_menu[$kat];
    }

    public function insert($post)
    {
        $data               = [];
        $data['email']      = $post['email'];
        $data['owner']      = $post['owner'];
        $data['tipe']       = $post['tipe'];
        $data['status']     = $post['status'];
        $data['subjek']     = strip_tags($post['subjek']);
        $data['komentar']   = strip_tags($post['komentar']);
        $data['permohonan'] = bilangan($post['permohonan']);
        $data['id_artikel'] = 775;
        $data['tgl_upload'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $outp               = $this->db->insert('komentar', $data);
        status_sukses($outp);
    }

    /**
     * Tipe 1: Inbox untuk admin, Outbox untuk pengguna layanan mandiri
     * Tipe 2: Outbox untuk admin, Inbox untuk pengguna layanan mandiri
     *
     * @param mixed $nik
     * @param mixed $tipe
     */
    public function get_all_pesan($nik = '', $tipe = 1)
    {
        return $this->db
            ->where('email', $nik)
            ->where('tipe', $tipe)
            ->where('id_artikel', 775)
            ->from($this->table)
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_pesan($nik = '', $id = '')
    {
        return $this->db
            ->where('email', $nik)
            ->where('id', $id)
            ->where('id_artikel', 775)
            ->from($this->table)
            ->get()
            ->row_array();
    }

    public function ubah_status_pesan($nik = '', $id = '', $status = 1)
    {
        return $this->db
            ->where('email', $nik)
            ->where('id', $id)
            ->where('tipe', 2)
            ->where('id_artikel', 775)
            ->update('komentar', ['status' => $status]);
    }

    public function count_inbox_pesan($nik = '')
    {
        return $this->db
            ->where('email', $nik)
            ->where('tipe', 2)
            ->where('status', 2)
            ->where('id_artikel', 775)
            ->from($this->table)
            ->count_all_results();
    }
}
