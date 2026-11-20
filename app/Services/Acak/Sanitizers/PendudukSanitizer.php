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
use App\Models\Penduduk;
use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use Carbon\Carbon;

/**
 * Acak data penduduk (PII inti).
 *
 * Menggabungkan dua implementasi lama:
 *  - `AcakDataCommand` (regenerasi NIK/nama/atribut penuh), dan
 *  - `App\Libraries\Acak` (pembaruan referensi silang ke tabel bantuan).
 *
 * Membangun {@see AcakContext::$nikMap} agar KeluargaSanitizer dan referensi
 * lain tetap konsisten.
 */
final class PendudukSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak data penduduk';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $count = 0;

        // Keunikan NIK dijaga dari nilai yang DIGENERATE run ini saja (bukan preload
        // dari DB), agar hasil reproducible: seed sama → NIK sama meski dijalankan
        // ulang. Urutan proses (orderBy id) deterministik sehingga probing seq stabil.
        $used = [];
        // Contoh 5 baris pertama (lama → baru) untuk ditampilkan di halaman hasil.
        $sample = [];

        Penduduk::withoutGlobalScopes()
            ->select([
                'id', 'sex', 'nik', 'nama', 'tanggallahir', 'tanggalperkawinan',
                'ayah_nik', 'ibu_nik', 'telepon', 'tanggal_cetak_ktp',
            ])
            ->orderBy('id')
            ->chunkById(500, function ($penduduks) use ($context, &$count, &$used, &$sample): void {
                foreach ($penduduks as $penduduk) {
                    $count += $this->acakSatu($penduduk, $context, $used, $sample);
                }
                $context->progress('Mengacak data penduduk… (' . number_format($count) . ')');
            });

        if ($count > 0) {
            $result->markTable('tweb_penduduk');
            $result->addRows($count);
            $result->niksReplaced += $count;
            $result->namesReplaced += $count;
            $result->pendudukSample = $sample;
        }
    }

    /**
     * @param array<string, mixed>                                                            $used
     * @param list<array{nik_lama: string, nik_baru: string, nama_lama: string, nama_baru: string}> $sample
     */
    private function acakSatu(Penduduk $penduduk, AcakContext $context, array &$used, array &$sample): int
    {
        $gen = $context->gen;
        $sex = (int) $penduduk->sex === 1 ? 1 : 2;

        // Tanggal lahir sintetis deterministik (tahun dipertahankan) → reproducible.
        $dobStr  = $gen->syntheticDate('dob', (int) $penduduk->id, (string) $penduduk->tanggallahir) ?? '1990-01-01';
        $dob     = Carbon::parse($dobStr);
        $oldNik  = (string) $penduduk->nik;
        $newNik  = $this->uniqueNik((int) $penduduk->id, $oldNik, $dob, $sex, $context, $used);
        $newNama = $gen->name((int) $penduduk->id, $sex);

        if (count($sample) < 5) {
            $sample[] = [
                'nik_lama'  => $oldNik,
                'nik_baru'  => $newNik,
                'nama_lama' => (string) $penduduk->nama,
                'nama_baru' => $newNama,
            ];
        }

        $update = [
            'nik'              => $newNik,
            'nama'             => $newNama,
            'tempatlahir'      => $gen->city((int) $penduduk->id),
            'tanggallahir'     => $dobStr,
            'ayah_nik'         => $penduduk->ayah_nik ? $gen->nik16('ayah-' . $penduduk->id) : null,
            'ibu_nik'          => $penduduk->ibu_nik ? $gen->nik16('ibu-' . $penduduk->id) : null,
            'nama_ayah'        => $gen->firstName('ayah-' . $penduduk->id, 1),
            'nama_ibu'         => $gen->firstName('ibu-' . $penduduk->id, 2),
            'akta_lahir'       => $gen->aktaLahir((int) $penduduk->id),
            'akta_perkawinan'  => $gen->aktaPerkawinan((int) $penduduk->id),
            'tag_id_card'      => $gen->tagIdCard((int) $penduduk->id),
            'no_asuransi'      => $gen->noAsuransi((int) $penduduk->id),
            'tempat_cetak_ktp' => $context->namaKabupaten,
            'telepon'          => $penduduk->telepon ? $gen->phone((int) $penduduk->id) : null,
        ];

        // Kolom foto: hanya dikosongkan pada mode 'none'. Pada mode 'avatar'/'blur'
        // penanganan foto ditangani UserPhotoSanitizer (butuh nama sintetis & berkas asli).
        if ($context->options->photoMode === 'none') {
            $update['foto'] = null;
        }

        if ($penduduk->tanggalperkawinan) {
            $update['tanggalperkawinan'] = $gen->syntheticDate('kawin', (int) $penduduk->id, (string) $penduduk->tanggalperkawinan);
        }
        if ($penduduk->tanggal_cetak_ktp) {
            $update['tanggal_cetak_ktp'] = $gen->syntheticDate('cetak', (int) $penduduk->id, (string) $penduduk->tanggal_cetak_ktp);
        }

        Penduduk::withoutGlobalScopes()->where('id', $penduduk->id)->update($update);

        // Jaga integritas referensial: peserta bantuan yang memakai NIK ini,
        // serta pemetaan nik→nama baru untuk konsistensi di tabel lain (log, dll).
        if ($oldNik !== '' && $oldNik !== '0') {
            $context->nikMap[$oldNik]  = $newNik;
            $context->nikNama[$oldNik] = $newNama;
            BantuanPeserta::withoutGlobalScopes()->where('peserta', $oldNik)->update(['peserta' => $newNik]);
        }

        return 1;
    }

    /**
     * NIK sintetis 16 digit: region(6) + tanggal lahir(6) + urut(4). Urutan
     * ditelusuri (probing) hingga menemukan nilai yang belum dipakai.
     *
     * @param array<string, mixed> $used
     */
    private function uniqueNik(int $id, string $origNik, Carbon $dob, int $sex, AcakContext $context, array &$used): string
    {
        $gen    = $context->gen;
        $prefix = $gen->region6($origNik, $context->kodeKecamatan);
        $mid    = $gen->nikDob($dob, $sex);

        for ($i = 0; $i < 9000; $i++) {
            $nik = $prefix . $mid . $gen->seq4('nik-seq', (string) $id, $i);
            if (! isset($used[$nik])) {
                $used[$nik] = true;

                return $nik;
            }
        }

        // Sangat jarang: seluruh 9000 slot untuk region+tanggal ini penuh.
        return $prefix . $mid . $gen->seq4('nik-seq', (string) $id);
    }
}
