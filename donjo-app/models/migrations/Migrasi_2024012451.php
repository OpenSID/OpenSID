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

use App\Models\Komentar;
use App\Models\Penduduk;
use App\Models\PesanMandiri;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024012451 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2024011751($hasil);

        return $hasil && $this->migrasi_2024012251($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        $hasil = $this->migrasi_2024011951($hasil);

        return $hasil && $this->migrasi_2024012351($hasil);
    }

    protected function migrasi_2024011751($hasil)
    {
        if (! Schema::hasTable('pesan_mandiri')) {
            Schema::create('pesan_mandiri', static function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->integer('config_id');
                $table->string('owner', 50);
                $table->integer('penduduk_id');
                $table->tinyText('subjek')->nullable();
                $table->text('komentar');
                $table->timestamp('tgl_upload')->useCurrent();
                $table->tinyInteger('status')->nullable();
                $table->tinyInteger('tipe')->nullable();
                $table->text('permohonan')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
                $table->tinyInteger('is_archived')->nullable()->default(0);
                $table->unique(['uuid', 'config_id']);
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('penduduk_id')->references('id')->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            });

            $komentarMandiri = Komentar::whereJenis(LAPORAN_MANDIRI)->get();
            if ($komentarMandiri) {
                foreach ($komentarMandiri as $key => $item) {
                    $penduduk = Penduduk::whereNik(trim($item->email))->first();
                    // masukkan data penduduk yang valid saja, ada kemungkinan nik tidak ditemukan ( case ganti nik )
                    if ($penduduk) {
                        $item->penduduk_id = $penduduk->id;
                        PesanMandiri::create($item->toArray());
                    }
                }
                Komentar::whereJenis(LAPORAN_MANDIRI)->delete();
            }

            Schema::table('komentar', static function (Blueprint $table) {
                $table->dropColumn('jenis');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024011951($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'kotak-pesan', 'url' => 'mailbox/clear'],
            ['url' => 'mailbox']
        );
    }

    protected function migrasi_2024012251($hasil)
    {
        if ($this->db->field_exists('userid', 'program')) {
            $hasil = $hasil && $this->dbforge->drop_column('program', 'userid');
        }

        return $hasil;
    }

    // migrasi 2312.0.0 - 2312.0.3 yang tidak sama dengan struktur instalasi awal
    protected function migrasi_2024012351($hasil)
    {
        $this->tambahIndeks('klasifikasi_surat', 'config_id, kode', 'UNIQUE', true);

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'qr-code', 'url' => 'qr_code/clear'],
            ['url' => 'qr_code']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pendaftar-layanan-mandiri', 'url' => 'mandiri/clear'],
            ['url' => 'mandiri']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'pengguna', 'url' => 'man_user/clear'],
            ['url' => 'man_user']
        );
    }
}
