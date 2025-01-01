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

use App\Libraries\Shortcode;
use App\Models\Artikel as ModelsArtikel;
use App\Models\Kategori;
use App\Models\Komentar;

defined('BASEPATH') || exit('No direct script access allowed');

class Artikel extends Web_Controller
{
    /*
    | Artikel bisa ditampilkan menggunakan parameter pertama sebagai id, dan semua parameter lainnya dikosongkan. url artikel/:id
    | Kalau menggunakan slug, dipanggil menggunakan url artikel/:thn/:bln/:hri/:slug
    */
    public function index($thn = null, $bln = null, $hr = null, $url = null): void
    {
        if ($url == null || $thn == null || $bln == null || $hr == null) {
            show_404();
        }

        if (is_numeric($url)) {
            $data_artikel = ModelsArtikel::find($url);

            if ($data_artikel) {
                $data_artikel['slug'] = $this->security->xss_clean($data_artikel['slug']);
                redirect('artikel/' . buat_slug($data_artikel));
            }
        }

        ModelsArtikel::read($url);
        $artikel        = ModelsArtikel::with(['author', 'category', 'agenda'])->sitemap()->berdasarkan($thn, $bln, $hr, $url)->first();
        $artikel->judul = htmlspecialchars_decode(bersihkan_xss($artikel->judul));
        $singleArtikel  = $artikel->toArray() + [
            'kategori'         => $artikel->category->kategori,
            'kat_slug'         => $artikel->category->slug,
            'owner'            => $artikel->author->nama,
            'tgl_upload_local' => tgl_indo($artikel->tgl_upload),
        ];
        $data['single_artikel']        = $singleArtikel;
        $data['links']                 = $artikel;
        $data['single_artikel']['isi'] = (new Shortcode())->shortcode($artikel->isi);
        $data['title']                 = ucwords($data['single_artikel']['judul']);
        $data['detail_agenda']         = $artikel->agenda;
        $data['komentar']              = Komentar::with('children')
            ->where('id_artikel', $artikel->id)
            ->where('status', Komentar::ACTIVE)
            ->whereNull('parent_id')
            ->get()->toArray();

        $data['layout'] = match ($artikel->tampilan) {
            3       => 'full-content',
            2       => 'left-sidebar',
            default => 'right-sidebar',
        };

        view('theme::partials.artikel.detail', $data);
    }

    public function kategori($id): void
    {
        $cari                   = trim(request()->get('cari'));
        $data['judul_kategori'] = ['kategori' => Kategori::where(static fn ($q) => $q->where('id', $id)->orWhere('slug', $id))->first()?->kategori ?? "Artikel Kategori {$id}"];
        $data['title']          = 'Artikel ' . $data['judul_kategori']['kategori'];
        $artikel                = ModelsArtikel::when($cari, static fn ($q) => $q->cari($cari))->kategori($id)->paginate();
        $data['artikel']        = $artikel ?? collect([]);
        $data['links']          = $artikel;

        view('theme::partials.artikel.index', $data);
    }
}
