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

use App\Models\Pelapak;
use App\Models\Produk;
use App\Models\ProdukKategori;

defined('BASEPATH') || exit('No direct script access allowed');

class Lapak_admin extends Admin_Controller
{
    public $modul_ini           = 'lapak';
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

        if ($data['navigasi']['jml_pelapak']['aktif'] <= 0) {
            redirect_with('error', 'Pelapak tidak tersedia, silakan tambah pelapak terlebih dahulu', "{$this->controller}/pelapak");
        }

        if ($data['navigasi']['jml_kategori']['aktif'] <= 0) {
            redirect_with('error', 'Kategori tidak tersedia, silakan tambah kategori terlebih dahulu', "{$this->controller}/kategori");
        }

        if ($this->input->is_ajax_request()) {
            $status             = $this->input->get('status');
            $id_pend            = $this->input->get('id_pend');
            $id_produk_kategori = $this->input->get('id_produk_kategori');

            $query = Produk::listProduk()
                ->when($status, static function ($query, $status): void {
                    $query->where('produk.status', $status);
                })
                ->when($id_pend, static function ($query, $id_pend): void {
                    $query->where('p.id', $id_pend);
                })
                ->when($id_produk_kategori, static function ($query, $id_produk_kategori): void {
                    $query->where('pk.id', $id_produk_kategori);
                });

            return datatables($query)
                ->addIndexColumn()
                ->editColumn('deskripsi', static fn ($row) => e($row->deskripsi))
                ->make();
        }

        $data['pelapak']  = Pelapak::listPelapak()->where('pelapak.status', 1)->get();
        $data['kategori'] = ProdukKategori::listKategori()->where('produk_kategori.status', 1)->get();

        return view('admin.lapak.produk.index', $data);
    }

    public function produk_form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['main']        = Produk::listProduk()->where('produk.id', $id)->first() ?? show_404();
            $data['aksi']        = 'Ubah';
            $data['form_action'] = site_url("{$this->controller}/produk_update/{$id}");
        } else {
            $data['main']                = new stdClass();
            $data['main']->tipe_potongan = 1;
            $data['aksi']                = 'Tambah';
            $data['form_action']         = site_url("{$this->controller}/produk_insert");
        }

        $data['pelapak']  = Pelapak::listPelapak()->where('pelapak.status', 1)->get();
        $data['kategori'] = ProdukKategori::listKategori()->where('produk_kategori.status', 1)->get();
        $data['satuan']   = Produk::listSatuan();

        return view('admin.lapak.produk.form', $data);
    }

    public function produk_insert(): void
    {
        isCan('u');

        if ((new Produk())->produkInsert($this->input->post())) {
            redirect_with('success', 'Berhasil menambah data', "{$this->controller}/produk");
        }

        redirect_with('error', 'Gagal menambah data', "{$this->controller}/produk");
    }

    public function produk_update($id = ''): void
    {
        isCan('u');

        if ((new Produk())->produkUpdate($id, $this->input->post())) {
            redirect_with('success', 'Berhasil mengubah data', "{$this->controller}/produk");
        }

        redirect_with('error', 'Gagal mengubah data', "{$this->controller}/produk");
    }

    public function produk_delete($id): void
    {
        isCan('h');

        if ((new Produk())->produkDelete($id)) {
            redirect_with('success', 'Berhasil Hapus Data', "{$this->controller}/produk");
        }

        redirect_with('error', 'Gagal Hapus Data', "{$this->controller}/produk");
    }

    public function produk_delete_all(): void
    {
        isCan('h');

        if ((new Produk())->produkDeleteAll()) {
            redirect_with('success', 'Berhasil Hapus Data', "{$this->controller}/produk");
        }

        redirect_with('error', 'Gagal Hapus Data', "{$this->controller}/produk");
    }

    public function produk_detail($id = 0)
    {
        isCan('u');

        $data['main'] = Produk::listProduk()->where('produk.id', $id)->first() ?? show_404();

        return view('admin.lapak.produk.detail', $data);
    }

    public function produk_status($id = 0, $status = 0): void
    {
        isCan('u');

        if (Produk::where('id', $id)->update(['status' => $status])) {
            redirect_with('success', 'Berhasil mengubah data', "{$this->controller}/produk");
        }

        redirect_with('error', 'Gagal mengubah data', "{$this->controller}/produk");
    }

    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = site_url("lapak_admin/produk/aksi/{$aksi}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function aksi($aksi = 'cetak'): void
    {
        $post                   = $this->input->post();
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
        $data['main']           = Produk::with(['pelapak.penduduk:id,nama', 'kategori:id,kategori'])->get();
        $data['file']           = 'Data Produk';
        $data['isi']            = 'admin.lapak.produk.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];

        view('admin.layouts.components.format_cetak', $data);
    }
}
