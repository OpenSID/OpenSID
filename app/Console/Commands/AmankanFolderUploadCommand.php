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

use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Menimpa berkas .htaccess lama yang lemah di folder yang dapat ditulis
 * (unggahan, arsip, storage, assets) dengan template keras dari
 * config/installer.php.
 *
 * Perbaikan untuk https://github.com/OpenSID/premium/issues/7008: instalasi
 * existing tidak pernah memperbarui .htaccess karena folder() hanya menulis
 * saat berkas belum ada. Perintah ini menyapu semua .htaccess lama.
 *
 * Contoh:
 *   php artisan opensid:amankan-folder-upload            # terapkan
 *   php artisan opensid:amankan-folder-upload --dry-run  # pratinjau saja
 */
class AmankanFolderUploadCommand extends Command
{
    
    /**
     * Penanda yang selalu ada di template keras. Berkas .htaccess yang tidak
     * mengandung penanda ini dianggap template lama dan akan ditimpa.
     */
    private const PENANDA = 'premium#7008';

    /**
     * Root yang di-scan rekursif untuk menemukan .htaccess turunan (mis.
     * subfolder tanggal di desa/upload/media/). Nilai => template.
     */
    private const ROOT_REKURSIF = [
        'desa/upload' => 'htaccess1',
        'assets' => 'htaccess3',
        'storage/app' => 'htaccess3',
    ];

    protected $signature = 'opensid:amankan-folder-upload
        {--dry-run : Tampilkan perubahan tanpa menulis berkas}';

    protected $description = 'Timpa .htaccess lemah di folder unggah/arsip/storage dengan template keras (premium#7008)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $targets = $this->kumpulkanTarget();

        $dibuat = $diperbarui = $dilewati = 0;
        $baris = [];

        foreach ($targets as $folder => $template) {
            $path = base_path($folder);
            $isi = (string) config('installer.'.$template);
            $hFile = $path.DIRECTORY_SEPARATOR.'.htaccess';

            if (! is_dir($path)) {
                continue;
            }

            $status = $this->prosesBerkas($hFile, $isi, $dryRun);

            match ($status) {
                'dibuat' => $dibuat++,
                'diperbarui' => $diperbarui++,
                default => $dilewati++,
            };

            if ($status !== 'ok') {
                $baris[] = [$folder.'/.htaccess', $template, $status];
            }
        }

        if ($baris !== []) {
            $this->line('');
            $this->table(['Berkas', 'Template', 'Aksi'], $baris);
        }

        $this->line('');
        $this->info(sprintf(
            '%s: %d dibuat, %d diperbarui, %d sudah aman.',
            $dryRun ? 'Pratinjau (tidak ada berkas ditulis)' : 'Selesai',
            $dibuat,
            $diperbarui,
            $dilewati,
        ));

        if ($dryRun && ($dibuat + $diperbarui) > 0) {
            $this->comment('Jalankan tanpa --dry-run untuk menerapkan.');
        }

        $this->line('');
        $this->comment('Catatan: .htaccess DIABAIKAN oleh Nginx. Untuk Nginx/OpenLiteSpeed lihat');
        $this->comment('dokumentasi/PANDUAN_PROTEKSI_EKSEKUSI_UPLOAD.md');

        return self::SUCCESS;
    }

    /**
     * Gabungkan folder terdaftar di config/installer.php dengan hasil sapuan
     * rekursif pada ROOT_REKURSIF.
     *
     * @return array<string, string> path relatif => nama template
     */
    private function kumpulkanTarget(): array
    {
        $targets = [];

        foreach (['desa', 'lainnya'] as $grup) {
            foreach ((array) config('installer.'.$grup) as $folder => $opsi) {
                $template = $opsi[1] ?? null;

                // htaccess4 (rfm) sengaja mengizinkan PHP — jangan disentuh.
                if ($template === null || $template === 'htaccess4') {
                    continue;
                }

                $targets[rtrim($folder, '/')] = $template;
            }
        }

        foreach (self::ROOT_REKURSIF as $root => $template) {
            $rootPath = base_path($root);

            if (! is_dir($rootPath)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST,
            );

            foreach ($iterator as $item) {
                if ($item->isDir()) {
                    continue;
                }

                if ($item->getFilename() !== '.htaccess') {
                    continue;
                }

                $rel = str_replace('\\', '/', substr($item->getPath(), strlen(base_path()) + 1));
                $targets[$rel] ??= $template;
            }
        }

        ksort($targets);

        return $targets;
    }

    /**
     * @return string 'dibuat' | 'diperbarui' | 'ok'
     */
    private function prosesBerkas(string $hFile, string $isi, bool $dryRun): string
    {
        if (! File::exists($hFile)) {
            if (! $dryRun) {
                File::ensureDirectoryExists(\dirname($hFile));
                File::put($hFile, $isi);
            }

            return 'dibuat';
        }

        if (str_contains((string) File::get($hFile), self::PENANDA)) {
            return 'ok';
        }

        if (! $dryRun) {
            File::put($hFile, $isi);
        }

        return 'diperbarui';
    }
}
