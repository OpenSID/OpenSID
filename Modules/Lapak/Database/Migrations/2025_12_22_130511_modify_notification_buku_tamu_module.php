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

use App\Models\User;
use App\Notifications\BukuTamu\TamuBaru;
use Illuminate\Database\Migrations\Migration;
use Modules\BukuTamu\Models\TamuModel;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Buku Tamu
        TamuModel::baru()->get()->each(function (TamuModel $tamu) {
            $this->getUserAccessNotifications(modul: 'data-tamu')->each(function (User $user) use ($tamu) {
                $this->notifyIfNotExists(
                    user: $user,
                    notification: new TamuBaru(tamu: $tamu),
                    notificationClass: TamuBaru::class,
                    dataKey: 'tamu_id',
                    uniqueId: $tamu->id
                );
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    private function getUserAccessNotifications(string $modul, string $akses = 'b')
    {
        return User::status()
            ->get()
            ->filter(static fn (User $user) => can(akses: $akses, slugModul: $modul, user: $user));
    }

    /**
     * Kirim notifikasi hanya jika belum ada
     *
     * @param User   $user              User yang akan menerima notifikasi
     * @param mixed  $notification      Instance dari notification class
     * @param string $notificationClass Nama lengkap notification class
     * @param string $dataKey           Key yang digunakan di dalam data JSON
     * @param mixed  $uniqueId          ID unik untuk pengecekan duplikasi
     */
    private function notifyIfNotExists(
        User $user,
        $notification,
        string $notificationClass,
        string $dataKey,
        $uniqueId
    ): void {
        $exists = $user->notifications()
            ->where('type', $notificationClass)
            ->where("data->data->{$dataKey}", $uniqueId)
            ->exists();

        if (! $exists) {
            $user->notify($notification);
        }
    }
};
