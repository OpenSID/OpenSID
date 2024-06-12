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

use App\Enums\StatusEnum;
use App\Models\Pelapak;
use App\Models\Penduduk;
use App\Models\Produk as ProdukModel;
use App\Models\ProdukKategori;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Produk extends Mandiri_Controller
{
    private $lapak;

    public function __construct()
    {
        parent::__construct();
        $this->lapak = Pelapak::with('produk', 'produk.kategori')->where('id_pend', $this->is_login->id_pend)->first();
    }

    public function index(): void
    {
        $this->verifikasi();

        $data['produk'] = $this->lapak->produk;

        $this->render('lapak/index', $data);
    }

    public function form($id = null): void
    {
        $this->verifikasi();

        if ($id) {
            $data['produk']      = ProdukModel::findOrFail($id);
            $data['form_action'] = site_url("layanan-mandiri/produk/update/{$id}");
            $data['verifikasi']  = $data['produk']->status == StatusEnum::YA;
            if (! $data['verifikasi']) {
                $data['notifikasi'] = [
                    'status' => 'warning',
                    'pesan'  => 'Produk ini sedang dalam proses verifikasi. Silahkan tunggu beberapa saat.',
                ];
            }
        } else {
            $produk        = ProdukModel::where('id_pelapak', $this->lapak->id)->whereDate('created_at', date('Y-m-d'))->count();
            $data['batas'] = $produk >= setting('jumlah_pengajuan_produk');
            if ($data['batas']) {
                $data['notifikasi'] = [
                    'status' => 'warning',
                    'pesan'  => 'Anda telah mencapai jumlah maksimal ' . setting('jumlah_pengajuan_produk') . ' produk / hari yang dapat didaftarkan.',
                ];
            }
            $data['produk']      = null;
            $data['form_action'] = site_url('layanan-mandiri/produk/store');
        }

        $data['kategori'] = ProdukKategori::listKategori()->where('produk_kategori.status', 1)->get();
        $data['satuan']   = ProdukModel::listSatuan();

        $this->render('lapak/form', $data);
    }

    public function store(): void
    {
        $this->verifikasi();

        $post               = $this->input->post();
        $post['id_pelapak'] = $this->lapak->id;
        $post['status']     = StatusEnum::TIDAK;

        if ((new ProdukModel())->produkInsert($post)) {
            redirect_with('success', 'Berhasil menambah data', 'layanan-mandiri/produk');
        }

        redirect_with('error', 'Gagal menambah data', 'layanan-mandiri/produk/form');
    }

    public function update($id): void
    {
        $this->verifikasi();

        $post               = $this->input->post();
        $post['id_pelapak'] = $this->lapak->id;
        $post['status']     = StatusEnum::TIDAK;

        ProdukModel::where('id_pelapak', $this->lapak->id)->findOrFail($id);

        if ((new ProdukModel())->produkUpdate($id, $post)) {
            redirect_with('success', 'Berhasil mengubah data', 'layanan-mandiri/produk');
        }

        redirect_with('error', 'Gagal mengubah data', "layanan-mandiri/produk/form/{$id}");
    }

    public function pengaturan(): void
    {
        if (! $this->lapak) {
            $pelapak    = null;
            $notifikasi = [
                'status' => 'danger',
                'pesan'  => 'Anda belum terdaftar sebagai pelapak. Silahkan daftar terlebih dahulu untuk menggunakan layanan ini.',
            ];
            $aksi = 'Daftar';
        } else {
            $pelapak    = $this->lapak;
            $notifikasi = null;
            $aksi       = 'Ubah';
            $verifikasi = $pelapak->status == StatusEnum::YA;

            if (! $verifikasi) {
                $notifikasi = [
                    'status' => 'warning',
                    'pesan'  => 'Pendaftaran anda sedang dalam proses verifikasi. Silahkan tunggu beberapa saat.',
                ];
            }
        }

        $desa     = identitas();
        $penduduk = Penduduk::with('map')->find($pelapak->id_pend);
        $zoom     = config('app.map.zoom');

        switch (true) {
            case $pelapak->lat || $pelapak->lng:
                $lat  = $pelapak->lat;
                $lng  = $pelapak->lng;
                $zoom = $pelapak->zoom ?? $zoom;
                break;

            case $penduduk['lat'] || $penduduk['lng']:
                $lat  = $penduduk['lat'];
                $lng  = $penduduk['lng'];
                $zoom = $penduduk['zoom'] ?? $zoom;
                break;

            case $desa['lat'] || $desa['lng']:
                $lat  = $desa['lat'];
                $lng  = $desa['lng'];
                $zoom = $desa['zoom'] ?? $zoom;
                break;

            default:
                $lat = config('app.map.point.lat');
                $lng = config('app.map.point.lng');
                break;
        }

        $data['pelapak'] = $pelapak;
        $data['lokasi']  = [
            'lat'  => $lat,
            'lng'  => $lng,
            'zoom' => $zoom,
        ];
        $data['desa']        = $desa;
        $data['wil_atas']    = $desa;
        $data['dusun_gis']   = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']      = Wilayah::rw()->get()->toArray();
        $data['rt_gis']      = Wilayah::rt()->get()->toArray();
        $data['form_action'] = site_url('layanan-mandiri/produk/pengaturan-update');
        $data['notifikasi']  = $notifikasi;
        $data['aksi']        = $aksi;
        $data['verifikasi']  = $verifikasi;

        $this->render('lapak/pengaturan', $data);
    }

    public function pengaturanUpdate(): void
    {
        $post    = $this->input->post();
        $pelapak = [
            'id_pend' => $this->is_login->id_pend,
            'telepon' => $post['telepon'],
            'lat'     => $post['lat'],
            'lng'     => $post['lng'],
            'zoom'    => $post['zoom'],
        ];

        if (! $this->lapak) {
            $pelapak['status'] = StatusEnum::TIDAK;
            if (Pelapak::create($pelapak)) {
                redirect_with('success', 'Berhasil melakukan pendaftaran', 'layanan-mandiri/produk/pengaturan');
            }
            redirect_with('error', 'Gagal melakukan pendaftaran', 'layanan-mandiri/produk/pengaturan');
        } else {
            $cek = Pelapak::find($this->lapak->id);
            if ($cek->update($pelapak)) {
                redirect_with('success', 'Berhasil mengubah data', 'layanan-mandiri/produk/pengaturan');
            }
            redirect_with('error', 'Gagal mengubah data', 'layanan-mandiri/produk/pengaturan');
        }
    }

    public function verifikasi(): void
    {
        if (! $this->lapak || $this->lapak->status !== StatusEnum::YA) {
            redirect('layanan-mandiri/produk/pengaturan');
        }
    }
}
