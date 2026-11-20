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

namespace Tests\Feature\Services;

use App\Enums\SasaranEnum;
use App\Models\BantuanPeserta;
use App\Models\Dtks;
use App\Models\DtksAnggota;
use App\Models\DtksPengaturanProgram;
use App\Models\User;
use App\Services\DTKSRegsosEk2022k;
use Carbon\Carbon;
use Database\Factories\BantuanFactory;
use Database\Factories\KeluargaFactory;
use Database\Factories\PendudukFactory;
use Illuminate\Support\Facades\Schema;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Regresi untuk DTKSRegsosEk2022k::syncProgramPerlindunganSosial() &
 * syncKepesertaanProgramKeluarga() -- null-deref saat anggota/keluarga
 * bukan peserta program bantuan yang sedang dicek, atau program bantuannya
 * sudah dihapus/tidak ditemukan.
 */
class DTKSRegsosEk2022kSyncProgramTest extends BaseTestCase
{
    use RefreshDatabase;

    private DTKSRegsosEk2022k $service;

    protected function setUp(): void
    {
        parent::setUp();

        // AuthorObserver mengisi created_by & updated_by dari auth('admin') saat creating,
        // sehingga factory penduduk/keluarga butuh user admin yang sedang login.
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');

        $this->service = new DTKSRegsosEk2022k();
    }

    public function test_sync_program_perlindungan_sosial_tidak_crash_saat_anggota_bukan_peserta(): void
    {
        $bantuan = BantuanFactory::new()->forSasaran(SasaranEnum::PENDUDUK)->create();

        DtksPengaturanProgram::create([
            'config_id'       => 1,
            'versi_kuisioner' => 2,
            'kode'            => '431b',
            'id_bantuan'      => $bantuan->id,
            'target_table'    => 'dtks_anggota',
            'target_field'    => 'kd_ikut_prakerja',
        ]);

        // anggota ini sama sekali tidak punya baris program_peserta untuk program di atas
        $agt         = PendudukFactory::new()->create();
        $dtksAnggota = DtksAnggota::create([
            'config_id'   => 1,
            'id_penduduk' => $agt->id,
        ]);

        $result = $this->service->syncProgramPerlindunganSosial($dtksAnggota, $agt, null, []);

        $this->assertNull($result->kd_ikut_prakerja);
    }

    public function test_sync_program_perlindungan_sosial_mengisi_nilai_saat_anggota_peserta_aktif(): void
    {
        $bantuan = BantuanFactory::new()->forSasaran(SasaranEnum::PENDUDUK)->create([
            'edate' => Carbon::now()->subMonth()->toDateString(),
        ]);

        DtksPengaturanProgram::create([
            'config_id'       => 1,
            'versi_kuisioner' => 2,
            'kode'            => '431b',
            'id_bantuan'      => $bantuan->id,
            'target_table'    => 'dtks_anggota',
            'target_field'    => 'kd_ikut_prakerja',
        ]);

        $agt = PendudukFactory::new()->create();

        BantuanPeserta::create([
            'config_id'           => 1,
            'peserta'             => $agt->nik,
            'program_id'          => $bantuan->id,
            'kartu_nik'           => $agt->nik,
            'kartu_nama'          => $agt->nama,
            'kartu_tanggal_lahir' => Carbon::now()->subYears(20)->toDateString(),
        ]);

        $dtksAnggota = DtksAnggota::create([
            'config_id'   => 1,
            'id_penduduk' => $agt->id,
        ]);

        $result = $this->service->syncProgramPerlindunganSosial($dtksAnggota, $agt, null, []);

        $this->assertSame(1, $result->kd_ikut_prakerja);
    }

    public function test_sync_kepesertaan_program_keluarga_tidak_crash_saat_program_bantuan_tidak_ditemukan(): void
    {
        $idBantuanTidakAda = 999999;

        $keluarga = KeluargaFactory::new()->create();
        PendudukFactory::new()->forKeluarga($keluarga)->kepalaKeluarga()->create();

        // Simulasikan program bantuan yang sudah dihapus: baris pengaturan & kepesertaan
        // masih menyisakan id_bantuan/program_id yang menggantung (dangling reference),
        // sehingga FK constraint dinonaktifkan sementara saat menyiapkan data uji.
        Schema::withoutForeignKeyConstraints(function () use ($idBantuanTidakAda, $keluarga): void {
            DtksPengaturanProgram::create([
                'config_id'       => 1,
                'versi_kuisioner' => 2,
                'kode'            => '501b',
                'id_bantuan'      => $idBantuanTidakAda,
                'target_table'    => 'dtks',
                'target_field'    => 'kd_pkh,bulan_pkh,tahun_pkh',
            ]);

            // program_id merujuk ke program bantuan yang sudah tidak ada (mis. sudah dihapus)
            BantuanPeserta::create([
                'config_id'           => 1,
                'peserta'             => $keluarga->no_kk,
                'program_id'          => $idBantuanTidakAda,
                'kartu_nik'           => '0',
                'kartu_nama'          => 'Peserta',
                'kartu_tanggal_lahir' => Carbon::now()->subYears(30)->toDateString(),
            ]);
        });

        $dtks = Dtks::create([
            'config_id'   => 1,
            'id_keluarga' => $keluarga->id,
        ]);

        $result = $this->service->syncKepesertaanProgramKeluarga($dtks);

        $this->assertNull($result->kd_pkh);
    }
}
