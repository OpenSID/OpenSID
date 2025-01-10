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

use App\Imports\SuratDinasImports;
use App\Models\SettingAplikasi;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024080171 extends MY_Model
{
    public function up()
    {
        $this->migrasi_2024071051();
        $this->migrasi_2024122271();
        $this->migrasi_2024072751();
        $this->migrasi_2024072752();
        $this->migrasi_2024072753();
        $this->migrasi_2024072754();
        $this->migrasi_2024072755();
        $this->migrasi_2024072756();
        $this->migrasi_2024072651();
        $this->migrasi_2024040271();
        $this->migrasi_2024042171();
        $this->migrasi_2024072951();
        $this->migrasi_2024051253();
        $this->migrasi_2024073071();
    }

    protected function migrasi_2024051253()
    {
        $rws = DB::table('tweb_wil_clusterdesa')
            ->where('config_id', identitas('id'))
            ->whereNull('id_kepala')
            ->whereNotIn('rw', ['0', '-'])
            ->where('rt', '-')
            ->get();

        foreach ($rws as $value) {
            $id_kepala = DB::table('tweb_wil_clusterdesa')
                ->where('config_id', identitas('id'))
                ->where('dusun', $value->dusun)
                ->where('rw', $value->rw)
                ->where('rt', '0')
                ->value('id_kepala');

            DB::table('tweb_wil_clusterdesa')
                ->where('id', $value->id)
                ->update(['id_kepala' => $id_kepala]);
        }
    }

    protected function migrasi_2024071051()
    {
        Schema::table('artikel', static function ($table) {
            $table->longText('isi')->change();
        });
    }

    protected function migrasi_2024072651()
    {
        DB::table('gambar_gallery')
            ->where('parrent', 0)
            ->where('tipe', 0)
            ->update(['tipe' => 1]);
    }

    protected function migrasi_2024072751()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_lapak_web')
            ->update([
                'jenis'     => 'select-boolean',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'icon_lapak_peta')
            ->update([
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'jumlah_pengajuan_produk')
            ->update([
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'jumlah_produk_perhalaman')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'banyak_foto_tiap_produk')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 5,
                    'step'  => 1,
                ]),
            ]);
    }

    protected function migrasi_2024072752()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'ukuran_lebar_bagan')
            ->update([
                'jenis'     => 'select-array',
                'attribute' => [
                    'class' => 'required',
                ],
                'option' => json_encode([
                    '800'  => '800',
                    '1200' => '1200',
                    '1400' => '1400',
                ]),
                'keterangan' => 'Ukuran Lebar Bagan',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'media_sosial_pemerintah_desa')
            ->update([
                'jenis'     => 'select-multiple-array',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'sebutan_pemerintah_desa')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);
    }

    protected function migrasi_2024072753()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'jumlah_gambar_galeri')
            ->update([
                'attribute' => [
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'urutan_gambar_galeri')
            ->update([
                'jenis'     => 'select-array',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);
    }

    protected function migrasi_2024072754()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_kehadiran')
            ->update([
                'jenis'     => 'select-boolean',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'ip_adress_kehadiran')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class'       => 'ip_address',
                    'placeholder' => '127.0.0.1',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'mac_adress_kehadiran')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class'       => 'mac_address',
                    'placeholder' => '00:1B:44:11:3A:B7',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'id_pengunjung_kehadiran')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class'       => 'alfanumerik',
                    'placeholder' => 'ad02c373c2a8745d108aff863712fe92',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'rentang_waktu_kehadiran')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 0,
                    'max'         => 3600,
                    'step'        => 1,
                    'placeholder' => '10',
                ],
            ]);
    }

    protected function migrasi_2024072755()
    {
        DB::table('setting_aplikasi')
            ->where('key', 'rentang_waktu_notifikasi_rilis')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 0,
                    'max'         => 365,
                    'step'        => 1,
                    'placeholder' => '7',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'kode_desa_bps')
            ->update([
                'jenis'     => 'input-text',
                'kategori'  => 'Status SDGs',
                'attribute' => [
                    'class'       => 'required',
                    'max-length'  => 15,
                    'placeholder' => '3312110003',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tgl_data_lengkap_aktif')
            ->update([
                'jenis'     => 'select-boolean',
                'kategori'  => 'Data Lengkap',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'pembangunan')
            ->update([
                'kategori' => 'Pembangunan',
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'beranda')
            ->update([
                'kategori' => 'Beranda',
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'lapak')
            ->update([
                'kategori' => 'Lapak',
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'kehadiran')
            ->update([
                'kategori' => 'Kehadiran',
            ]);
    }

    protected function migrasi_2024072756()
    {
        DB::table('setting_aplikasi')
            ->where('kategori', 'peta')
            ->update([
                'kategori' => 'Peta',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'mapbox_key')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'mapbox_key')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'min_zoom_peta')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 1,
                    'max'         => 50,
                    'step'        => 1,
                    'placeholder' => '1',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'max_zoom_peta')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 1,
                    'max'         => 30,
                    'step'        => 1,
                    'placeholder' => '30',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_tombol_peta')
            ->update([
                'jenis'     => 'select-multiple-array',
                'attribute' => null,
                'option'    => json_encode([
                    [
                        'id'   => 'Statistik Penduduk',
                        'nama' => 'Statistik Penduduk',
                    ],
                    [
                        'id'   => 'Statistik Bantuan',
                        'nama' => 'Statistik Bantuan',
                    ],
                    [
                        'id'   => 'Aparatur Desa',
                        'nama' => 'Aparatur Desa',
                    ],
                    [
                        'id'   => 'Kepala Wilayah',
                        'nama' => 'Kepala Wilayah',
                    ],
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tampil_luas_peta')
            ->update([
                'jenis'     => 'select-boolean',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'jenis_peta')
            ->update([
                'jenis'     => 'select-array',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->whereIn('key', ['default_tampil_peta_wilayah', 'default_tampil_peta_infrastruktur'])
            ->update([
                'jenis'     => 'select-multiple-array',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('jenis', 'select-simbol')
            ->update([
                'attribute' => [
                    'class' => 'required',
                ],
            ]);
    }

    protected function migrasi_2024122271()
    {
        if (! Schema::hasTable('log_perubahan_surat')) {
            Schema::create('log_perubahan_surat', static function (Blueprint $table) {
                $table->id();
                $table->integer('config_id')->nullable();
                $table->integer('log_surat_id')->nullable();
                $table->text('keterangan')->nullable();
                $table->timesWithUserstamps();
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (! Schema::hasColumn('log_surat', 'lock')) {
            Schema::table('log_surat', static function (Blueprint $table) {
                $table->integer('lock')->nullable();
            });

            DB::table('log_surat')->update(['lock' => 1]);
        }
    }

    protected function migrasi_2024073071()
    {
        (new SuratDinasImports(null, ['config_id' => identitas('id'), 'url_surat' => 'surat-pernyataan']))->import();
    }

    protected function migrasi_2024040271()
    {
        $penduduk_luar = SettingAplikasi::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'form_penduduk_luar')->first();
        if ($penduduk_luar) {
            $value             = json_decode($penduduk_luar->value, true);
            $value[3]['input'] = 'nama,no_ktp,tempat_lahir,tanggal_lahir,jenis_kelamin,agama,pendidikan_kk,pekerjaan,warga_negara,alamat,golongan_darah,status_perkawinan,tanggal_perkawinan,shdk,no_paspor,no_kitas,nama_ayah,nama_ibu,no_kk,kepala_kk';
            $penduduk_luar->update(['value' => json_encode($value)]);
        }
    }

    protected function migrasi_2024042171()
    {
        Schema::table('kelompok', static function (Blueprint $table) {
            if (! Schema::hasColumn('kelompok', 'logo')) {
                $table->string('logo', 255)->nullable()->after('kode');
            }
            if (! Schema::hasColumn('kelompok', 'no_sk_pendirian')) {
                $table->string('no_sk_pendirian', 255)->nullable()->after('logo');
            }
        });
    }

    protected function migrasi_2024072951()
    {
        $this->dbforge->modify_column('log_surat', [
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }
}
