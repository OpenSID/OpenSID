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

use App\Models\Komentar;
use App\Models\LogSurat;
use App\Models\PermohonanSurat;
use App\Models\PesanMandiri;
use App\Models\User;
use App\Notifications\BukuTamu\TamuBaru;
use App\Notifications\Komentar\KomentarBaru;
use App\Notifications\Pesan\PesanMasuk;
use App\Notifications\Surat\PermohonanSuratBaru;
use App\Notifications\Surat\PermohonanSuratMasuk;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\BukuTamu\Models\TamuModel;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->ubahKolomTipeTableAnjungan();
        $this->tambahUuidTableAnjungan();
        $this->notifikasiTable();
        $this->addNullableConfigIdArtikel();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }

    public function ubahKolomTipeTableAnjungan()
    {
        if (Schema::hasTable('anjungan') && Schema::hasColumn('anjungan', 'tipe')) {
            DB::statement('ALTER TABLE anjungan MODIFY tipe TEXT NULL');
        }
    }

    public function tambahUuidTableAnjungan()
    {
        try {
            if (Schema::hasTable('anjungan') && ! Schema::hasColumn('anjungan', 'uuid')) {
                Schema::table('anjungan', static function ($table) {
                    $table->string('uuid')->unique()->nullable()->after('id');
                    $table->text('user_agent')->nullable()->after('uuid');
                });
            }

            set_session('success', 'Kolom uuid berhasil ditambahkan pada tabel anjungan.');
        } catch (Exception $e) {
            log_message('error', 'Gagal menambahkan kolom uuid pada tabel anjungan: ' . $e->getMessage());
        }
    }

    public function notifikasiTable()
    {
        // Buat tabel notifications untuk Laravel Notification Database
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', static function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->configId();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Pastikan index ada jika belum buat index
        if (Schema::hasTable('notifications') && ! Schema::hasIndex('notifications', 'notifications_notifiable_type_notifiable_id_index')) {
            Schema::table('notifications', static function (Blueprint $table) {
                $table->index(['notifiable_type', 'notifiable_id']);
            });
        }

        // Permohonan surat
        $isAdmin     = auth('admin')->user()?->pamong;
        $listJabatan = [
            'jabatan_id'        => $isAdmin?->jabatan_id,
            'jabatan_kades_id'  => kades()?->id,
            'jabatan_sekdes_id' => sekdes()?->id,
        ];

        LogSurat::whereNull('deleted_at')->masuk($isAdmin, $listJabatan)->each(function (LogSurat $logSurat) {
            $this->getUserAccessNotifications(modul: 'arsip-layanan')->each(function (User $user) use ($logSurat) {
                $this->notifyIfNotExists(
                    user: $user,
                    notification: new PermohonanSuratMasuk(logSurat: $logSurat),
                    notificationClass: PermohonanSuratMasuk::class,
                    dataKey: 'log_surat_id',
                    uniqueId: $logSurat->id
                );
            });
        });

        // Permohonan Surat Masuk
        PermohonanSurat::baru()->get()->each(function (PermohonanSurat $surat) {
            $this->getUserAccessNotifications(modul: 'permohonan-surat')->each(function (User $user) use ($surat) {
                $this->notifyIfNotExists(
                    user: $user,
                    notification: new PermohonanSuratBaru(permohonan: $surat),
                    notificationClass: PermohonanSuratBaru::class,
                    dataKey: 'permohonan_id',
                    uniqueId: $surat->id
                );
            });
        });

        // Komentar
        Komentar::unread()->whereNull('parent_id')->each(function (Komentar $komentar) {
            $this->getUserAccessNotifications(modul: 'komentar')->each(function (User $user) use ($komentar) {
                $this->notifyIfNotExists(
                    user: $user,
                    notification: new KomentarBaru(komentar: $komentar),
                    notificationClass: KomentarBaru::class,
                    dataKey: 'komentar_id',
                    uniqueId: $komentar->id
                );
            });
        });

        // Pesan Masuk
        PesanMandiri::where('status', PesanMandiri::UNREAD)
            ->where('tipe', 1)
            ->where('is_archived', 0)
            ->each(function (PesanMandiri $pesan) {
                $this->getUserAccessNotifications(modul: 'kotak-pesan')->each(function (User $user) use ($pesan) {
                    $this->notifyIfNotExists(
                        user: $user,
                        notification: new PesanMasuk(pesan: $pesan),
                        notificationClass: PesanMasuk::class,
                        dataKey: 'pesan_id',
                        uniqueId: $pesan->id
                    );
                });
            });

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

    public function addNullableConfigIdArtikel()
    {
        if (Schema::hasTable('artikel') && ! Schema::hasIndex('artikel', 'artikel_config_fk')) {
            Schema::table('artikel', static function ($table): void {
                $table->integer('config_id')->nullable()->index('artikel_config_fk')->change();
            });
        }
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
