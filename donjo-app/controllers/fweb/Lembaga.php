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

class Lembaga extends Web_Controller
{
    protected $tipe = 'lembaga';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kelompok_model');
        $this->kelompok_model->set_tipe($this->tipe);
    }

    public function detail($slug = null)
    {
        $id = $this->kelompok_model->slug($slug);

        if (! $this->web_menu_model->menu_aktif("data-lembaga/{$id}")) {
            show_404();
        }

        $data = $this->includes;

        $data['detail']   = $this->kelompok_model->get_kelompok($id);
        $data['title']    = 'Data Lembaga ' . $data['detail']['nama'];
        $data['anggota']  = $this->kelompok_model->list_anggota(0, 0, 500, $id, 'anggota');
        $data['pengurus'] = $this->kelompok_model->list_pengurus($id);

        // Jika lembaga tdk tersedia / sudah terhapus pd modul lembaga
        if ($data['detail'] == null) {
            show_404();
        }

        $this->_get_common_data($data);
        $this->set_template('layouts/kelompok.tpl.php');
        $this->load->view($this->template, $data);
    }
}
