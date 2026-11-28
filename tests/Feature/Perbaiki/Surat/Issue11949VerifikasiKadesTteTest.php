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

namespace Tests\Feature\Surat;

use App\Models\LogSurat;
use App\Models\SettingAplikasi;
use App\Repositories\SettingAplikasiRepository;
use Database\Factories\LogSuratFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Regresi untuk issue #11949 - Tidak muncul tombol untuk passphrase tte di akun kades.
 *
 * Bug: Untuk surat dengan format RTF/Word (bukan TinyMCE), Keluar::verifikasi()
 * tetap menulis log_verifikasi = 'TTE' walau surat tersebut TIDAK pernah
 * diteruskan ke tahap tte (kolom `tte` tetap NULL, bukan direset ke 0), karena
 * modul tte via passphrase memang hanya berlaku untuk surat TinyMCE. Akibatnya
 * surat tersebut langsung "lompat" ke Arsip berlabel "Menunggu TTE" tanpa
 * pernah tampil lagi di tab Permohonan dan tanpa tombol passphrase.
 *
 * Fix: log_verifikasi hanya diisi 'TTE' bila surat benar-benar diteruskan ke
 * tahap tte (variabel $next di Keluar::verifikasi() / Surat_dinas_arsip::verifikasi()
 * bernilai 'tte').
 */
class Issue11949VerifikasiKadesTteTest extends BaseTestCase
{
    use RefreshDatabase;

    private function setSetting(string $key, mixed $value): void
    {
        SettingAplikasi::updateOrCreate(['key' => $key], ['value' => $value]);
        app(SettingAplikasiRepository::class)->forgetResolvedSetting();
    }

    #[Test]
    public function surat_tinymce_tetap_tampil_di_permohonan_dengan_tombol_passphrase(): void
    {
        $this->setSetting('tte', 1);
        $this->setSetting('verifikasi_kades', 1);

        // Kondisi setelah kades klik "Setujui" untuk surat TinyMCE: verifikasi_kades=1,
        // tte direset ke 0 (menunggu passphrase).
        $log = LogSuratFactory::new()->create([
            'verifikasi_operator' => 1,
            'verifikasi_kades' => 1,
            'tte' => 0,
            'log_verifikasi' => 'TTE',
        ]);

        $jabatanKadesId = 1;
        $jabatanSekdesId = 2;
        $isAdmin = (object) ['jabatan_id' => $jabatanKadesId];
        $listJabatan = [
            'jabatan_id' => $jabatanKadesId,
            'jabatan_kades_id' => $jabatanKadesId,
            'jabatan_sekdes_id' => $jabatanSekdesId,
        ];

        $masukIds = LogSurat::query()->masuk($isAdmin, $listJabatan)->pluck('id');

        $this->assertTrue(
            $masukIds->contains($log->id),
            'Surat TinyMCE yang menunggu TTE harus tetap tampil di tab Permohonan agar tombol passphrase muncul'
        );
        $this->assertSame(2, $log->statusPeriksa($jabatanKadesId, $jabatanKadesId, $jabatanSekdesId));
    }

    #[Test]
    public function surat_yang_tidak_diteruskan_ke_tahap_tte_tidak_dianggap_menunggu_passphrase(): void
    {
        $this->setSetting('tte', 1);
        $this->setSetting('verifikasi_kades', 1);

        // Surat RTF tidak mendukung tte via passphrase, jadi $next di
        // Keluar::verifikasi() bernilai null dan kolom tte TIDAK pernah disentuh
        // (tetap NULL). Sebelum fix, log_verifikasi tetap ditulis 'TTE' walau
        // surat ini tidak pernah melalui tahap tte, sehingga surat langsung
        // "lompat" ke Arsip berlabel "Menunggu TTE" tanpa tombol passphrase.
        $log = LogSuratFactory::new()->create([
            'verifikasi_operator' => 1,
            'verifikasi_kades' => 1,
            'tte' => null,
        ]);

        $jabatanKadesId = 1;
        $jabatanSekdesId = 2;

        // statusPeriksa == 1 (bukan 2) menandakan tombol passphrase memang tidak
        // relevan untuk surat ini (bukan surat TinyMCE / tte tidak pernah dimulai).
        $this->assertSame(1, $log->statusPeriksa($jabatanKadesId, $jabatanKadesId, $jabatanSekdesId));
    }
}
