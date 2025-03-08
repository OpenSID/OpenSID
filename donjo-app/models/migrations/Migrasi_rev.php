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
use App\Enums\StatusEnum;
use App\Models\Modul as ModulModel;
use App\Models\PembangunanDokumentasi;
use App\Repositories\SettingAplikasiRepository;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
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
