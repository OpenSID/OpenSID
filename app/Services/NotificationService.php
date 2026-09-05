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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services;

use App\Models\DatabaseNotification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class NotificationService
{
    /**
     * Kategori notifikasi yang tersedia
     * Didefinisikan melalui config atau bisa di-extend dengan method
     */
    private static array $categories = [];

    /**
     * Register kategori notifikasi
     */
    public static function registerCategory(string $key, array $config): void
    {
        self::$categories[$key] = $config;
    }

    /**
     * Get semua kategori notifikasi yang terdaftar
     */
    public static function getCategories(): array
    {
        if (empty(self::$categories)) {
            // Load dari config jika ada
            self::$categories = config('notifications.categories', []);
        }

        return self::$categories;
    }

    /**
     * Get kategori spesifik
     */
    public static function getCategory(string $key): ?array
    {
        return self::getCategories()[$key] ?? null;
    }

    /**
     * Get jumlah notifikasi berdasarkan kategori
     * Secara dinamis membaca dari kategori yang terdaftar
     *
     * @param User|null $user
     */
    public static function getNotificationCounts($user = null): array
    {
        if (! $user) {
            $user = auth('admin')->user();
        }

        if (! $user) {
            return self::buildEmptyCounts();
        }

        if (Schema::hasTable('notifications') === false) {
            return self::buildEmptyCounts();
        }

        // Ambil notifikasi yang belum dibaca dan kelompokkan berdasarkan kategori
        $notifications = $user->unreadNotifications()
            ->get()
            ->groupBy(static fn ($notification) => $notification->data['category'] ?? 'other');

        // Build hasil berdasarkan kategori yang terdaftar
        $counts = [];

        foreach (self::getCategories() as $key => $config) {
            $counts[$key] = $notifications->get($key, collect())->count();
        }

        return $counts;
    }

    /**
     * Mark notification as read berdasarkan kategori
     */
    public static function markCategoryAsRead(User $user, string $category): int
    {
        return DatabaseNotification::query()
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->whereRaw("JSON_EXTRACT(data, '$.category') = ?", [$category])
            ->update(['read_at' => now()]);
    }

    /**
     * Mark single notification as read
     */
    public static function markAsRead(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();

            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead(User $user, array $notificationIds = []): void
    {
        if (! empty($notificationIds)) {
            $user->unreadNotifications
                ->whereIn('id', $notificationIds)
                ->markAsRead();

            return;
        }

        $user->unreadNotifications->markAsRead();
    }

    /**
     * Get recent notifications dengan limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRecentNotifications(User $user, int $limit = 10)
    {
        if (Schema::hasTable('notifications') === false) {
            return collect();
        }

        return $user->notifications()
            ->reorder()
            ->orderByRaw('read_at is null desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get notifikasi berdasarkan kategori
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNotificationsByCategory(User $user, string $category, int $limit = 10)
    {
        return $user->notifications()
            ->whereRaw("JSON_EXTRACT(data, '$.category') = ?", [$category])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete old read notifications (cleanup)
     *
     * @param int $days Hapus notifikasi yang sudah dibaca lebih dari X hari
     */
    public static function cleanupOldNotifications(int $days = 30): int
    {
        return DatabaseNotification::query()
            ->whereNotNull('read_at')
            ->where('read_at', '<', Carbon::now()->subDays($days))
            ->delete();
    }

    /**
     * Get statistik notifikasi per kategori
     */
    public static function getStatistics(User $user): array
    {
        $allNotifications    = $user->notifications()->count();
        $unreadNotifications = $user->unreadNotifications()->count();
        $readNotifications   = $allNotifications - $unreadNotifications;

        return [
            'total'       => $allNotifications,
            'unread'      => $unreadNotifications,
            'read'        => $readNotifications,
            'by_category' => self::getNotificationCounts($user),
        ];
    }

    /**
     * Build array kosong berdasarkan kategori yang terdaftar
     */
    private static function buildEmptyCounts(): array
    {
        $counts = [];

        foreach (self::getCategories() as $key => $config) {
            $counts[$key] = 0;
        }

        return $counts;
    }
}
