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

namespace App\Services\Surat;

use App\Enums\StatusValidasiEnum;
use App\Libraries\TinyMCE;
use App\Libraries\TinyMCE\FakeDataIsian;
use DOMDocument;
use Spipu\Html2Pdf\Html2Pdf;
use TCPDF_FONTS;
use Throwable;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Validasi struktural template surat kustom (FormatSurat/SuratDinas jenis TINYMCE_DESA)
 * sebelum boleh dipakai mencetak surat sungguhan.
 *
 * Dua tahap:
 * - Statis: murah, tanpa render (kurung siku, tag HTML terlarang, HTML dasar, marker pagebreak).
 * - Dry-run: render sungguhan memakai data sintetis (FakeDataIsian, jalur yang sama dipakai
 *   fitur "Tinjau PDF") sampai ke Html2Pdf, hasilnya dibuang -- supaya kesalahan nyata
 *   (HTML rusak, kode isian tak dikenal, font hilang) tertangkap saat validasi, bukan saat
 *   warga/petugas sedang menunggu suratnya.
 */
class ValidatorTemplateSurat
{
    public const LEVEL_ERROR   = 'error';
    public const LEVEL_WARNING = 'warning';

    /** @var array<int, array{level: string, pesan: string}> */
    private array $temuan = [];

    /**
     * @param \App\Models\FormatSurat|\App\Models\SuratDinas $surat
     *
     * @return array{status: int, temuan: array<int, array{level: string, pesan: string}>}
     */
    public static function validasi($surat, bool $suratDinas = false): array
    {
        return (new self())->jalankan($surat, $suratDinas);
    }

    /**
     * Tahap statis saja -- murah dan tanpa database, sehingga bisa diuji secara terisolasi.
     *
     * @return array<int, array{level: string, pesan: string}>
     */
    public function validasiStatis(?string $templateDesa): array
    {
        $this->temuan = [];
        $templateDesa = (string) $templateDesa;

        $this->cekKurungSiku($templateDesa);
        $this->cekTagTerlarang($templateDesa);
        $this->cekHtmlDasar($templateDesa);
        $this->cekPagebreakMarker($templateDesa);

        return $this->temuan;
    }

    /**
     * @param \App\Models\FormatSurat|\App\Models\SuratDinas $surat
     *
     * @return array{status: int, temuan: array<int, array{level: string, pesan: string}>}
     */
    private function jalankan($surat, bool $suratDinas): array
    {
        $this->temuan = $this->validasiStatis($surat->template_desa);

        // Kalau HTML dasarnya saja sudah rusak parah, render dry-run pasti gagal juga --
        // cukup laporkan temuan statis agar pesannya fokus ke akar masalah.
        if (! $this->adaError()) {
            $this->jalankanDryRun($surat, $suratDinas);
        }

        return [
            'status' => $this->adaError() ? StatusValidasiEnum::TIDAK_VALID : StatusValidasiEnum::VALID,
            'temuan' => $this->temuan,
        ];
    }

    private function adaError(): bool
    {
        foreach ($this->temuan as $item) {
            if ($item['level'] === self::LEVEL_ERROR) {
                return true;
            }
        }

        return false;
    }

    private function tambah(string $level, string $pesan): void
    {
        $this->temuan[] = ['level' => $level, 'pesan' => $pesan];
    }

    private function cekKurungSiku(string $template): void
    {
        $buka  = substr_count($template, '[');
        $tutup = substr_count($template, ']');

        if ($buka !== $tutup) {
            $this->tambah(self::LEVEL_ERROR, "Kurung siku pada template tidak seimbang ({$buka} '[' vs {$tutup} ']'). Placeholder yang tidak tertutup dapat merusak tampilan surat, misalnya tabel jadi berantakan.");
        }
    }

    private function cekTagTerlarang(string $template): void
    {
        foreach (invalid_tags() as $tag) {
            if (str_contains($template, $tag)) {
                $this->tambah(self::LEVEL_ERROR, "Tag HTML {$tag} tidak didukung mesin cetak PDF (Html2Pdf/TCPDF) dan akan menyebabkan surat gagal dicetak.");
            }
        }
    }

    private function cekHtmlDasar(string $template): void
    {
        if (trim($template) === '') {
            $this->tambah(self::LEVEL_WARNING, 'Isi template surat masih kosong.');

            return;
        }

        $sebelumnya = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $dom = new DOMDocument();
        $dom->loadHTML('<div>' . $template . '</div>');

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($sebelumnya);

        foreach ($errors as $error) {
            if ($error->level === LIBXML_ERR_FATAL) {
                $this->tambah(self::LEVEL_ERROR, 'HTML template tidak valid: ' . trim($error->message));
            }
        }
    }

