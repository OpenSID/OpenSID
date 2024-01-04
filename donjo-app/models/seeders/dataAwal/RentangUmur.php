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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class RentangUmur extends CI_Model
{
    public function getData()
    {
        return [
            [
                'nama'   => 'BALITA',
                'dari'   => 0,
                'sampai' => 5,
                'status' => 0,
            ],
            [
                'nama'   => 'ANAK-ANAK',
                'dari'   => 6,
                'sampai' => 17,
                'status' => 0,
            ],
            [
                'nama'   => 'DEWASA',
                'dari'   => 18,
                'sampai' => 30,
                'status' => 0,
            ],
            [
                'nama'   => 'TUA',
                'dari'   => 31,
                'sampai' => 99999,
                'status' => 0,
            ],
            [
                'nama'   => 'Di bawah 1 Tahun',
                'dari'   => 0,
                'sampai' => 1,
                'status' => 1,
            ],
            [
                'nama'   => '2 s/d 4 Tahun',
                'dari'   => 2,
                'sampai' => 4,
                'status' => 1,
            ],
            [
                'nama'   => '5 s/d 9 Tahun',
                'dari'   => 5,
                'sampai' => 9,
                'status' => 1,
            ],
            [
                'nama'   => '10 s/d 14 Tahun',
                'dari'   => 10,
                'sampai' => 14,
                'status' => 1,
            ],
            [
                'nama'   => '15 s/d 19 Tahun',
                'dari'   => 15,
                'sampai' => 19,
                'status' => 1,
            ],
            [
                'nama'   => '20 s/d 24 Tahun',
                'dari'   => 20,
                'sampai' => 24,
                'status' => 1,
            ],
            [
                'nama'   => '25 s/d 29 Tahun',
                'dari'   => 25,
                'sampai' => 29,
                'status' => 1,
            ],
            [
                'nama'   => '30 s/d 34 Tahun',
                'dari'   => 30,
                'sampai' => 34,
                'status' => 1,
            ],
            [
                'nama'   => '35 s/d 39 Tahun ',
                'dari'   => 35,
                'sampai' => 39,
                'status' => 1,
            ],
            [
                'nama'   => '40 s/d 44 Tahun',
                'dari'   => 40,
                'sampai' => 44,
                'status' => 1,
            ],
            [
                'nama'   => '45 s/d 49 Tahun',
                'dari'   => 45,
                'sampai' => 49,
                'status' => 1,
            ],
            [
                'nama'   => '50 s/d 54 Tahun',
                'dari'   => 50,
                'sampai' => 54,
                'status' => 1,
            ],
            [
                'nama'   => '55 s/d 59 Tahun',
                'dari'   => 55,
                'sampai' => 59,
                'status' => 1,
            ],
            [
                'nama'   => '60 s/d 64 Tahun',
                'dari'   => 60,
                'sampai' => 64,
                'status' => 1,
            ],
            [
                'nama'   => '65 s/d 69 Tahun',
                'dari'   => 65,
                'sampai' => 69,
                'status' => 1,
            ],
            [
                'nama'   => '70 s/d 74 Tahun',
                'dari'   => 70,
                'sampai' => 74,
                'status' => 1,
            ],
            [
                'nama'   => 'Di atas 75 Tahun',
                'dari'   => 75,
                'sampai' => 99999,
                'status' => 1,
            ],
        ];
    }
}
