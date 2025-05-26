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

use App\Enums\SistemEnum;
use App\Enums\StatusEnum;
use App\Libraries\Keuangan;
use App\Models\Agenda;
use App\Models\ArsipArtikel;
use App\Models\Artikel;
use App\Models\Galery;
use App\Models\Kategori;
use App\Models\KehadiranPamong;
use App\Models\Komentar;
use App\Models\Menu;
use App\Models\StatistikPengunjung;
use App\Models\TeksBerjalan;
use App\Models\Widget;
use App\Services\LaporanPenduduk;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Modules\Kehadiran\Models\JamKerja;
use Symfony\Component\HttpFoundation\Session\Session;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_Controller extends MY_Controller
{
    public $CI;
    public $cek_anjungan;

    public function __construct()
    {
        parent::__construct();
        $CI           = &get_instance();
        $this->header = identitas();
        $this->load->helper('theme');

        theme_active();

        if (setting('offline_mode') == 2 || (setting('offline_mode') == 1 && can('b', 'web'))) {
            $this->maintenance();

            exit;
        }

        $this->viewShare();
    }

    /**
     * Bagikan data yang sering digunakan di view
     */
    public function viewShare(): void
    {
        $counterVisitor = true;

        if ((new Session())->has('pengunjungOnline') || identitas() === null) {
            $counterVisitor = false;
        }
        if ($counterVisitor) {
            StatistikPengunjung::counterVisitor(request()->ip());
            (new Session())->set('pengunjungOnline', date('Y-m-d'));
        }

        $statistik_pengunjung = StatistikPengunjung::summary();
        $teksBerjalan         = null;
        if (Schema::hasColumn('teks_berjalan', 'tipe')) {
            $teksBerjalan = TeksBerjalan::with(['artikel'])->status(StatusEnum::YA)->get()->map(static function ($item, $index) {
                $item->no            = $index + 1;
                $item->tautan        = $item->tipe == 1 ? $item->artikel->url_slug : $item->tautan;
                $item->tampil_tautan = $item->tipe == 1 ? tgl_indo($item->artikel->tgl_upload) . ' <br> ' . $item->artikel->judul : $item->tautan;
                $item->tampilkan     = SistemEnum::valueOf($item->tipe);

                return $item;
            })->toArray();
        }
        $sumber     = setting('sumber_gambar_slider');
        $limit      = setting('jumlah_gambar_slider') ?? 10;
        $sharedData = [
            'statistik_pengunjung' => $statistik_pengunjung,
            'latar_website'        => default_file((new App\Models\Theme())->lokasiLatarWebsite() . setting('latar_website'), DEFAULT_LATAR_WEBSITE),
            'menu_kiri'            => Kategori::daftar(),
            'teks_berjalan'        => $teksBerjalan,
            'slide_artikel'        => Artikel::withOnly([])->slideShow()->get()->toArray(),
            'slider_gambar'        => Artikel::slideGambar($sumber, $limit),
            'cek_anjungan'         => $this->cek_anjungan,
            'widgetAktif'          => $this->widgetAktif(),
            'w_gal'                => Galery::widget(),
            'hari_ini'             => Agenda::show('hari_ini')->get()->toArray(),
            'yad'                  => Agenda::show('yad')->get()->toArray(),
            'lama'                 => Agenda::show('lama')->get()->toArray(),
            'komen'                => Komentar::show()->limit(10)->get()->toArray(),
            'sosmed'               => media_sosial(),
            'arsip_terkini'        => ArsipArtikel::show('terkini'),
            'arsip_populer'        => ArsipArtikel::show('populer'),
            'arsip_acak'           => ArsipArtikel::show('acak'),
            'aparatur_desa'        => KehadiranPamong::widget(),
            'stat_widget'          => (new LaporanPenduduk())->listData(4),
            'sinergi_program'      => getWidgetSetting('sinergi_program'),
            'widget_keuangan'      => (new Keuangan())->widget_keuangan(),
            'jam_kerja'            => JamKerja::orderBy('id')->get(),
        ];

        if (setting('apbdes_footer') && setting('apbdes_footer_all')) {
            $sharedData['transparansi'] = (new Keuangan())->grafik_keuangan_tema(setting('apbdes_tahun'));
        }

        foreach (['arsip'] as $kolom) {
            if (isset($sharedData[$kolom])) {
                $sharedData[$kolom] = $this->security->xss_clean($sharedData[$kolom]);
            }
        }

        $sharedData['tema_premium'] = $this->pemesanan();

        View::share($sharedData);
    }

    /**
     * Ambil data widget yang aktif untuk ditampilkan di website
     *
     * @return mixed
     */
    private function widgetAktif()
    {
        return Widget::status()
            ->when(setting('layanan_mandiri') == '0', static function ($query) {
                $query->whereNotIn('isi', ['layanan_mandiri.php', 'layanan_mandiri.blade.php']);
            })
            ->orderBy('urut')
            ->get()
            ->map(static function ($item) {
                $item->judul = SebutanDesa($item->judul);
                $item->isi   = $item->jenis_widget == 3
                    ? bersihkan_xss($item->isi)
                    : str_replace('.blade.php', '', $item->isi);

                return $item;
            });
    }

    /**
     * Tampilkan halaman maintenance
     *
     * @return void
     */
    private function maintenance()
    {
        return view('theme::partials.maintenance.index');
    }

    /**
     * Cek apakah menu aktif
     *
     * @param string $link
     *
     * @return bool
     */
    public function menuAktif($link)
    {
        return Menu::active()->whereLink($link)->exists();
    }

    /**
     * Cek hak akses menu
     *
     * @param string $link
     *
     * @return void
     */
    protected function hak_akses_menu($link)
    {
        $menuAktif = $this->menuAktif($link);
        if (! $menuAktif) {
            view('theme::menu_not_active');

            exit;
        }
    }

    public function pemesanan()
    {
        return cache()->remember('tema_premium', 604800, static function () {
            $data = app('ci')->cache->file->get('status_langganan');
            return collect($data->body->pemesanan)
                ->pluck('layanan')
                ->flatten(1)
                ->filter(fn($layanan) => $layanan->nama_kategori === 'Tema')
                ->pluck('product_key')
                ->filter()
                ->values()
                ->toArray();
        });
    }
}
