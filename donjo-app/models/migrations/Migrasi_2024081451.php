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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024081451 extends MY_model
{
    public function up()
    {
        $hasil = true;
        $hasil = $this->migrasi_2024081252($hasil);
        $hasil = $this->migrasi_2024081151($hasil);
        $hasil = $this->migrasi_2024080851($hasil);

        return $hasil && true;
    }

    public function migrasi_2024080851($hasil)
    {
        $daftarKomentar = DB::table('komentar')->whereNull('id_artikel')->get();

        foreach ($daftarKomentar as $komentar) {
            $penduduk_id = DB::table('tweb_penduduk')->where('nik', $komentar->email)->value('id');
            if ($penduduk_id) {
                DB::table('pesan_mandiri')->insert([
                    'uuid'        => Str::uuid(),
                    'config_id'   => $komentar->config_id,
                    'owner'       => $komentar->owner,
                    'penduduk_id' => $penduduk_id,
                    'subjek'      => $komentar->subjek,
                    'komentar'    => $komentar->komentar,
                    'tgl_upload'  => $komentar->tgl_upload,
                    'status'      => $komentar->status,
                    'tipe'        => $komentar->tipe,
                    'permohonan'  => $komentar->permohonan,
                    'created_at'  => $komentar->tgl_upload ?? now(),
                    'updated_at'  => $komentar->updated_at ?? now(),
                    'is_archived' => $komentar->is_archived,
                ]);
            }
            DB::table('komentar')->where('id', $komentar->id)->delete();
        }

        return $hasil;
    }

    protected function migrasi_2024081151($hasil)
    {
        if (! $this->db->field_exists('remember_token', 'user')) {
            $hasil = $hasil && $this->dbforge->add_column('user', [
                'remember_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'password',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2024081252($hasil)
    {
        Schema::table('tweb_penduduk', static function (Blueprint $table) {
            $table->date('tanggallahir')->nullable(false)->change();
        });

        return $hasil;
    }
}
