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

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\LogHapusPenduduk;
use App\Models\LogPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_penduduk_mutasi extends Admin_Controller
{
    public $modul_ini           = 'buku-administrasi-desa';
    public $sub_modul_ini       = 'administrasi-penduduk';
    public $kategori_pengaturan = 'Data Lengkap';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['selectedNav'] = 'mutasi';
        $data['subtitle']    = 'Buku Mutasi Penduduk Desa';
        $data['tahun']       = LogPenduduk::tahun()->pluck('tahun');
        $data['mainContent'] = 'admin.bumindes.penduduk.mutasi.index';
        $data['hapus']       = LogHapusPenduduk::with(['penduduk.log_latest'])->data()->count();

        return view('admin.bumindes.penduduk.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->editColumn('sex', static fn ($row): string => strtoupper((string) JenisKelaminEnum::valueOf($row->penduduk->sex)))
                ->editColumn('tanggallahir', static fn ($row) => tgl_indo_out($row->penduduk->tanggallahir))
                ->editColumn('warganegara', static fn ($row): string => strtoupper((string) WargaNegaraEnum::valueOf($row->penduduk->warganegara_id)))
                ->editColumn('alamat_sebelumnya', static fn ($row) => $row->kode_peristiwa == LogPenduduk::BARU_PINDAH_MASUK ? $row->penduduk->alamat_sebelumnya : '-')
                ->editColumn('tanggal_sebelumnya', static fn ($row) => $row->kode_peristiwa == LogPenduduk::BARU_PINDAH_MASUK ? tgl_indo_out($row->penduduk->created_at) : '-')
                ->editColumn('alamat_tujuan', static fn ($row) => $row->kode_peristiwa == LogPenduduk::PINDAH_KELUAR ? $row->alamat_tujuan : '-')
                ->editColumn('tanggal_tujuan', static fn ($row) => $row->kode_peristiwa == LogPenduduk::PINDAH_KELUAR ? tgl_indo_out($row->tgl_peristiwa) : '-')
                ->editColumn('meninggal_di', static fn ($row) => $row->kode_peristiwa == LogPenduduk::MATI ? $row->meninggal_di : '-')
                ->editColumn('tanggal_meninggal', static fn ($row) => $row->kode_peristiwa == LogPenduduk::MATI ? tgl_indo_out($row->tgl_peristiwa) : '-')
                ->editColumn('ket', static fn ($row): string => $row->penduduk->created_at ? ($row->catatan ? strtoupper($row->catatan) : '-') : ('Penduduk sudah dihapus.'))
                ->make();
        }

        return show_404();
    }

    public function datatablesHapus()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(LogHapusPenduduk::with(['penduduk.log_latest'])->data()
                ->where('deleted_at', '>', 'penduduk.log_latest.created_at'))
                ->addIndexColumn()
                ->editColumn('deleted_at', static fn ($row) => tgl_indo_out($row->deleted_at))
                ->editColumn('catatan', static fn ($row) => $row->penduduk->log_latest->catatan ?: 'Data dihapus karena salah pengisian.')
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $tahun = $this->input->get('tahun') ?? null;
        $bulan = $this->input->get('bulan') ?? null;

        return LogPenduduk::with(['penduduk'])
            ->whereIn('kode_peristiwa', [LogPenduduk::MATI, LogPenduduk::PINDAH_KELUAR, LogPenduduk::BARU_PINDAH_MASUK])
            ->whereHas('penduduk', static function ($query): void {
                $query->where('status', StatusPendudukEnum::TETAP);
            })
            ->orderByDesc('tgl_lapor')
            ->when($tahun, static fn ($q) => $q->whereYear('tgl_lapor', $tahun))
            ->when($bulan, static fn ($q) => $q->whereMonth('tgl_lapor', $bulan));
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('bumindes_penduduk_mutasi.cetak', $aksi);

        return view('admin.bumindes.penduduk.mutasi.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $paramDatatable = json_decode((string) $this->input->post('params'), 1);
        $_GET           = $paramDatatable;
        $query          = $this->sumberData();
        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }

        $data         = $this->modal_penandatangan();
        $data['aksi'] = $aksi;
        $data['main'] = $query->take($paramDatatable['length'])->get();

        $data['tgl_cetak'] = $this->input->post('tgl_cetak');
        $data['file']      = 'Buku Mutasi Penduduk';
        $data['isi']       = 'admin.bumindes.penduduk.mutasi.cetak';
        $data['letak_ttd'] = ['1', '2', '8'];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
