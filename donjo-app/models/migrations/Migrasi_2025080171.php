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
use App\Models\Widget;
use App\Enums\AktifEnum;
use App\Traits\Migrator;
use App\Models\ProfilDesa;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025080171
{
    use Migrator;

    public function up()
    {
        $this->updateRestrictFkNew();
        $this->tambahDataProfilDesa();
        $this->checkMailBoxClear();
        $this->urutPengaturanKehadiran();
        $this->tambahPengaturanKehadiran();
        $this->perbaikiAnalisisResponHasil();
        $this->hapusDuplikasiKeuangan();
        $this->hapusDuplikasiWidgetJamKerja();
        $this->hapusModulNavigasiSimbol();
    }

    public function updateRestrictFkNew()
    {
        $table      = 'tweb_penduduk_mandiri';
        $column     = 'config_id';
        $foreignKey = 'tweb_penduduk_mandiri_config_fk';
        $refTable   = 'config';
        $this->resetForeignKey($table, $column, $foreignKey, $refTable);
    }

    public function tambahDataProfilDesa()
    {
        ProfilDesa::where('key', 'kegiatan_adat')->delete();

        $exists = ProfilDesa::where('key', 'regulasi_penetapan_kampung_adat')->exists();

        if (! $exists) {
            ProfilDesa::create([
                'kategori' => 'adat',
                'judul'    => 'Regulasi Penetapan Kampung Adat',
                'key'      => 'regulasi_penetapan_kampung_adat',
            ]);
        }

        $exists = ProfilDesa::where('key', 'dokumen_regulasi_penetapan_kampung_adat')->exists();

        if (! $exists) {
            ProfilDesa::create([
                'kategori' => 'adat',
                'judul'    => 'Dokumen Regulasi Penetapan Kampung Adat',
                'key'      => 'dokumen_regulasi_penetapan_kampung_adat',
            ]);
        }
    }

    public function checkMailBoxClear()
    {
        Modul::where('url', 'mailbox/clear')->update(['url' => 'mailbox']);
    }

    public function urutPengaturanKehadiran()
    {
        $kategoriMap = [
            'log_penduduk'    => 'Catatan Peristiwa',
            'hubung warga'    => 'Hubung Warga',
            'conf_web'        => 'Website',
            'setting_mandiri' => 'Layanan Mandiri',
            'anjungan'        => 'Anjungan',
        ];

        collect($kategoriMap)->each(static function ($baru, $lama) {
            SettingAplikasi::where('kategori', $lama)->update(['kategori' => $baru]);
        });

        if (! Schema::hasColumn('setting_aplikasi', 'urut')) return;

        $urutan = [
            // Kehadiran
            'tampilkan_kehadiran'     => 1,
            'ip_adress_kehadiran'     => 2,
            'mac_adress_kehadiran'    => 3,
            'id_pengunjung_kehadiran' => 4,
            'latar_kehadiran'         => 5,
            'rentang_waktu_keluar'    => 6,
            'rentang_waktu_masuk'     => 7,

            // Pemerintah Desa
            'sebutan_pemerintah_desa'      => 1,
            'sebutan_pj_kepala_desa'       => 2,
            'ukuran_lebar_bagan'           => 3,
            'media_sosial_pemerintah_desa' => 4,

            // Wilayah Administratif
            'sebutan_dusun'        => 1,
            'sebutan_kepala_dusun' => 2,

            // Peta
            'jenis_peta'                        => 1,
            'mapbox_key'                        => 2,
            'tampil_luas_peta'                  => 3,
            'min_zoom_peta'                     => 4,
            'max_zoom_peta'                     => 5,
            'tampilkan_tombol_peta'             => 6,
            'default_tampil_peta_wilayah'       => 7,
            'default_tampil_peta_infrastruktur' => 8,
            'tampilkan_cdesa_petaweb'           => 9,

            // Pengaduan
            'jumlah_aduan_pengguna' => 1,

            // Pembangunan
            'icon_pembangunan_peta' => 1,

            // Lapak
            'tampilkan_lapak_web'      => 1,
            'icon_lapak_peta'          => 2,
            'pesan_singkat_wa'         => 3,
            'jumlah_produk_perhalaman' => 4,
            'banyak_foto_tiap_produk'  => 5,
            'jumlah_pengajuan_produk'  => 6,

            // DTKS
            'sebutan_dtks'             => 1,
            'sebutan_lengkap_regsosek' => 2,
            'sebutan_singkat_regsosek' => 3,

            // Catatan Peristiwa
            'surat_kelahiran_terkait_penduduk'     => 1,
            'surat_kematian_terkait_penduduk'      => 2,
            'surat_pindah_keluar_terkait_penduduk' => 3,
            'surat_hilang_terkait_penduduk'        => 4,
            'surat_pindah_masuk_terkait_penduduk'  => 5,
            'surat_pergi_terkait_penduduk'         => 6,

            // Analisis
            'api_gform_id_script'    => 1,
            'api_gform_credential'   => 2,
            'api_gform_redirect_uri' => 3,

            // Hubung Warga
            'aktifkan_sms'                => 1,
            'hubung_warga_balas_otomatis' => 2,

            // Web
            'artikel_statis'          => 1,
            'link_feed'               => 2,
            'apbdes_tahun'            => 3,
            'apbdes_footer'           => 4,
            'apbdes_footer_all'       => 5,
            'covid_desa'              => 6,
            'covid_rss'               => 7,
            'daftar_penerima_bantuan' => 8,
            'statistik_chart_3d'      => 9,

            // Layanan Mandiri
            'layanan_mandiri'       => 1,
            'tampilkan_pendaftaran' => 2,

            // Buku Tamu
            'buku_tamu_kamera' => 1,
        ];

        SettingAplikasi::whereIn('key', array_keys($urutan))
            ->get(['id', 'key'])
            ->each(static function ($item) use ($urutan) {
                $item->urut = $urutan[$item->key];
                $item->save();
            });

        (new SettingAplikasi())->flushQueryCache();
    }

    public function tambahPengaturanKehadiran()
    {
        $this->createSetting([
            'judul'      => 'Tampilkan Status Kehadiran Pada Hari Libur',
            'key'        => 'tampilkan_status_kehadiran_pada_hari_libur',
            'value'      => AktifEnum::AKTIF,
            'urut'       => 2,
            'keterangan' => 'Jika diaktifkan, status kehadiran perangkat desa akan tetap muncul di hari libur.',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'Kehadiran',
            'attribute'  => json_encode([
                'class' => 'required',
            ]),
        ]);
    }

    public function perbaikiAnalisisResponHasil()
    {
        $this->hapusForeignKey('analisis_respon_hasil_subjek_fk', 'analisis_respon_hasil', 'analisis_parameter');

        DB::table('analisis_periode')
            ->join('analisis_respon', 'analisis_periode.id', '=', 'analisis_respon.id_periode')
            ->join('analisis_respon_hasil', 'analisis_periode.id', '=', 'analisis_respon_hasil.id_periode')
            ->selectRaw('DISTINCT(analisis_respon.id_subjek) AS new_id_subjek, analisis_respon.config_id, analisis_respon.id_periode')
            ->whereNotNull('analisis_respon.id_subjek')
            ->whereNull('analisis_respon_hasil.id_subjek')
            ->get()
            ->each(static function ($item) {
                DB::table('analisis_respon_hasil')
                    ->whereNull('id_subjek')
                    ->where('config_id', $item->config_id)
                    ->where('id_periode', $item->id_periode)
                    ->limit(1)
                    ->update(['id_subjek' => $item->new_id_subjek]);
            });

        DB::table('analisis_respon_hasil')
            ->whereNull('id_subjek')
            ->delete();
    }

    public function hapusDuplikasiKeuangan()
    {
        DB::statement('
            DELETE FROM keuangan
            WHERE id IN (
                SELECT id FROM (
                    SELECT k1.id
                    FROM keuangan k1
                    JOIN keuangan k2
                    ON k1.template_uuid = k2.template_uuid
                    AND k1.tahun = k2.tahun
                    AND k1.id > k2.id
                ) AS subquery
            )
        ');
    }

    public function hapusDuplikasiWidgetJamKerja()
    {
        Widget::where('isi', 'jam_kerja')
            ->get()
            ->groupBy('judul')
            ->each(function ($group) {
                $group->shift();
                $group->each->delete();
            });
    }

    public function hapusModulNavigasiSimbol()
    {
        DB::table('setting_modul')
            ->where('slug', 'simbol')
            ->delete();
    }
}
