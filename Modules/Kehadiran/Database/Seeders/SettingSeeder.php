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

namespace Modules\Kehadiran\Database\Seeders;

use App\Enums\StatusEnum;
use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createSettings([
            [
                'judul'      => 'Tampilkan Kehadiran',
                'key'        => 'tampilkan_kehadiran',
                'value'      => StatusEnum::YA,
                'keterangan' => 'Aktif / Non-aktifkan Halaman Website Kehadiran',
                'jenis'      => 'select-boolean',
                'attribute'  => json_encode([
                    'class' => 'required',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'IP Adress Kehadiran',
                'key'        => 'ip_adress_kehadiran',
                'value'      => null,
                'keterangan' => 'IP Address Perangkat Kehadiran',
                'jenis'      => 'input-text',
                'attribute'  => json_encode([
                    'class'       => 'ip_address',
                    'placeholder' => '127.0.0.1',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'MAC Adress Kehadiran',
                'key'        => 'mac_adress_kehadiran',
                'value'      => null,
                'keterangan' => 'MAC Address Perangkat Kehadiran',
                'jenis'      => 'input-text',
                'attribute'  => json_encode([
                    'class'       => 'mac_address',
                    'placeholder' => '00:1B:44:11:3A:B7',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'Latar Kehadiran',
                'key'        => 'latar_kehadiran',
                'value'      => null,
                'keterangan' => 'Latar Kehadiran',
                'jenis'      => 'unggah',
                'kategori'   => 'Kehadiran',
            ],
            [
                'judul'      => 'Id Pengunjung Kehadiran',
                'key'        => 'id_pengunjung_kehadiran',
                'value'      => null,
                'keterangan' => 'ID Pengunjung Perangkat Kehadiran',
                'jenis'      => 'input-text',
                'attribute'  => json_encode([
                    'class'       => 'alfanumerik',
                    'placeholder' => 'ad02c373c2a8745d108aff863712fe92',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'Rentang Waktu Masuk',
                'key'        => 'rentang_waktu_masuk',
                'value'      => 10,
                'keterangan' => 'Rentang waktu kehadiran ketika masuk. (satuan: menit)',
                'jenis'      => 'input-number',
                'attribute'  => json_encode([
                    'class'       => 'required',
                    'min'         => 0,
                    'max'         => 3600,
                    'step'        => 1,
                    'placeholder' => '10',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'Rentang Waktu Keluar',
                'key'        => 'rentang_waktu_keluar',
                'value'      => 10,
                'keterangan' => 'Rentang waktu kehadiran ketika keluar. (satuan: menit)',
                'jenis'      => 'input-number',
                'attribute'  => json_encode([
                    'class'       => 'required',
                    'min'         => 0,
                    'max'         => 3600,
                    'step'        => 1,
                    'placeholder' => '10',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'Tampilkan Status Kehadiran Pada Hari Libur',
                'key'        => 'tampilkan_status_kehadiran_pada_hari_libur',
                'value'      => StatusEnum::YA,
                'keterangan' => 'Jika diaktifkan, status kehadiran perangkat desa akan tetap muncul di hari libur.',
                'jenis'      => 'select-boolean',
                'attribute'  => json_encode([
                    'class' => 'required',
                ]),
                'kategori' => 'Kehadiran',
            ],
            [
                'judul'      => 'Ikuti Hari Libur Terdaftar',
                'key'        => 'ikuti_hari_libur_terdaftar',
                'value'      => StatusEnum::TIDAK,
                'keterangan' => 'Jika diaktifkan, jam kerja akan otomatis berubah menjadi "Libur" ketika bertepatan dengan hari libur terdaftar.',
                'jenis'      => 'select-boolean',
                'attribute'  => json_encode([
                    'class' => 'required',
                ]),
                'kategori' => 'Kehadiran',
            ],
        ]);
    }
}
