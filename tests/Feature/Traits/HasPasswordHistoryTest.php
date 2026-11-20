<?php

namespace Tests\Feature\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class HasPasswordHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Asumsi RefreshDatabase/seeder sudah menyediakan minimal 1 user.
        $this->user = User::first();
    }

    /**
     * Set password awal secara quiet (tanpa memicu event 'updating'),
     * agar setara dengan kondisi record baru dibuat via factory (tidak
     * menghasilkan riwayat password).
     */
    private function setInitialPassword(User $user, string $plainPassword): User
    {
        $user->forceFill([
            'password' => Hash::make($plainPassword),
        ])->saveQuietly();

        return $user->refresh();
    }

    public function test_tidak_ada_riwayat_saat_user_baru_dibuat(): void
    {
        $user = $this->setInitialPassword($this->user, 'SandiAwal123!');

        $this->assertCount(0, $user->passwordHistories()->get());
    }

    public function test_password_lama_tercatat_saat_update(): void
    {
        $user = $this->setInitialPassword($this->user, 'SandiLama123!');

        $user->password = Hash::make('SandiBaru456!');
        $user->update();

        $histories = $user->passwordHistories()->get();

        $this->assertCount(1, $histories);
        $this->assertTrue(Hash::check('SandiLama123!', $histories->first()->password));
    }

    public function test_tidak_mencatat_riwayat_jika_password_tidak_berubah(): void
    {
        $user = $this->setInitialPassword($this->user, 'SandiTetap123!');

        $user->nama = 'Nama Baru';
        $user->update();

        $this->assertCount(0, $user->passwordHistories()->get());
    }

    public function test_was_password_used_before_true_untuk_sandi_lama(): void
    {
        $user = $this->setInitialPassword($this->user, 'SandiLama123!');

        $user->password = Hash::make('SandiBaru456!');
        $user->update();

        $this->assertTrue($user->wasPasswordUsedBefore('SandiLama123!'));
    }

    public function test_was_password_used_before_false_untuk_sandi_belum_pernah_dipakai(): void
    {
        $user = $this->setInitialPassword($this->user, 'SandiLama123!');

        $user->password = Hash::make('SandiBaru456!');
        $user->update();

        $this->assertFalse($user->wasPasswordUsedBefore('SandiBelumPernah789!'));
    }

    public function test_riwayat_dipangkas_sesuai_limit(): void
    {
        $user = $this->setInitialPassword($this->user, 'Sandi0!');

        foreach (range(1, 12) as $i) {
            $user->password = Hash::make("Sandi{$i}Baru!");
            $user->update();
        }

        $this->assertLessThanOrEqual(
            $user->passwordHistoryLimit(),
            $user->passwordHistories()->count()
        );
    }

    public function test_riwayat_paling_lama_ikut_terpangkas(): void
    {
        $user = $this->setInitialPassword($this->user, 'Sandi0!');

        foreach (range(1, 12) as $i) {
            $user->password = Hash::make("Sandi{$i}Baru!");
            $user->update();
        }

        // Sandi0! adalah password lama pertama yang dicatat -> paling tua,
        // seharusnya sudah terpangkas setelah 12 pergantian (limit default 10).
        $this->assertFalse($user->wasPasswordUsedBefore('Sandi0!', limit: 20));
    }

    public function test_default_limit_password_history_adalah_10(): void
    {
        $this->assertSame(10, (new User())->passwordHistoryLimit());
    }

    public function test_default_kolom_password_history_adalah_password(): void
    {
        $this->assertSame('password', (new User())->passwordHistoryColumn());
    }

    public function test_password_kosong_tidak_dicatat_ke_riwayat(): void
    {
        $user = $this->user;

        $user->recordPasswordHistory(null);
        $user->recordPasswordHistory('');

        $this->assertCount(0, $user->passwordHistories()->get());
    }

    public function test_limit_kustom_dihormati_saat_recordPasswordHistory(): void
    {
        $user = $this->setInitialPassword($this->user, 'Sandi0!');

        foreach (range(1, 5) as $i) {
            $user->recordPasswordHistory(Hash::make("Sandi{$i}!"), limit: 3);
        }

        $this->assertLessThanOrEqual(3, $user->passwordHistories()->count());
    }
}
