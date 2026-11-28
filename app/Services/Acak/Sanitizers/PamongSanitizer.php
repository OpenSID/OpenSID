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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Acak data pamong desa (tweb_desa_pamong). Bila pamong juga penduduk, memakai
 * nama/NIK sintetis penduduk terkait agar konsisten.
 */
final class PamongSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak data pamong desa';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        if (! Schema::hasTable('tweb_desa_pamong')) {
            return;
        }

        $gen   = $context->gen;
        $count = 0;

        $rows = DB::table('tweb_desa_pamong')
            ->select(['pamong_id', 'pamong_nik', 'pamong_tanggallahir', 'pamong_sex'])
            ->get();

        foreach ($rows as $r) {
            $id      = (int) $r->pamong_id;
            $sex     = (int) ($r->pamong_sex ?: 1) === 1 ? 1 : 2;
            $origNik = (string) ($r->pamong_nik ?? '');

            $newNik = $origNik !== ''
                ? ($context->nikMap[$origNik] ?? $gen->orphanNik($origNik))
                : null;
            $newNama = ($origNik !== '' && isset($context->nikNama[$origNik]))
                ? $context->nikNama[$origNik]
                : $gen->name('pamong-' . $id, $sex);

            $dobStr = $gen->syntheticDate('pamong-dob', $id, (string) $r->pamong_tanggallahir) ?? '1980-01-01';

            $update = [
                'pamong_nama'         => $newNama,
                'pamong_nik'          => $newNik,
                'pamong_tempatlahir'  => $gen->city('pamong-' . $id),
                'pamong_tanggallahir' => $dobStr,
                'pamong_nip'          => $gen->nip('pamong-' . $id),
                'pamong_pin'          => null,
                'pamong_tag_id_card'  => null,
            ];

            // Foto pamong dikosongkan hanya pada mode 'none'; mode 'avatar'/'blur'
            // ditangani UserPhotoSanitizer.
            if ($context->options->photoMode === 'none') {
                $update['foto'] = null;
            }

            DB::table('tweb_desa_pamong')->where('pamong_id', $id)->update($update);
            $count++;
        }

        if ($count > 0) {
            $result->markTable('tweb_desa_pamong');
            $result->addRows($count);
            $result->namesReplaced += $count;
        }
    }
}
