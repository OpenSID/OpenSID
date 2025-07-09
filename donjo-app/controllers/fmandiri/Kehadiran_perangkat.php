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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
    public function index()
    {
        return view('layanan_mandiri.kehadiran.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $order = $this->input->get('order') ?? false;

            $query = Pamong::with([
                'penduduk',
                'jabatan',
                'kehadiranPerangkat' => static function ($query): void {
                    $query->where(static function ($query): void {
                        $query->where('tanggal', DB::raw('curdate()'))
                            ->orWhereNull('tanggal');
                    });
                },
                'kehadiranPengaduan',
            ])
                ->when(! $order, static function ($query): void {
                    $query->urut();
                })
                ->aktif()
                ->where('kehadiran', 1);

            return datatables($query)
                ->addIndexColumn()
                ->addColumn('status_kehadiran', static fn ($item) => $item?->kehadiranPerangkat?->last()?->status_kehadiran ?? '-')
                ->addColumn('aksi', function ($item) {
                    if ($item?->kehadiranPerangkat?->last()?->status_kehadiran == 'hadir' && setting('tampilkan_kehadiran') == '1') {
                        if ($item->id_penduduk == $this->session->is_login->id_pend && date('Y-m-d', strtotime($item?->kehadiranPengaduan?->last()?->waktu)) === date('Y-m-d')) {
                            return "<a class='btn btn-primary btn-sm btn-proses btn-social'><i class='fa fa-exclamation'></i> Telah dilaporkan</a> ";
                        }
                            $url = base_url("layanan-mandiri/kehadiran/lapor/{$item->pamong_id}");

                            return "<a href='#' data-href='{$url}' class='btn btn-primary btn-sm btn-social' title='Laporkan perangkat desa' data-toggle='modal' data-target='#confirm-delete'><i class='fa fa-exclamation'></i> Laporkan</a>";

                    }
                })
                ->rawColumns(['status_kehadiran', 'aksi'])
                ->make();
        }

        return show_404();
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
