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

use App\Enums\PendidikanKKEnum;
use App\Enums\PendudukBidangEnum;
use App\Enums\PendudukKursusEnum;
use App\Models\KaderMasyarakat;
use App\Models\Penduduk;
use Illuminate\Support\Facades\View;

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

                    $aksi = View::make('admin.layouts.components.buttons.edit', [
                        'url' => "bumindes_kader/form/{$row->id}",
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => route('bumindes_kader.delete', ['id' => $row->id]),
                        'confirmDelete' => true,
                    ])->render();

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
        $nama   = trim((string) $this->input->get('nama'));
        $kursus = PendudukKursusEnum::values();
        $new    = [];

        if ($list_data = KaderMasyarakat::select('kursus')->get()->toArray()) {

            foreach ($list_data as $row) {
                if (empty($row['kursus'])) {
                    continue;
                }

                // Bersihkan per string (BUKAN array)
                $clean = preg_replace('/[^a-zA-Z, ]/', '', $row['kursus']);

                $exploded = array_map(
                    'trim',
                    explode(',', $clean)
                );

                $new = array_merge($new, $exploded);
            }
        }

        // Gabungkan enum + data lama
        $data = collect(array_unique(array_filter([
            ...$kursus,
            ...$new,
        ])));

        // Filter pencarian (case-insensitive)
        if ($nama !== '') {
            $data = $data->filter(
                static fn ($item) => stripos($item, $nama) !== false
            );
        }

        // Format response untuk autocomplete
        $result = $data
            ->values()
            ->map(static fn ($item) => ['value' => $item])
            ->toArray();

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function get_bidang(): void
    {
        $nama   = trim((string) $this->input->get('nama'));
        $bidang = PendudukBidangEnum::values();
        $new    = [];

        if ($list_data = KaderMasyarakat::select('bidang')->get()->toArray()) {

            foreach ($list_data as $row) {
                if (empty($row['bidang'])) {
                    continue;
                }

                $clean = preg_replace('/[^a-zA-Z, ]/', '', $row['bidang']);

                $exploded = array_map(
                    'trim',
                    explode(',', $clean)
                );

                $new = array_merge($new, $exploded);
            }
        }

        $data = collect(array_unique(array_filter([
            ...$bidang,
            ...$new,
        ])));

        if ($nama !== '') {
            $data = $data->filter(
                static fn ($item) => stripos($item, $nama) !== false
            );
        }

        $result = $data
            ->values()
            ->map(static fn ($item) => ['value' => $item])
            ->toArray();

        header('Content-Type: application/json');
        echo json_encode($result);
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
}
