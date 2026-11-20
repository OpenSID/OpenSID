<?php

declare(strict_types=1);

namespace Tests\Feature\FolderDesaCleaner;

use App\Services\FolderDesaCleaner\FolderDesaKeepList;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Persistensi daftar file yang dipertahankan (setting_aplikasi), yang menjadi
 * satu sumber kebenaran bagi Bersihkan Folder Desa maupun proses Acak.
 */
class FolderDesaKeepListTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function set_get_clear_berjalan(): void
    {
        $keep = new FolderDesaKeepList();

        $this->assertSame([], $keep->get());

        $keep->set(['desa/arsip/a.pdf', 'desa/upload/thumbs/b.jpg']);
        $this->assertSame(['desa/arsip/a.pdf', 'desa/upload/thumbs/b.jpg'], $keep->get());

        // set() mengganti seluruh daftar (memangkas entri lama).
        $keep->set(['desa/arsip/c.pdf']);
        $this->assertSame(['desa/arsip/c.pdf'], $keep->get());

        $keep->clear();
        $this->assertSame([], $keep->get());
        $this->assertNull(DB::table('setting_aplikasi')->where('key', 'bersih_folder_keep')->first());
    }

    #[Test]
    public function menyaring_nilai_non_string_dan_duplikat(): void
    {
        $keep = new FolderDesaKeepList();

        // @phpstan-ignore-next-line — sengaja kirim nilai kotor
        $keep->set(['desa/a.txt', 'desa/a.txt', 123, null, 'desa/b.txt']);

        $this->assertSame(['desa/a.txt', 'desa/b.txt'], $keep->get());
    }
}
