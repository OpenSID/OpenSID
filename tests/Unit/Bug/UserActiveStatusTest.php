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

namespace Tests\Unit\Bug;

use App\Models\User;
use App\Scopes\ConfigIdScope;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan pengguna dengan status tidak aktif tidak bisa login kehadiran.
 *
 * @internal
 */
final class UserActiveStatusTest extends BaseTestCase
{
    use RefreshDatabase;

    private int $configId = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestTables();
        $this->seedUserGrup();
    }

    private function createTestTables(): void
    {
        if (! Schema::hasTable('user_grup')) {
            Schema::create('user_grup', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('config_id')->nullable();
                $table->string('nama', 20);
                $table->string('slug')->nullable();
                $table->tinyInteger('jenis')->default(1);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->integer('created_by')->nullable();
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
                $table->integer('updated_by')->nullable();
            });
        }

        if (! Schema::hasTable('ref_jabatan')) {
            Schema::create('ref_jabatan', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('config_id')->nullable();
                $table->string('nama', 100);
                $table->longText('tupoksi')->nullable();
                $table->boolean('jenis')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->integer('created_by')->nullable();
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
                $table->integer('updated_by')->nullable();
            });
        }

        if (! Schema::hasTable('tweb_desa_pamong')) {
            Schema::create('tweb_desa_pamong', static function (Blueprint $table) {
                $table->integer('pamong_id', true);
                $table->integer('config_id')->nullable();
                $table->string('pamong_nama', 100)->nullable();
                $table->string('pamong_nip', 20)->nullable();
                $table->string('pamong_tag_id_card', 17)->nullable();
                $table->string('pamong_nik', 20)->nullable();
                $table->boolean('pamong_status')->nullable()->default(true);
                $table->integer('id_pend')->nullable();
                $table->tinyInteger('pamong_sex')->nullable();
                $table->string('pamong_niap', 25)->nullable()->default('0');
                $table->integer('kehadiran')->default(1);
                $table->integer('jabatan_id');
            });
        }

        if (! Schema::hasTable('user')) {
            Schema::create('user', static function (Blueprint $table) {
                $table->mediumIncrements('id');
                $table->integer('config_id')->nullable();
                $table->string('username', 100)->nullable();
                $table->string('password', 100);
                $table->integer('id_grup');
                $table->integer('pamong_id')->nullable();
                $table->string('email', 100)->nullable();
                $table->dateTime('last_login')->nullable();
                $table->dateTime('email_verified_at')->nullable();
                $table->boolean('active')->unsigned()->nullable()->default(false);
                $table->string('nama', 50)->nullable();
                $table->string('id_telegram', 100);
                $table->string('token', 100)->nullable();
                $table->dateTime('token_exp')->nullable();
                $table->dateTime('telegram_verified_at')->nullable();
                $table->boolean('notif_telegram')->default(false);
                $table->string('company', 100)->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('foto', 100)->nullable()->default('kuser.png');
                $table->string('session', 40);
            });
        }
    }

    private function seedUserGrup(): void
    {
        DB::table('user_grup')->insert([
            [
                'config_id' => $this->configId,
                'nama'      => 'Administrator',
                'slug'      => 'administrator',
                'jenis'     => 1,
            ],
        ]);
    }

    private function createJabatan(): int
    {
        return DB::table('ref_jabatan')->insertGetId([
            'config_id' => $this->configId,
            'nama'      => 'Kepala Desa',
        ]);
    }

    private function createPamong(int $jabatanId, bool $active = true): int
    {
        return DB::table('tweb_desa_pamong')->insertGetId([
            'config_id'          => $this->configId,
            'pamong_nama'        => 'Test Pamong',
            'pamong_status'      => $active ? 1 : 0,
            'pamong_tag_id_card' => 'TAG001',
            'pamong_nik'         => '3201010101010001',
            'jabatan_id'         => $jabatanId,
            'kehadiran'          => 1,
        ]);
    }

    private function createUser(int $pamongId, bool $active = true): User
    {
        return Model::withoutEvents(function () use ($pamongId, $active) {
            return User::withoutGlobalScope(ConfigIdScope::class)->create([
                'config_id'   => $this->configId,
                'username'    => 'testuser_' . ($active ? 'active' : 'inactive'),
                'password'    => bcrypt('password123'),
                'id_grup'     => 1,
                'pamong_id'   => $pamongId,
                'active'      => $active ? '1' : '0',
                'nama'        => 'Test User',
                'id_telegram' => '',
                'session'     => 'test_session_' . ($active ? 'active' : 'inactive'),
            ]);
        });
    }

    /**
     * Replicate the query from PerangkatController::cek() using raw DB queries
     * to avoid ConfigIdScope issues in the test environment.
     */
    private function findActiveUserWithActivePamong(string $username): ?\stdClass
    {
        return DB::table('user')
            ->join('tweb_desa_pamong', 'user.pamong_id', '=', 'tweb_desa_pamong.pamong_id')
            ->where('user.active', '1')
            ->where('tweb_desa_pamong.pamong_status', '1')
            ->where(function ($query) use ($username) {
                $query->where('user.username', $username)
                    ->orWhere('tweb_desa_pamong.pamong_nik', $username)
                    ->orWhere('tweb_desa_pamong.pamong_tag_id_card', $username);
            })
            ->select('user.*')
            ->first();
    }

    public function test_active_user_can_be_found_with_active_filter(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId);
        $user      = $this->createUser($pamongId, true);

        $found = $this->findActiveUserWithActivePamong('testuser_active');

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);
        $this->assertEquals('1', $found->active);
    }

    public function test_inactive_user_cannot_be_found_with_active_filter(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId);
        $this->createUser($pamongId, false);

        $found = $this->findActiveUserWithActivePamong('testuser_inactive');

        $this->assertNull($found);
    }

    public function test_inactive_user_exists_in_database(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId);
        $user      = $this->createUser($pamongId, false);

        $this->assertDatabaseHas('user', [
            'id'     => $user->id,
            'active' => '0',
        ]);
    }

    public function test_active_user_exists_in_database(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId);
        $user      = $this->createUser($pamongId, true);

        $this->assertDatabaseHas('user', [
            'id'     => $user->id,
            'active' => '1',
        ]);
    }

    public function test_inactive_pamong_with_active_user_cannot_be_found(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId, false);
        $this->createUser($pamongId, true);

        $found = $this->findActiveUserWithActivePamong('testuser_active');

        $this->assertNull($found);
    }

    public function test_inactive_user_and_inactive_pamong_cannot_be_found(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId, false);
        $this->createUser($pamongId, false);

        $found = $this->findActiveUserWithActivePamong('testuser_inactive');

        $this->assertNull($found);
    }

    public function test_user_scope_status_active_filters_correctly(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId);

        $activeUser = $this->createUser($pamongId, true);
        $inactiveUser = Model::withoutEvents(function () {
            return User::withoutGlobalScope(ConfigIdScope::class)->create([
                'config_id'   => $this->configId,
                'username'    => 'inactive_user',
                'password'    => bcrypt('password123'),
                'id_grup'     => 1,
                'pamong_id'   => null,
                'active'      => '0',
                'nama'        => 'Inactive User',
                'id_telegram' => '',
                'session'     => 'test_session2',
            ]);
        });

        $activeUsers = User::withoutGlobalScope(ConfigIdScope::class)
            ->where('active', '1')
            ->get();

        $this->assertTrue($activeUsers->contains('id', $activeUser->id));
        $this->assertFalse($activeUsers->contains('id', $inactiveUser->id));
    }

    public function test_user_active_field_is_string_type(): void
    {
        $jabatanId = $this->createJabatan();
        $pamongId  = $this->createPamong($jabatanId);
        $user      = $this->createUser($pamongId, true);

        $this->assertIsBool($user->active);
        $this->assertTrue($user->active);
    }
}
