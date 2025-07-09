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

use App\Enums\PendidikanKKEnum;
use App\Models\KaderMasyarakat;
use App\Models\Penduduk;
use App\Models\RefPendudukBidang;
use App\Models\RefPendudukKursus;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_kader extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-pembangunan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['selectedNav'] = 'kader';
        $data['subtitle']    = 'Buku Kader Pemberdayaan';
        $data['mainContent'] = 'admin.bumindes.pembangunan.kader.index';

        return view('admin.bumindes.pembangunan.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('bumindes_kader.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('bumindes_kader.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('umur', static fn ($row): string => usia($row->penduduk->tanggallahir, null, '%y'))
                ->editColumn('pendidikan', static fn ($row) => PendidikanKKEnum::valueOf($row->penduduk->pendidikan_kk_id) . '</br>' . preg_replace('/[^a-zA-Z, ]/', '', $row->kursus))
                ->editColumn('bidang', static fn ($row): array|string|null => preg_replace('/[^a-zA-Z, ]/', '', $row->bidang))
                ->orderColumn('umur', static function ($query, $order): void {
                     $query->whereHas('penduduk', static fn ($q) => $q->orderBy('tanggallahir', $order));
                })
                ->rawColumns(['ceklist', 'aksi', 'pendidikan'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        return KaderMasyarakat::select([
            'kader_pemberdayaan_masyarakat.id',
            'kader_pemberdayaan_masyarakat.penduduk_id',
            'kader_pemberdayaan_masyarakat.kursus',
            'kader_pemberdayaan_masyarakat.bidang',
            'kader_pemberdayaan_masyarakat.keterangan'])
            ->with(['penduduk']);
    }

    public function form($id = 0)
    {
        if ($id) {
            $data['main']       = $this->sumberData()->find($id) ?? show_404();
            $data['action']     = 'Ubah';
            $data['formAction'] = ci_route('bumindes_kader.update', $id);
            $penduduk_id        = KaderMasyarakat::where('id', '!=', $id)->get()->pluck('penduduk_id');
        } else {
            $data['main']       = null;
            $data['action']     = 'Tambah';
            $data['formAction'] = ci_route('bumindes_kader.create', $id);
            $penduduk_id        = KaderMasyarakat::get()->pluck('penduduk_id');
        }

        $data['daftar_penduduk'] = Penduduk::select(['id', 'nama', 'nik'])->whereNotIn('id', $penduduk_id)->get();

        return view('admin.bumindes.pembangunan.kader.form', $data);
    }

    public function get_kursus(): void
    {
        $nama   = $this->input->get('nama');
        $kursus = RefPendudukKursus::get()->pluck('nama')->toArray();
        $new    = [];
        if ($list_data = KaderMasyarakat::select('kursus')->get()->toArray()) {
            $list = [];

            foreach ($list_data as $value) {
                if ($value) {
                    $list[] = $value['kursus'];
                }
            }

            $list = preg_replace('/[^a-zA-Z, ]/', '', $list);

            foreach ($list as $value) {
                $exploded = explode(',', (string) $value);
                $exploded = array_map('trim', $exploded);
                $new      = array_merge($new, $exploded);
            }
        }

        $data = collect(array_filter(array_unique([...$kursus, ...$new])));

        $data = $data->filter(static fn ($item): bool => stripos((string) $item, (string) $nama) !== false);

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function get_bidang(): void
    {
        $nama   = $this->input->get('nama');
        $bidang = RefPendudukBidang::get()->pluck('nama')->toArray();
        $new    = [];
        if ($list_data = KaderMasyarakat::select('bidang')->get()->toArray()) {
            $list = [];

            foreach ($list_data as $value) {
                if ($value) {
                    $list[] = $value['bidang'];
                }
            }

            $list = preg_replace('/[^a-zA-Z, ]/', '', $list);

            foreach ($list as $value) {
                $exploded = explode(',', (string) $value);
                $exploded = array_map('trim', $exploded);
                $new      = array_merge(array_filter($new), $exploded);
            }
        }

        $data = collect(array_filter(array_unique([...$bidang, ...$new])));

        $data = $data->filter(static fn ($item): bool => stripos((string) $item, (string) $nama) !== false);

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function create(): void
    {
        isCan('u');

        if (KaderMasyarakat::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $update = KaderMasyarakat::findOrFail($id);

        $data = $this->validate($this->request);

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        if (KaderMasyarakat::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        if (KaderMasyarakat::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validate(array $request = []): array
    {
        $kursus = array_unique(explode(',', (string) $request['kursus']));
        $bidang = array_unique(explode(',', (string) $request['bidang']));

        return [
            'penduduk_id' => bilangan($request['penduduk_id']),
            'kursus'      => json_encode($kursus),
            'bidang'      => json_encode($bidang),
            'keterangan'  => alfanumerik_spasi($request['keterangan']),
        ];
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('bumindes_kader.cetak', $aksi);

        return view('admin.bumindes.pembangunan.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $query        = $this->sumberData();
        $data         = $this->modal_penandatangan();
        $data['aksi'] = $aksi;
        $data['main'] = $query->get();

        $data['tgl_cetak'] = $this->input->post('tgl_cetak');
        $data['file']      = 'Buku Mutasi Penduduk';
        $data['isi']       = 'admin.bumindes.pembangunan.kader.cetak';
        $data['letak_ttd'] = ['2', '2', '5'];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
