<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserGrup;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Feature test untuk App\Models\User, khususnya scopeSuperAdmin().
 */
class UserTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_super_admin_mengembalikan_builder_bukan_langsung_dieksekusi(): void
    {
        $builder = User::superAdmin();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $builder);
    }

    #[Test]
    public function scope_super_admin_first_mengembalikan_user_administrator(): void
    {
        User::query()->delete();
        $admin = User::create([
            'username' => 'admin_super_test',
            'password' => bcrypt('rahasia'),
            'id_grup'  => UserGrup::getGrupId(UserGrup::ADMINISTRATOR),
            'active'   => 1,
            'nama'     => 'Admin Super Test',
        ]);

        $result = User::superAdmin()->first();

        $this->assertNotNull($result);
        $this->assertSame($admin->id, $result->id);
    }

    #[Test]
    public function scope_super_admin_first_mengembalikan_null_jika_tidak_ada_administrator(): void
    {
        User::query()->delete();

        $result = User::superAdmin()->first();

        $this->assertNull($result);
    }

    #[Test]
    public function scope_super_admin_exists_mengembalikan_false_jika_tidak_ada_administrator(): void
    {
        User::query()->delete();

        $this->assertFalse(User::superAdmin()->exists());
    }

    #[Test]
    public function scope_super_admin_exists_mengembalikan_true_jika_ada_administrator(): void
    {
        User::query()->delete();
        User::create([
            'username' => 'admin_super_test',
            'password' => bcrypt('rahasia'),
            'id_grup'  => UserGrup::getGrupId(UserGrup::ADMINISTRATOR),
            'active'   => 1,
            'nama'     => 'Admin Super Test',
        ]);

        $this->assertTrue(User::superAdmin()->exists());
    }
}
