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
use App\Services\Acak\Support\AvatarGenerator;
use App\Services\Acak\Support\ImageToolkit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;

/**
 * Penanganan foto warga (tweb_penduduk) & pamong (tweb_desa_pamong) sesuai
 * {@see \App\Services\Acak\AcakOptions::$photoMode}:
 *
 *  - 'none'   → tidak dijalankan (kolom foto sudah dikosongkan sanitizer inti).
 *  - 'avatar' → hapus foto asli, buat avatar kartun deterministik, repoint kolom foto.
 *  - 'blur'   → kaburkan wajah pada berkas foto asli via skrip Python (in-place).
 *
 * Foto pamong diberi ruang-nama id terpisah agar tak menimpa avatar warga.
 */
final class UserPhotoSanitizer implements Sanitizer
{
    /** Offset id agar nama berkas avatar pamong tidak bentrok dengan warga. */
    private const PAMONG_ID_OFFSET = 900000000;

    /**
     * @param string|null $uploadDir  Path folder user_pict; default desa/upload/user_pict.
     *                                Wajib di-set eksplisit pada pengujian (jika null saat
     *                                testing, langkah ini dilewati demi keamanan berkas).
     * @param string|null $blurScript Path skrip Python blur; default bin/blur_user_pict.py.
     */
    public function __construct(
        private readonly ?string $uploadDir = null,
        private readonly ?string $blurScript = null,
    ) {
    }

    public function label(): string
    {
        return 'Memproses foto warga & pamong';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $mode = $context->options->photoMode;
        if ($mode === 'none') {
            return;
        }

        $dir = $this->resolveUploadDir();
        if ($dir === null) {
            return; // guard pengujian: tanpa uploadDir eksplisit, jangan sentuh berkas.
        }

        if (! is_dir($dir)) {
            $result->addWarning("foto: direktori foto tidak ditemukan ({$dir})");

            return;
        }

        $dir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;

        if ($mode === 'blur') {
            $this->blur($dir, $result);

            return;
        }

        $this->avatars($dir, $context, $result);
    }

    /**
     * Mode avatar: bersihkan folder foto lalu bangun avatar deterministik untuk tiap
     * warga/pamong yang sebelumnya memiliki foto, dan arahkan ulang kolom foto.
     */
    private function avatars(string $dir, AcakContext $context, AcakResult $result): void
    {
        $toolkit   = new ImageToolkit($context->options->seed);
        $generator = new AvatarGenerator($toolkit);

        if (! $toolkit->gdAvailable()) {
            $result->addWarning('avatar: ekstensi GD tidak tersedia, foto tidak diproses');

            return;
        }

        $this->clearImages($dir);

        $count = 0;

        if (Schema::hasTable('tweb_penduduk')) {
            DB::table('tweb_penduduk')
                ->select(['id', 'sex', 'nama', 'foto'])
                ->whereNotNull('foto')->where('foto', '!=', '')
                ->orderBy('id')
                ->chunkById(500, function ($rows) use ($generator, $dir, &$count, $result, $context): void {
                    foreach ($rows as $r) {
                        $file = $generator->generate((int) $r->id, (int) $r->sex, (string) ($r->nama ?? 'X'), $dir);
                        if ($file === '') {
                            continue;
                        }
                        DB::table('tweb_penduduk')->where('id', $r->id)->update(['foto' => $file]);
                        $result->trackUrl($dir . $file);
                        $result->trackUrl($dir . 'kecil_' . $file); // thumbnail yang ditampilkan AmbilFoto()
                        $count++;
                    }
                    $context->progress('Membuat avatar warga');
                });

            $result->markTable('tweb_penduduk (foto)');
        }

        if (Schema::hasTable('tweb_desa_pamong')) {
            $pamong = DB::table('tweb_desa_pamong')
                ->select(['pamong_id', 'pamong_sex', 'pamong_nama', 'foto'])
                ->whereNotNull('foto')->where('foto', '!=', '')
                ->get();

            foreach ($pamong as $p) {
                $avatarId = self::PAMONG_ID_OFFSET + (int) $p->pamong_id;
                $file     = $generator->generate($avatarId, (int) ($p->pamong_sex ?: 1), (string) ($p->pamong_nama ?? 'X'), $dir);
                if ($file === '') {
                    continue;
                }
                DB::table('tweb_desa_pamong')->where('pamong_id', $p->pamong_id)->update(['foto' => $file]);
                $result->trackUrl($dir . $file);
                $result->trackUrl($dir . 'kecil_' . $file); // thumbnail yang ditampilkan AmbilFoto()
                $count++;
            }

            if ($pamong->isNotEmpty()) {
                $result->markTable('tweb_desa_pamong (foto)');
            }
        }

        $result->photosGenerated += $count;
    }

