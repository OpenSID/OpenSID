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

use App\Enums\AktifEnum;
use App\Enums\StatusEnum;
use App\Libraries\TinyMCE;
use App\Libraries\TinyMCE\KodeIsianGambar;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Render ulang satu surat arsip dari template + entri form (`input`, sudah
 * disanitasi pada tahap metadata) menjadi PDF di desa/arsip/{namaArsip}.
 *
 * Mereplikasi pipeline cetak SuratController::pdf(): gantiKodeIsian → formatPdf →
 * KodeIsianGambar::set → generateSurat → pdfMerge. WAJIB berjalan pada konteks web
 * (CI3 tersedia, karena TinyMCE memakai helper CI3). Melempar exception bila gagal
 * agar pemanggil dapat jatuh ke placeholder.
 */
final class SuratRenderer
{
    public function render(int $logSuratId, string $arsipDir, string $namaArsip): void
    {
        $arsipDir = rtrim($arsipDir, '/\\') . DIRECTORY_SEPARATOR;

        $r = DB::table('log_surat')->where('id', $logSuratId)->first();
        if (! $r || ! $this->hasInput($r->input)) {
            throw new RuntimeException('input kosong');
        }

        $surat = FormatSurat::find($r->id_format_surat);
        if (! $surat) {
            throw new RuntimeException("format surat #{$r->id_format_surat} tidak ditemukan");
        }

        $input   = json_decode((string) $r->input, true) ?: [];
        $tinymce = new TinyMCE();

        $log_surat = [
            'id_format_surat' => $r->id_format_surat,
            'id_pend'         => $r->id_pend,
            'id_pamong'       => $r->id_pamong,
            'nama_jabatan'    => $r->nama_jabatan,
            'nama_pamong'     => $r->nama_pamong,
            'tanggal'         => $r->tanggal ? Carbon::parse($r->tanggal) : Carbon::now(),
            'bulan'           => $r->bulan,
            'tahun'           => $r->tahun,
            'no_surat'        => $r->no_surat,
            'keterangan'      => $r->keterangan,
            'kecamatan'       => $r->kecamatan,
            'nama_non_warga'  => $r->nama_non_warga,
            'nik_non_warga'   => $r->nik_non_warga,
            'surat'           => $surat,
            'input'           => $input,
        ];

        $setting_header = $surat->header == StatusEnum::TIDAK ? '' : setting('header_surat');
        $setting_footer = $surat->footer == StatusEnum::YA
            ? (setting('tte') == StatusEnum::YA ? setting('footer_surat_tte') : setting('footer_surat'))
            : '';
        $log_surat['isi_surat'] = preg_replace('/\\\\/', '', (string) $setting_header)
            . '<!-- pagebreak -->'
            . ($surat->template_desa ?: $surat->template)
            . '<!-- pagebreak -->'
            . preg_replace('/\\\\/', '', (string) $setting_footer);

        $isi_surat = $tinymce->gantiKodeIsian($log_surat, false);
        $isi_cetak = $tinymce->formatPdf((string) $surat->header, (string) $surat->footer, $isi_surat, false);

        $logModel    = LogSurat::find($logSuratId);
        $data_gambar = KodeIsianGambar::set($surat, $isi_cetak, $logModel);
        $isi_cetak   = $data_gambar['result'];

        $margin = $surat->margin_global == AktifEnum::AKTIF
            ? setting('surat_margin_cm_to_mm')
            : $surat->margin_cm_to_mm;
        $font = underscore(setting('font_surat'));

        $cetak = ['surat' => $surat, 'input' => $input, 'id_pend' => $r->id_pend];
        $tinymce->generateSurat($isi_cetak, $cetak, $margin, $font);

        ob_start();
        $tinymce->pdfMerge->merge($arsipDir . $namaArsip, 'F');
        ob_get_clean();

        if (! is_file($arsipDir . $namaArsip)) {
            throw new RuntimeException('file PDF tidak tertulis');
        }
    }

    private function hasInput(mixed $input): bool
    {
        $input = (string) $input;

        return $input !== '' && $input !== '[]' && $input !== '{}' && $input !== 'null';
    }
}
