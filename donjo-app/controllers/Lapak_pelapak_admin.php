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

class Lapak_pelapak_admin extends Admin_Controller
{
    public $modul_ini           = 'lapak';
    public $aliasController     = 'lapak_admin';
    public $kategori_pengaturan = 'lapak';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('penduduk_model');
        $this->load->model('pamong_model');
    }

    public function index()
    {
        $data['navigasi'] = Produk::navigasi();

        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');

            $query = Pelapak::listPelapak()
                ->when($status, static function ($query, $status): void {
                    $query->where('pelapak.status', $status);
                });

            return datatables($query)
                ->addIndexColumn()
                ->make();
        }

        return view('admin.lapak.pelapak.index', $data);
    }

    public function pelapak_form($id = '')
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

        return view('admin.lapak.pelapak.form', $data);
    }

    public function pelapak_maps($id = '')
    {
        $desa    = $this->header['desa'];
        $pelapak = Pelapak::listPelapak()->where('pelapak.id', $id)->first() ?? show_404();

        if ($pelapak) {
            $penduduk = $this->penduduk_model->get_penduduk_map($pelapak->id_pend);
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
            'ini'  => $ini,
            'lat'  => $lat,
            'lng'  => $lng,
            'zoom' => $zoom,
        ];
        $data['desa']        = $desa;
        $data['wil_atas']    = $desa;
        $data['dusun_gis']   = $this->wilayah_model->list_dusun();
        $data['rw_gis']      = $this->wilayah_model->list_rw();
        $data['rt_gis']      = $this->wilayah_model->list_rt();
        $data['form_action'] = site_url("lapak_admin/pelapak_update_maps/{$id}");

        return view('admin.lapak.pelapak.maps', $data);
    }

    public function pelapak_insert(): void
    {
        isCan('u');

        (new Pelapak())->pelapakInsert();

        redirect_with('success', 'Berhasil menambah data', 'lapak_admin/pelapak');
    }

    public function pelapak_update_maps($id = ''): void
    {
        isCan('u');

        (new Pelapak())->pelapakUpdateMaps($id);

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/pelapak');
    }

    public function pelapak_update($id = ''): void
    {
        isCan('u');

        (new Pelapak())->pelapakUpdate($id);

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/pelapak');
    }

    public function pelapak_delete($id): void
    {
        isCan('h');

        if (Pelapak::listPelapak()->find($id)->jumlah > 0) {
            redirect_with('error', 'Pelapak tersebut memiliki produk, silahkan hapus terlebih dahulu', 'lapak_admin/pelapak');
        } else {
            (new Pelapak())->pelapakDelete($id);
        }

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/pelapak');
    }

    public function pelapak_delete_all(): void
    {
        isCan('h');

        (new Pelapak())->pelapakDeleteAll();

        redirect_with('success', 'Berhasil menghapus data', 'lapak_admin/pelapak');
    }

    public function pelapak_status($id = 0, $status = 0): void
    {
        isCan('u');

        Pelapak::where('id', $id)
            ->update(['status' => $status]);

        redirect_with('success', 'Berhasil mengubah data', 'lapak_admin/pelapak');
    }

    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = site_url("lapak_admin/pelapak/aksi/{$aksi}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function aksi($aksi = 'cetak'): void
    {
        $post                   = $this->input->post();
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
        $data['main']           = Pelapak::with('penduduk:id,nama')->withCount('produk')->get();
        $data['file']           = 'Data Pelapak';
        $data['isi']            = 'admin.lapak.pelapak.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];

        view('admin.layouts.components.format_cetak', $data);
    }
}
