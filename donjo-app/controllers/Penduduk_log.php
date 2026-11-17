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

use App\Enums\AgamaEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PeristiwaPendudukEnum;
use App\Enums\PindahEnum;
use App\Enums\StatusDasarEnum;
use App\Models\LogPenduduk;
use App\Models\RentangUmur;
use App\Models\Wilayah;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Penduduk_log extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'kependudukan';
    public $sub_modul_ini = 'peristiwa';
    private $pertanyaan   = 'Apakah Anda yakin ingin mengembalikan status data penduduk ini?<br> Perubahan ini akan mempengaruhi laporan penduduk bulanan.';
    private $judulStatistik;
    private $statistikFilter = [];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $tglLaporAwal  = LogPenduduk::whereNotNull('tgl_lapor')->min('tgl_lapor');
        $defaultFilter = [];

        if ($this->statistikFilter) {
            $defaultFilter = $this->statistikFilter;
        }
        $data['tahun_log_pertama']    = $tglLaporAwal ? (Carbon::createFromFormat('Y-m-d H:i:s', $tglLaporAwal))->format('Y') : date('Y');
        $data['list_jenis_peristiwa'] = LogPenduduk::kodePeristiwa();
        $data['list_sex']             = JenisKelaminEnum::all();
        $data['list_agama']           = AgamaEnum::all();
        $data['wilayah']              = Wilayah::treeAccess();
        $data['defaultFilter']        = $defaultFilter;
        $data['statistikFilter']      = $this->statistikFilter;
        $data['judul_statistik']      = $this->judulStatistik;
        $data['pertanyaan']           = $this->pertanyaan;

        view('admin.penduduk_log.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $dataLengkap = data_lengkap();
            $pertanyaan  = $this->pertanyaan;
            $ubah        = can('u');

            return datatables()->of($this->sumberData())
                ->addColumn('ceklist', static fn ($row) => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>')
                ->addColumn('foto', static fn ($row) => '<img class="penduduk_kecil" src="' . AmbilFoto($row->penduduk->foto, '', $row->penduduk->sex) . '" alt="Foto Penduduk" />')->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($dataLengkap, $pertanyaan, $ubah) {
                    if ($ubah) {
                        $aksi = View::make('admin.layouts.components.buttons.edit', [
                            'url'   => 'penduduk_log/edit/' . $row->id,
                            'modal' => true,
                        ])->render();
                        if (! in_array($row->kode_peristiwa, [PeristiwaPendudukEnum::BARU_LAHIR->value, PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value, PeristiwaPendudukEnum::TIDAK_TETAP_PERGI->value])) {
                            if ($dataLengkap) {
                                $aksi .= View::make('admin.layouts.components.buttons.btn', [
                                    'url'         => '#',
                                    'judul'       => 'Kembalikan Status',
                                    'icon'        => 'fa fa-undo',
                                    'type'        => 'bg-olive',
                                    'modal'       => true,
                                    'buttonOnly'  => true,
                                    'modalTarget' => 'confirm-status',
                                    'dataHref'    => ci_route("penduduk_log.kembalikan_status.{$row->id}"),
                                    'dataBody'    => $pertanyaan,
                                ])->render();

                                if ($row->isKembaliDatang() && $row->isLogPergiTerakhir() && in_array($row->penduduk->status_dasar, [StatusDasarEnum::PINDAH, StatusDasarEnum::PERGI])) {
                                    $aksi .= View::make('admin.layouts.components.buttons.btn', [
                                        'url'         => ci_route("penduduk_log.ajax_kembalikan_status_pergi.{$row->id}"),
                                        'judul'       => 'Kembalikan Penduduk',
                                        'icon'        => 'fa fa-angle-double-left',
                                        'type'        => 'bg-purple',
                                        'modal'       => true,
                                        'buttonOnly'  => true,
                                        'modalTarget' => 'modalBox',
                                    ])->render();
                                }
                            }
                        }
                    }

                    if ($row->kode_peristiwa == PeristiwaPendudukEnum::MATI->value) {
                        $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                            'url'   => ci_route("penduduk_log.dokumen.{$row->id}"),
                            'blank' => true,
                            'judul' => 'Lihat File Akta Kematian',
                        ])->render();
                    }

                    if ($ubah) {
                        switch ($row->kode_peristiwa) {
                            case PeristiwaPendudukEnum::BARU_LAHIR->value:
                                $suratTerkait = json_decode(setting('surat_kelahiran_terkait_penduduk'), 1);
                                break;

                            case PeristiwaPendudukEnum::MATI->value:
                                $suratTerkait = json_decode(setting('surat_kematian_terkait_penduduk'), 1);
                                break;

                            case PeristiwaPendudukEnum::PINDAH_KELUAR->value:
                                $suratTerkait = json_decode(setting('surat_pindah_keluar_terkait_penduduk'), 1);
                                break;

                            case PeristiwaPendudukEnum::HILANG->value:
                                $suratTerkait = json_decode(setting('surat_hilang_terkait_penduduk'), 1);
                                break;

                            case PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value:
                                $suratTerkait = json_decode(setting('surat_pindah_masuk_terkait_penduduk'), 1);
                                break;

                            case PeristiwaPendudukEnum::TIDAK_TETAP_PERGI->value:
                                $suratTerkait = json_decode(setting('surat_pergi_terkait_penduduk'), 1);
                                break;
                        }

                        if ($suratTerkait) {
                            foreach ($suratTerkait as $item) {
                                $aksi .= View::make('admin.layouts.components.buttons.btn', [
                                    'url' => ci_route("surat.form.{$item}")
                                                    . '#' . $row->penduduk->id
                                                    . '#' . $row->penduduk->nik
                                                    . '#' . $row->penduduk->nama,
                                    'judul'      => str_replace('-', ' ', $item),
                                    'icon'       => 'fa fa-file-word-o',
                                    'type'       => 'bg-purple',
                                    'blank'      => true,
                                    'buttonOnly' => false,
                                    'modal'      => false,
                                    'slug'       => true,
                                ])->render();
                            }
                        }
                    }

                    return $aksi;
                })->editColumn('status_menjadi', static fn ($q) => LogPenduduk::kodePeristiwaAll($q->kode_peristiwa))
                ->editColumn('tgl_peristiwa', static fn ($q) => tgl_indo($q->tgl_peristiwa))
                ->editColumn('tgl_lapor', static fn ($q) => tgl_indo($q->tgl_lapor))
                ->addColumn('umur', static fn ($q) => $q->penduduk->umur)
                ->addColumn('kepala_keluarga', static fn ($q) => $q->penduduk->keluarga->kepalaKeluarga->nama ?? '-')
                ->rawColumns(['aksi', 'ceklist', 'foto'])
                ->make();
        }

        return show_404();
    }

    public function dokumen($id): void
    {
        $log = LogPenduduk::findOrFail($id);

        // download file
        $this->load->helper('download');
        $file = $log->file_akta_mati;
        if ($file != '') {
            $path = LOKASI_DOKUMEN . $file;
            force_download($path, null);
        } else {
            show_404();
        }
    }

    public function edit($id): void
    {
        isCan('u');
        $data['log_status_dasar'] = LogPenduduk::with('penduduk')->findOrFail($id);
        $data['list_ref_pindah']  = PindahEnum::all();
        $data['sebab']            = unserialize(SEBAB);
        $data['penolong_mati']    = unserialize(PENOLONG_MATI);
        $data['form_action']      = ci_route("penduduk_log.update.{$id}");

        view('admin.penduduk_log.ajax_edit', $data);
    }

    public function update($id): void
    {
        isCan('u');
        $log             = LogPenduduk::findOrFail($id);
        $data['catatan'] = htmlentities($this->input->post('catatan'));
        if ($this->input->post('alamat_tujuan')) {
            $data['alamat_tujuan'] = htmlentities($this->input->post('alamat_tujuan'));
        }

        if ($this->input->post('ref_pindah')) {
            $data['ref_pindah'] = (int) $this->input->post('ref_pindah');
        }

        if ($this->input->post('meninggal_di')) {
            $data['meninggal_di'] = htmlentities($this->input->post('meninggal_di'));
        }

        if ($this->input->post('jam_mati')) {
            $data['jam_mati'] = htmlentities($this->input->post('jam_mati'));
        }

        if ($this->input->post('sebab')) {
            $data['sebab'] = (int) $this->input->post('sebab');
        }

        if ($this->input->post('penolong_mati')) {
            $data['penolong_mati'] = (int) $this->input->post('penolong_mati');
        }

        if ($this->input->post('akta_mati')) {
            $data['akta_mati'] = $this->input->post('akta_mati');
            if (! empty($_FILES['nama_file']['name'])) {
                $data['file_akta_mati'] = $this->uploadAktaMati($id);
            }
        }

        $penduduk = [];
        if ($this->input->post('anak_ke')) {
            $penduduk['kelahiran_anak_ke'] = (int) $this->input->post('anak_ke');
        }

        if ($this->input->post('alamat_sebelumnya')) {
            $penduduk['alamat_sebelumnya'] = htmlentities($this->input->post('alamat_sebelumnya'));
        }

        if ($penduduk) {
            $log->penduduk()->update($penduduk);
        }
        $data['tgl_peristiwa'] = rev_tgl($this->input->post('tgl_peristiwa'));
        $data['tgl_lapor']     = rev_tgl($this->input->post('tgl_lapor'), null);
        $data['updated_at']    = date('Y-m-d H:i:s');
        $data['updated_by']    = ci_auth()->id;

        $log->update($data);

        redirect_with('success', 'Berhasil ubah data catatan peristiwa');
    }

    public function kembalikan_status($id): void
    {
        isCan('u');

        if (! data_lengkap()) {
            show_404();
        }
        $log = LogPenduduk::findOrFail($id);
        DB::beginTransaction();

        try {
            $log->kembalikan_status();
            DB::commit();
            redirect_with('success', 'Berhasil mengembalikan status');
        } catch (Exception $e) {
            DB::rollBack();
            redirect_with('error', $e->getMessage());
        }
    }

    public function ajax_kembalikan_status_pergi($id): void
    {
        isCan('u');
        $data['log_status_dasar'] = LogPenduduk::findOrFail($id);
        $data['form_action']      = ci_route("penduduk_log.kembalikan_status_pergi.{$id}");

        view('admin.penduduk_log.ajax_edit_status_dasar_pergi', $data);
    }

    public function kembalikan_status_pergi($id): void
    {
        isCan('u');

        if (! data_lengkap()) {
            show_404();
        }
        $data = [
            'tgl_lapor'     => $this->input->post('tgl_lapor'),
            'tgl_peristiwa' => $this->input->post('tgl_peristiwa'),
            'maksud_tujuan' => $this->input->post('maksud_tujuan'),
        ];
        $log = LogPenduduk::findOrFail($id);

        try {
            $log->kembalikan_status_pergi($data);
            redirect_with('success', 'Berhasil mengembalikan status pergi');
        } catch (Exception $e) {
            redirect_with('error', $e->getMessage());
        }
    }

    public function kembalikan_status_all(): void
    {
        isCan('u');

        if (! data_lengkap()) {
            show_404();
        }
        $ids  = $this->input->post('id_cb') ?? [];
        $logs = LogPenduduk::whereIn('id', $ids)->get();
        DB::beginTransaction();

        try {
            foreach ($logs as $log) {
                $log->kembalikan_status();
            }
            DB::commit();
            redirect_with('success', 'Berhasil mengembalikan status');
        } catch (Exception $e) {
            DB::rollBack();
            redirect_with('error', $e->getMessage());
        }
    }

    public function cetak($aksi = 'cetak', $privasi_nik = 0): void
    {
        $query = datatables($this->sumberData())
            ->filter(function ($query) {
                $query->when($this->input->post('id_cb'), static function ($query, $ids) {
                    $query->whereIn('id', json_decode($ids));
                });
            });

        $data = [
            'main'  => $query->prepareQuery()->results(),
            'judul' => $this->input->post('judul'),
            'aksi'  => $aksi,
        ];
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=Log_Penduduk_' . date('Ymd') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.penduduk_log.cetak', $data);
    }

    public function ajax_cetak(string $aksi = 'cetak')
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route('penduduk_log.cetak', $aksi);

        return view('admin.layouts.components.ajax-cetak-bersama', $data);
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        $dusun                          = $this->input->get('dusun');
        $rw                             = $this->input->get('rw');
        $rt                             = $this->input->get('rt');
        $this->statistikFilter['sex']   = ($sex == 0) ? null : $sex;
        $judulJenisKelamin              = $sex ? ' - ' . JenisKelaminEnum::valueToUpper($sex) : '';
        $this->statistikFilter['dusun'] = $dusun;
        $this->statistikFilter['rw']    = $rw;
        $this->statistikFilter['rt']    = $rt;
        $this->statistikFilter['value'] = $nomor;
        if ((string) $tipe === 'akta-kematian') {
            $kategori                                = 'AKTA KEMATIAN : ';
            $this->statistikFilter['status_dasar']   = StatusDasarEnum::MATI;
            $this->statistikFilter['kode_peristiwa'] = PeristiwaPendudukEnum::MATI->value;
        }

        switch ($nomor) {
            case BELUM_MENGISI:
                $this->judulStatistik = $kategori . 'BELUM MENGISI';
                break;

            case TOTAL:
                $this->judulStatistik = $kategori . 'TOTAL';
                break;

            case JUMLAH:
                $this->judulStatistik = $kategori . 'JUMLAH';
                break;

            default:
                $judul = RentangUmur::find($nomor);
                if ($judul['nama']) {
                    $this->judulStatistik = $kategori . $judul['nama'];
                }
                break;
        }
        $this->judulStatistik .= $judulJenisKelamin;
        $this->index();
    }

    private function sumberData()
    {
        $kodePeristiwa   = $this->input->get('kode_peristiwa') ?? null;
        $bulan           = $this->input->get('bulan') ?? null;
        $tahun           = $this->input->get('tahun') ?? null;
        $sex             = $this->input->get('jenis_kelamin') ?? null;
        $dusun           = $this->input->get('dusun') ?? null;
        $rw              = $this->input->get('rw') ?? null;
        $rt              = $this->input->get('rt') ?? null;
        $agama           = $this->input->get('agama') ?? null;
        $statistikFilter = $this->input->get('statistikfilter') ?? null;

        if ($statistikFilter) {
            $dusun  = $statistikFilter['dusun'];
            $rw     = $statistikFilter['dusun'] . '__' . $statistikFilter['rw'];
            $namaRw = $statistikFilter['rw'];
            $namaRt = $statistikFilter['rt'];
            if ($namaRt) {
                $rt = Wilayah::whereDusun($dusun)->whereRw($namaRw)->whereRt($namaRt)->select(['id'])->first()->id;
            }
        }

        $idCluster = $rt ? [$rt] : [];

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        return LogPenduduk::with([
            'penduduk.keluarga.kepalaKeluarga', // Eager load nested untuk mencegah N+1 query
            'penduduk.keluarga.wilayah',        // Eager load untuk accessor getAlamatWilayahAttribute
            'penduduk.wilayah',                 // Eager load wilayah penduduk
            'penduduk.rtm',                     // Eager load rtm untuk accessor getLokasiAttribute
            'keluarga',
            'pergiTerakhir',
        ])
            ->when($kodePeristiwa, static fn ($r) => $r->whereKodePeristiwa($kodePeristiwa))
            ->when($tahun, static fn ($r) => $r->whereYear('tgl_lapor', $tahun))
            ->when($bulan, static fn ($r) => $r->whereMonth('tgl_lapor', $bulan))
            ->when($statistikFilter, static function ($q) use ($statistikFilter) {
                $kriteria = $statistikFilter['value'];

                switch ($kriteria) {
                    case TOTAL:
                        return $q;

                    case BELUM_MENGISI:
                        return $q->whereNull('file_akta_mati');

                    case JUMLAH:
                        return $q->whereNotNull('file_akta_mati');

                    default:
                        return $q->whereNotNull('file_akta_mati');
                }
            })
            ->whereHas(
                'penduduk',
                static function ($r) use ($idCluster, $sex, $agama, $statistikFilter) {
                    $r->when($idCluster, static fn ($s) => $s->whereIn('id_cluster', $idCluster))
                        ->when($agama, static fn ($s) => $s->whereAgamaId($agama))
                        ->when($sex, static fn ($s) => $s->whereSex($sex));

                    $kriteria = $statistikFilter['value'];

                    switch ($kriteria) {
                        case TOTAL:
                        case BELUM_MENGISI:
                        case JUMLAH:
                            // Untuk kasus khusus ini, logika bisa kamu tambahkan sendiri
                            break;

                        default:
                            $judul = RentangUmur::find($kriteria);

                            if ($judul && is_numeric($judul->dari) && is_numeric($judul->sampai)) {
                                $dari   = $judul->dari;
                                $sampai = $judul->sampai;

                                $r->whereRaw("(
                (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y') + 0)
                BETWEEN {$dari} AND {$sampai}
            )");
                            }
                            break;
                    }
                }
            );
    }

    private function uploadAktaMati($idLog)
    {
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 1024 * 10;
        $config['file_name']     = 'akta_mati_' . $idLog . '_' . time();
        $config['overwrite']     = true;

        return $this->upload('nama_file', $config);
    }
}
