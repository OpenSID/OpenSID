<?php

declare(strict_types=1);

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

namespace App\Services\Acak;

/**
 * Opsi bersama untuk sekali proses acak.
 *
 * Dibangun dari opsi CLI ({@see \App\Console\Commands\AcakDataCommand}) atau
 * dari request HTTP ({@see \App\Http\Controllers\Admin\AcakController}), lalu
 * dioper ke {@see AcakService::run()}. Titik cabang antara kedua pemanggil ada
 * di konstruksi objek ini — bukan di logika instalasi.
 */
final class AcakOptions
{
    /** Mode penanganan foto warga/pamong. */
    public const PHOTO_MODES = ['none', 'avatar', 'blur'];

    /** Mode penanganan foto konten (artikel/galeri/media). */
    public const FOTO_KONTEN_MODES = ['none', 'watermark', 'picsum'];

    /** Mode pembuatan berkas arsip surat. */
    public const SURAT_MODES = ['placeholder', 'sintetis'];

    /** @var string mode foto warga/pamong yang berlaku (tervalidasi) */
    public readonly string $photoMode;

    /** @var string mode foto konten yang berlaku (tervalidasi) */
    public readonly string $fotoKontenMode;

    /** @var string mode berkas arsip surat yang berlaku (tervalidasi) */
    public readonly string $suratMode;

    /**
     * @param string       $seed           Seed deterministik untuk generasi nilai sintetis.
     *                                    Seed yang sama menghasilkan data demo yang sama.
     * @param list<string> $keepFiles      Path relatif file yang DIKECUALIKAN dari pembersihan
     *                                    folder desa (file yang di-deselect pengguna di halaman
     *                                    konfirmasi acak / Bersihkan Folder Desa).
     * @param string       $photoMode      Penanganan foto warga/pamong:
     *                                    'none'   → kosongkan kolom foto (NULL),
     *                                    'avatar' → ganti dengan avatar kartun deterministik,
     *                                    'blur'   → kaburkan wajah di berkas (butuh Python).
     * @param string       $fotoKontenMode Penanganan foto konten (artikel/galeri/media):
     *                                    'none'      → biarkan,
     *                                    'watermark' → tempel watermark "FOTO DEMO",
     *                                    'picsum'    → ganti dengan foto acak deterministik.
     * @param string       $suratMode      Pembuatan berkas arsip surat:
     *                                    'placeholder' → PDF placeholder minimal + thumbnail,
     *                                    'sintetis'    → render ulang surat dari template
     *                                    (hanya konteks web; jatuh ke placeholder bila gagal).
     */
    public function __construct(
        public readonly string $seed = 'opensid-demo',
        public readonly array $keepFiles = [],
        string $photoMode = 'blur',
        string $fotoKontenMode = 'watermark',
        string $suratMode = 'sintetis',
    ) {
        $this->photoMode      = in_array($photoMode, self::PHOTO_MODES, true) ? $photoMode : 'blur';
        $this->fotoKontenMode = in_array($fotoKontenMode, self::FOTO_KONTEN_MODES, true) ? $fotoKontenMode : 'watermark';
        $this->suratMode      = in_array($suratMode, self::SURAT_MODES, true) ? $suratMode : 'sintetis';
    }
}
