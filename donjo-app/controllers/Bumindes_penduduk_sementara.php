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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\StatusDasarEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\LogPenduduk;
use App\Models\Penduduk;

class Bumindes_penduduk_sementara extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-penduduk';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['tahun'] = LogPenduduk::tahun()->pluck('tahun');

        return view('admin.bumindes.penduduk.sementara.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->editColumn('ttl', static fn ($row) => $row->tempatlahir . ', ' . tgl_indo_out($row->tanggallahir) . ' / ' . usia($row->tanggallahir))
                ->editColumn('warganegara', static fn ($row) => strtoupper(WargaNegaraEnum::valueOf($row->warganegara_id)))
                ->editColumn('alamat_wilayah', static fn ($row) => strtoupper($row->alamat_wilayah))
                ->editColumn('tanggal_datang', static fn ($row) => tgl_indo_out($row->log_latest->tgl_lapor))
                ->editColumn('tanggal_pergi', static fn ($row) => $row->log_latest->kode_peristiwa == 6 ? tgl_indo_out($row->log_latest->tgl_lapor) : null)
                ->make();
        }

        return show_404();
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']      = $aksi;
        $data['field_nik'] = false;
        $data['action']    = ci_route('bumindes_penduduk_sementara.cetak', $aksi);

        return view('admin.bumindes.penduduk.induk.dialog', $data);
    }

    public function cetak($aksi = 'cetak')
    {
        $data = [
            'aksi'    => $aksi,
            'main'    => datatables($this->sumberData())->prepareQuery()->results(),
            'start'   => app('datatables.request')->start(),
            'filters' => [
                'tahun' => request()->get('tahun'),
                'bulan' => request()->get('bulan'),
            ],
            'tgl_cetak'    => request()->get('tgl_cetak'),
            'privasi_nik'  => request()->get('privasi_nik'),
            'file'         => 'Buku Penduduk Sementara',
            'letak_ttd'    => ['2', '2', '9'],
            'is_landscape' => true,
        ];

        return view('admin.bumindes.penduduk.sementara.cetak', $data);
    }

    private function sumberData()
    {
        $filters = [
            'tahun' => $this->input->get('tahun') ?? null,
            'bulan' => $this->input->get('bulan') ?? null,
        ];

        return Penduduk::with(['log_latest'])
            ->statusPenduduk(StatusPendudukEnum::TIDAK_TETAP)
            ->statusDasar([StatusDasarEnum::HIDUP, StatusDasarEnum::PERGI])
            ->filterLog($filters);
    }
}
