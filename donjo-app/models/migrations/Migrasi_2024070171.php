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
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024070171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $this->migrasi_2024051253();
        $this->migrasi_2024060152();
        $this->migrasi_2024061151();
        $this->migrasi_2024062051();
        $this->migrasi_2024062851();
        $this->migrasi_2024052271();
        $this->migrasi_2024061471();

        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', LOKASI_FONT_DESA);
    }

    protected function migrasi_2024051253()
    {
        Schema::table('alias_kodeisian', static function (Blueprint $table) {
            $table->string('judul', 20)->change();
        });
    }

    protected function migrasi_2024060152()
    {
        Modul::where('slug', 'pengaturan-analisis')->update(['url' => 'setting_analisis']);
        Modul::where('slug', 'pengaturan-web')->update(['url' => 'setting_web']);
        Modul::where('slug', 'pengaturan-layanan-mandiri')->update(['url' => 'setting_mandiri']);
    }

    protected function migrasi_2024061151()
    {
        DB::table('produk')->where('status', 2)->update(['status' => 0]);
        DB::table('produk_kategori')->where('status', 2)->update(['status' => 0]);
        DB::table('pelapak')->where('status', 2)->update(['status' => 0]);
    }

    protected function migrasi_2024062051()
    {
        if (! Schema::hasColumn('keuangan_ta_jurnal_umum_rinci', 'Kd_SubRinci')) {
            Schema::table('keuangan_ta_jurnal_umum_rinci', static function (Blueprint $table) {
                $table->string('Kd_SubRinci', 10)->nullable()->after('Kd_Rincian');
            });
        }

        if (! Schema::hasColumn('keuangan_ta_mutasi', 'Kd_SubRinci')) {
            Schema::table('keuangan_ta_mutasi', static function (Blueprint $table) {
                $table->string('Kd_SubRinci', 10)->nullable()->after('Kd_Rincian');
            });
        }
    }

    public function migrasi_2024052271()
    {
        if (! $this->db->field_exists('status_pejabat', 'tweb_desa_pamong')) {
            $this->dbforge->add_column('tweb_desa_pamong', [
                'status_pejabat' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                    'null'       => false,
                    'default'    => 0,
                ],
            ]);
        }

        $this->createSetting([
            'judul'      => 'Sebutan PJ Kepala Desa',
            'key'        => 'sebutan_pj_kepala_desa',
            'value'      => 'Pj.',
            'keterangan' => 'Pengganti sebutan PJ Kepala Desa',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'Pemerintah Desa',
        ]);
    }

    protected function migrasi_2024061471()
    {
        $perbaris = 0;

        if (! Schema::hasTable('sinergi_program')) {
            Schema::create('sinergi_program', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id')->nullable();
                $table->string('judul', 100);
                $table->string('gambar', 100)->nullable();
                $table->string('tautan', 200);
                $table->integer('urut')->default(1);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
            });

            $data_sinergi_program = DB::table('widget')->where('config_id', identitas('id'))->where('isi', 'sinergi_program.php')->first()->setting;
            $setting              = json_decode($data_sinergi_program, true);

            if (count($setting) > 0) {
                foreach ($setting as $key => $data) {
                    if (! empty($data['kolom']) && $perbaris < $data['kolom']) {
                        $perbaris = $data['kolom'];
                    }

                    $data = [
                        'uuid'      => Str::uuid(),
                        'config_id' => identitas('id'),
                        'judul'     => $data['judul'],
                        'gambar'    => $data['gambar'],
                        'tautan'    => $data['tautan'],
                        'urut'      => $data['urut'] ?? $key + 1,
                        'status'    => $data['status'] ?? 1,
                    ];

                    DB::table('sinergi_program')->insert($data);
                }

                DB::table('widget')->where('config_id', identitas('id'))->where('isi', 'sinergi_program.php')->update(['form_admin' => 'sinergi_program', 'setting' => null]);
            }

            $this->createModul([
                'modul'      => 'Sinergi Program',
                'slug'       => 'sinergi-program',
                'url'        => 'sinergi_program',
                'aktif'      => 1,
                'ikon'       => 'fa-clone',
                'urut'       => 3,
                'level'      => 1,
                'hidden'     => 0,
                'ikon_kecil' => 'fa-clone',
                'parent'     => $this->db->get_where('setting_modul', ['config_id' => identitas('id'), 'slug' => 'admin-web'])->row()->id,
            ]);
        }

        $this->createSetting([
            'judul'      => 'Jumlah Gambar Sinergi Program Dalam 1 Baris',
            'key'        => 'gambar_sinergi_program_perbaris',
            'value'      => $perbaris == 0 ? 3 : $perbaris,
            'keterangan' => 'Jumlah gambar yang akan ditampilkan dalam 1 baris pada halaman Sinergi Program.',
            'jenis'      => 'input',
            'option'     => null,
            'attribute'  => 'class="bilangan required" placeholder="3" min="1" max="12" type="number"',
            'kategori'   => 'sinergi_program',
        ]);
    }

    protected function migrasi_2024062851()
    {
        $this->createSetting([
            'judul'      => 'Sumber Penduduk Berulang Global',
            'key'        => 'sumber_penduduk_berulang_surat',
            'value'      => null,
            'keterangan' => 'Sumber Penduduk Berulang Global untuk surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat',
        ]);
    }
}
