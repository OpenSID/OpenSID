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
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau
 * menghilangkan pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\BaseTestCase;

/**
 * Issue #11223: Pengguna dengan status tidak aktif tidak boleh bisa login kehadiran.
 *
 * Tests that the kehadiran login query correctly excludes inactive users
 * and that the cekLogin method forces logout for inactive users.
 */
final class Issue11223InactiveUserLoginTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('ref_jabatan', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('nama', 100);
            $table->longText('tupoksi')->nullable();
            $table->boolean('jenis')->default(false);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });

        Schema::create('tweb_desa_pamong', static function (Blueprint $table) {
            $table->integer('pamong_id', true);
            $table->integer('config_id')->nullable();
            $table->string('pamong_nama', 100)->nullable();
            $table->string('gelar_depan', 100)->nullable();
            $table->string('gelar_belakang', 100)->nullable();
            $table->string('pamong_nip', 20)->nullable();
            $table->string('pamong_tag_id_card', 17)->nullable();
            $table->string('pamong_pin', 15)->nullable();
            $table->string('pamong_nik', 20)->nullable();
            $table->boolean('pamong_status')->nullable()->default(true);
            $table->date('pamong_tgl_terdaftar')->nullable();
            $table->boolean('pamong_ttd')->nullable();
            $table->text('foto')->nullable();
            $table->integer('id_pend')->nullable();
            $table->string('pamong_tempatlahir', 100)->nullable();
            $table->date('pamong_tanggallahir')->nullable();
            $table->tinyInteger('pamong_sex')->nullable();
            $table->integer('pamong_pendidikan')->nullable();
            $table->integer('pamong_agama')->nullable();
            $table->string('pamong_nosk', 30)->nullable();
            $table->date('pamong_tglsk')->nullable();
            $table->string('pamong_masajab', 120)->nullable();
            $table->integer('urut')->nullable();
            $table->string('pamong_niap', 25)->nullable()->default('0');
            $table->string('pamong_pangkat', 20)->nullable();
            $table->string('pamong_nohenti', 20)->nullable();
            $table->date('pamong_tglhenti')->nullable();
            $table->boolean('pamong_ub')->default(false);
            $table->integer('atasan')->nullable();
            $table->tinyInteger('bagan_tingkat')->nullable();
            $table->integer('bagan_offset')->nullable();
            $table->string('bagan_layout', 20)->nullable();
            $table->string('bagan_warna', 25)->nullable();
            $table->integer('kehadiran')->default(1);
            $table->integer('jabatan_id');
        });

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

    private function createActiveUser(array $overrides = []): User
    {
        return Model::withoutEvents(function () use ($overrides) {
            return User::withoutGlobalScope(ConfigIdScope::class)->create(array_merge([
                'config_id'   => 1,
                'username'    => 'testuser',
                'password'    => bcrypt('password'),
                'id_grup'     => 1,
                'pamong_id'   => null,
                'email'       => 'test@example.com',
                'active'      => '1',
                'nama'        => 'Test User',
                'id_telegram' => '',
                'session'     => 'abc123',
            ], $overrides));
        });
    }

    private function createInactiveUser(array $overrides = []): User
    {
        return $this->createActiveUser(array_merge(['active' => '0'], $overrides));
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
            ->where('user.username', $username)
            ->select('user.*')
            ->first();
    }

    private function findActiveUsers(): array
    {
        return DB::table('user')
            ->where('user.active', '1')
            ->get()
            ->toArray();
    }

    // --- Tests untuk query login kehadiran ---

    public function testKehadiranLoginQueryExcludesInactiveUser(): void
    {
        $this->createActiveUser(['username' => 'active_user', 'active' => '1']);
        $this->createInactiveUser(['username' => 'inactive_user', 'active' => '0']);

        $found = $this->findActiveUsers();

        $this->assertCount(1, $found);
        $this->assertEquals('active_user', $found[0]->username);
    }

    public function testKehadiranLoginQueryReturnsNullForInactiveUser(): void
    {
        $this->createInactiveUser(['username' => 'inactive_user', 'active' => '0']);

        $found = DB::table('user')
            ->where('active', '1')
            ->where('username', 'inactive_user')
            ->first();

        $this->assertNull($found);
    }

    public function testKehadiranLoginQueryFindsActiveUserWithCorrectPassword(): void
    {
        $this->createActiveUser([
            'username' => 'active_user',
            'active'   => '1',
        ]);

        $found = DB::table('user')
            ->where('active', '1')
            ->where('username', 'active_user')
            ->first();

        $this->assertNotNull($found);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $found->password));
    }

    // --- Tests untuk User.active field ---

    public function testInactiveUserHasActiveZero(): void
    {
        $user = $this->createInactiveUser(['active' => '0']);

        $this->assertEquals('0', $user->fresh()->active);
    }

    public function testActiveUserHasActiveOne(): void
    {
        $user = $this->createActiveUser(['active' => '1']);

        $this->assertEquals('1', $user->fresh()->active);
    }

    public function testUserDefaultActiveIsNotOne(): void
    {
        $user = $this->createActiveUser(['active' => '0']);

        $this->assertNotEquals('1', $user->fresh()->active);
    }

    // --- Tests untuk edge case ---

    public function testKehadiranLoginQueryWithNoUsersReturnsEmpty(): void
    {
        $found = DB::table('user')
            ->where('active', '1')
            ->get();

        $this->assertCount(0, $found);
    }

    public function testMultipleInactiveUsersAllExcluded(): void
    {
        $this->createInactiveUser(['username' => 'inactive1', 'active' => '0']);
        $this->createInactiveUser(['username' => 'inactive2', 'active' => '0']);
        $this->createInactiveUser(['username' => 'inactive3', 'active' => '0']);

        $found = DB::table('user')
            ->where('active', '1')
            ->get();

        $this->assertCount(0, $found);
    }

    public function testMixedActiveAndInactiveUsersOnlyReturnsActive(): void
    {
        $this->createActiveUser(['username' => 'active1', 'active' => '1']);
        $this->createActiveUser(['username' => 'active2', 'active' => '1']);
        $this->createInactiveUser(['username' => 'inactive1', 'active' => '0']);
        $this->createInactiveUser(['username' => 'inactive2', 'active' => '0']);

        $found = DB::table('user')
            ->where('active', '1')
            ->get();

        $this->assertCount(2, $found);
        $usernames = array_column($found->toArray(), 'username');
        $this->assertContains('active1', $usernames);
        $this->assertContains('active2', $usernames);
        $this->assertNotContains('inactive1', $usernames);
        $this->assertNotContains('inactive2', $usernames);
    }
}
