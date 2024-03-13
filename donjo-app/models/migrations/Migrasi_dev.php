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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\KaderMasyarakat;
use App\Models\RefPendudukBidang;
use App\Models\RefPendudukKursus;
use Illuminate\Support\Facades\DB;

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
        return $hasil && $this->migrasi_2024080302($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024270201($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024080301($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024031171($hasil, $id);
        }

        // Migrasi tanpa config_id

        $hasil = $hasil && $this->migrasi_2024280201($hasil);
        $hasil = $hasil && $this->migrasi_2024030551($hasil);

        return $hasil && $this->migrasi_2024070301($hasil);
    }

    protected function migrasi_2024270201($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Sinkronisasi OpenDK Server',
            'key'        => 'sinkronisasi_opendk',
            'value'      => setting('api_opendk_key') ? 1 : 0,
            'keterangan' => 'Aktifkan Sinkronisasi Server OpenDK',
            'kategori'   => 'opendk',
            'jenis'      => 'boolean',
            'option'     => null,
        ], $id);
    }

    protected function migrasi_2024280201($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'buku-tanah-di-desa', 'url' => 'bumindes_tanah_desa/clear'],
            ['url' => 'bumindes_tanah_desa']
        );
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

    protected function migrasi_2024070301($hasil)
    {
        $kader  = KaderMasyarakat::get();
        $bidang = RefPendudukBidang::get();
        $kursus = RefPendudukKursus::get();

        foreach ($kader as $item) {
            $resultBidang = [];
            $resultKursus = [];

            foreach ($bidang as $valueBidang) {
                if (strpos($item->bidang, $valueBidang['nama']) !== false) {
                    $resultBidang[] = $valueBidang['nama'];
                }
            }

            foreach ($kursus as $valueKursus) {
                if (strpos($item->kursus, $valueKursus['nama']) !== false) {
                    $resultKursus[] = $valueKursus['nama'];
                }
            }
            KaderMasyarakat::find($item->id)->update([
                'bidang' => json_encode($resultBidang),
                'kursus' => json_encode($resultKursus),
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2024030551($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'rumah-tangga', 'url' => 'rtm/clear'],
            ['url' => 'rtm']
        );
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
}
