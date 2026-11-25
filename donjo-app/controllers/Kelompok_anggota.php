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
use App\Enums\JabatanKelompokEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\StatusDasarEnum;
use App\Http\Requests\Kelompok\KelompokAnggotaRequest;
use App\Models\Kelompok;
use App\Models\KelompokAnggota as KelompokAnggotaModel;
use App\Models\Pamong;
use App\Models\Penduduk;
use App\Traits\Upload;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Kelompok_anggota extends Admin_Controller
{
    use Upload;

    public $modul_ini       = 'kependudukan';
    public $sub_modul_ini   = 'kelompok';
    public $tipe            = 'kelompok';
    public $aliasController = 'kelompok';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        redirect($this->aliasController);
    }

    public function detail($id = 0): void
    {
        $data['func']              = 'anggota/' . $id;
        $data['controller']        = $this->controller;
        $data['tipe']              = ucwords((string) $this->tipe);
        $data['kelompok']          = Kelompok::tipe($this->tipe)->find($id) ?? show_404();
        $data['list_status_dasar'] = StatusDasarEnum::all();

        view('admin.kelompok.anggota.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $id_kelompok  = $this->input->post_get('id_kelompok');
            $status_dasar = $this->input->post_get('status_dasar'); // TAMBAHKAN INI
            $controller   = $this->controller;
            $tipe         = $this->tipe;

            $query = KelompokAnggotaModel::with('anggota')
                ->tipe($tipe)
                ->where('id_kelompok', '=', $id_kelompok)
                ->orderBy('jabatan');

            if ($status_dasar) {
                $query->where(static function ($q) use ($status_dasar) {
                    $q->whereHas('anggota', static function ($q2) use ($status_dasar) {
                        $q2->where('status_dasar', $status_dasar);
                    });
                    // Penduduk luar desa dianggap selalu Hidup
                    if ((int) $status_dasar === StatusDasarEnum::HIDUP) {
                        $q->orWhereNull('id_penduduk');
                    }
                });
            }

            return datatables()->of($query)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($controller): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= View::make('admin.layouts.components.buttons.edit', [
                            'url' => "{$controller}/form/" . $row->id_kelompok . '/' . $row->id,
                        ])->render();
                    }

                    if (can('h')) {
                        $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                            'url'           => route("{$controller}.delete", ['id_kelompok' => $row->id_kelompok, 'id' => $row->id]),
                            'confirmDelete' => true,
                        ])->render();
                    }

                    return $aksi;
                })
                ->editColumn('foto', static function ($row) use ($tipe): string {
                    $sex        = $row->id_penduduk === null ? $row->sex_luar : ($row->anggota ? $row->anggota->sex : null);
                    $foto       = $row->foto ?? ($row->anggota ? $row->anggota->foto : null);
                    $lokasiFoto = $row->foto && $tipe === 'kelompok'
                        ? LOKASI_FOTO_KELOMPOK
                        : ($row->foto ? LOKASI_FOTO_LEMBAGA : LOKASI_USER_PICT);

                    $urlFoto = AmbilFoto($foto, '', $sex, $lokasiFoto);

                    return '<img src="' . $urlFoto . '" alt="Foto Penduduk" class="img-circle" width="50px">';
                })
                ->editColumn('nik', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return $row->nik_luar ?? '-';
                    }

                    return $row->anggota ? $row->anggota->nik : '-';
                })
                ->editColumn('nama', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return strtoupper($row->nama_luar ?? '-');
                    }

                    return $row->anggota ? strtoupper($row->anggota->nama) : '-';
                })
                ->editColumn('alamat', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return $row->alamat_luar ?? '-';
                    }

                    return $row->anggota ? ($row->anggota->alamat_wilayah ?? '-') : '-';
                })
                ->editColumn('jk', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return JenisKelaminEnum::valueOf($row->sex_luar) ?: '-';
                    }

                    return $row->anggota ? JenisKelaminEnum::valueOf($row->anggota->sex) : '-';
                })
                ->editColumn('jabatan', static function ($row): string {
                    if ($row->jabatan != 90) {
                        return JabatanKelompokEnum::valueOf($row->jabatan) ?: strtoupper($row->jabatan);
                    }

                    return JabatanKelompokEnum::valueOf($row->jabatan);
                })
                ->editColumn('status_dasar', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return '<span class="label label-success">Hidup</span>';
                    }

                    if (! $row->anggota) {
                        return '<span class="label label-default">-</span>';
                    }

                    $status = StatusDasarEnum::valueOf($row->anggota->status_dasar);

                    switch($row->anggota->status_dasar) {
                        case StatusDasarEnum::HIDUP:
                            return '<span class="label label-success">' . $status . '</span>';

                        case StatusDasarEnum::MATI:
                            return '<span class="label label-danger">' . $status . '</span>';

                        case StatusDasarEnum::PINDAH:
                            return '<span class="label label-warning">' . $status . '</span>';

                        default:
                            return '<span class="label label-default">' . $status . '</span>';
                    }
                })
                ->editColumn('umur', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return $row->tanggallahir_luar ? usia($row->tanggallahir_luar, null, '%y') : '-';
                    }

                    return $row->anggota ? $row->anggota->umur : '-';
                })
                ->editColumn('tanggallahir', static function ($row): string {
                    if ($row->id_penduduk === null) {
                        return strtoupper($row->tempatlahir_luar ?? '-') . ' / ' . strtoupper((string) tgl_indo($row->tanggallahir_luar));
                    }

                    return $row->anggota
                        ? strtoupper($row->anggota->tempatlahir) . ' / ' . strtoupper((string) tgl_indo($row->anggota->tanggallahir))
                        : '-';
                })
                ->rawColumns(['aksi', 'ceklist', 'foto', 'tanggallahir', 'jk', 'jabatan', 'umur', 'status_dasar', 'nik', 'nama', 'alamat'])
                ->make();
        }

        return show_404();
    }

    public function aksi($aksi = '', $id = 0): void
    {
        $_SESSION['aksi'] = $aksi;

        redirect("{$this->controller}/form/{$id}");
    }

    public function form($id = 0, $id_a = 0): void
    {
        isCan('u');
        $data['controller']      = $this->controller;
        $data['kelompok']        = $id;
        $data['tipe']            = ucwords((string) $this->tipe);
        $data['list_jabatan1']   = JabatanKelompokEnum::all();
        $data['list_jabatan2']   = KelompokAnggotaModel::listJabatan($id, $this->tipe);
        $data['list_jk']         = JenisKelaminEnum::all();
        $data['list_agama']      = AgamaEnum::all();
        $data['list_pendidikan'] = PendidikanKKEnum::all();

        if ($id_a == 0) {
            $data['pend']        = null;
            $data['form_action'] = route($this->controller . '.insert', $id);
        } else {
            $kelompok = KelompokAnggotaModel::find($id_a);

            if (! $kelompok || $kelompok->id_kelompok != $id) {
                show_404();
            }

            if ($kelompok->id_penduduk === null) {
                $data['pend'] = collect($kelompok)->merge([
                    'nama'            => $kelompok->nama_luar,
                    'id_sex'          => $kelompok->sex_luar,
                    'nik'             => $kelompok->nik_luar,
                    'alamat'          => $kelompok->alamat_luar,
                    'agama_luar'      => $kelompok->agama_luar,
                    'pendidikan_luar' => $kelompok->pendidikan_luar,
                    'foto_anggota'    => $kelompok->foto,
                ])->toArray();
            } else {
                $pend     = Penduduk::whereId($kelompok->id_penduduk)->first();
                $penduduk = collect($pend)->merge([
                    'alamat' => $pend->getAlamatWilayahAttribute() ?? '',
                ])->toArray();

                $data['pend'] = collect($kelompok)->merge([
                    'nama'         => $penduduk['nama'],
                    'id_sex'       => $penduduk['sex'],
                    'nik'          => $penduduk['nik'],
                    'alamat'       => $penduduk['alamat'],
                    'foto_anggota' => $penduduk['foto'],
                ])->toArray();
            }

            $data['form_action'] = route($this->controller . '.update', ['id_kelompok' => $id, 'id' => $id_a]);
        }

        view('admin.kelompok.anggota.form', $data);
    }

    public function insert($id = 0)
    {
        isCan('u');

        try {
            $data                = (new KelompokAnggotaRequest())->validated();
            $data['id_kelompok'] = $id;
            $data['tipe']        = $this->tipe;

            if (! empty($data['id_penduduk'])) {
                KelompokAnggotaModel::UbahJabatan($data['id_kelompok'], $data['id_penduduk'], $data['jabatan'], null);
            }

            if ($data['id_kelompok']) {
                // Cek duplikat hanya untuk penduduk desa
                if (! empty($data['id_penduduk'])) {
                    $validasi_anggota = KelompokAnggotaModel::whereIdPenduduk($data['id_penduduk'])->whereIdKelompok($data['id_kelompok'])->first();
                    if ($validasi_anggota && $validasi_anggota->id_penduduk == $data['id_penduduk']) {
                        return json(['status' => false, 'message' => 'Nama Anggota yang dipilih sudah masuk kelompok']);
                    }
                }

                $validasi_anggota1 = KelompokAnggotaModel::whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
                if ($validasi_anggota1 && $validasi_anggota1->no_anggota == $data['no_anggota']) {
                    return json(['status' => false, 'message' => "Nomor anggota {$data['no_anggota']} tidak bisa digunakan. Silakan gunakan nomor anggota yang lain!"]);
                }
            }

            $result     = KelompokAnggotaModel::create($data);
            $id_anggota = $result->id;

            if ($foto = $this->uploadFotoPenduduk(
                nama_file: time() . '-' . $id_anggota . '-' . random_int(10000, 999999),
                lokasi: $this->tipe == 'kelompok' ? LOKASI_FOTO_KELOMPOK : LOKASI_FOTO_LEMBAGA
            )) {
                KelompokAnggotaModel::where('id', $id_anggota)->update(['foto' => $foto]);
            }

            return json([
                'status'       => true,
                'message'      => 'Anggota berhasil disimpan',
                'redirect_url' => route($this->controller . '.detail', $id),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return json(['status' => false, 'message' => 'Anggota gagal disimpan']);
        }
    }

    public function update($id = 0, $id_a = 0)
    {
        isCan('u');

        try {
            $data                = (new KelompokAnggotaRequest())->validated();
            $data['id_kelompok'] = $id;
            $data['tipe']        = $this->tipe;
            $anggota             = KelompokAnggotaModel::find($id_a);

            if (! $anggota || $anggota->id_kelompok != $id) {
                return json(['status' => false, 'message' => 'Data anggota tidak ditemukan']);
            }

            if ($anggota->id_penduduk !== null && ! empty($data['id_penduduk'])) {
                KelompokAnggotaModel::UbahJabatan($id, $anggota->id_penduduk, $data['jabatan'], $this->input->post('jabatan_lama'));
            }

            if ($data['id_kelompok']) {
                $validasi_anggota1 = KelompokAnggotaModel::where('id', '!=', $id_a)->whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
                if ($anggota->no_anggota != $data['no_anggota'] && $validasi_anggota1 && $validasi_anggota1->no_anggota == $data['no_anggota']) {
                    return json(['status' => false, 'message' => "Nomor anggota {$data['no_anggota']} tidak bisa digunakan. Silakan gunakan nomor anggota yang lain!"]);
                }
            }

            if ($foto = $this->uploadFotoPenduduk(
                nama_file: time() . '-' . $id_a . '-' . random_int(10000, 999999),
                lokasi: $this->tipe == 'kelompok' ? LOKASI_FOTO_KELOMPOK : LOKASI_FOTO_LEMBAGA
            )) {
                $data['foto'] = $foto;
            }

            // Jangan ubah id_penduduk dan luar_desa saat update
            unset($data['id_penduduk'], $data['luar_desa']);

            $anggota->update($data);

            return json([
                'status'       => true,
                'message'      => 'Anggota berhasil diubah',
                'redirect_url' => route($this->controller . '.detail', $id),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return json(['status' => false, 'message' => 'Anggota gagal diubah']);
        }
    }

    public function delete($id = 0, $a = 0): void
    {
        isCan('h');
        $kelompok = Kelompok::find($id);

        try {
            $anggota = KelompokAnggotaModel::find($a);

            if (! $anggota || $anggota->id_kelompok != $id) {
                redirect_with('error', 'Data anggota tidak ditemukan', route($this->controller . '.detail', $id));
            }

            KelompokAnggotaModel::destroy($anggota->id);
            redirect_with('success', 'Anggota ' . ucfirst($kelompok->nama) . ' berhasil dihapus', route($this->controller . '.detail', $id));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota ' . ucfirst($kelompok->nama) . ' gagal dihapus', route($this->controller . '.detail', $id));
        }
    }

    public function delete_all($id_kelompok = 0): void
    {
        isCan('h');

        try {
            KelompokAnggotaModel::destroy($this->request['id_cb']);
            redirect_with('success', 'Anggota ' . ucfirst($this->tipe) . ' berhasil dihapus', route($this->controller . '.detail', $id_kelompok));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota ' . ucfirst($this->tipe) . ' gagal dihapus', route($this->controller . '.detail', $id_kelompok));
        }
    }

    public function dialog($aksi = 'cetak', $id = 0): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = route($this->controller . '.daftar', ['aksi' => $aksi, 'id' => $id]);

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function daftar($aksi = 'cetak', $id = 0): void
    {
        $post = $this->input->post();

        $kelompok     = KelompokAnggotaModel::with('anggota.Wilayah')->tipe($this->tipe)->where('id_kelompok', '=', $id)->orderByRaw('CAST(jabatan AS UNSIGNED) + 30 - jabatan, CAST(no_anggota AS UNSIGNED)')->get();
        $list_anggota = collect($kelompok)
            ->map(
                static fn ($item) => collect($item)->merge(
                    $item->id_penduduk === null
                        ? [
                            'nama'         => $item->nama_luar,
                            'nik'          => $item->nik_luar,
                            'tempatlahir'  => $item->tempatlahir_luar,
                            'tanggallahir' => $item->tanggallahir_luar,
                            'id_sex'       => $item->sex_luar,
                            'sex'          => JenisKelaminEnum::valueOf($item->sex_luar) ?: '-',
                            'foto'         => null,
                            'pendidikan'   => PendidikanKKEnum::valueOf($item->pendidikan_luar) ?: '-',
                            'agama'        => AgamaEnum::valueOf($item->agama_luar) ?: '-',
                            'umur'         => $item->tanggallahir_luar ? usia($item->tanggallahir_luar, null, '%y') : '-',
                            'jabatan'      => $item->nama_jabatan,
                            'dusun'        => '',
                            'rw'           => '',
                            'rt'           => '',
                            'alamat'       => $item->alamat_luar ?? '-',
                        ]
                        : [
                            'nama'         => $item->anggota->nama,
                            'nik'          => $item->anggota->nik,
                            'tempatlahir'  => $item->anggota->tempatlahir,
                            'tanggallahir' => $item->anggota->tanggallahir,
                            'id_sex'       => $item->anggota->jenis_kelamin_id,
                            'sex'          => $item->anggota->jenis_kelamin,
                            'foto'         => $item->anggota->foto,
                            'pendidikan'   => $item->anggota->pendidikan_kk,
                            'agama'        => $item->anggota->agama,
                            'umur'         => $item->anggota->umur,
                            'jabatan'      => $item->nama_jabatan,
                            'dusun'        => $item->anggota->wilayah->dusun,
                            'rw'           => $item->anggota->wilayah->rw,
                            'rt'           => $item->anggota->wilayah->rt,
                            'alamat'       => $item->anggota ? ($item->anggota->alamat_wilayah ?? '-') : '-',
                        ]
                )
                    ->forget('anggota')
            )
            ->toArray();
        $data['aksi']           = $aksi;
        $data['tipe']           = ucwords((string) $this->tipe);
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $post['pamong_ttd']])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $post['pamong_ketahui']])->first()->toArray();
        $data['main']           = $list_anggota;
        $data['kelompok']       = Kelompok::find($id);
        $data['file']           = 'Laporan Data ' . $data['tipe'] . ' ' . $data['kelompok']['nama']; // nama file
        $data['label']          = $data['tipe'];
        $data['letak_ttd']      = ['2', '3', '2'];

        view('admin.kelompok.anggota.cetak', $data);
    }

    public function anggota()
    {
        $id_penduduk = $this->input->get('id_penduduk');
        $id_anggota  = $this->input->get('id_anggota');
        $kategori    = strtolower($this->input->get('kategori'));

        $individu   = Penduduk::findOrFail($id_penduduk);
        $foto       = $individu->foto;
        $lokasiFoto = LOKASI_USER_PICT;

        if ($id_anggota) {
            $anggota = KelompokAnggotaModel::find($id_anggota);
            if ($anggota && $anggota->foto) {
                $foto       = $anggota->foto;
                $lokasiFoto = ($kategori === 'kelompok') ? LOKASI_FOTO_KELOMPOK : LOKASI_FOTO_LEMBAGA;
            }
        }

        $urlFoto = AmbilFoto($foto, '', $individu->sex, $lokasiFoto);
        $html    = view('admin.kelompok.anggota.konfirmasi', ['individu' => $individu], [], true);

        $sumber = [
            'html' => (string) $html,
            'foto' => $urlFoto,
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($sumber, JSON_THROW_ON_ERROR));
    }
}
