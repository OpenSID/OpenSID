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
use App\Models\PendudukMap;
use App\Models\Wilayah;
use Modules\Lapak\Models\Pelapak;
use Modules\Lapak\Models\Produk;

class LapakPelapakAdminController extends AdminModulController
{
    public $moduleName          = 'Lapak';
    public $modul_ini           = 'lapak';
    public $aliasController     = 'lapak_admin';
    public $kategori_pengaturan = 'Lapak';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('penduduk_model');
    }

    public function index()
    {
        $data['navigasi'] = Produk::navigasi();

        if (request()->ajax()) {
            $status = request('status');

            $query = Pelapak::listPelapak()
                ->when($status !== '', static function ($query) use ($status): void {
                    $query->where('pelapak.status', $status);
                });

            return datatables($query)
                ->addIndexColumn()
                ->make();
        }

        return view('lapak::backend.pelapak.index', $data);
    }

    public function pelapakForm($id = '')
    {
        isCan('u');

        if ($id) {
            $data['main']        = Pelapak::find($id) ?? show_404();
            $data['form_action'] = site_url("lapak_admin/pelapak_update/{$id}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url('lapak_admin/pelapak_insert');
        }

        $data['list_penduduk'] = (new Pelapak())->listPenduduk($data['main']->id_pend ?? 0);

        return view('lapak::backend.pelapak.form', $data);
    }

    public function pelapakMaps($id = '')
    {
        $desa    = $this->header['desa'];
        $pelapak = Pelapak::listPelapak()->where('pelapak.id', $id)->first() ?? show_404();

        if ($pelapak) {
            $penduduk = PendudukMap::find($pelapak->id_pend)->first()?->toArray();
        }

        switch (true) {
            case $pelapak->lat || $pelapak->lng:
                $lat  = $pelapak->lat;
                $lng  = $pelapak->lng;
                $zoom = $pelapak->zoom ?? 10;
                break;

            case $penduduk['lat'] || $penduduk['lng']:
                $lat  = $penduduk['lat'];
                $lng  = $penduduk['lng'];
                $zoom = $penduduk['zoom'] ?? 10;
                break;

            case $desa['lat'] || $desa['lng']:
                $lat  = $desa['lat'];
                $lng  = $desa['lng'];
                $zoom = $desa['zoom'] ?? 10;
                break;

            default:
                $lat  = -1.0546279422758742;
                $lng  = 116.71875000000001;
                $zoom = 10;
                break;
        }

        $data['pelapak'] = $pelapak;
        $data['lokasi']  = [
            'ini'  => $ini, // TODO: ini apa?
            'lat'  => $lat,
            'lng'  => $lng,
            'zoom' => $zoom,
        ];
        $data['desa']        = $desa;
        $data['wil_atas']    = $desa;
        $data['dusun_gis']   = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']      = Wilayah::rw()->get()->toArray();
        $data['rt_gis']      = Wilayah::rt()->get()->toArray();
        $data['form_action'] = site_url("lapak_admin/pelapak_update_maps/{$id}");

        return view('lapak::backend.pelapak.maps', $data);
    }

    public function pelapakInsert(): void
    {
        isCan('u');

        (new Pelapak())->pelapakInsert();

        redirect_with('success', 'Berhasil menambah data', 'lapak_admin/pelapak');
    }

    public function pelapakUpdateMaps($id = ''): void
    {
        isCan('u');

        (new Pelapak())->pelapakUpdateMaps($id);

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/pelapak');
    }

    public function pelapakUpdate($id = ''): void
    {
        isCan('u');

        (new Pelapak())->pelapakUpdate($id);

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/pelapak');
    }

    public function pelapakDelete($id): void
    {
        isCan('h');

        if (Pelapak::listPelapak()->find($id)->jumlah > 0) {
            redirect_with('error', 'Pelapak tersebut memiliki produk, silahkan hapus terlebih dahulu', 'lapak_admin/pelapak');
        } else {
            (new Pelapak())->pelapakDelete($id);
        }

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/pelapak');
    }

    public function pelapakDeleteAll(): void
    {
        isCan('h');

        (new Pelapak())->pelapakDeleteAll();

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/pelapak');
    }

    public function pelapakStatus($id = 0): void
    {
        isCan('u');

        if (Pelapak::gantiStatus($id)) {
            redirect_with('success', 'Berhasil mengubah status', 'lapak_admin/pelapak');
        }

        redirect_with('error', 'Gagal mengubah status', 'lapak_admin/pelapak');
    }

    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = site_url("lapak_admin/pelapak/aksi/{$aksi}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function aksi($aksi = 'cetak'): void
    {
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => request('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => request('pamong_ketahui')])->first()->toArray();
        $data['main']           = Pelapak::with('penduduk:id,nama')->withCount('produk')->get();
        $data['file']           = 'Data Pelapak';
        $data['isi']            = 'lapak::backend.pelapak.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];

        view('admin.layouts.components.format_cetak', $data);
    }
}
