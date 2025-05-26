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

use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_beta
{
    use Migrator;

    public function up()
    {
        $this->createTableActivity();
        $this->refaktorLogLogin();
        $this->tambahPengaturanAnjunganSurat();
    }

    public function createTableActivity()
    {
        if (! Schema::hasTable('log_activity')) {
            Schema::create('log_activity', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->configId();
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->nullableMorphs('subject', 'subject');
                $table->string('event')->nullable();
                $table->nullableMorphs('causer', 'causer');
                $table->json('properties')->nullable();
                $table->uuid('batch_uuid')->nullable();
                $table->timestamps();
                $table->index('log_name');
            });
        }
    }

    public function refaktorLogLogin()
    {
        if (Schema::hasTable('log_login')) {
            DB::table('log_login')
                ->get()
                ->chunk(500)
                ->each(static function ($chunk) {
                    $userCache = [];

                    $chunk = $chunk->map(static function ($item) use (&$userCache) {
                        $userKey = "{$item->config_id}|{$item->username}";

                        if (! isset($userCache[$userKey])) {
                            $userCache[$userKey] = DB::table('user')
                                ->where('config_id', $item->config_id)
                                ->where('nama', $item->username)
                                ->value('id');
                        }

                        $causerId = $userCache[$userKey] ?? null;

                        return [
                            'config_id'   => $item->config_id,
                            'log_name'    => 'Login',
                            'description' => 'Pengguna berhasil masuk',
                            'event'       => 'Login',
                            'causer_type' => 'App\Models\User',
                            'causer_id'   => $causerId,
                            'properties'  => json_encode([
                                'username'   => $item->username,
                                'ip_address' => $item->ip_address,
                                'user_agent' => $item->user_agent,
                                'referer'    => $item->referer,
                                'lainnya'    => json_decode($item->lainnya, true),
                            ]),
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                        ];
                    });

                    DB::table('log_activity')->insert($chunk->toArray());
                });
        }

        if (Schema::hasTable('login_attempts')) {
            DB::table('login_attempts')
                ->get()
                ->chunk(500)
                ->each(static function ($chunk) {
                    $chunk = $chunk->map(static function ($item) use (&$userCache) {
                        return [
                            'config_id'   => $item->config_id,
                            'log_name'    => 'Login',
                            'description' => 'Pengguna tidak berhasil masuk',
                            'event'       => 'Failed',
                            'properties'  => json_encode([
                                'username'   => $item->username,
                                'ip_address' => $item->ip_address,
                                'time'       => $item->time,
                            ]),
                            'created_at' => Carbon::createFromTimestamp($item->time)->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::createFromTimestamp($item->time)->format('Y-m-d H:i:s'),
                        ];
                    });

                    DB::table('log_activity')->insert($chunk->toArray());
                });
        }

        Schema::dropIfExists('log_login');
        Schema::dropIfExists('login_attempts');
    }

    public function tambahPengaturanAnjunganSurat()
    {
        if (! Schema::hasColumn('anjungan', 'permohonan_surat_tanpa_akun')) {
            Schema::table('anjungan', static function (Blueprint $table) {
                $table->boolean('permohonan_surat_tanpa_akun')->default(0)->after('status');
            });
        }
    }
}
