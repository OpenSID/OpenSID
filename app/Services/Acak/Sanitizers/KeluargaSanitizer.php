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

use App\Models\BantuanPeserta;
use App\Models\Keluarga;
use App\Models\PendudukSaja;
use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use Carbon\Carbon;

/**
 * Acak nomor Kartu Keluarga (no_kk) dan pertahankan integritas referensial.
 *
 * Berjalan SESUDAH PendudukSanitizer agar nama kepala keluarga yang ditulis ke
 * log_penduduk sudah tersintesis (tidak membocorkan nama asli). Membangun
 * {@see AcakContext::$noKkMap} dan meremap referensi no_kk di log_penduduk,
 * no_kk_sebelumnya penduduk, dan peserta bantuan.
 */
final class KeluargaSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak data keluarga';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $count = 0;

        // Keunikan dari nilai yang digenerate run ini saja (bukan preload DB), agar
        // hasil reproducible untuk seed yang sama.
        $used = [];

        Keluarga::withoutGlobalScopes()
            ->withOnly(['kepalaKeluarga'])
            ->select(['id', 'no_kk', 'tgl_daftar'])
            ->orderBy('id')
            ->chunkById(500, function ($keluargas) use ($context, &$count, &$used): void {
                foreach ($keluargas as $keluarga) {
                    $count += $this->acakSatu($keluarga, $context, $used);
                }
                $context->progress('Mengacak data keluarga… (' . number_format($count) . ')');
            });

        if ($count > 0) {
            $result->markTable('tweb_keluarga');
            $result->addRows($count);
            $result->kksReplaced += $count;
        }
    }

    /**
     * @param array<string, mixed> $used
     */
    private function acakSatu(Keluarga $keluarga, AcakContext $context, array &$used): int
    {
        $oldKk = (string) $keluarga->no_kk;
        if ($oldKk === '' || $oldKk === '0') {
            return 0;
        }

        $tglStr    = $context->gen->syntheticDate('kk-daftar', (int) $keluarga->id, (string) $keluarga->tgl_daftar) ?? '2015-01-01';
        $tglDaftar = Carbon::parse($tglStr);
        $newKk     = $this->uniqueKk((int) $keluarga->id, $oldKk, $tglDaftar, $context, $used);

        $namaKk = $keluarga->kepalaKeluarga->nama ?? '';

        Keluarga::withoutGlobalScopes()->where('id', $keluarga->id)->update([
            'no_kk'      => $newKk,
            'tgl_daftar' => $tglDaftar->format('Y-m-d'),
        ]);

        $context->noKkMap[$oldKk]      = $newKk;
        $context->kkKepalaNama[$oldKk] = $namaKk;

        // Referensi silang. (log_penduduk ditangani LogPendudukSanitizer memakai peta di atas.)
        PendudukSaja::withoutGlobalScopes()->where('no_kk_sebelumnya', $oldKk)->update(['no_kk_sebelumnya' => $newKk]);
        BantuanPeserta::withoutGlobalScopes()->where('peserta', $oldKk)->update(['peserta' => $newKk]);

        return 1;
    }

    /**
     * No. KK sintetis 16 digit: region(6) + tanggal daftar(6) + urut(4), dengan
     * penelusuran (probing) urutan agar unik.
     *
     * @param array<string, mixed> $used
     */
    private function uniqueKk(int $id, string $origKk, Carbon $tglDaftar, AcakContext $context, array &$used): string
    {
        $gen    = $context->gen;
        $prefix = $gen->region6($origKk, $context->kodeKecamatan);
        $mid    = $tglDaftar->format('dmy');

        for ($i = 0; $i < 9000; $i++) {
            $kk = $prefix . $mid . $gen->seq4('kk-seq', (string) $id, $i);
            if (! isset($used[$kk])) {
                $used[$kk] = true;

                return $kk;
            }
        }

        return $prefix . $mid . $gen->seq4('kk-seq', (string) $id);
    }
}
