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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\AktifEnum;
use App\Models\Keuangan;
use App\Traits\Migrator;
use App\Enums\StatusEnum;
use App\Models\KeuanganTemplate;
use Illuminate\Support\Facades\DB;
use App\Models\Modul as ModulModel;
use App\Models\PembangunanDokumentasi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Repositories\SettingAplikasiRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025031551
{
    use Migrator;

    public function up()
    {
        $this->hapusWidgetDinamis();
        $this->bersihkanTablePembangunanDokumentasi();
        $this->ubahUrlSlider();
        $this->hapusDanUbahConfigIdMenjadiWajib();
        $this->ubahNilaiKolomAktifModul();
        $this->ubahDefaultSlider();
        $this->sesuaikanStatusMediaSosial();
        $this->sesuaikanKbbi();
        $this->updateMaxZoomPeta();
        $this->ubahKeuanganTemplate();

        (new SettingAplikasiRepository())->flushCache();
    }

    public function hapusWidgetDinamis()
    {
        DB::table('widget')
            ->where('jenis_widget', 3)
            ->update(['enabled' => AktifEnum::TIDAK_AKTIF]);
    }

    protected function bersihkanTablePembangunanDokumentasi()
    {
        PembangunanDokumentasi::whereDoesntHave('pembangunan')->delete();
    }

    protected function ubahUrlSlider()
    {
        ModulModel::whereUrl('web/slider')->update([
            'url' => 'slider',
        ]);
    }

    protected function hapusDanUbahConfigIdMenjadiWajib(): void
    {
        // Daftar tabel untuk kebutuhan OpenKAB
        $tabelTerkecuali = ['kategori', 'program', 'suplemen', 'point'];

        // Ambil semua tabel di database aktif yang memiliki kolom config_id masih bisa NULL
        $tabels = DB::table('INFORMATION_SCHEMA.COLUMNS')
            ->select('TABLE_NAME')
            ->where('COLUMN_NAME', 'config_id')
            ->where('IS_NULLABLE', 'YES')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->whereNotIn('TABLE_NAME', static function ($query) {
                $query->select('TABLE_NAME')
                    ->from('INFORMATION_SCHEMA.VIEWS');
            })
            ->whereNotIn('TABLE_NAME', $tabelTerkecuali)
            ->pluck('TABLE_NAME');

        foreach ($tabels as $tabel) {
            // Hapus semua data yang config_id nya NULL
            DB::table($tabel)->whereNull('config_id')->delete();

            // Ubah config_id menjadi NOT NULL
            Schema::table($tabel, static function (Blueprint $table) {
                $table->integer('config_id')->nullable(false)->change();
            });
        }
    }

    protected function ubahNilaiKolomAktifModul()
    {
        ModulModel::where('aktif', 2)->update(['aktif' => StatusEnum::TIDAK]);
    }

    protected function ubahDefaultSlider()
    {
        $settings = new SettingAplikasiRepository();
        if ($settings->firstByKey('sumber_gambar_slider')->value == 3) {
            $settings->updateWithKey('sumber_gambar_slider', 1);
        }
    }

    public function sesuaikanStatusMediaSosial()
    {
        DB::table('media_sosial')
            ->whereNotIn('enabled', AktifEnum::keys())
            ->update(['enabled' => AktifEnum::TIDAK_AKTIF]);
    }

    public function ubahKeuanganTemplate()
    {
        $data = [
            ['uuid' => '5', 'uraian' => 'Belanja', 'parent_uuid' => null],
            ['uuid' => '5.1', 'uraian' => 'BIDANG PENYELENGGARAN PEMERINTAHAN DESA', 'parent_uuid' => 5],
            ['uuid' => '5.1.1', 'uraian' => 'Penyelenggaran Belanja Siltap, Tunjangan dan Operasional Pemerintah Desa', 'parent_uuid' => '5.1'],
            ['uuid' => '5.1.2', 'uraian' => 'Sarana dan Prasaran Pemerintah Desa', 'parent_uuid' => '5.1'],
            ['uuid' => '5.1.3', 'uraian' => 'Administrasi Kependudukan, Pencatatan Sipil, Statistik dan Kearsipan', 'parent_uuid' => '5.1'],
            ['uuid' => '5.1.4', 'uraian' => 'Tata Praja Pemerintahan, Perencanaan, Keuangan', 'parent_uuid' => '5.1'],
            ['uuid' => '5.1.5', 'uraian' => 'Sub Bidang Pertanahan', 'parent_uuid' => '5.1'],
            ['uuid' => '5.2', 'uraian' => 'BIDANG PELAKSANAAN PEMBANGUNAN DESA', 'parent_uuid' => 5],
            ['uuid' => '5.2.1', 'uraian' => 'Sub Bidang Pendidikan', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.2', 'uraian' => 'Sub Bidang Kesehatan', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.3', 'uraian' => 'Sub Bidang Pekerjaan Umum dan Penataan Ruang', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.4', 'uraian' => 'Sub Bidang Kawasan Pemukiman', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.5', 'uraian' => 'Sub Bidang Kehutanan dan Lingkungan Hidup', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.6', 'uraian' => 'Sub Bidang Perhubungan, Komunikasi dan Informatika', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.7', 'uraian' => 'Sub Bidang Energi dan Sumber Daya Mineral', 'parent_uuid' => '5.2'],
            ['uuid' => '5.2.8', 'uraian' => 'Sub Bidang Pariwisata', 'parent_uuid' => '5.2'],
            ['uuid' => '5.3', 'uraian' => 'BIDANG PEMBINAAN KEMASYARAKATAN', 'parent_uuid' => 5],
            ['uuid' => '5.3.1', 'uraian' => 'Ketenteraman, Ketertiban Umum, dan Perlindungan Masyarakat', 'parent_uuid' => '5.3'],
            ['uuid' => '5.3.2', 'uraian' => 'Kebudayaan dan Keagamaan', 'parent_uuid' => '5.3'],
            ['uuid' => '5.3.3', 'uraian' => 'Kepemudaan dan Olah Raga', 'parent_uuid' => '5.3'],
            ['uuid' => '5.3.4', 'uraian' => 'Kelembagaan Masyarakat', 'parent_uuid' => '5.3'],
            ['uuid' => '5.4', 'uraian' => 'BIDANG PEMBERDAYAAN MASYARAKAT', 'parent_uuid' => 5],
            ['uuid' => '5.4.1', 'uraian' => 'Sub Bidang Kelautan dan Perikanan', 'parent_uuid' => '5.4'],
            ['uuid' => '5.4.2', 'uraian' => 'Sub Bidang Pertanian dan Peternakan', 'parent_uuid' => '5.4'],
            ['uuid' => '5.4.3', 'uraian' => 'Sub Bidang Peningkatan Kapasita Aparatur Desa', 'parent_uuid' => '5.4'],
            ['uuid' => '5.4.4', 'uraian' => 'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga', 'parent_uuid' => '5.4'],
            ['uuid' => '5.4.5', 'uraian' => 'Koperasi, Usaha Mikro Kecil dan Menegah (UMKM)', 'parent_uuid' => '5.4'],
            ['uuid' => '5.4.6', 'uraian' => 'Dukungan Penanaman Modal', 'parent_uuid' => '5.4'],
            ['uuid' => '5.4.7', 'uraian' => 'Perdagangan dan Perindustrian', 'parent_uuid' => '5.4'],
            ['uuid' => '5.5', 'uraian' => 'PENAGGULANGAN BENCANA, KEADAAN DARURAT DAN MENDESAK', 'parent_uuid' => 5],
            ['uuid' => '5.5.1', 'uraian' => 'Penanggulangan Bencana', 'parent_uuid' => '5.5'],
            ['uuid' => '5.5.2', 'uraian' => 'Keadaan Darurat', 'parent_uuid' => '5.5'],
            ['uuid' => '5.5.3', 'uraian' => 'Mendesak', 'parent_uuid' => '5.5'],
        ];

        // Ambil daftar tahun dari Keuangan
        $tahun = Keuangan::pluck('tahun')->unique()->toArray();
        $created_by = super_admin();
        $config_id  = identitas('id');

        foreach ($data as $item) {
            KeuanganTemplate::upsert(
                $item + ['created_by' => $created_by, 'updated_by' => $created_by], 
                ['uuid'], 
                ['uraian', 'parent_uuid']
            );

            if (!$tahun) continue;

            foreach ($tahun as $thn) {
                Keuangan::withoutGlobalScopes()->updateOrCreate(
                    compact('config_id') + ['template_uuid' => $item['uuid'], 'tahun' => $thn],
                    ['anggaran' => 0, 'realisasi' => 0, 'updated_by' => $created_by]
                );
            }
        }
    }

    protected function sesuaikanKbbi()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_pendaftaran')
            ->update(['keterangan' => 'Aktifkan / Nonaktifkan Pendaftaran Layanan Mandiri']);
    }

    protected function updateMaxZoomPeta()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'max_zoom_peta')
            ->whereRaw('CAST(value AS UNSIGNED) > 30')
            ->update(['value' => '30']);
    }
}
