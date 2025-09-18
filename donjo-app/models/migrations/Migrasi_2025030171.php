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
use App\Enums\SumberDanaEnum;
use App\Models\Config;
use App\Models\Dokumen;
use App\Models\Modul;
use App\Models\Pembangunan;
use App\Models\PembangunanDokumentasi;
use App\Models\SettingAplikasi;
use App\Models\Widget;
use App\Scopes\ConfigIdScope;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025030171
{
    use Migrator;

    public function up()
    {
        $this->tambahKolomSumberPadaTabelPoint();
        $this->updateSumberDanaPembangunana();
        $this->updateProgramTable();
        $this->updateIdPendDokumen();
        $this->ubahLinkWidgetKeuangan();
        $this->tambahPengaturanAPBD();
        $this->perbaikiUrlQrcodeSurat();
        $this->ubahWidgetIsi();
        $this->updateKategoriTable();
        $this->ubahPengaturanFormatTanggalSurat();
        $this->hapusForeignKeyTidakDigunakan();
        $this->ubahOpsiCascade();
        $this->hapusAksesInventarisApi();
        $this->ubahNamaInventaris();
        $this->ubahStatusWidget();
        $this->tambahKodeDesaBps();
        $this->hapusWidgetDinamis();
        $this->bersihkanTablePembangunanDokumentasi();
        $this->tambahPengaturanSSL();
        $this->updateKeteranganRecaptcha();
    }

    protected function tambahKolomSumberPadaTabelPoint()
    {
        if (! Schema::hasColumn('point', 'sumber')) {
            Schema::table('point', static function (Blueprint $table) {
                $table->enum('sumber', ['OpenSID', 'OpenKab'])->default('OpenSID');
            });
        }
    }

    public function updateSumberDanaPembangunana()
    {
        $enumValues = SumberDanaEnum::all();

        $mapping = [
            'Pendapatan Asli Daerah'                                        => $enumValues[SumberDanaEnum::PAD],
            'Alokasi Anggaran Pendapatan dan Belanja Negara (Dana Desa)'    => $enumValues[SumberDanaEnum::DANA_DESA],
            'Bagian Hasil Pajak Daerah dan Retribusi Daerah Kabupaten/Kota' => $enumValues[SumberDanaEnum::PAJAK_DAERAH],
            'Alokasi Dana Desa'                                             => $enumValues[SumberDanaEnum::ALOKASI_DANA_DESA],
            'Bantuan Keuangan dari APBD Provinsi dan APBD Kabupaten/Kota'   => $enumValues[SumberDanaEnum::BANTUAN_PROVINSI],
            'Hibah dan Sumbangan yang Tidak Mengikat dari Pihak Ketiga'     => $enumValues[SumberDanaEnum::BANTUAN_KAB_KOTA],
            'Lain-lain Pendapatan Desa yang Sah'                            => $enumValues[SumberDanaEnum::PENDAPATAN_LAIN],
        ];

        foreach ($mapping as $oldValue => $newValue) {
            Pembangunan::withoutGlobalScope(ConfigIdScope::class)
                ->where('sumber_dana', $oldValue)
                ->update(['sumber_dana' => $newValue]);
        }
    }

    public function updateProgramTable()
    {
        DB::table('program')->whereNull('sasaran')->update(['sasaran' => 0]);

        Schema::table('program', static function (Blueprint $table) {
            $table->integer('sasaran')->nullable(false)->change();
        });
    }

    public function updateIdPendDokumen()
    {
        Dokumen::where('id_pend', 0)->update(['id_pend' => null]);
    }

    public function ubahLinkWidgetKeuangan()
    {
        Widget::where('isi', 'keuangan.php')->update(['form_admin' => 'keuangan_manual']);
    }

    public function tambahPengaturanAPBD()
    {
        $this->createSetting([
            'key'        => 'apbdes_tahun',
            'judul'      => 'Tahun APBDes',
            'keterangan' => 'Tahun APBDes yang akan ditampilkan dihalaman depan',
            'value'      => null,
            'jenis'      => 'text',
            'kategori'   => 'conf_web',
        ]);

        SettingAplikasi::where('key', 'apbdes_manual_input')->delete();
    }

    public function perbaikiUrlQrcodeSurat()
    {
        DB::table('urls')
            ->leftJoin('log_surat', 'log_surat.urls_id', '=', 'urls.id')
            ->whereNotNull('log_surat.urls_id')
            ->where('urls.url', 'REGEXP', '/c1/$')
            ->where('log_surat.config_id', identitas('id'))
            ->update([
                'urls.url' => DB::raw('CONCAT(urls.url, log_surat.id)'),
            ]);
    }

    public function ubahWidgetIsi()
    {
        DB::table('widget')
            ->where('config_id', identitas('id'))
            ->whereRaw('isi REGEXP "\\.blade\\.php$|\\.php$"')
            ->update([
                'isi' => DB::raw('REGEXP_REPLACE(SUBSTRING_INDEX(isi, "/", -1), "\\.blade\\.php$|\\.php$", "")'),
            ]);
    }

    public function updateKategoriTable()
    {
        Schema::table('kategori', static function (Blueprint $table) {
            $table->integer('parrent')->change();
        });
    }

    public function ubahPengaturanFormatTanggalSurat()
    {
        $this->createSetting([
            'judul'      => 'Format Tanggal Surat',
            'key'        => 'format_tanggal_surat',
            'value'      => 'd F Y',
            'keterangan' => 'Format tanggal pada kode isian surat.',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => json_encode([
                'class'       => 'format_tanggal required',
                'placeholder' => 'd F Y',
                'type'        => 'text',
            ]),
            'kategori' => 'format_surat',
        ]);

        $this->createSetting([
            'judul'      => 'Format Tanggal Surat',
            'key'        => 'format_tanggal_surat_dinas',
            'value'      => 'd F Y',
            'keterangan' => 'Format tanggal pada kode isian surat.',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => json_encode([
                'class'       => 'format_tanggal required',
                'placeholder' => 'd F Y',
                'type'        => 'text',
            ]),
            'kategori' => 'format_surat',
        ]);
    }

    public function hapusForeignKeyTidakDigunakan()
    {
        $this->hapusForeignKey('suplemen_terdata_suplemen_1', 'suplemen_terdata', 'suplemen');
        $this->hapusForeignKey('analisis_respon_subjek_fk', 'analisis_respon', 'analisis_parameter');
    }

    public function ubahOpsiCascade()
    {
        Schema::table('mutasi_inventaris_peralatan', static function (Blueprint $table) {
            $table->dropForeign('FK_mutasi_inventaris_peralatan');
            $table->foreign('id_inventaris_peralatan', 'FK_mutasi_inventaris_peralatan')->references('id')->on('inventaris_peralatan')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('mutasi_inventaris_jalan', static function (Blueprint $table) {
            $table->dropForeign('FK_mutasi_inventaris_jalan');
            $table->foreign('id_inventaris_jalan', 'FK_mutasi_inventaris_jalan')->references('id')->on('inventaris_jalan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function hapusAksesInventarisApi()
    {
        Modul::where('modul', 'like', '%api_inventaris%')->delete();
    }

    public function ubahNamaInventaris()
    {
        $moduls = Modul::where('modul', 'like', '%inventaris_%')->get();

        foreach ($moduls as $modul) {
            $modul->modul = ucwords(str_replace('_', ' ', $modul->modul));
            $modul->save();
        }

        $laporan = Modul::where('modul', 'laporan_inventaris')->first();
        if ($laporan) {
            $laporan->update(['modul' => 'Laporan Inventaris']);
        }

        Modul::where('slug', 'inventaris')->update(['url' => 'inventaris_master']);

        $this->createModul([
            'modul'       => 'Inventaris Tanah',
            'slug'        => 'inventaris-tanah',
            'url'         => 'inventaris_tanah',
            'ikon'        => '',
            'level'       => 0,
            'hidden'      => 2,
            'parent_slug' => 'sekretariat',
        ]);
    }

    public function ubahStatusWidget()
    {
        DB::table('widget')
            ->whereNotIn('enabled', AktifEnum::keys())
            ->update(['enabled' => AktifEnum::TIDAK_AKTIF]);
    }

    private function tambahKodeDesaBps()
    {
        if (! Schema::hasColumn('config', 'kode_desa_bps')) {
            Schema::table('config', static function (Blueprint $table) {
                $table->string('kode_desa_bps', 10)->nullable()->after('kode_desa');
            });
        }

        // update dengan nilai dari pengaturan
        $kodeDesaBps = SettingAplikasi::where('key', 'kode_desa_bps')->first();
        if ($kodeDesaBps) {
            Config::appKey()->update(['kode_desa_bps' => $kodeDesaBps->value]);
            $kodeDesaBps->delete();
        }
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

    private function tambahPengaturanSSL()
    {
        $this->createSetting([
            'judul'      => 'SSL TTE',
            'key'        => 'ssl_tte',
            'value'      => '1',
            'keterangan' => 'SSL TTE',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'tte',
        ]);
    }

    protected function updateKeteranganRecaptcha()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'google_recaptcha')
            ->where('keterangan', '!=', 'Gunakan Aktif untuk Google reCAPTCHA atau Tidak untuk reCAPTCHA bawaan sistem.')
            ->update(['keterangan' => 'Gunakan Aktif untuk Google reCAPTCHA atau Tidak untuk reCAPTCHA bawaan sistem.']);
    }
}
