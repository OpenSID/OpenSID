<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Database\Factories\DatabaseNotificationFactory;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Services\NotificationService.
 *
 * Menguji method yang query DB tabel `notifications` memakai RefreshDatabase +
 * DatabaseNotificationFactory. NotificationService bersih dari helper CI3.
 */
class NotificationServiceTest extends BaseTestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetCategories();
        $this->user = User::query()->firstOrFail();
    }

    protected function tearDown(): void
    {
        $this->resetCategories();
        parent::tearDown();
    }

    private function resetCategories(): void
    {
        $prop = (new ReflectionClass(NotificationService::class))->getProperty('categories');
        $prop->setAccessible(true);
        $prop->setValue(null, []);
    }

    #[Test]
    public function get_notification_counts_menghitung_unread_per_kategori(): void
    {
        NotificationService::registerCategory('uji_surat', ['label' => 'Surat']);

        DatabaseNotificationFactory::new()->forUser($this->user)->kategori('uji_surat')->belumDibaca()->count(3)->create();
        DatabaseNotificationFactory::new()->forUser($this->user)->kategori('uji_surat')->dibaca()->create();

        $counts = NotificationService::getNotificationCounts($this->user);

        $this->assertSame(3, $counts['uji_surat']);
    }

    #[Test]
    public function mark_category_as_read_menandai_semua_kategori_terkait(): void
    {
        $notifs = DatabaseNotificationFactory::new()->forUser($this->user)->kategori('uji_mark')->belumDibaca()->count(2)->create();

        $jumlah = NotificationService::markCategoryAsRead($this->user, 'uji_mark');

        $this->assertSame(2, $jumlah);
        foreach ($notifs as $n) {
            $this->assertNotNull($n->fresh()->read_at);
        }
    }

    #[Test]
    public function mark_as_read_single_notification(): void
    {
        $notif = DatabaseNotificationFactory::new()->forUser($this->user)->belumDibaca()->create();

        $this->assertTrue(NotificationService::markAsRead($this->user, $notif->id));
        $this->assertNotNull($notif->fresh()->read_at);

        $this->assertFalse(NotificationService::markAsRead($this->user, 'id-tidak-ada'));
    }

    #[Test]
    public function mark_all_as_read_menandai_semua_unread(): void
    {
        $notifs = DatabaseNotificationFactory::new()->forUser($this->user)->belumDibaca()->count(2)->create();

        NotificationService::markAllAsRead($this->user);

        foreach ($notifs as $n) {
            $this->assertNotNull($n->fresh()->read_at);
        }
    }

    #[Test]
    public function get_recent_notifications_menghormati_limit(): void
    {
        DatabaseNotificationFactory::new()->forUser($this->user)->count(3)->create();

        $hasil = NotificationService::getRecentNotifications($this->user, 2);

        $this->assertInstanceOf(Collection::class, $hasil);
        $this->assertCount(2, $hasil);
    }

    #[Test]
    public function get_notifications_by_category_hanya_kategori_tertentu(): void
    {
        DatabaseNotificationFactory::new()->forUser($this->user)->kategori('uji_cat')->count(2)->create();
        DatabaseNotificationFactory::new()->forUser($this->user)->kategori('lainnya')->create();

        $hasil = NotificationService::getNotificationsByCategory($this->user, 'uji_cat', 10);

        $this->assertGreaterThanOrEqual(2, $hasil->count());
        foreach ($hasil as $n) {
            $this->assertSame('uji_cat', $n->data['category']);
        }
    }

    #[Test]
    public function cleanup_old_notifications_menghapus_yang_lama_dan_sudah_dibaca(): void
    {
        $lama  = DatabaseNotificationFactory::new()->forUser($this->user)->dibaca(Carbon::now()->subDays(40))->create();
        $baru  = DatabaseNotificationFactory::new()->forUser($this->user)->dibaca(Carbon::now()->subDay())->create();

        NotificationService::cleanupOldNotifications(30);

        $this->assertNull($lama->fresh());
        $this->assertNotNull($baru->fresh());
    }

    #[Test]
    public function get_statistics_mengembalikan_struktur_lengkap(): void
    {
        DatabaseNotificationFactory::new()->forUser($this->user)->belumDibaca()->create();

        $stats = NotificationService::getStatistics($this->user);

        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('unread', $stats);
        $this->assertArrayHasKey('read', $stats);
        $this->assertArrayHasKey('by_category', $stats);
        $this->assertGreaterThanOrEqual(1, $stats['unread']);
        $this->assertSame($stats['total'] - $stats['unread'], $stats['read']);
    }
}