    private function cekPagebreakMarker(string $template): void
    {
        // Marker <!-- pagebreak --> yang dibungkus <div class="new-break"> berasal dari
        // tombol "Tambah Halaman Baru" pada editor. Itu sah: TinyMCE::generateMultiPage()
        // memang memakainya untuk memecah template jadi beberapa halaman (issue #11924).
        // Yang dilarang hanya marker "telanjang" -- itu yang dipakai sistem secara internal
        // untuk memisahkan header/isi/footer dan akan mengacaukan pemisahan halaman.
        // Pola sama persis dengan yang dipakai TinyMCE::generateMultiPage() (satu konstanta
        // dipakai berdua) supaya validator & mesin cetak tidak pernah beda pendapat.
        $tanpaHalamanBaru = preg_replace(TinyMCE::POLA_PENANDA_HALAMAN_BARU, '', $template);

        // preg_replace() mengembalikan null kalau pola gagal (mis. kena pcre.backtrack_limit
        // pada template raksasa); pakai template asli agar marker telanjang tetap ketahuan.
        $jumlah = substr_count($tanpaHalamanBaru ?? $template, '<!-- pagebreak -->');

        if ($jumlah > 0) {
            $this->tambah(self::LEVEL_ERROR, "Template mengandung marker <!-- pagebreak --> sebanyak {$jumlah}x di luar tombol \"Tambah Halaman Baru\". Marker ini dipakai sistem secara otomatis untuk memisahkan header/isi/footer dan tidak boleh dituliskan manual di dalam template, karena akan mengacaukan pemisahan halaman saat dicetak.");
        }
    }

    /**
     * @param \App\Models\FormatSurat|\App\Models\SuratDinas $surat
     */
    private function jalankanDryRun($surat, bool $suratDinas): void
    {
        $tempFile = null;

        try {
            // Pakai atribut mentah (persis seperti data yang tersimpan di DB / dikirim form),
            // bukan hasil accessor Eloquent yang sudah men-decode JSON.
            $payload = $surat->getAttributes();
            $jenis   = $suratDinas ? '_dinas' : null;

            $preview = FakeDataIsian::set($payload, $jenis, false);
            $hasil   = (string) $preview->getResult();

            $this->cekSisaKodeIsian($hasil);

            $tinymce  = new TinyMCE();
            $isiCetak = $tinymce->formatPdf((string) ($payload['header'] ?? 1), (string) ($payload['footer'] ?? 1), $hasil);

            $this->cekFont($isiCetak);

            $margins = $suratDinas ? setting('surat_dinas_margin_cm_to_mm') : setting('surat_margin_cm_to_mm');
            $font    = underscore((string) setting($suratDinas ? 'font_surat_dinas' : 'font_surat'));

            $pdf = new Html2Pdf($payload['orientasi'] ?? 'Potrait', $payload['ukuran'] ?? 'F4', 'en', true, 'UTF-8', $margins);
            $pdf->setTestTdInOnePage(false)->setDefaultFont($font);
            $pdf->writeHTML(str_replace(base_url(), FCPATH, $isiCetak));

            $tempFile = tempnam(sys_get_temp_dir(), 'validasi_surat_') . '.pdf';
            $pdf->output($tempFile, 'F');
        } catch (Throwable $e) {
            $this->tambah(self::LEVEL_ERROR, 'Gagal merender surat (dry-run): ' . $e->getMessage());
        } finally {
            if ($tempFile && file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
    }

    /**
     * Setelah kode isian dikenal diganti (gantiKodeIsian), placeholder `[xxx]` yang masih
     * tersisa berarti tidak dikenali sistem -- termasuk kasus regresi kurung siku yang merusak
     * tabel (lihat tests/playwright/e2e/regression/surat/kurung-siku-tabel-jumlah-surat-tanah.spec.ts).
     */
    private function cekSisaKodeIsian(string $hasil): void
    {
        if (preg_match_all('/\[[a-z][a-z0-9_]*\]/i', $hasil, $matches)) {
            $daftar = implode(', ', array_unique($matches[0]));
            $this->tambah(self::LEVEL_ERROR, "Ditemukan kode isian yang tidak dikenali/tidak tergantikan: {$daftar}. Placeholder ini akan tampil apa adanya (atau merusak tabel) pada surat yang dicetak.");
        }
    }

    private function cekFont(string $isiCetak): void
    {
        preg_match_all("/font-family:\\s*'([^']+)'/", $isiCetak, $matches);

        $fontDipakai = array_unique($matches[1]);
        $fontDipakai = array_diff($fontDipakai, ['arial']);

        if ($fontDipakai === []) {
            return;
        }

        $fontTersedia = $this->daftarFontTersedia();

        foreach (array_diff($fontDipakai, $fontTersedia) as $font) {
            $namaFont = ucwords(str_replace('_', ' ', (string) $font));
            $this->tambah(self::LEVEL_ERROR, "Font \"{$namaFont}\" tidak ditemukan di sistem. Surat akan gagal/tidak tampil sesuai desain jika font ini tidak tersedia -- silakan unggah font kustom terlebih dahulu.");
        }
    }

    private function daftarFontTersedia(): array
    {
        $path  = TCPDF_FONTS::_getfontpath();
        $fonts = [];

        if ($path && is_dir($path)) {
            foreach (scandir($path) as $file) {
                if (str_ends_with($file, '.php')) {
                    $fonts[] = strtolower(basename($file, '.php'));
                }
            }
        }

        return $fonts;
    }
}
