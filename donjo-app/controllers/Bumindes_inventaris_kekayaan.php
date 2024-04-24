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

use App\Models\MasterInventaris;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_inventaris_kekayaan extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {

        $data = [
            'subtitle'     => 'Buku Inventaris dan Kekayaan ' . ucwords($this->setting->sebutan_desa),
            'selected_nav' => 'inventaris',
            'main_content' => 'admin.dokumen.inventaris_kekayaan.table',
            'min_tahun'    => MasterInventaris::minTahun(),
        ];

        view('admin.bumindes.umum.main', $data);
    }

    private function sumberData($tahun = null)
    {
        return MasterInventaris::permen47($tahun);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
        $tahun = $this->input->get('tahun') ?? date('Y');

        return datatables()->of($this->sumberData($tahun))
            ->addIndexColumn()
            ->editColumn('keterangan', static function (array $row): string {
                $html = '';

                foreach ($row['keterangan'] as $ket) {
                    $html .= '<li>' . $ket . '</li>';
                }

                return $html;
            })
            ->editColumn('tgl_hapus', static fn ($row) => tgl_indo($row['tgl_hapus']))
            ->rawColumns(['aksi', 'keterangan'])
            ->make();
        }

        return show_404();
    }

    public function cetak($aksi = '')
    {
        $tahun             = date('Y');
        $query             = $this->sumberData($tahun);
        $data              = $this->modal_penandatangan();
        $data['aksi']      = $aksi;
        $data['main']      = $query;
        $data['config']    = $this->header['desa'];
        $data['bulan']     = date('m');
        $data['tahun']     = date('Y');
        $data['isi']       = 'admin.dokumen.inventaris_kekayaan.cetak';
        $data['letak_ttd'] = ['1', '1', '23'];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
