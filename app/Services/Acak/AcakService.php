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

use App\Exceptions\Acak\MultiTenantNotSupportedException;
use App\Models\Config;
use App\Services\Acak\Contracts\Sanitizer;
use App\Services\Acak\Sanitizers\ArsipSuratSanitizer;
use App\Services\Acak\Sanitizers\ArtikelFotoSanitizer;
use App\Services\Acak\Sanitizers\BersihFolderDesaSanitizer;
use App\Services\Acak\Sanitizers\BukuTamuSanitizer;
use App\Services\Acak\Sanitizers\DiacakFlagSanitizer;
use App\Services\Acak\Sanitizers\DtksSanitizer;
use App\Services\Acak\Sanitizers\GaleriFotoSanitizer;
use App\Services\Acak\Sanitizers\IdentitasDesaSanitizer;
use App\Services\Acak\Sanitizers\KeluargaSanitizer;
use App\Services\Acak\Sanitizers\KiaSanitizer;
use App\Services\Acak\Sanitizers\KontakSanitizer;
use App\Services\Acak\Sanitizers\LayananMandiriSanitizer;
use App\Services\Acak\Sanitizers\LogActivitySanitizer;
use App\Services\Acak\Sanitizers\LogHapusPendudukSanitizer;
use App\Services\Acak\Sanitizers\LogPendudukSanitizer;
use App\Services\Acak\Sanitizers\LogSuratDinasSanitizer;
use App\Services\Acak\Sanitizers\LogSuratSanitizer;
use App\Services\Acak\Sanitizers\MediaFotoSanitizer;
use App\Services\Acak\Sanitizers\PamongSanitizer;
use App\Services\Acak\Sanitizers\PelapakSanitizer;
use App\Services\Acak\Sanitizers\PeriksaResolverSanitizer;
use App\Services\Acak\Sanitizers\PendudukSanitizer;
use App\Services\Acak\Sanitizers\PengaduanSanitizer;
use App\Services\Acak\Sanitizers\PpidSanitizer;
use App\Services\Acak\Sanitizers\ProgramPesertaSanitizer;
use App\Services\Acak\Sanitizers\RtmSanitizer;
use App\Services\Acak\Sanitizers\SentitemsSanitizer;
use App\Services\Acak\Sanitizers\SuratKeluarSanitizer;
use App\Services\Acak\Sanitizers\SuratMasukSanitizer;
use App\Services\Acak\Sanitizers\UserPhotoSanitizer;
use App\Services\Acak\Sanitizers\WilayahSanitizer;
use App\Services\Acak\Support\SyntheticValueGenerator;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Orkestrator tunggal fitur acak (de-identifikasi) data desa.
 *
 * Menggantikan dua implementasi terpisah yang sebelumnya ada:
 *  - `App\Console\Commands\AcakDataCommand` (logika inline dengan fake()), dan
 *  - `App\Libraries\Acak` yang dipanggil `Database::acak()` (jalur web).
 *
 * Baik CLI maupun web kini memanggil {@see self::run()} dengan {@see AcakOptions}
 * yang sama, sehingga hasilnya identik. Setiap langkah domain diisolasi: kegagalan
 * satu Sanitizer dicatat sebagai warning dan proses lanjut ke Sanitizer berikutnya.
 */
class AcakService
{
    /** @var list<Sanitizer> */
    private array $sanitizers;

    private ?Closure $progressCallback = null;

    /**
     * @param list<Sanitizer>|null $sanitizers Daftar Sanitizer berurutan.
     *                                        Default: rangkaian standar OpenSID.
     *                                        Dapat di-inject untuk pengujian.
     */
    public function __construct(?array $sanitizers = null)
    {
        $this->sanitizers = $sanitizers ?? self::defaultSanitizers();
    }

