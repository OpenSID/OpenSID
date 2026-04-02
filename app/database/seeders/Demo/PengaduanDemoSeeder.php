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

namespace Database\Seeders\Demo;

use App\Models\Pengaduan;
use App\Models\User;
use App\Notifications\Pengaduan\PengaduanBaru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Gate;

class PengaduanDemoSeeder extends Seeder
{
    /**
     * Data pengaduan dummy untuk keperluan demo / testing notifikasi.
     * Setiap pengaduan dibuat dengan status "Menunggu Diproses" dan
     * notifikasi belum dibaca (read_at = null) dikirim ke semua admin
     * yang memiliki akses modul pengaduan.
     */
    public function run(): void
    {
        $data = [
            [
                'nama'  => 'Budi Santoso',
                'judul' => 'Jalan rusak di RT 03',
                'isi'   => 'Jalan di depan RT 03 sudah rusak parah dan sangat membahayakan warga. Mohon segera diperbaiki.',
            ],
            [
                'nama'  => 'Siti Rahayu',
                'judul' => 'Lampu jalan mati',
                'isi'   => 'Lampu jalan di sepanjang jalan utama sudah mati selama 2 minggu. Warga merasa tidak aman saat malam hari.',
            ],
            [
                'nama'  => 'Ahmad Fauzi',
                'judul' => 'Drainase tersumbat',
                'isi'   => 'Saluran drainase di dekat pasar tersumbat dan menyebabkan banjir kecil saat hujan deras.',
            ],
        ];

        $users = User::status()->get()->filter(
            static fn (User $user) => Gate::forUser($user)->allows('pengaduan:b', ['b', 'pengaduan', false, false])
        );

        foreach ($data as $item) {
            $pengaduan = Pengaduan::create([
                'nama'       => $item['nama'],
                'judul'      => $item['judul'],
                'isi'        => $item['isi'],
                'ip_address' => '127.0.0.1',
                'status'     => 1, // MENUNGGU_DIPROSES
            ]);

            // Kirim notifikasi belum dibaca ke semua admin yang punya akses pengaduan
            $users->each(static function (User $user) use ($pengaduan): void {
                $user->notify(new PengaduanBaru(pengaduan: $pengaduan));
            });
        }

        $this->command->info('PengaduanDemoSeeder: ' . count($data) . ' pengaduan + notifikasi berhasil dibuat.');
    }
}
