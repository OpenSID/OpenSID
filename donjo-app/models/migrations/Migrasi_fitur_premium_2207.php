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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Keluarga;
use App\Models\LogKeluarga;
use App\Models\Penduduk;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2207 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2206');
        $hasil = $hasil && $this->migrasi_2022060851($hasil);
        $hasil = $hasil && $this->migrasi_2022060951($hasil);

        return $hasil && $this->migrasi_2022060371($hasil);
    }

    protected function migrasi_2022060851($hasil)
    {
        // updated_by default null
        if ($this->db->field_exists('updated_by', 'log_keluarga')) {
            return $hasil && $this->dbforge->modify_column('log_keluarga', [
                'updated_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        }

        if ($sudahAda = LogKeluarga::pluck('id_kk')) {
            if ($belumAdaLog = Keluarga::whereNotIn('id', $sudahAda)->get()) {
                foreach ($belumAdaLog as $data) {
                    $hasil = $hasil && LogKeluarga::insert([
                        'id_kk'         => $data->id,
                        'kk_sex'        => Penduduk::select('sex')->find($data->nik_kepala)->sex,
                        'id_peristiwa'  => 1, // KK Baru
                        'tgl_peristiwa' => $data->tgl_daftar,
                        'updated_by'    => $this->session->user,
                    ]);
                }
            }
        }

        return $hasil;
    }

    protected function migrasi_2022060951($hasil)
    {
        // Cek data ganda
        $akanDihapus = [];

        if ($daftarBantuan = Bantuan::pluck('id')) {

            // Hapus semua peserta dengan program bantuan yang sudah tidak ada
            BantuanPeserta::whereNotIn('program_id', $daftarBantuan)->delete();

            foreach ($daftarBantuan as $program_id) {
                $duplikat = BantuanPeserta::select('id')
                    ->where('program_id', $program_id)
                    ->whereIn('kartu_id_pend', static function ($query) use ($program_id) {
                        $query->select('kartu_id_pend')
                            ->from('program_peserta')
                            ->where('program_id', $program_id)
                            ->groupBy('kartu_id_pend')
                            ->having(DB::raw('count(kartu_id_pend)'), '>', 1);
                    })
                    ->orderBy('updated_at', 'desc')
                    ->pluck('id');

                // Hapus Peserta Lama dan Sisakan 1 yang paling baru
                $akanDihapus = collect($duplikat)
                    ->except([0])
                    ->values()
                    ->merge($akanDihapus);
            }
        }

        // Hapus data yang duplikasi
        if ($akanDihapus) {
            foreach ($akanDihapus as $peserta) {
                $data = BantuanPeserta::find($peserta);

                log_message('error', "ID : {$peserta}, Peserta : {$data->peserta}, Nama : {$data->kartu_nama} sudah di hapus.");

                $hasil = $hasil && $data->delete();
            }
        }

        // Tambahkan index pada program_id dan kartu_id_pend
        if (! $this->cek_indeks('program_peserta', 'program_peserta_program_id_kartu_id_pend_unique')) {
            Schema::table('program_peserta', static function (Blueprint $table) {
                $table->unique(['program_id', 'kartu_id_pend']);
            });
        }

        return $hasil;
    }

    protected function migrasi_2022060371($hasil)
    {
        // Buat tabel log sinkronisasi
        if (! $this->db->table_exists('log_backup')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'ukuran' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
                'path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'downloaded_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
                'status' => [
                    'type'    => 'int',
                    'null'    => false,
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('log_backup', true);

            $hasil = $hasil && $this->timestamps('log_backup', false);
        }

        return $hasil;
    }
}
