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

namespace App\Services\Acak\Support;

/**
 * Penulis berkas arsip surat placeholder: PDF minimal valid (murni PHP, tanpa
 * dependensi) berlabel "[ISI SURAT DIREDAKSI]" beserta thumbnail PNG (GD).
 *
 * Bersifat idempoten: berkas yang sudah ada tidak ditimpa, sehingga proses acak
 * dapat diulang tanpa menumpuk perubahan.
 */
final class ArsipPlaceholderWriter
{
    private const LABEL = '[ISI SURAT DIREDAKSI]';

    public function __construct(private readonly ImageToolkit $toolkit = new ImageToolkit())
    {
    }

    /**
     * Tulis PDF placeholder untuk berkas surat utama (dan lampiran bila ada) di
     * $arsipDir. Mengembalikan true bila berkas utama baru dibuat.
     */
    public function writePlaceholder(string $arsipDir, string $newName, ?string $newLampiran): bool
    {
        $arsipDir = rtrim($arsipDir, '/\\') . DIRECTORY_SEPARATOR;
        $created  = false;

        $mainPath = $arsipDir . $newName;
        if (! is_file($mainPath)) {
            file_put_contents($mainPath, $this->pdf());
            $created = true;
        }

        if ($newLampiran !== null) {
            $lampiranPath = $arsipDir . $newLampiran;
            if (! is_file($lampiranPath)) {
                file_put_contents($lampiranPath, $this->pdf());
            }
        }

        return $created;
    }

    /**
     * Buat thumbnail PNG 150×212 untuk berkas PDF surat di $thumbsDir. Idempoten.
     */
    public function thumbnail(string $thumbsDir, string $pdfName): void
    {
        if (! $this->toolkit->gdAvailable()) {
            return;
        }

        $thumbsDir = rtrim($thumbsDir, '/\\') . DIRECTORY_SEPARATOR;
        $thumbPath = $thumbsDir . pathinfo($pdfName, PATHINFO_FILENAME) . '.png';
        if (is_file($thumbPath)) {
            return;
        }

        $w   = 150;
        $h   = 212;
        $img = imagecreatetruecolor($w, $h);
        imagefilledrectangle($img, 0, 0, $w - 1, $h - 1, imagecolorallocate($img, 255, 255, 255));
        imagerectangle($img, 0, 0, $w - 1, $h - 1, imagecolorallocate($img, 180, 180, 180));
        $red = imagecolorallocate($img, 160, 50, 50);

        $font = $this->toolkit->fontPath();
        if ($font !== null) {
            $fontSize = 7;
            $bbox     = imagettfbbox($fontSize, 0, $font, self::LABEL);
            $tx       = (int) (($w - ($bbox[2] - $bbox[0])) / 2) - $bbox[0];
            $ty       = (int) ($h / 2) - $bbox[7];
            imagettftext($img, $fontSize, 0, $tx, $ty, $red, $font, self::LABEL);
        } else {
            imagestring($img, 1, 5, (int) ($h / 2) - 4, self::LABEL, $red);
        }

        imagepng($img, $thumbPath);
        imagedestroy($img);
    }

    /**
     * Bangun PDF 1 halaman minimal yang valid (Helvetica) berisi label redaksi.
     */
    public function pdf(): string
    {
        $stream = 'BT /F1 14 Tf 72 750 Td (' . self::LABEL . ') Tj ET';

        $objs = [
            1 => '<</Type/Catalog/Pages 2 0 R>>',
            2 => '<</Type/Pages/Kids[3 0 R]/Count 1>>',
            3 => '<</Type/Page/MediaBox[0 0 595 842]/Parent 2 0 R/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>',
            4 => '<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>',
            5 => '<</Length ' . strlen($stream) . ">>\nstream\n{$stream}\nendstream",
        ];

        $body    = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objs as $n => $content) {
            $offsets[$n] = strlen($body);
            $body .= "{$n} 0 obj\n{$content}\nendobj\n";
        }

        $xrefPos = strlen($body);
        $count   = count($objs) + 1;
        $xref    = "xref\n0 {$count}\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $xref .= sprintf("%010d 00000 n \n", $offset);
        }
        $xref .= "trailer\n<</Size {$count}/Root 1 0 R>>\nstartxref\n{$xrefPos}\n%%EOF";

        return $body . $xref;
    }
}
