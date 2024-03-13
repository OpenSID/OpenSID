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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2024080302($hasil);
        $hasil = $hasil && $this->migrasi_2024031375($hasil);

        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024080301($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024031171($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024021371($hasil, $id);
        }

        // Migrasi tanpa config_id
        // $hasil = $hasil && $this->migrasi_xxxxxxxx($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024080301($hasil, $config_id)
    {
        $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Surat Dinas',
            'slug'       => 'surat-dinas',
            'url'        => '',
            'aktif'      => 1,
            'ikon'       => 'fa-book',
            'urut'       => 60,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-book',
            'parent'     => 0,
        ]);

        return $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Pengaturan Surat',
            'slug'       => 'pengaturan-surat-dinas',
            'url'        => 'surat_dinas',
            'aktif'      => 1,
            'ikon'       => 'fa-cog',
            'urut'       => 1,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-cog',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'surat-dinas'])->row()->id,
        ]);
    }

    protected function migrasi_2024080302($hasil)
    {
        if (! Schema::hasTable('surat_dinas')) {
            Schema::create('surat_dinas', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('config_id')->nullable();
                $table->string('nama', 100);
                $table->string('url_surat', 100);
                $table->string('kode_surat', 10)->nullable();
                $table->string('lampiran', 100)->nullable();
                $table->boolean('kunci')->default(false);
                $table->boolean('favorit')->default(false);
                $table->tinyInteger('jenis')->default(2);
                $table->integer('masa_berlaku')->nullable()->default(1);
                $table->string('satuan_masa_berlaku', 15)->nullable()->default('M');
                $table->boolean('qr_code')->default(false);
                $table->boolean('logo_garuda')->default(false);
                $table->longText('template')->nullable();
                $table->longText('template_desa')->nullable();
                $table->longText('form_isian')->nullable();
                $table->longText('kode_isian')->nullable();
                $table->string('orientasi', 10)->nullable();
                $table->string('ukuran', 10)->nullable();
                $table->text('margin')->nullable();
                $table->boolean('margin_global')->nullable()->default(false);
                $table->integer('footer')->default(1);
                $table->integer('header')->default(1);
                $table->string('format_nomor', 100)->nullable();
                $table->tinyInteger('format_nomor_global')->nullable()->default(1);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->integer('created_by')->nullable();
                $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
                $table->integer('updated_by')->nullable();

                $table->unique(['config_id', 'url_surat'], 'url_surat_config');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024031171($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Tinggi Header',
            'key'        => 'tinggi_header_surat_dinas',
            'value'      => 3.5,
            'keterangan' => 'Tinggi Header Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Tinggi Footer',
            'key'        => 'tinggi_footer_surat_dinas',
            'value'      => 2,
            'keterangan' => 'Tinggi Footer Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul' => 'Header Surat',
            'key'   => 'header_surat_dinas',
            'value' => '<table style="border-collapse: collapse; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 10%;">[logo]</td>
            <td style="text-align: center; width: 90%;">
            <p style="margin: 0; text-align: center;"><span style="font-size: 14pt;">PEMERINTAH [SEbutan_kabupaten] [NAma_kabupaten] <br>KECAMATAN [NAma_kecamatan]<strong><br>[SEbutan_desa] [NAma_desa] </strong></span></p>
            <p style="margin: 0; text-align: center;"><em><span style="font-size: 10pt;">[Alamat_desA]</span></em></p>
            </td>
            </tr>
            </tbody>
            </table>
            <hr style="border: 3px solid;">',
            'keterangan' => 'Header Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul' => 'Footer Surat',
            'key'   => 'footer_surat_dinas',
            'value' => "<table style=\"border-collapse: collapse; width: 100%; height: 10px;\" border=\"0\">
            <tbody>
            <tr>
            <td style=\"width: 11.2886%; height: 10px;\">[kode_desa]</td>
            <td style=\"width: 78.3174%; height: 10px;\">
            <p style=\"text-align: center;\">\u{a0}</p>
            </td>
            <td style=\"width: 10.3939%; height: 10px; text-align: right;\">[KOde_surat]</td>
            </tr>
            </tbody>
            </table>",
            'keterangan' => 'Footer Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul' => 'Footer Surat TTE',
            'key'   => 'footer_surat_dinas_tte',
            'value' => "<table style=\"border-collapse: collapse; width: 100%; height: 10px;\" border=\"0\">
            <tbody>
            <tr>
            <td style=\"width: 11.2886%; height: 10px;\">[kode_desa]</td>
            <td style=\"width: 78.3174%; height: 10px;\">
            <p style=\"text-align: center;\">\u{a0}</p>
            </td>
            <td style=\"width: 10.3939%; height: 10px; text-align: right;\">[KOde_surat]</td>
            </tr>
            </tbody>
            </table>",
            'keterangan' => 'Footer Surat TTE',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Font Surat',
            'key'        => 'font_surat_dinas',
            'value'      => 'Arial',
            'keterangan' => 'Font Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Format Nomor Surat',
            'key'        => 'format_nomor_surat_dinas',
            'value'      => '[kode_surat]/[nomor_surat, 3]/[kode_desa]/[bulan_romawi]/[tahun]',
            'keterangan' => 'Fomat penomoran surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Format Tanggal Surat',
            'key'        => 'format_tanggal_surat_dinas',
            'value'      => 'd F Y',
            'keterangan' => 'Format tanggal pada kode isian surat.',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Margin Global',
            'key'        => 'surat_dinas_margin',
            'value'      => json_encode(['kiri' => 1.78, 'atas' => 0.63, 'kanan' => 1.78, 'bawah' => 1.37]),
            'keterangan' => 'Margin Global untuk surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ], $id);
    }

    public function migrasi_2024021371($hasil, $config_id)
    {
        if (!Schema::hasTable('shortcut')) {
            Schema::create('shortcut', function (Blueprint $table) {
                $table->id();
                $table->integer('config_id');
                $table->string('judul', 50);
                $table->string('link', 50)->nullable();
                $table->string('akses', 100)->nullable();
                $table->tinyInteger('jenis_query')->default(0);
                $table->string('raw_query', 150)->nullable();
                $table->string('icon', 50)->nullable();
                $table->string('warna', 25)->nullable();
                $table->integer('urut')->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (DB::table('shortcut')->where('config_id', $config_id)->count() == 0) {
            DB::table('shortcut')->insert([
                [
                    'config_id' => $config_id,
                    'judul' => 'Wilayah [desa]',
                    'link' => 'wilayah',
                    'akses' => 'wilayah-administratif',
                    'raw_query' => 'Dusun',
                    'icon' => 'fa-map-marker',
                    'urut' => 1,
                    'warna' => '#605ca8',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Penduduk',
                    'link' => 'penduduk',
                    'akses' => 'penduduk',
                    'raw_query' => 'Penduduk',
                    'icon' => 'fa-user',
                    'urut' => 2,
                    'warna' => '#00c0ef',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Keluarga',
                    'link' => 'keluarga',
                    'akses' => 'keluarga',
                    'raw_query' => 'Keluarga',
                    'icon' => 'fa-users',
                    'urut' => 3,
                    'warna' => '#00a65a',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Surat Tercetak',
                    'link' => 'keluar',
                    'akses' => 'arsip-layanan',
                    'raw_query' => 'Surat Tercetak',
                    'icon' => 'fa-file-text-o',
                    'urut' => 4,
                    'warna' => '#0073b7',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Kelompok',
                    'link' => 'kelompok',
                    'akses' => 'kelompok',
                    'raw_query' => 'Kelompok',
                    'icon' => 'fa-user-plus',
                    'urut' => 5,
                    'warna' => '#dd4b39',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Rumah Tangga',
                    'link' => 'rtm',
                    'akses' => 'rumah-tangga',
                    'raw_query' => 'RTM',
                    'icon' => 'fa-home',
                    'urut' => 6,
                    'warna' => '#d2d6de',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Bantuan',
                    'link' => 'program_bantuan',
                    'akses' => 'bantuan',
                    'raw_query' => 'Bantuan',
                    'icon' => 'fa-handshake-o',
                    'urut' => 7,
                    'warna' => '#f39c12',
                    'status' => 1,
                ],
                [
                    'config_id' => $config_id,
                    'judul' => 'Verifikasi Layanan Mandiri',
                    'link' => 'mandiri',
                    'akses' => 'pendaftar-layanan-mandiri',
                    'raw_query' => 'Verifikasi Layanan Mandiri',
                    'icon' => 'fa-drivers-license',
                    'urut' => 8,
                    'warna' => '#39cccc',
                    'status' => 1,
                ],
            ]);
        }

        return $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Shortcut',
            'slug'       => 'shortcut',
            'url'        => 'shortcut',
            'aktif'      => 1,
            'ikon'       => 'fa-chain',
            'urut'       => 20,
            'level'      => 1,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-chain',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'pengaturan'])->row()->id,
        ]);
    }

    protected function migrasi_2024031375($hasil)
    {
        if (!$this->db->field_exists('parent_id', 'komentar')) {
            $hasil = $hasil && $this->db->query("ALTER TABLE `komentar` ADD COLUMN `parent_id` INT(11) NULL");
        }

        return $hasil;
    }
}
