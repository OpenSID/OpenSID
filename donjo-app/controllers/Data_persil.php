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

use App\Models\Area;
use App\Models\Cdesa;
use App\Models\Persil;
use App\Models\RefPersilKelas;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_persil extends Admin_Controller
{
    public $modul_ini       = 'pertanahan';
    public $sub_modul_ini   = 'daftar-persil';
    public $aliasController = 'data_persil';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['list_kelas'] = Persil::distinct('kelas')->with(['refKelas'])->get()->groupBy('refKelas.tipe');
        $data['wilayah']    = Wilayah::treeAccess();

        view('admin.pertanahan.persil.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canUpdate = can('u');
            $canDelete = can('h');

            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                    $aksi = '';
                    if ($row->mutasi_count) {
                        $aksi .= '<a href="' . ci_route('data_persil.rincian', $row->id) . '" class="btn bg-purple btn-sm" title="Rincian"><i class="fa fa-bars"></i></a> ';
                    } else {
                        $aksi .= '<a class="btn bg-purple btn-sm" disabled title="Rincian"><i class="fa fa-bars"></i></a> ';
                    }

                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route('data_persil.form', $row->id) . '" class="btn bg-orange btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }
                    if ($canDelete) {
                        if ($row->mutasi_count) {
                            $aksi .= '<a class="btn bg-maroon btn-sm" disabled><i class="fa fa-trash-o"></i></a>';
                        } else {
                            $aksi .= '<a href="#" data-href="' . ci_route('data_persil.delete', $row->id) . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('cdesa_awal', static fn ($row) => '<a href="' . ci_route("cdesa.mutasi.{$row->cdesa_awal}.{$row->id}") . '">' . $row->cdesa->nomor . '</a>')
                ->editColumn('kelas', static fn ($row) => $row->refKelas->kode)
                ->editColumn('lokasi', static fn ($row) => $row->wilayah ? $row->wilayah->alamat : ($row->lokasi ?? 'Lokasi Tidak Ditemukan'))
                ->editColumn('nomor', static fn ($row) => $row->nomor . ':' . $row->nomor_urut_bidang)
                ->rawColumns(['aksi', 'cdesa_awal'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $dusun   = $this->input->get('dusun') ?? null;
        $rw      = $this->input->get('rw') ?? null;
        $rt      = $this->input->get('rt') ?? null;
        $wilayah = $this->input->get('lokasi') ?? null;
        $kelas   = $this->input->get('kelas') ?? null;
        $tipe    = $this->input->get('tipe') ?? null;

        $idCluster = $rt ? [$rt] : [];
        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }
        $query = Persil::withCount('mutasi')->with(['refKelas', 'cdesa', 'wilayah'])
            ->when($kelas, static fn ($q) => $q->where('kelas', $kelas))
            ->when($tipe, static fn ($q) => $q->whereIn('kelas', static function ($r) use ($tipe) {
                $r->select('id')->from('ref_persil_kelas')->where('tipe', $tipe);
            }))
            ->when($wilayah, static function ($q) use ($wilayah) {
                if ($wilayah == 1) {
                    return $q->whereNotNull('id_wilayah');
                }
                if ($wilayah == 2) {
                    return $q->whereNull('id_wilayah');
                }
            })->when($idCluster, static fn ($q) => $q->whereIn('id_wilayah', $idCluster));

        return $query;
    }

    public function rincian($id): void
    {

        $data['desa']   = identitas();
        $data['persil'] = Persil::with(['refKelas', 'cdesa', 'wilayah', 'mutasi' => static fn ($q) => $q->with(['cdesaMasuk', 'cdesaKeluar'])])->findOrFail($id);

        view('admin.pertanahan.persil.rincian.index', $data);
    }

    public function form($id = '', $id_cdesa = ''): void
    {
        isCan('u');

        $data                = $this->navigasi_peta();
        $data['form_action'] = ci_route('data_persil.simpan');
        if ($id) {
            $persil              = Persil::findOrFail($id) ?? show_404();
            $data['persil']      = $persil;
            $data['form_action'] = ci_route('data_persil.simpan', $id);
            $data['tipe_tanah']  = $persil->refKelas->tipe;
        }
        if ($id_cdesa) {
            $data['id_cdesa'] = $id_cdesa;
        }

        $data['list_cdesa'] = Cdesa::with(['penduduk'])->orderByRaw('cast(nomor as unsigned)')->get();

        $data['persil_lokasi'] = Wilayah::get();
        $data['persil_kelas']  = RefPersilKelas::select(['id', 'tipe', 'kode', 'ndesc'])->get()->groupBy('tipe');
        $data['peta']          = Area::areaMap();

        view('admin.pertanahan.persil.form', $data);
    }

    public function simpan(): void
    {
        isCan('u');
        $data = static::validate();
        DB::beginTransaction();

        try {
            $id_persil = $this->request['id_persil'] ?: Persil::select('id')->where('nomor', $data['nomor'])->where('nomor_urut_bidang', $data['nomor_urut_bidang'])->first()?->id;
            if ($id_persil) {
                $persil = Persil::findOrFail($id_persil);
                $persil->update($data);
            } else {
                $data['cdesa_awal'] = bilangan($this->request['cdesa_awal']);
                $persil             = Persil::create($data);
                $persil->mutasi()->create($this->dataMutasi($data));
            }
            DB::commit();
            $cdesa_awal = $this->request['cdesa_awal'];
            if (! $this->request['id_persil'] && $cdesa_awal) {
                redirect_with('success', 'Data persil berhasil disimpan', ci_route("cdesa.mutasi.{$cdesa_awal}.{$persil->id}"));
            } else {
                redirect_with('success', 'Data persil berhasil ditambahkan');
            }

        } catch (Exception $e) {
            DB::rollBack();
            log_message('error', 'Gagal Tambah Data ' . $e->getMessage());
            redirect_with('error', 'Gagal Tambah Data ' . $e->getMessage());
        }
    }

    public function dialog_cetak($aksi = ''): void
    {
        $data               = $this->modal_penandatangan();
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('data_persil.cetak', $aksi);
        view('admin.layouts.components.dialog_cetak', $data);
    }

    public function cetak($aksi = ''): void
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);
        $_GET           = $paramDatatable;

        $data                 = $this->modal_penandatangan();
        $data['aksi']         = $aksi;
        $data['persil']       = $this->sumberData()->get();
        $data['persil_kelas'] = RefPersilKelas::get()->keyBy('id')->toArray();

        //pengaturan data untuk format cetak/ unduh
        $data['file'] = 'Persil';
        $data['isi']  = 'admin.pertanahan.persil.cetak';
        //colspan tepi, colspan ttd pertama, colspan jarak ke ttd kedua
        $data['letak_ttd'] = ['1', '2', '2'];
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=data_persil.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        view('admin.layouts.components.format_cetak', $data);
    }

    public function area_map()
    {
        if (! $this->input->is_ajax_request()) {
            exit('access restricted');
        }

        $id   = $this->input->get('id');
        $data = Area::findOrFail($id)->toArray();

        return json([
            'data'   => $data,
            'status' => true,
        ]);
    }

    public function delete($id): void
    {
        isCan('h');
        $persil = Persil::with('mutasi')->findOrFail($id);
        if ($persil->mutasi->count()) {
            redirect_with('error', 'Gagal hapus data, sudah ada data mutasi cdesa');
        }

        if ($persil->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    protected function validate()
    {
        $this->validated(request(), [
            'no_persil'         => 'required|numeric',
            'nomor_urut_bidang' => 'required|numeric|min:1|max:1000',
            'kelas'             => 'required|numeric',
        ]);
        $post                      = $this->request;
        $data['nomor']             = bilangan($post['no_persil']);
        $data['nomor_urut_bidang'] = bilangan($post['nomor_urut_bidang']);
        $data['kelas']             = $post['kelas'];
        $data['id_wilayah']        = $post['id_wilayah'] ?: null;
        $data['luas_persil']       = bilangan($post['luas_persil']) ?: null;
        $data['lokasi']            = $post['lokasi'] ?: null;
        $data['path']              = $post['path'];
        $data['is_publik']         = $post['is_publik'];
        $data['id_peta']           = ($post['area_tanah'] == 1 || $post['area_tanah'] == null) ? (empty($post['id_peta']) ? null : $post['id_peta']) : null;

        return $data;
    }

    private function dataMutasi($data)
    {
        $mutasi['id_cdesa_masuk'] = $data['cdesa_awal'];
        $mutasi['jenis_mutasi']   = '9';
        $mutasi['tanggal_mutasi'] = date('Y-m-d H:i:s');
        $mutasi['luas']           = $data['luas_persil'];
        $mutasi['keterangan']     = 'Pemilik awal persil ini';
        $mutasi['path']           = $data['path'];
        $mutasi['id_peta']        = ($data['area_tanah'] == 1 || $data['area_tanah'] == null) ? $data['id_peta'] : null;

        return $mutasi;
    }
}
