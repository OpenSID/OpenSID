<?php

namespace Tests\Feature\Models;

use App\Models\PasswordHistory;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class PasswordHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_tersimpan_via_fillable(): void
    {
        $user = User::first();

        $history = $user->passwordHistories()->create(['password' => 'hash-dummy']);

        $this->assertDatabaseHas('password_histories', [
            'id'                   => $history->id,
            'authenticatable_id'   => $user->id,
            'authenticatable_type' => User::class,
            'password'             => 'hash-dummy',
        ]);
    }

    public function test_config_id_terisi_otomatis(): void
    {
        $user = User::first();

        $history = $user->passwordHistories()->create(['password' => 'hash-dummy']);

        $this->assertNotNull($history->config_id);
        $this->assertSame(identitas('id'), $history->config_id);
    }

    public function test_relasi_authenticatable_mengembalikan_model_pemilik(): void
    {
        $user = User::first();

        $history = $user->passwordHistories()->create(['password' => 'hash-dummy']);

        $this->assertTrue($history->authenticatable->is($user));
    }

    public function test_model_tidak_menggunakan_timestamps_bawaan(): void
    {
        $this->assertFalse((new PasswordHistory())->timestamps);
    }

    public function test_created_at_terisi_otomatis_oleh_database(): void
    {
        $user = User::first();

        $history = $user->passwordHistories()->create(['password' => 'hash-dummy']);

        $this->assertNotNull($history->fresh()->created_at);
    }

    public function test_config_id_tidak_termasuk_fillable(): void
    {
        $this->assertNotContains('config_id', (new PasswordHistory())->getFillable());
    }
}
