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

use App\Models\Pamong;
use Modules\Lapak\Models\Produk;
use Modules\Lapak\Models\ProdukKategori;

class LapakKategoriAdminController extends AdminModulController
{
    public $moduleName          = 'Lapak';
    public $modul_ini           = 'lapak';
    public $aliasController     = 'lapak_admin';
    public $kategori_pengaturan = 'Lapak';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['navigasi'] = Produk::navigasi();

        if (request()->ajax()) {
            $status = request('status');

            $query = ProdukKategori::listKategori()
                ->when($status !== '', static function ($query) use ($status): void {
                    $query->where('status', $status);
                });

            return datatables($query)
                ->addIndexColumn()
                ->make();
        }

        return view('lapak::backend.kategori.index', $data);
    }

    public function kategoriForm($id = '')
    {
        isCan('u');

        if ($id) {
            $data['main']        = ProdukKategori::find($id) ?? show_404();
            $data['form_action'] = site_url("lapak_admin/kategori_update/{$id}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url('lapak_admin/kategori_insert');
        }

        return view('lapak::backend.kategori.form', $data);
    }

    public function kategoriInsert(): void
    {
        isCan('u');

        (new ProdukKategori())->kategoriInsert(request()->post());

        redirect_with('success', 'Berhasil menambah data', 'lapak_admin/kategori');
    }

    public function kategoriUpdate($id = ''): void
    {
        isCan('u');

        (new ProdukKategori())->kategoriUpdate($id, request()->post());

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/kategori');
    }

    public function kategoriDelete($id): void
    {
        isCan('h');

        if (ProdukKategori::listKategori()->find($id)->jumlah > 0) {
            redirect_with('error', 'Kategori tersebut memiliki produk, silakan hapus terlebih dahulu', 'lapak_admin/kategori');
        } else {
            (new ProdukKategori())->kategoriDelete($id);
        }

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/kategori');
    }

    public function kategoriDeleteAll(): void
    {
        isCan('h');

        (new ProdukKategori())->kategoriDeleteAll();

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/kategori');
    }

    public function kategoriStatus($id = 0): void
    {
        isCan('u');

        if (ProdukKategori::gantiStatus($id)) {
            redirect_with('success', 'Berhasil mengubah status', 'lapak_admin/kategori');
        }

        redirect_with('error', 'Gagal mengubah status', 'lapak_admin/kategori');
    }

    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = site_url("lapak_admin/kategori/aksi/{$aksi}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function aksi($aksi = 'cetak'): void
    {
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => request('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => request('pamong_ketahui')])->first()->toArray();
        $data['main']           = ProdukKategori::withCount('produk')->get();
        $data['file']           = 'Data Kategori Produk';
        $data['isi']            = 'lapak::backend.kategori.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];

        view('admin.layouts.components.format_cetak', $data);
    }
}
