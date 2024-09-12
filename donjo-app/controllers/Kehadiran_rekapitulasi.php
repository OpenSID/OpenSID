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

use App\Models\Kehadiran;
use App\Models\Pamong;
use Illuminate\Support\Facades\DB;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

defined('BASEPATH') || exit('No direct script access allowed');

class Kehadiran_rekapitulasi extends Admin_Controller
{
    public $modul_ini           = 'kehadiran';
    public $sub_modul_ini       = 'rekapitulasi';
    public $kategori_pengaturan = 'kehadiran';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $pamong    = Pamong::daftar()->get();
        $kehadiran = Kehadiran::get();

        return view('admin.rekapitulasi.index', ['pamong' => $pamong, 'kehadiran' => $kehadiran]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $filters = [
                'tanggal' => $this->input->get('daterange'),
                'status'  => $this->input->get('status'),
                'pamong'  => $this->input->get('pamong'),
            ];

            return datatables()->of(Kehadiran::with(['pamong', 'pamong.penduduk', 'pamong.jabatan'])
                ->select('*', DB::raw('TIMEDIFF( jam_keluar, jam_masuk ) as total'))
                ->filter($filters))
                ->addIndexColumn()
                ->editColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->editColumn('jam_masuk', static fn ($row): string => date('H:i', strtotime($row->jam_masuk)))
                ->editColumn('jam_keluar', static fn ($row): string => $row->jam_keluar == null ? '-' : date('H:i', strtotime($row->jam_keluar)))
                ->editColumn('total', static fn ($row): string => date('H:i', strtotime($row->total)))
                ->editColumn('status_kehadiran', static function ($row): string {
                    $tipe = ($row->status_kehadiran == 'hadir') ? 'success' : (($row->status_kehadiran == 'tidak berada di kantor') ? 'danger' : 'warning');

                    return '<span class="label label-' . $tipe . '">' . ucwords($row->status_kehadiran) . ' </span>';
                })
                ->rawColumns(['status_kehadiran'])
                ->make();
        }

        return show_404();
    }

    public function ekspor(): void
    {
        $filters = [
            'tanggal' => $this->input->get('daterange'),
            'status'  => $this->input->get('status'),
            'pamong'  => $this->input->get('pamong'),
        ];

        $judul = [
            'Nama',
            'Jabatan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Total Waktu',
            'Status Kehadiran',
        ];

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser(namafile('kehadiran') . '.xlsx');
        $writer->addRow(WriterEntityFactory::createRowFromArray($judul));

        $data_kehadiran = Kehadiran::with(['pamong'])
            ->select('*', Kehadiran::raw('TIMEDIFF( jam_keluar, jam_masuk ) as total'))
            ->filter($filters)
            ->get();

        foreach ($data_kehadiran as $row) {
            $data = [
                $row->pamong->pamong_nama != null ? $row->pamong->pamong_nama : $row->pamong->penduduk->nama,
                $row->pamong->jabatan->nama,
                tgl_indo($row->tanggal),
                date('H:i', strtotime($row->jam_masuk)),
                $row->jam_keluar == null ? '-' : date('H:i', strtotime($row->jam_keluar)),
                date('H:i', strtotime($row->total)),
                ucwords($row->status_kehadiran),
            ];
            $writer->addRow(WriterEntityFactory::createRowFromArray($data));
        }
        $writer->close();
    }
}
