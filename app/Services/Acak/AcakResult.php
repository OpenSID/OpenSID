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
 * Akumulator statistik hasil proses acak.
 *
 * Dibagikan ke setiap Sanitizer agar mencatat berapa baris/kolom yang diubah,
 * lalu dikembalikan oleh {@see AcakService::run()} untuk ditampilkan di CLI
 * maupun UI web.
 */
final class AcakResult
{
    public int $rowsProcessed = 0;

    /** @var array<string, true> tabel yang tersentuh (dipakai sebagai set) */
    private array $tablesModified = [];

    public int $namesReplaced = 0;

    public int $niksReplaced = 0;

    public int $kksReplaced = 0;

    public int $phonesReplaced = 0;

    /** File usang dihapus oleh pembersihan folder desa. */
    public int $orphanedDeleted = 0;

    /** @var array<string, int> masalah /periksa → jumlah yang diperbaiki */
    public array $periksaResolved = [];

    /** @var list<string> masalah /periksa yang masih tersisa */
    public array $periksaResidual = [];

    // Penghitung forward-compatible (diisi batch foto/arsip/surat berikutnya;
    // ditampilkan hanya bila > 0).
    public int $photosBlurred = 0;

    public int $photosGenerated = 0;

    public int $fotosArtikelProcessed = 0;

    public int $fotosGaleriProcessed = 0;

    public int $fotosMediaProcessed = 0;

    public int $arsipReplaced = 0;

    public int $suratSintetisGenerated = 0;

    /** @var list<string> URL foto yang diproses (untuk cache-bust di browser) */
    public array $processedUrls = [];

    /** @var list<array{nik_lama: string, nik_baru: string, nama_lama: string, nama_baru: string}> contoh 5 baris pertama */
    public array $pendudukSample = [];

    /** @var list<string> */
    public array $warnings = [];

    public float $elapsedSeconds = 0.0;

    /**
     * Rekonstruksi hasil dari array {@see self::toArray()} — dipakai untuk
     * membaca kembali hasil job latar belakang yang disimpan sebagai JSON.
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $result = new self();

        foreach ($data['tables_modified'] ?? [] as $table) {
            $result->markTable((string) $table);
        }

        $result->rowsProcessed          = (int) ($data['rows_processed'] ?? 0);
        $result->namesReplaced          = (int) ($data['names_replaced'] ?? 0);
        $result->niksReplaced           = (int) ($data['niks_replaced'] ?? 0);
        $result->kksReplaced            = (int) ($data['kks_replaced'] ?? 0);
        $result->phonesReplaced         = (int) ($data['phones_replaced'] ?? 0);
        $result->orphanedDeleted        = (int) ($data['orphaned_deleted'] ?? 0);
        $result->periksaResolved        = (array) ($data['periksa_resolved'] ?? []);
        $result->periksaResidual        = (array) ($data['periksa_residual'] ?? []);
        $result->photosBlurred          = (int) ($data['photos_blurred'] ?? 0);
        $result->photosGenerated        = (int) ($data['photos_generated'] ?? 0);
        $result->fotosArtikelProcessed  = (int) ($data['fotos_artikel_processed'] ?? 0);
        $result->fotosGaleriProcessed   = (int) ($data['fotos_galeri_processed'] ?? 0);
        $result->fotosMediaProcessed    = (int) ($data['fotos_media_processed'] ?? 0);
        $result->arsipReplaced          = (int) ($data['arsip_replaced'] ?? 0);
        $result->suratSintetisGenerated = (int) ($data['surat_sintetis_generated'] ?? 0);
        $result->processedUrls          = (array) ($data['processed_urls'] ?? []);
        $result->pendudukSample         = (array) ($data['penduduk_sample'] ?? []);
        $result->warnings               = (array) ($data['warnings'] ?? []);
        $result->elapsedSeconds         = (float) ($data['elapsed_seconds'] ?? 0.0);

        return $result;
    }

    public function markTable(string $table): void
    {
        $this->tablesModified[$table] = true;
    }

    public function addRows(int $count): void
    {
        $this->rowsProcessed += $count;
    }

    public function addWarning(string $message): void
    {
        $this->warnings[] = $message;
    }

    /**
     * Catat URL web berkas gambar yang diproses agar browser dapat me-refresh cache.
     * Path absolut dikonversi menjadi path relatif terhadap root aplikasi.
     */
    public function trackUrl(string $absPath): void
    {
        if (! is_file($absPath)) {
            return;
        }

        $this->processedUrls[] = '/' . ltrim(str_replace(['\\', base_path()], ['/', ''], $absPath), '/');
    }

    /**
     * @return list<string>
     */
    public function tables(): array
    {
        return array_keys($this->tablesModified);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'rows_processed'           => $this->rowsProcessed,
            'tables_modified'          => $this->tables(),
            'names_replaced'           => $this->namesReplaced,
            'niks_replaced'            => $this->niksReplaced,
            'kks_replaced'             => $this->kksReplaced,
            'phones_replaced'          => $this->phonesReplaced,
            'orphaned_deleted'         => $this->orphanedDeleted,
            'periksa_resolved'         => $this->periksaResolved,
            'periksa_residual'         => $this->periksaResidual,
            'photos_blurred'           => $this->photosBlurred,
            'photos_generated'         => $this->photosGenerated,
            'fotos_artikel_processed'  => $this->fotosArtikelProcessed,
            'fotos_galeri_processed'   => $this->fotosGaleriProcessed,
            'fotos_media_processed'    => $this->fotosMediaProcessed,
            'arsip_replaced'           => $this->arsipReplaced,
            'surat_sintetis_generated' => $this->suratSintetisGenerated,
            'processed_urls'           => $this->processedUrls,
            'penduduk_sample'          => $this->pendudukSample,
            'warnings'                 => $this->warnings,
            'elapsed_seconds'          => $this->elapsedSeconds,
        ];
    }
}
