<?php

namespace Tests\Feature\Actions\Admin;

use App\Actions\Admin\HandleSetupWarningsAction;
use App\Models\User;
use App\Models\UserGrup;
use App\Repositories\SettingAplikasiRepository;
use Illuminate\Session\Store as SessionStore;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Feature test untuk cabang HandleSetupWarningsAction yang bergantung pada User::superAdmin().
 */
class HandleSetupWarningsActionTest extends BaseTestCase
{
    use MockeryPHPUnitIntegration;
    use RefreshDatabase;

    private function makeAction(): HandleSetupWarningsAction
    {
        $session  = Mockery::mock(SessionStore::class);
        $settings = Mockery::mock(SettingAplikasiRepository::class);
        $settings->shouldReceive('resolveSetting')->andReturn((object) [
            'email_notifikasi'    => '',
            'telegram_notifikasi' => '',
        ]);
        $session->shouldReceive('put')->with('setup_warning', Mockery::any())->byDefault();

        return new HandleSetupWarningsAction($session, $settings);
    }

    #[Test]
    public function tidak_ada_peringatan_saat_super_admin_tersedia(): void
    {
        User::query()->delete();
        $superAdmin = User::create([
            'username' => 'super_admin_test',
            'password' => bcrypt('rahasia'),
            'id_grup'  => UserGrup::getGrupId(UserGrup::ADMINISTRATOR),
            'active'   => 1,
            'nama'     => 'Super Admin Test',
        ]);

        $action = $this->makeAction();

        $this->assertNull($action->execute('home', true, $superAdmin));
    }

    #[Test]
    public function peringatan_notifikasi_muncul_saat_tidak_ada_super_admin(): void
    {
        User::query()->delete();
        $operator = User::create([
            'username' => 'operator_test',
            'password' => bcrypt('rahasia'),
            'id_grup'  => UserGrup::getGrupId('operator'),
            'active'   => 1,
            'nama'     => 'Operator Test',
        ]);

        $action = $this->makeAction();
        $result = $action->execute('home', true, $operator);

        $this->assertNotNull($result);
        $this->assertSame('Atur Notifikasi Akun', $result['title']);
    }
}
