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

namespace App\Http\Transformers;

use App\Models\Artikel;
use League\Fractal\TransformerAbstract;

class ArtikelTransformer extends TransformerAbstract
{
    /**
     * Whitelist kunci payload artikel publik. Jangan pernah `return $artikel->toArray()`
     * mentah di sini: Artikel eager-load relasi `author` (App\Models\User) sehingga
     * payload berisiko membocorkan PII operator desa (email, phone, telegram id,
     * otp_identifier, session, akses_wilayah, dst) tanpa autentikasi di
     * endpoint publik GET/POST /internal_api/arsip.
     */
    private const ALLOWED_KEYS = [
        'id', 'judul', 'isi', 'gambar', 'gambar1', 'gambar2', 'gambar3',
        'tgl_upload', 'tgl_upload_local', 'slug', 'url_slug', 'hit',
        'enabled', 'headline', 'boleh_komentar', 'tipe', 'id_kategori',
        'author', 'category',
    ];

    /**
     * Kolom author yang boleh tampil di endpoint artikel publik. Hanya nama
     * penulis yang dibutuhkan tema (partials/arsip/index.blade.php).
     */
    private const AUTHOR_FIELDS = ['nama'];

    public function transform(Artikel $artikel): array
    {
        $artikel->tgl_upload_local = tgl_indo($artikel->tgl_upload);

        $data = collect($artikel->toArray())->only(self::ALLOWED_KEYS)->toArray();

        if (isset($data['author']) && is_array($data['author'])) {
            $data['author'] = collect($data['author'])->only(self::AUTHOR_FIELDS)->toArray();
        }

        return $data;
    }
}