    /**
     * Mode blur: kaburkan wajah pada berkas foto yang dirujuk database via skrip
     * Python. Degradasi aman: bila Python/skrip tidak tersedia, catat peringatan.
     */
    private function blur(string $dir, AcakResult $result): void
    {
        $script = $this->blurScript ?? base_path('bin/blur_user_pict.py');
        if (! is_file($script)) {
            $result->addWarning("blur: skrip tidak ditemukan ({$script})");

            return;
        }

        $files = [];
        foreach (['tweb_penduduk' => 'foto', 'tweb_desa_pamong' => 'foto'] as $table => $col) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $files = array_merge($files, DB::table($table)
                ->whereNotNull($col)->where($col, '!=', '')
                ->distinct()->pluck($col)->all());
        }
        $files = array_values(array_unique(array_filter(array_map('strval', $files))));

        if ($files === []) {
            $result->addWarning('blur: tidak ada foto yang dirujuk di database');

            return;
        }

        foreach ($files as $file) {
            $result->trackUrl($dir . $file);
            // Halaman menampilkan foto lewat AmbilFoto() ukuran 'kecil_', jadi
            // thumbnail (diregenerasi skrip blur) juga perlu di-cache-bust.
            $result->trackUrl($dir . 'kecil_' . $file);
        }

        $python = $this->findPython();
        $cmd    = array_merge([$python, $script, '--dir', $dir, '--files'], $files);
        $proc   = Process::timeout(600)->run($cmd);

        if (! $proc->successful()) {
            $stderr = trim($proc->errorOutput());
            $result->addWarning('blur: ' . ($stderr !== '' ? $stderr : 'proses gagal (exit ' . $proc->exitCode() . ')'));
            Log::warning('[AcakService] blur_user_pict gagal', ['stderr' => $stderr]);

            return;
        }

        $result->photosBlurred += substr_count($proc->output(), 'wajah');
        $result->markTable('user_pict (blur)');
    }

    /**
     * Cari Python 3 (venv proyek, venv pengguna, lalu sistem).
     */
    private function findPython(): string
    {
        $home = $_SERVER['HOME'] ?? (function_exists('posix_getpwuid') ? (posix_getpwuid(posix_getuid())['dir'] ?? '/root') : '/root');

        $candidates = [
            base_path('.venv/bin/python3'),
            "{$home}/venvs/anonymizer/bin/python3",
            '/usr/local/bin/python3',
            '/usr/bin/python3',
        ];

        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        return 'python3';
    }

    /**
     * Hapus semua JPEG/PNG di folder foto sebelum membangun avatar.
     */
    private function clearImages(string $dir): void
    {
        foreach (glob($dir . '*.{jpg,jpeg,png}', GLOB_BRACE) ?: [] as $file) {
            @unlink($file);
        }
    }

    private function resolveUploadDir(): ?string
    {
        if ($this->uploadDir !== null) {
            return $this->uploadDir;
        }

        if (app()->environment('testing')) {
            return null;
        }

        return base_path('desa/upload/user_pict');
    }
}