    /**
     * Rangkaian Sanitizer standar, dalam urutan yang bergantung antar-langkah.
     *
     * Penduduk berjalan sebelum Keluarga agar nama kepala keluarga yang ditulis
     * ke log_penduduk sudah tersintesis.
     *
     * @return list<Sanitizer>
     */
    public static function defaultSanitizers(): array
    {
        return [
            // Inti (urutan penting): penduduk sebelum keluarga; keduanya sebelum log.
            new IdentitasDesaSanitizer(),
            new WilayahSanitizer(),
            new RtmSanitizer(),
            new PendudukSanitizer(),
            new KeluargaSanitizer(),
            new LogPendudukSanitizer(),
            new LogHapusPendudukSanitizer(),
            // Tabel pendukung ber-PII (memakai peta nik/no_kk dari inti bila relevan).
            new PamongSanitizer(),
            new ProgramPesertaSanitizer(),
            new PengaduanSanitizer(),
            new PpidSanitizer(),
            new KiaSanitizer(),
            new KontakSanitizer(),
            new BukuTamuSanitizer(),
            new SentitemsSanitizer(),
            new LayananMandiriSanitizer(),
            new DtksSanitizer(),
            new LogActivitySanitizer(),
            new PelapakSanitizer(),
            // Metadata surat: redaksi isi, nama pamong sintetis, scrub PII di input JSON.
            new LogSuratSanitizer(),
            new LogSuratDinasSanitizer(),
            new SuratMasukSanitizer(),
            new SuratKeluarSanitizer(),
            // Foto: avatar/blur warga & pamong, watermark/ganti foto konten. Sebelum
            // pembersihan folder agar berkas hasil (avatar) tetap dirujuk & tidak tersapu.
            new UserPhotoSanitizer(),
            new ArtikelFotoSanitizer(),
            new GaleriFotoSanitizer(),
            new MediaFotoSanitizer(),
            // Setelah de-identifikasi DB: sapu file usang di folder desa.
            new BersihFolderDesaSanitizer(),
            // Lalu buat arsip surat sintetis (placeholder/render) menggantikan berkas asli.
            new ArsipSuratSanitizer(),
            // Lalu rapikan agar /periksa lolos (web-only).
            new PeriksaResolverSanitizer(),
            // Selalu terakhir: tandai database sudah diacak.
            new DiacakFlagSanitizer(),
        ];
    }

    /**
     * Daftarkan callback progress (langkah demi langkah), dipakai mis. untuk
     * streaming ke UI web atau progress bar CLI.
     */
    public function setProgressCallback(Closure $callback): void
    {
        $this->progressCallback = $callback;
    }

    /**
     * Apakah database ini multi-desa (multitenant)?
     *
     * Acak hanya sesuai untuk database satu desa. Definisi multitenant mengikuti
     * konvensi OpenSID (`setting('multi_desa')`), yakni lebih dari satu baris config.
     * Dibuat non-final agar mudah di-stub pada pengujian tanpa database.
     */
    public function isMultiTenant(): bool
    {
        return Config::count() > 1;
    }

    /**
     * Jalankan seluruh proses acak dan kembalikan statistiknya.
     *
     * @throws MultiTenantNotSupportedException bila database multi-desa
     */
    public function run(?AcakOptions $options = null): AcakResult
    {
        // Jaring pengaman terakhir: tolak database multitenant apa pun titik masuknya.
        if ($this->isMultiTenant()) {
            throw new MultiTenantNotSupportedException();
        }

        @set_time_limit(0);

        $options = $options ?? new AcakOptions();
        $result  = new AcakResult();
        $context = new AcakContext(
            options: $options,
            gen: new SyntheticValueGenerator($options->seed),
            kodeKecamatan: (string) (identitas('kode_kecamatan') ?? ''),
            namaKabupaten: (string) (identitas('nama_kabupaten') ?? ''),
        );
        // Sanitizer besar melaporkan progres per-batch lewat context agar stream tetap hidup.
        $context->setProgress($this->progressCallback);

        $t0 = microtime(true);

        foreach ($this->sanitizers as $sanitizer) {
            $context->progress($sanitizer->label());

            try {
                $sanitizer->sanitize($context, $result);
            } catch (Throwable $e) {
                $msg = $sanitizer->label() . ' gagal: ' . $e->getMessage();
                $result->addWarning($msg);
                Log::warning('[AcakService] ' . $msg);
            }
        }

        cache()->flush();

        $result->elapsedSeconds = round(microtime(true) - $t0, 1);

        return $result;
    }
}
