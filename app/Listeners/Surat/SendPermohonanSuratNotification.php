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

namespace App\Listeners\Surat;

use App\Events\Surat\PermohonanSuratSubmitted;
use App\Models\LogNotifikasiAdmin;
use App\Models\PendudukMandiri;
use App\Models\User;
use App\Notifications\Surat\PermohonanSuratBaru;
use Exception;
use Illuminate\Support\Collection;
use NotificationChannels\Telegram\Telegram;

class SendPermohonanSuratNotification
{
    /**
     * Handle the event.
     */
    public function handle(PermohonanSuratSubmitted $event): void
    {
        // Send notifications to users with permohonan-surat access
        $users = User::status()->get()->filter(static fn (User $user) => can(akses: 'b', slugModul: 'permohonan-surat', user: $user));

        $users->each(static function (User $user) use ($event): void {
            $user->notify(new PermohonanSuratBaru(permohonan: $event->permohonan));
        });

        // Dual-write ke log_notifikasi_admin agar notifikasi terbaca di KelolaDesa (mobile) via opensid-api.
        $this->createLogNotifikasiAdmin($event, $users);

        // Send telegram notification if enabled
        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->sendTelegramNotification($event);
        }
    }

    /**
     * Catat notifikasi ke tabel log_notifikasi_admin (dibaca opensid-api / KelolaDesa mobile).
     *
     * @param \Illuminate\Support\Collection<int, User> $users
     */
    private function createLogNotifikasiAdmin(PermohonanSuratSubmitted $event, Collection $users): void
    {
        try {
            $dariMandiri = $event->penduduk instanceof PendudukMandiri;

            // Judul disamakan dengan PermohonanSuratBaru (notifikasi web), dan sengaja
            // dibedakan per jalur (Mandiri: issue #6831, Anjungan: issue #11911) agar
            // tidak tertukar dengan notifikasi verifikasi surat dari Keluar.php yang
            // memakai template setting('notifikasi_pengajuan_surat').
            $judul = $dariMandiri ? 'Permohonan Surat Baru' : "Pengajuan Surat - {$event->surat->nama}";

            $isiNotifikasi = [
                'judul' => $judul,
                'isi'   => "Permohonan surat {$event->surat->nama} dari {$this->namaPemohon($event, $dariMandiri)}",
                // $event->permohonan selalu row permohonan_surat, terlepas dari jalur
                // Mandiri atau Anjungan — payload TIDAK boleh ikut bercabang di $dariMandiri
                // (beda dari judul/nama), kalau tidak permohonan Anjungan menunjuk ke
                // route yang di-resolve lewat log_surat, bukan permohonan_surat.
                'payload' => '/permohonan/mandiri/periksa/' . $event->permohonan->id,
                'read'    => 0,
            ];

            foreach ($users as $user) {
                LogNotifikasiAdmin::create([
                    'id_user'   => $user->id,
                    'config_id' => $user->config_id,
                    ...$isiNotifikasi,
                ]);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }

    /**
     * $event->penduduk bisa PendudukMandiri (akun Layanan Mandiri — tidak punya
     * kolom `nama` sendiri, harus lewat relasi ->penduduk) atau Penduduk langsung
     * (pemohon Anjungan, termasuk tamu tanpa akun tweb_penduduk_mandiri — issue #11911).
     */
    private function namaPemohon(PermohonanSuratSubmitted $event, bool $dariMandiri): string
    {
        return $dariMandiri ? $event->penduduk->penduduk->nama : $event->penduduk->nama;
    }

    /**
     * Send telegram notification.
     */
    private function sendTelegramNotification(PermohonanSuratSubmitted $event): void
    {
        try {
            $pesanTelegram = [
                '[nama_penduduk]' => $event->permohonan->penduduk->nama,
                '[judul_surat]'   => $event->surat->nama,
                '[tanggal]'       => tgl_indo2(date('Y-m-d H:i:s')),
                '[melalui]'       => 'Layanan Mandiri',
                '[website]'       => APP_URL,
            ];

            $kirimPesan = setting('notifikasi_pengajuan_surat');
            $kirimPesan = str_replace(array_keys($pesanTelegram), array_values($pesanTelegram), $kirimPesan);

            $telegram = new Telegram(setting('telegram_token'));
            $telegram->sendMessage([
                'text'       => $kirimPesan,
                'parse_mode' => 'Markdown',
                'chat_id'    => setting('telegram_user_id'),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }
}
