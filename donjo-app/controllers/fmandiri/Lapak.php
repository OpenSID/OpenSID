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

class Lapak extends Mandiri_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lapak_model');
    }

    public function index($p = 1): void
    {
        $data['id_kategori'] = $this->input->get('id_kategori', true);
        $data['keyword']     = $this->input->get('keyword', true);

        // TODO : Sederhanakan bagian panging dengan suffix
        $data['paging']       = $this->lapak_model->paging_produk($p, $data['keyword'], $data['id_kategori']);
        $data['paging_page']  = 'layanan-mandiri/lapak';
        $data['paging_range'] = 3;
        $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']        = range($data['start_paging'], $data['end_paging']);

        if ($data['keyword']) {
            $data['produk'] = $this->lapak_model->get_produk($data['keyword'], 1);
        } else {
            $data['produk'] = $this->lapak_model->get_produk('', 1);
        }

        if ($data['id_kategori'] != '') {
            $data['produk'] = $data['produk']->where('id_produk_kategori', $data['id_kategori']);
        }

        $data['produk']   = $data['produk']->limit($data['paging']->per_page, $data['keyword'] ? 0 : $data['paging']->offset)->get()->result();
        $data['kategori'] = $this->lapak_model->get_kategori()->get()->result();

        $this->render('lapak', $data);
    }
}
