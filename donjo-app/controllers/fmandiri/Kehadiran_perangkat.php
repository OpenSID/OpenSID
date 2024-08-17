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

use App\Models\KehadiranPengaduan;
use App\Models\Pamong;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Kehadiran_perangkat extends Mandiri_Controller
{
    public function index(): void
    {
        $kehadiran = Pamong::kehadiranPamong()
            ->daftar()
            ->where(static function ($query): void {
                $query->where('tanggal', DB::raw('curdate()'))
                    ->orWhereNull('tanggal');
            })
            ->orderBy('urut')
            ->get();
        $perangkat = $kehadiran->each(function ($item) {
            if ($item->id_penduduk != $this->session->is_login->id_pend) {
                return $item->id_penduduk = 0;
            }

            return $item;
        })->values()->all();

        $this->render('kehadiran', ['perangkat' => $perangkat]);
    }

    public function lapor($id): void
    {
        $data = [
            'waktu'       => date('Y-m-d H:i:s'),
            'status'      => 1,
            'id_penduduk' => $this->session->is_login->id_pend,
            'id_pamong'   => $id,
        ];

        if (KehadiranPengaduan::create($data)) {
            redirect('layanan-mandiri/kehadiran');
        }

        redirect('layanan-mandiri/kehadiran');
    }
}
