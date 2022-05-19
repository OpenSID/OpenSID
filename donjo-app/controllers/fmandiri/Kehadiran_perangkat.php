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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\KehadiranPengaduan;
use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Kehadiran_perangkat extends Mandiri_Controller
{
    public function index()
    {
        $kehadiran = Pamong::kehadiranPamong()->daftar()->get();
        $kehadiran = $kehadiran->each(function ($item) {
            if ($item->id_penduduk != $this->session->is_login->id_pend) {
                return $item->id_penduduk = 0;
            }

            return $item;
        })
            ->sortBy([['tanggal', 'desc'], ['jam_masuk', 'desc'], ['id_penduduk', 'desc'], ['waktu', 'desc']])
            ->values()->all();

        $data = [
            'perangkat' => $kehadiran,
        ];

        $this->render('kehadiran', $data);
    }

    public function lapor($id)
    {
        $data = [
            'waktu'       => date('Y-m-d H:i:s'),
            'status'      => 1,
            'id_penduduk' => $this->session->is_login->id_pend,
            'id_pamong'   => $id,
        ];

        if (KehadiranPengaduan::insert($data)) {
            redirect('layanan-mandiri/kehadiran');
        }

        redirect('layanan-mandiri/kehadiran');
    }
}
