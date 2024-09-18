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

use App\Enums\AgamaEnum;
use App\Enums\BahasaEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\LogPenduduk;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_penduduk_induk extends Admin_Controller
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
        $data['selectedNav'] = 'induk';
        $data['subtitle']    = 'Buku Induk Penduduk';
        $data['tahun']       = LogPenduduk::tahun()->pluck('tahun');
        $data['mainContent'] = 'admin.bumindes.penduduk.induk.index';

        return view('admin.bumindes.penduduk.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->editColumn('sex', static fn ($row) => strtoupper(JenisKelaminEnum::valueOf($row->sex)))
                ->editColumn('status_kawin', static fn ($row) => strtoupper(in_array($row->status_kawin, [1, 2]) ? StatusKawinEnum::valueOf($row->status_kawin) : (($row->sex == 1) ? 'DUDA' : 'JANDA')))
                ->editColumn('tanggallahir', static fn ($row) => tgl_indo_out($row->tanggallahir))
                ->editColumn('agama', static fn ($row) => strtoupper(AgamaEnum::valueOf($row->agama_id)))
                ->editColumn('pendidikan', static fn ($row) => strtoupper(PendidikanKKEnum::valueOf($row->pendidikan_kk_id)))
                ->editColumn('bahasa', static fn ($row) => strtoupper(BahasaEnum::valueOf($row->bahasa_id)))
                ->editColumn('warganegara', static fn ($row) => strtoupper(WargaNegaraEnum::valueOf($row->warganegara_id)))
                ->editColumn('kk_level', static fn ($row) => strtoupper(SHDKEnum::valueOf($row->kk_level)))
                ->editColumn('alamat_wilayah', static fn ($row) => strtoupper($row->alamat_wilayah))
                ->editColumn('nik', static fn ($row) => '<a href="' . ci_route('penduduk.detail.1.0', $row->id) . '">' . $row->nik . '</a>')
                ->editColumn('kk', static fn ($row) => '<a href="' . ci_route('keluarga.kartu_keluarga.1.0', $row->id_kk) . '">' . $row->keluarga->no_kk . '</a>')
                ->rawColumns(['nik', 'kk'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $filters = [
            'tahun' => $this->input->get('tahun') ?? null,
            'bulan' => $this->input->get('bulan') ?? null,
        ];

        return Penduduk::with(['log_latest'])
            ->statusPenduduk(StatusPendudukEnum::TETAP)
            ->statusDasar([StatusDasarEnum::HIDUP, StatusDasarEnum::HILANG])
            ->filterLog($filters);
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('bumindes_penduduk_induk.cetak', $aksi);

        return view('admin.bumindes.penduduk.induk.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);
        $_GET           = $paramDatatable;
        $query          = $this->sumberData();
        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }

        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['main']        = $query->take($paramDatatable['length'])->get();
        $data['config']      = $this->header['desa'];
        $data['tgl_cetak']   = $this->input->post('tgl_cetak');
        $data['privasi_nik'] = $this->input->post('privasi_nik') ?? null;
        $data['file']        = 'Buku Induk Kependudukan';
        $data['isi']         = 'admin.bumindes.penduduk.induk.cetak';
        $data['letak_ttd']   = ['2', '2', '9'];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
