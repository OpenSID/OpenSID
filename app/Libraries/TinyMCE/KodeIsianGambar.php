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

namespace App\Libraries\TinyMCE;

use App\Models\LogSurat;
use App\Models\Penduduk;

class KodeIsianGambar
{
    private $urls_id;

    /**
     * @var CI_Controller
     */
    protected $ci;

    public function __construct(private $request, private $result, private $surat = null, private $lampiran = false)
    {
        $this->ci = &get_instance();
        $this->ci->load->model('surat_model');
    }

    public static function set($request, $result, $surat = null, $lampiran = false): array
    {
        $result = str_replace(['alt="[logo]"', 'alt="[logo_bsre]"', 'alt="[foto_penduduk]"'], 'alt=""', $result);

        return (new self($request, $result, $surat, $lampiran))->setKodeIsianGambar();
    }

    public function setKodeIsianGambar(): array
    {
        // Logo Surat
        $file_logo = $this->request['logo_garuda'] ? FCPATH . LOGO_GARUDA : gambar_desa(identitas()->logo, false, true);
        $this->replacePlaceholder('[logo]', $file_logo, 90, 90);

        // Logo BSrE
        if (setting('tte') == 1) {
            $this->replacePlaceholder('[logo_bsre]', FCPATH . LOGO_BSRE, height: 90);
        }

        // Foto Penduduk
        $fotoPath = FCPATH . LOKASI_USER_PICT . Penduduk::find($this->surat['id_pend'])->foto;
        $this->replacePlaceholder('[foto_penduduk]', $fotoPath, 90, 'auto');

        // QR_Code Surat
        $this->handleQrCode();

        return [
            'result'  => $this->result,
            'urls_id' => $this->urls_id ?? null,
        ];
    }

    /**
     * Mengganti placeholder dengan tag gambar berformat base64 jika file tersedia.
     *
     * @param mixed $height
     */
    private function replacePlaceholder(string $placeholder, string $filePath, int $width = 90, $height = 90): void
    {
        $realPath = realpath($filePath);
        $imgTag   = ''; // Placeholder dihapus jika gambar tidak tersedia
        if ($realPath && file_exists($realPath)) {
            $base64   = base64_encode(file_get_contents($realPath));
            $mimeType = mime_content_type($realPath);
            $imgSrc   = "data:{$mimeType};base64,{$base64}";
            $imgTag   = "<img src=\"{$imgSrc}\" width=\"{$width}\" height=\"{$height}\" />";
        }

        $this->result = str_replace($placeholder, $imgTag, $this->result);
    }

    /**
     * Menangani logika kode QR, memastikan kompatibilitas dengan Html2Pdf.
     */
    private function handleQrCode(): void
    {
        app('ci')->load->model('surat_model');
        if (! $this->request['qr_code']) {
            $this->result = str_replace('[qr_code]', '', $this->result);

            return;
        }

        // Generate kode QR (dari surat atau dummy)
        $cek = $this->surat ? $this->surat_model->buatQrCode($this->surat->nama_surat) : dummyQrCode($this->header['desa']['logo']);

        // Pastikan gambar kode QR valid sebelum diproses
        $qrcodePath = $cek['viewqr'] ?? null;
        if ($qrcodePath && file_exists($qrcodePath)) {
            $base64   = base64_encode(file_get_contents($qrcodePath));
            $mimeType = mime_content_type($qrcodePath);
            $qrcode   = "<img src=\"data:{$mimeType};base64,{$base64}\" width=\"90\" height=\"90\" alt=\"qrcode-surat\" />";
        } else {
            $qrcode = ''; // Placeholder dihapus jika kode QR tidak tersedia
        }

        if ($this->surat) {
            // Periksa apakah ada kode QR yang sudah ada dalam hasil
            preg_match('/<img[^>]+src="([^"]*qrcode[^"]*temp[^"]*)"/i', $this->result, $matches);

            if (isset($matches[1]) && ! file_exists($matches[1])) {
                // Ganti kode QR yang tidak valid dengan yang baru
                $this->result = str_replace($matches[1], $qrcode, $this->result);
                $this->surat->update(['isi_surat' => $this->result]);
            } elseif ($this->shouldIncludeQrCode()) {
                // Pastikan kode QR hanya disisipkan jika memenuhi kondisi
                $this->result = str_replace('[qr_code]', $qrcode, $this->result);
            }
        } else {
            // Langsung ganti placeholder jika tidak ada surat yang diberikan
            $this->result = str_replace('[qr_code]', $qrcode, $this->result);
        }

        $this->urls_id = $cek['urls_id'] ?? null;
    }

    /**
     * Menentukan apakah kode QR harus dimasukkan dalam hasil.
     */
    private function shouldIncludeQrCode(): bool
    {
        return (setting('tte') == 1 && ($this->surat->verifikasi_kades == LogSurat::TERIMA || $this->lampiran)) || setting('tte') == 0;
    }

    public function __get($name)
    {
        return $this->ci->{$name};
    }

    public function __call($method, $arguments)
    {
        return $this->ci->{$method}(...$arguments);
    }
}
