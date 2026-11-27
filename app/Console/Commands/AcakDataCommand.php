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

namespace App\Console\Commands;

use App\Services\Acak\AcakOptions;
use App\Services\Acak\AcakService;
use App\Services\FolderDesaCleaner\FolderDesaKeepList;
use Illuminate\Console\Command;
use Throwable;

/**
 * Adapter CLI tipis untuk {@see AcakService}. Seluruh logika de-identifikasi
 * hidup di service tersebut sehingga jalur CLI dan web memakai inti yang sama.
 */
class AcakDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensid:db-acak
        {--seed=opensid-demo : Seed deterministik untuk generasi nilai sintetis}
        {--foto=blur : Foto warga/pamong: none|avatar|blur (blur butuh Python)}
        {--foto-konten=watermark : Foto artikel/galeri/media: none|watermark|picsum}
        {--surat=sintetis : Arsip surat: placeholder|sintetis (sintetis hanya konteks web)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Acak (de-identifikasi) data desa: identitas, wilayah, RTM, keluarga, penduduk, dan pelapak.';

    /**
     * Execute the console command.
     */
    public function handle(AcakService $acak): int
    {
        if ($acak->isMultiTenant()) {
            $this->error(\App\Exceptions\Acak\MultiTenantNotSupportedException::PESAN);

            return self::FAILURE;
        }

        $seed        = (string) $this->option('seed');
        $photoMode   = (string) $this->option('foto');
        $fotoKonten  = (string) $this->option('foto-konten');
        $suratMode   = (string) $this->option('surat');
        $this->info(">_ Mulai proses acak data (seed: {$seed}, foto: {$photoMode}, foto konten: {$fotoKonten}, surat: {$suratMode})…");

        try {
            $acak->setProgressCallback(fn (string $step) => $this->line('   ' . $step));

            $result = $acak->run(new AcakOptions(
                $seed,
                (new FolderDesaKeepList())->get(),
                $photoMode,
                $fotoKonten,
                $suratMode,
            ));
        } catch (Throwable $e) {
            $this->error('Gagal mengacak data: ' . $e->getMessage());
            logger()->error($e);

            return self::FAILURE;
        }

        $this->newLine();
        $this->info(sprintf('>_ Selesai dalam %s detik', $result->elapsedSeconds));
        $this->info(sprintf('>_ Baris diproses     : %s', number_format($result->rowsProcessed)));
        $this->info(sprintf('>_ Tabel dimodifikasi : %d', count($result->tables())));

        if ($result->orphanedDeleted > 0) {
            $this->info(sprintf('>_ File usang dihapus  : %s', number_format($result->orphanedDeleted)));
        }
        if ($result->periksaResolved !== []) {
            $this->info(sprintf('>_ Masalah periksa OK  : %s', number_format(array_sum($result->periksaResolved))));
        }
        if ($result->periksaResidual !== []) {
            $this->info('>_ Periksa sisa        : ' . implode(', ', $result->periksaResidual));
        }
        if ($result->photosGenerated > 0) {
            $this->info(sprintf('>_ Avatar dibuat       : %s', number_format($result->photosGenerated)));
        }
        if ($result->photosBlurred > 0) {
            $this->info(sprintf('>_ Wajah dikaburkan    : %s', number_format($result->photosBlurred)));
        }
        $fotoKontenTotal = $result->fotosArtikelProcessed + $result->fotosGaleriProcessed + $result->fotosMediaProcessed;
        if ($fotoKontenTotal > 0) {
            $this->info(sprintf(
                '>_ Foto konten diproses: %s (artikel %s, galeri %s, media %s)',
                number_format($fotoKontenTotal),
                number_format($result->fotosArtikelProcessed),
                number_format($result->fotosGaleriProcessed),
                number_format($result->fotosMediaProcessed),
            ));
        }
        if ($result->arsipReplaced > 0) {
            $this->info(sprintf('>_ Arsip surat dibuat  : %s', number_format($result->arsipReplaced)));
        }
        if ($result->suratSintetisGenerated > 0) {
            $this->info(sprintf('>_ Surat sintetis      : %s', number_format($result->suratSintetisGenerated)));
        }

        foreach ($result->warnings as $warning) {
            $this->warn('   ! ' . $warning);
        }

        return self::SUCCESS;
    }
}
