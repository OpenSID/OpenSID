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

use App\Models\Kehadiran;
use App\Models\Pamong;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

defined('BASEPATH') || exit('No direct script access allowed');

class Admin_kehadiran extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 337;
        $this->sub_modul_ini = 341;
    }

    public function index()
    {
        $pamong = Pamong::all();

        return view('admin.kehadiran.index', compact('pamong'));
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $tanggal = $this->input->get('daterange');
            $status  = $this->input->get('status');
            $pamong  = $this->input->get('pamong');

            return datatables()->of(Kehadiran::with(['pamong'])->filter($tanggal, $status, $pamong))
                ->addIndexColumn()
                ->editColumn('nama', static function ($row) {
                    return $row['pamong']['pamong_nama'] != null ? $row['pamong']['pamong_nama'] : $row['pamong']['penduduk']['nama'];
                })
                ->editColumn('jabatan', static function ($row) {
                    return $row['pamong']['jabatan'];
                })
                ->editColumn('tanggal', static function ($row) {
                    return tgl_indo($row['tanggal']);
                })
                ->editColumn('jam_masuk', static function ($row) {
                    return date('H:i', strtotime($row['jam_masuk']));
                })
                ->editColumn('jam_pulang', static function ($row) {
                    return $row['jam_pulang'] == null ? '' : date('H:i', strtotime($row['jam_pulang']));
                })
                ->make();
        }

        return show_404();
    }

    public function export()
    {
        $tanggal = $this->input->get('daterange');
        $status  = $this->input->get('status');
        $pamong  = $this->input->get('pamong');

        $writer = WriterEntityFactory::createXLSXWriter();

        //Nama File
        $tgl      = date('d_m_Y');
        $fileName = 'kehadiran_' . $tgl . '.xlsx';
        $writer->openToBrowser($fileName); // stream data directly to the browser

        $daftar_kolom = [
            ['Nama', 'nama'],
            ['Jabatan', 'jabatan'],
            ['Tanggal', 'tanggal'],
            ['Jam Masuk', 'jam_masuk'],
            ['Jam Pulang', 'jam_pulang'],
            ['Status Kehadiran', 'status_kehadiran'],
        ];

        $judul  = array_column($daftar_kolom, 0);
        $header = WriterEntityFactory::createRowFromArray($judul);
        $writer->addRow($header);

        $get = Kehadiran::with(['pamong'])->filter($tanggal, $status, $pamong)->get();

        foreach ($get as $row) {
            $data = [
                $row->pamong->pamong_nama != null ? $row->pamong->pamong_nama : $row->pamong->penduduk->nama,
                $row->pamong->jabatan,
                $row->tanggal,
                $row->jam_masuk,
                $row->jam_pulang,
                $row->status_kehadiran,
            ];
            $rowFromValues = WriterEntityFactory::createRowFromArray($data);
            $writer->addRow($rowFromValues);
        }
        $writer->close();
    }
}
