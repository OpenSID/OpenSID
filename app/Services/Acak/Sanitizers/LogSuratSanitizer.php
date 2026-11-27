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

namespace App\Services\Acak\Sanitizers;

use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use App\Services\Acak\Support\SuratInputSanitizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * De-identifikasi metadata surat warga di `log_surat`.
 *
 * Meredaksi isi surat, mengganti nama pamong dengan nama sintetis, meremap
 * NIK/nama non-warga (via peta dari PendudukSanitizer, atau nilai yatim), dan
 * menyanitasi PII di dalam entri form (`input`) secara rekursif namun tetap
 * mempertahankannya. Nama berkas arsip (`nama_surat`/`lampiran`) sengaja
 * TIDAK diubah di sini — penggantian berkas arsip ditangani batch arsip PDF.
 */
final class LogSuratSanitizer implements Sanitizer
{
    public function __construct(private readonly SuratInputSanitizer $inputSanitizer = new SuratInputSanitizer())
    {
    }

    public function label(): string
    {
        return 'Mengacak metadata surat warga';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        if (! Schema::hasTable('log_surat')) {
            return;
        }

        $gen   = $context->gen;
        $count = 0;

        DB::table('log_surat')
            ->select(['id', 'nik_non_warga', 'nama_non_warga', 'input'])
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($context, $gen, &$count): void {
                foreach ($rows as $r) {
                    $origNik = (string) ($r->nik_non_warga ?? '');
                    $synNik  = $origNik !== ''
                        ? ($context->nikMap[$origNik] ?? $gen->orphanNik($origNik))
                        : null;
                    $synNama = ! empty($r->nama_non_warga)
                        ? $gen->name("non_warga:{$r->id}", 1)
                        : $r->nama_non_warga;

                    // Mode 'sintetis': pertahankan entri form (`input`) dengan PII di
                    // dalamnya disanitasi, agar arsip surat dapat di-render ulang dari
                    // template. Mode 'placeholder' (default): buang `input` seperti
                    // er-laravel, karena tidak diperlukan untuk placeholder.
                    $newInput = null;
                    if ($context->options->suratMode === 'sintetis' && $this->inputSanitizer->hasInput($r->input)) {
                        $decoded  = json_decode((string) $r->input, true);
                        $newInput = is_array($decoded)
                            ? json_encode($this->inputSanitizer->sanitize($decoded, (int) $r->id, $context))
                            : null;
                    }

                    DB::table('log_surat')->where('id', $r->id)->update([
                        'nama_pamong'    => $gen->name(800000 + (int) $r->id, 1),
                        'nik_non_warga'  => $synNik,
                        'nama_non_warga' => $synNama,
                        'isi_surat'      => '[ISI SURAT DIREDAKSI]',
                        'isi_surat_temp' => null,
                        'input'          => $newInput,
                        'pemohon'        => null,
                    ]);
                    $count++;
                }

                $context->progress('Mengacak metadata surat warga');
            });

        if ($count > 0) {
            $result->markTable('log_surat');
            $result->addRows($count);
        }
    }
}
