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

use App\Models\Modul;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025061851
{
    use Migrator;

    public function up()
    {
        $this->updateUrlArsipSuratDinas();
        $this->hapusTabelKeuangan();
        $this->updatePengaturanSurat();
        $this->updateRestrictFkNew();
        $this->addIsPublikField();
    }

    public function updateUrlArsipSuratDinas()
    {
        Modul::where('slug', 'arsip-surat-dinas')
            ->orWhere('url', 'arsip_surat_dinas')
            ->update(['slug' => 'arsip-surat-dinas', 'url' => 'surat_dinas_arsip']);
    }

    public function hapusTabelKeuangan()
    {
        $skipIfHasData = [
            'keuangan_manual_rinci',
            'keuangan_ta_rab_rinci',
        ];

        $tables = [
            'keuangan_manual_ref_bidang',
            'keuangan_manual_ref_kegiatan',
            'keuangan_manual_rinci',
            'keuangan_master',
            'keuangan_ref_bank_desa',
            'keuangan_ref_bel_operasional',
            'keuangan_ref_bidang',
            'keuangan_ref_bunga',
            'keuangan_ref_desa',
            'keuangan_ref_kecamatan',
            'keuangan_ref_kegiatan',
            'keuangan_ref_korolari',
            'keuangan_ref_neraca_close',
            'keuangan_ref_perangkat',
            'keuangan_ref_potongan',
            'keuangan_ref_rek1',
            'keuangan_ref_rek2',
            'keuangan_ref_rek3',
            'keuangan_ref_rek4',
            'keuangan_ref_sbu',
            'keuangan_ref_sumber',
            'keuangan_ta_anggaran',
            'keuangan_ta_anggaran_log',
            'keuangan_ta_anggaran_rinci',
            'keuangan_ta_bidang',
            'keuangan_ta_desa',
            'keuangan_ta_jurnal_umum',
            'keuangan_ta_jurnal_umum_rinci',
            'keuangan_ta_kegiatan',
            'keuangan_ta_mutasi',
            'keuangan_ta_pajak',
            'keuangan_ta_pajak_rinci',
            'keuangan_ta_pemda',
            'keuangan_ta_pencairan',
            'keuangan_ta_perangkat',
            'keuangan_ta_rab',
            'keuangan_ta_rab_rinci',
            'keuangan_manual_rinci_tpl',
            'keuangan_ta_rab_sub',
            'keuangan_ta_rpjm_bidang',
            'keuangan_ta_rpjm_kegiatan',
            'keuangan_ta_rpjm_misi',
            'keuangan_ta_rpjm_pagu_indikatif',
            'keuangan_ta_rpjm_pagu_tahunan',
            'keuangan_ta_rpjm_sasaran',
            'keuangan_ta_rpjm_tujuan',
            'keuangan_ta_rpjm_visi',
            'keuangan_ta_saldo_awal',
            'keuangan_ta_spj',
            'keuangan_ta_spj_bukti',
            'keuangan_ta_spj_rinci',
            'keuangan_ta_spj_sisa',
            'keuangan_ta_spjpot',
            'keuangan_ta_spp',
            'keuangan_ta_spp_rinci',
            'keuangan_ta_sppbukti',
            'keuangan_ta_spppot',
            'keuangan_ta_sts',
            'keuangan_ta_sts_rinci',
            'keuangan_ta_tbp',
            'keuangan_ta_tbp_rinci',
            'keuangan_ta_triwulan',
            'keuangan_ta_triwulan_rinci',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $canDeleteManualRinciTpl = true;

        foreach ($tables as $table) {
            if (in_array($table, $skipIfHasData) && Schema::hasTable($table) && DB::table($table)->exists()) {
                $canDeleteManualRinciTpl = false;

                continue;
            }

            if ($table === 'keuangan_manual_rinci_tpl' && ! $canDeleteManualRinciTpl) {
                continue;
            }

            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function updatePengaturanSurat()
    {
        SettingAplikasi::withoutGlobalScopes()
            ->where('key', 'penomoran_surat')
            ->whereIn('key', [
                'penomoran_surat',
                'panjang_nomor_surat',
                'format_nomor_surat',
            ])
            ->update(['kategori' => 'format_surat']);
    }

    public function updateRestrictFkNew()
    {
        $foreignKeys = [
            [
                'table'      => 'anggota_grup_kontak',
                'column'     => 'id_penduduk',
                'foreignKey' => 'anggota_grup_kontak_id_penduduk_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'covid19_pemudik',
                'column'     => 'id_terdata',
                'foreignKey' => 'fk_pemudik_penduduk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'dtks_anggota',
                'column'     => 'id_penduduk',
                'foreignKey' => 'FK_pend_dtks_anggota',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'kader_pemberdayaan_masyarakat',
                'column'     => 'penduduk_id',
                'foreignKey' => 'kader_pemberdayaan_masyarakat_penduduk_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'kehadiran_pengaduan',
                'column'     => 'id_penduduk',
                'foreignKey' => 'kehadiran_pengaduan_penduduk_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'kelompok',
                'column'     => 'id_ketua',
                'foreignKey' => 'kelompok_ketua_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'kelompok_anggota',
                'column'     => 'id_penduduk',
                'foreignKey' => 'kelompok_anggota_penduduk_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'kia',
                'column'     => 'anak_id',
                'foreignKey' => 'kia_anak_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'kia',
                'column'     => 'ibu_id',
                'foreignKey' => 'kia_ibu_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'log_hapus_penduduk',
                'column'     => 'id_pend',
                'foreignKey' => 'log_hapus_penduduk_pend_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'log_keluarga',
                'column'     => 'id_pend',
                'foreignKey' => 'log_keluarga_pend_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'log_perubahan_penduduk',
                'column'     => 'id_pend',
                'foreignKey' => 'log_perubahan_penduduk_pend_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'permohonan_surat',
                'column'     => 'id_pemohon',
                'foreignKey' => 'permohonan_surat_pemohon_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'pesan_mandiri',
                'column'     => 'penduduk_id',
                'foreignKey' => 'pesan_mandiri_penduduk_id_foreign',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'keuangan',
                'column'     => 'template_uuid',
                'foreignKey' => 'keuangan_template_uuid_foreign',
                'refTable'   => 'keuangan_template',
                'refColumn'  => 'uuid',
            ],
            [
                'table'      => 'tanah_desa',
                'column'     => 'id_penduduk',
                'foreignKey' => 'tanah_desa_penduduk_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'tweb_penduduk_mandiri',
                'column'     => 'id_pend',
                'foreignKey' => 'tweb_penduduk_mandiri_penduduk_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'tweb_penduduk_map',
                'column'     => 'id',
                'foreignKey' => 'tweb_penduduk_map_pend_fk',
                'refTable'   => 'tweb_penduduk',
            ],
            [
                'table'      => 'tweb_rtm',
                'column'     => 'nik_kepala',
                'foreignKey' => 'tweb_rtm_kepala_fk',
                'refTable'   => 'tweb_penduduk',
            ],
        ];

        foreach ($foreignKeys as $fk) {
            $table      = $fk['table'];
            $column     = $fk['column'];
            $foreignKey = $fk['foreignKey'];
            $refTable   = $fk['refTable'];
            $refColumn  = $fk['refColumn'] ?? 'id';
            $this->resetForeignKey($table, $column, $foreignKey, $refTable, $refColumn);
        }

        $table            = 'tweb_wil_clusterdesa';
        $column           = 'id_kepala';
        $foreignKey       = 'tweb_wil_clusterdesa_kepala_fk';
        $referencesTable  = 'tweb_penduduk';
        $referencesColumn = 'id';

        if ($this->foreignKeyExists($table, $foreignKey)) {
            Schema::table($table, static function (Blueprint $table) use ($column, $foreignKey, $referencesTable, $referencesColumn) {
                $table->dropForeign($foreignKey);

                $table->foreign($column, $foreignKey)
                    ->references($referencesColumn)->on($referencesTable)->onUpdate('cascade')->onDelete('set null');
            });
        }
    }

    protected function addIsPublikField()
    {
        if (! Schema::hasColumn('persil', 'is_publik')) {
            Schema::table('persil', static function (Blueprint $table) {
                $table->tinyInteger('is_publik')->default(1)->comment('1 = tampilkan di web publik, 0 = tidak ditampilkan di web publik');
            });
        }
    }
}
