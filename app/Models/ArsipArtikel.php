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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string|null $gambar
 * @property string $isi
 * @property int $enabled
 * @property \Illuminate\Support\Carbon $tgl_upload
 * @property int|null $id_kategori
 * @property int|null $id_user
 * @property string $judul
 * @property int $headline
 * @property string|null $gambar1
 * @property string|null $gambar2
 * @property string|null $gambar3
 * @property string|null $dokumen
 * @property string|null $link_dokumen
 * @property int $boleh_komentar
 * @property string|null $slug
 * @property int|null $hit
 * @property int|null $tampilan
 * @property int $slider
 * @property string $tipe
 * @property int|null $urut
 * @property int $jenis_widget
 * @property-read \App\Models\Agenda|null $agenda
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Kategori|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Komentar> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $kategori
 * @property-read mixed $perkiraan_membaca
 * @property-read void $url_gambar1
 * @property-read void $url_gambar2
 * @property-read void $url_gambar3
 * @property-read void $url_gambar
 * @property-read string $url_slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtikelWilayah> $wilayah
 * @property-read int|null $wilayah_count
 * @method static Builder<static>|ArsipArtikel active()
 * @method static Builder<static>|ArsipArtikel arsip()
 * @method static Builder<static>|ArsipArtikel artikelStatis()
 * @method static Builder<static>|ArsipArtikel berdasarkan($thn, $bln, $hr, $url)
 * @method static Builder<static>|ArsipArtikel cari($cari)
 * @method static Builder<static>|ArsipArtikel dinamis()
 * @method static Builder<static>|ArsipArtikel diunggahSekarang()
 * @method static Builder<static>|ArsipArtikel enable()
 * @method static Builder<static>|ArsipArtikel headline()
 * @method static Builder<static>|ArsipArtikel kategori($id)
 * @method static Builder<static>|ArsipArtikel keuangan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel newQuery()
 * @method static Builder<static>|ArsipArtikel onlyArticle()
 * @method static Builder<static>|ArsipArtikel openKab()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel query()
 * @method static Builder<static>|ArsipArtikel sitemap()
 * @method static Builder<static>|ArsipArtikel slideShow($gambarUtama = false)
 * @method static Builder<static>|ArsipArtikel statis()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereBolehKomentar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereGambar1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereGambar2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereGambar3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereHeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereHit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereIdKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereJenisWidget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereLinkDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereSlider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereTampilan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereTglUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel whereUrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArsipArtikel withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class ArsipArtikel extends Artikel
{
    public static function show($type = '')
    {
        // Artikel agenda (kategori=1000) tidak ditampilkan
        return self::selectRaw('artikel.*, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
            ->where('enabled', 1)
            ->whereNot('id_kategori', [1000])
            ->where('tgl_upload', '<', date('Y-m-d H:i:s'))
            ->when($type, static function ($q) use ($type): void {
                match ($type) {
                    'acak'    => $q->inRandomOrder(),
                    'populer' => $q->orderBy('hit', 'DESC'),
                    default   => $q->orderBy('tgl_upload', 'DESC'),
                };
            })->limit(7)->get()->map(static function ($item) {
                $item->judul = htmlspecialchars_decode((string) bersihkan_xss($item->judul));

                return $item;
            })->toArray();
    }
}
