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

use App\Libraries\Keuangan;
use App\Libraries\Shortcode;
use App\Models\Artikel;
use App\Services\LaporanPenduduk;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Utama extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('first_artikel_m');
    }

    public function index()
    {
        $cari            = trim(request()->get('cari'));
        $data['artikel'] = collect([]);
        $artikel         = Artikel::withOnly(['author', 'category', 'comments'])->where('headline', '!=', Artikel::HEADLINE)->when($cari, static fn ($q) => $q->cari($cari))->sitemap()->orderBy('tgl_upload', 'desc')->paginate(setting('web_artikel_per_page') ?? 10);
        if (! $artikel->isEmpty()) {
            $shortCode       = new Shortcode();
            $data['artikel'] = $artikel->map(static function ($item) use ($shortCode) {
                $item->judul           = htmlspecialchars_decode(bersihkan_xss($item->judul));
                $item->kategori        = $item->category?->kategori ?? '';
                $item->kat_slug        = $item->category?->slug ?? '';
                $item->owner           = $item->author?->nama ?? '';
                $item->isi             = $shortCode->convert_sc_list($item->isi);
                $item->jumlah_komentar = $item->comments->count();

                return $item;
            });
            $data['links'] = $artikel;
        }

        $data['headline'] = Artikel::withOnly(['author'])->headline()->enable()->where('tgl_upload', '<=', Carbon::now())->sitemap()->orderBy('tgl_upload', 'desc')->first();
        $data['cari']     = $cari;
        if (setting('covid_rss')) {
            $data['feed'] = [
                // TODO:: Pindahkan ke library
                'items' => $this->first_artikel_m->get_feed(),
                'title' => 'BERITA COVID19.GO.ID',
                'url'   => 'https://www.covid19.go.id',
            ];
        }

        if (setting('apbdes_footer')) {
            $data['transparansi'] = (new Keuangan())->grafik_keuangan_tema();
        }

        $data['covid'] = (new LaporanPenduduk())->listData('covid');
        if ($cari !== '') {
            $data['judul_kategori'] = 'Hasil pencarian : ' . substr(e($cari), 0, 50);
        }

        return view('theme::partials.artikel.index', $data);
    }
}
