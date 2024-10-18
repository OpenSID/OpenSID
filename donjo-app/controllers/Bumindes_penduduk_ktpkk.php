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

class Bumindes_penduduk_ktpkk extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-penduduk';

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $this->load->model(['pamong_model', 'penduduk_model']);
    }

    public function index(): void
    {
        $list_tahun = LogPenduduk::tahun()->pluck('tahun')->min() ?? date('Y');
        $data_tahun = [];

        for ($nYear = date('Y'); $nYear >= (int) $list_tahun; $nYear--) {
            $data_tahun[]['tahun'] = $nYear;
        }
        $data = [
            'mainContent' => 'admin.bumindes.penduduk.ktpkk.index',
            'subtitle'    => 'Buku KTP dan KK',
            'selectedNav' => 'ktpkk',
            'func'        => 'index',
            'controller'  => $this->controller,
            'list_tahun'  => $data_tahun,
        ];

        view('admin.bumindes.penduduk.index', $data);
    }

    public function datatables()
    {
        $data = $this->sumberData();
        if ($this->input->is_ajax_request()) {
            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('sex', static fn ($row): string => strtoupper(substr(JenisKelaminEnum::valueOf($row->sex), 0, 1)))
                ->editColumn('status_kawin', static fn ($row): string => strtoupper(in_array($row->status_kawin, [1, 2]) ? StatusKawinEnum::valueOf($row->status_kawin) : (($row->sex == 1) ? 'DUDA' : 'JANDA')))
                ->editColumn('tanggallahir', static fn ($row): string => strtoupper($row->tempatlahir) . ', ' . tgl_indo_out($row->tanggallahir))
                ->editColumn('agama', static fn ($row): string => strtoupper(AgamaEnum::valueOf($row->agama_id)))
                ->editColumn('pendidikan', static fn ($row): string => strtoupper(PendidikanKKEnum::valueOf($row->pendidikan_kk_id)))
                ->editColumn('pekerjaan', static fn ($row): string => strtoupper($row->pekerjaan->nama ?? '-'))
                ->editColumn('warganegara', static fn ($row): string => strtoupper(WargaNegaraEnum::valueOf($row->warganegara_id)))
                ->editColumn('kk_level', static fn ($row): string => strtoupper(SHDKEnum::valueOf($row->kk_level)))
                ->editColumn('golongan_darah', static fn ($row): string => strtoupper($row->golonganDarah->nama))
                ->editColumn('alamat_wilayah', static fn ($row): string => strtoupper($row->alamat . ' RT ' . $row->rt . ' / RW ' . $row->rw . ' ' . setting('sebutan_dusun') . ' ' . $row->dusun))
                ->editColumn('kk', static fn ($row) => $row->keluarga->no_kk)
                ->editColumn('tgl_keluar', static fn ($row): string => $row->tempat_cetak_ktp ? strtoupper($row->tempat_cetak_ktp) . ', ' . tgl_indo_out($row->tanggal_cetak_ktp) : '-')
                ->editColumn('tgl_datang', static fn ($row) => tgl_indo_out($row->log_latest->tgl_lapor))
                ->rawColumns(['nik', 'kk'])
                ->make();
        }

        return show_404();
    }

    public function dialog_cetak($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('bumindes_penduduk_ktpkk.cetak', $aksi);

        return view('admin.bumindes.penduduk.induk.dialog', $data);
    }

    private function sumberData()
    {
        $filters = [
            'tahun' => $this->input->get('tahun') ?? null,
            'bulan' => $this->input->get('bulan') ?? null,
        ];

        return Penduduk::with(['log_latest', 'keluarga'])
            ->urut()
            ->statusPenduduk(StatusPendudukEnum::TETAP)
            ->statusDasar([StatusDasarEnum::HIDUP])
            ->filterLog($filters);
    }

    public function cetak($aksi = '')
    {

        $paramDatatable = json_decode($this->input->post('params'), 1);
        $_GET           = $paramDatatable;
        $query          = $this->sumberData();

        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }

        $collected = collect($query->take($paramDatatable['length'])->get())->map(static function (array $row): array {
            $row['sex']            = strtoupper(substr(JenisKelaminEnum::valueOf($row->sex), 0, 1));
            $row['status_kawin']   = strtoupper(in_array($row->status_kawin, [1, 2]) ? StatusKawinEnum::valueOf($row->status_kawin) : (($row->sex == 1) ? 'DUDA' : 'JANDA'));
            $row['tanggallahir']   = tgl_indo_out($row['tanggallahir']);
            $row['agama']          = strtoupper(AgamaEnum::valueOf($row->agama_id));
            $row['pendidikan']     = strtoupper(PendidikanKKEnum::valueOf($row->pendidikan_kk_id));
            $row['pekerjaan']      = strtoupper($row->pekerjaan->nama ?? '-');
            $row['warganegara']    = strtoupper(WargaNegaraEnum::valueOf($row->warganegara_id));
            $row['kk_level']       = strtoupper(SHDKEnum::valueOf($row->kk_level));
            $row['golongan_darah'] = strtoupper($row->golonganDarah->nama);
            $row['alamat_wilayah'] = strtoupper($row->alamat . ' RT ' . $row->rt . ' / RW ' . $row->rw . ' ' . setting('sebutan_dusun') . ' ' . $row->dusun);
            $row['kk']             = $row->keluarga->no_kk;
            $row['tgl_keluar']     = $row->tempat_cetak_ktp ? strtoupper($row->tempat_cetak_ktp) . ', ' . tgl_indo_out($row->tanggal_cetak_ktp) : '-';
            $row['tgl_datang']     = tgl_indo_out($row->log_latest->tgl_lapor) ?? '-';

            return $row;
        });

        $data              = $this->modal_penandatangan();
        $data['aksi']      = $aksi;
        $data['main']      = $collected;
        $data['config']    = $this->header['desa'];
        $data['tgl_cetak'] = $this->input->post('tgl_cetak');
        $data['file']      = 'Buku KTP dan KK';
        $data['isi']       = 'admin.bumindes.penduduk.ktpkk.cetak';
        $data['letak_ttd'] = ['2', '2', '9'];

        $data['privasi_nik'] = $this->input->post('privasi_nik') ?? null;

        return view('admin.layouts.components.format_cetak', $data);
    }
}
