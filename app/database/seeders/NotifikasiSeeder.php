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

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode'  => 'persetujuan_penggunaan',
                'judul' => '<i class="fa fa-file-text-o text-black"></i> &nbsp;Persetujuan Penggunaan OpenSID',
                'jenis' => 'persetujuan',
                'isi'   => '<p><b>Untuk menggunakan OpenSID, Anda dan desa Anda perlu menyetujui ketentuan berikut:</b>
                    <ol>
                        <li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a>.</li>
                        <li>OpenSID gratis dan disediakan "SEBAGAIMANA ADANYA", di mana segala tanggung jawab termasuk keamanan data desa ada pada pengguna.</li>
                        <li>Pengguna paham bahwa setiap ubahan OpenSID juga berlisensi GPL V3 yang tidak dapat dimusnahkan.</li>
                        <li>OpenSID akan mengirim data penggunaan ke server OpenDesa secara berkala.</li>
                    </ol></p>
                    <b>Apakah Anda dan desa Anda setuju?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2022-03-01 04:16:23',
                'updated_at'     => '2021-12-01 04:16:23',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'notif/update_pengumuman,siteman/logout',
                'aktif'          => 1,
            ],
            [
                'kode'  => 'tracking_off',
                'judul' => '<i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Peringatan Tracking Off',
                'jenis' => 'peringatan',
                'isi'   => '<p>Kami mendeteksi bahwa Anda telah mematikan fitur tracking.</p>
                    <br><b>Hidupkan kembali tracking?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2020-07-30 03:37:42',
                'updated_at'     => '2020-07-30 10:37:03',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'setting/aktifkan_tracking,notif/update_pengumuman',
                'aktif'          => 0,
            ],
        ];

        foreach ($data as $item) {
            Notifikasi::updateOrCreate(
                [
                    'kode'  => $item['kode'],
                    'jenis' => $item['jenis'],
                ],
                $item
            );
        }
    }
}
