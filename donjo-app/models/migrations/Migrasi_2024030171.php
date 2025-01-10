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

use App\Imports\KlasifikasiSuratImports;
use App\Models\Modul;
use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024030171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $this->migrasi_tabel();

        $this->migrasi_data();
    }

    protected function migrasi_tabel()
    {
    }

    // Migrasi perubahan data
    protected function migrasi_data()
    {
        $this->migrasi_2024020651();
        $this->migrasi_2024020652();
        $this->migrasi_2024021351();
        $this->migrasi_2024022271();
        $this->migrasi_2024020551();
        $this->migrasi_2024130201();
        $this->migrasi_2024210201();
    }

    protected function migrasi_2024020551()
    {
        Modul::where('slug', 'buku-lembaran-dan-berita-desa')->update(['url' => 'lembaran_desa']);

        DB::table('setting_modul')
            ->whereIn('slug', ['log-penduduk', 'catatan-peristiwa'])
            ->update(['slug' => 'peristiwa']);
    }

    public function migrasi_2024020651()
    {
        if (! DB::table('widget')->where('config_id', identitas('id'))->where('isi', 'jam_kerja.php')->exists()) {
            DB::table('widget')->insert([
                'config_id'    => identitas('id'),
                'isi'          => 'jam_kerja.php',
                'enabled'      => 2,
                'judul'        => 'Jam Kerja',
                'jenis_widget' => 1,
                'urut'         => DB::table('widget')->where('config_id', identitas('id'))->latest('urut')->value('urut') + 1,
                'form_admin'   => null,
                'setting'      => null,
                'foto'         => null,
            ]);
        }
    }

    public function migrasi_2024020652()
    {
        if (DB::table('kategori')->where('config_id', identitas('id'))->count() === 0) {
            DB::table('kategori')->insert([
                'config_id' => identitas('id'),
                'kategori'  => 'Berita Desa',
                'tipe'      => 1,
                'urut'      => DB::table('kategori')->where('config_id', identitas('id'))->latest('urut')->value('urut') + 1,
                'enabled'   => 1,
                'parrent'   => 0,
                'slug'      => 'berita-desa',
            ]);
        }
    }

    protected function migrasi_2024021351()
    {
        DB::table('setting_aplikasi')
            ->where('config_id', identitas('id'))
            ->where('key', 'ukuran_lebar_bagan')
            ->update(['kategori' => 'Pemerintah Desa']);
    }

    protected function migrasi_2024130201()
    {
        Modul::where('slug', 'buku-ekspedisi')->update(['url' => 'ekspedisi']);
    }

    protected function migrasi_2024210201()
    {
        Modul::where('slug', 'administrasi-penduduk')->update(['url' => 'bumindes_penduduk_induk']);
        Modul::where('slug', 'buku-mutasi-penduduk')->update(['url' => 'bumindes_penduduk_mutasi']);
        Modul::where('slug', 'buku-penduduk-sementara')->update(['url' => 'bumindes_penduduk_sementara']);
    }

    protected function migrasi_2024022271()
    {
        $this->dbforge->modify_column('klasifikasi_surat', [
            'nama' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ]);

        if (DB::table('klasifikasi_surat')->where('config_id', identitas('id'))->count() === 0) {
            (new KlasifikasiSuratImports())->import();
        }
    }
}
