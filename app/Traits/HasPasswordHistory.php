<?php

namespace App\Traits;

use App\Models\PasswordHistory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

trait HasPasswordHistory
{
    public static function bootHasPasswordHistory(): void
    {
        // Sengaja tidak memeriksa keberadaan tabel di sini: boot model bisa terjadi
        // sebelum koneksi database terikat (mis. pada unit test tanpa aplikasi penuh),
        // dan Schema::hasTable() yang gagal akan membuat model tersangkut status
        // "sedang booting". Pemeriksaan tabel dilakukan saat perekaman di
        // recordPasswordHistory(), ketika koneksi sudah pasti tersedia.
        static::whenBooted(function (): void {
            static::updating(function ($model): void {
                $column = $model->passwordHistoryColumn();

                if ($model->isDirty($column)) {
                    $model->recordPasswordHistory(
                        $model->getOriginal($column),
                        $model->passwordHistoryLimit()
                    );
                }
            });
        });
    }

    public function passwordHistoryColumn(): string
    {
        return 'password';
    }

    public function passwordHistoryLimit(): int
    {
        return 10;
    }

    /**
     * @return MorphMany<PasswordHistory, $this>
     */
    public function passwordHistories(): MorphMany
    {
        return $this->morphMany(PasswordHistory::class, 'authenticatable')
            ->latest('created_at');
    }

    public function recordPasswordHistory(?string $hashedPassword, ?int $limit = null): void
    {
        if (empty($hashedPassword)) {
            return;
        }

        if (! Schema::hasTable('password_histories')) {
            return;
        }

        $limit ??= $this->passwordHistoryLimit();

        $this->passwordHistories()->create(['password' => $hashedPassword]);

        $this->passwordHistories()
            ->skip($limit)
            ->take(PHP_INT_MAX)
            ->pluck('id')
            ->each(fn ($id) => PasswordHistory::destroy($id));
    }

    public function wasPasswordUsedBefore(string $plainPassword, ?int $limit = null): bool
    {
        if (! Schema::hasTable('password_histories')) {
            return false;
        }

        $limit ??= $this->passwordHistoryLimit();

        return $this->passwordHistories()
            ->limit($limit)
            ->get()
            ->contains(fn (PasswordHistory $history) => Hash::check($plainPassword, $history->password));
    }
}
