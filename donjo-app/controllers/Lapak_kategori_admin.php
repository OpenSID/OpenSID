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

use App\Models\Produk;
use App\Models\ProdukKategori;

class Lapak_kategori_admin extends Admin_Controller
{
    public $modul_ini           = 'lapak';
    public $aliasController     = 'lapak_admin';
    public $kategori_pengaturan = 'lapak';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('pamong_model');
    }

    public function index()
    {
        $data['navigasi'] = Produk::navigasi();

        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');

            $query = ProdukKategori::listKategori()
                ->when($status, static function ($query, $status): void {
                    $query->where('status', $status);
                });

            return datatables($query)
                ->addIndexColumn()
                ->make();
        }

        return view('admin.lapak.kategori.index', $data);
    }

    public function kategori_form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['main']        = ProdukKategori::find($id) ?? show_404();
            $data['form_action'] = site_url("lapak_admin/kategori_update/{$id}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url('lapak_admin/kategori_insert');
        }

        return view('admin.lapak.kategori.form', $data);
    }

    public function kategori_insert(): void
    {
        isCan('u');

        (new ProdukKategori())->kategoriInsert($this->input->post());

        redirect_with('success', 'Berhasil menambah data', 'lapak_admin/kategori');
    }

    public function kategori_update($id = ''): void
    {
        isCan('u');

        (new ProdukKategori())->kategoriUpdate($id, $this->input->post());

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/kategori');
    }

    public function kategori_delete($id): void
    {
        isCan('h');

        if (ProdukKategori::listKategori()->find($id)->jumlah > 0) {
            redirect_with('error', 'Kategori tersebut memiliki produk, silakan hapus terlebih dahulu', 'lapak_admin/kategori');
        } else {
            (new ProdukKategori())->kategoriDelete($id);
        }

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/kategori');
    }

    public function kategori_delete_all(): void
    {
        isCan('h');

        (new ProdukKategori())->kategoriDeleteAll();

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/kategori');
    }

    public function kategori_status($id = 0, $status = 0): void
    {
        isCan('u');

        ProdukKategori::where('id', $id)
            ->update(['status' => $status]);

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/kategori');
    }

    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = site_url("lapak_admin/kategori/aksi/{$aksi}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function aksi($aksi = 'cetak'): void
    {
        $post                   = $this->input->post();
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
        $data['main']           = ProdukKategori::withCount('produk')->get();
        $data['file']           = 'Data Kategori Produk';
        $data['isi']            = 'admin.lapak.kategori.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];

        view('admin.layouts.components.format_cetak', $data);
    }
}
