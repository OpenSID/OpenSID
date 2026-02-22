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

use App\Enums\JabatanKelompokEnum;
use App\Enums\JenisKelaminEnum;
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
        $data['func']       = 'anggota/' . $id;
        $data['controller'] = $this->controller;
        $data['tipe']       = ucwords((string) $this->tipe);
        $kelompok           = Kelompok::tipe($this->tipe)->find($id) ?? show_404();
        $data['kelompok']   = collect($kelompok)->merge([
            'kategori'   => $kelompok->kelompokMaster()->first()->kelompok,
            'nama_ketua' => $kelompok->ketua()->first()->nama,
        ])->toArray();

        view('admin.kelompok.anggota.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $id_kelompok = $this->input->get('id_kelompok');
            $controller  = $this->controller;
            $tipe        = $this->tipe;

            return datatables()->of(KelompokAnggotaModel::with('anggota')
                ->tipe($tipe)
                ->where('id_kelompok', '=', $id_kelompok)
                ->orderBy('jabatan'))
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

                    if (can('h') && $row->jml_anggota <= 0) {
                        $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                            'url'           => ci_route("{$controller}.delete", [$row->id_kelompok, $row->id]),
                            'confirmDelete' => true,
                        ])->render();

                    }

                    return $aksi;
                })
                ->editColumn('foto', static function ($row) use ($tipe): string {
                    $foto       = $row->foto ?: $row->anggota?->foto;
                    $lokasiFoto = $row->foto && $tipe === 'kelompok'
                        ? LOKASI_FOTO_KELOMPOK
                        : ($row->foto ? LOKASI_FOTO_LEMBAGA : LOKASI_USER_PICT);
                    $sex = $row->id_sex_tampil ?: JenisKelaminEnum::LAKI_LAKI;

                    $urlFoto = AmbilFoto($foto, '', $sex, $lokasiFoto);

                    return '<img src="' . $urlFoto . '" alt="Foto Penduduk" class="img-circle" width="50px">';
                })
                ->editColumn('jk', static fn ($row): string => strtoupper((string) $row->sex_tampil))
                ->editColumn('jabatan', static function ($row): string {
                    if ($row->jabatan != 90) {
                        return JabatanKelompokEnum::valueOf($row->jabatan) ?: strtoupper($row->jabatan);
                    }

                    return JabatanKelompokEnum::valueOf($row->jabatan);
                })
                ->editColumn('umur', static function ($row): string {
                    $umur = $row->isSumberPenduduk()
                        ? $row->anggota?->umur
                        : umur($row->tanggallahir_tampil);

                    return (string) ($umur ?? '');
                })
                ->editColumn('tanggallahir', static function ($row): string {
                    $tempatLahir = strtoupper((string) $row->tempatlahir_tampil);
                    $tanggalLahir = $row->tanggallahir_tampil ? strtoupper((string) tgl_indo($row->tanggallahir_tampil)) : '';

                    if ($tempatLahir === '' && $tanggalLahir === '') {
                        return '';
                    }

                    if ($tempatLahir === '') {
                        return $tanggalLahir;
                    }

                    if ($tanggalLahir === '') {
                        return $tempatLahir;
                    }

                    return "{$tempatLahir} / {$tanggalLahir}";
                })
                ->rawColumns(['aksi', 'ceklist', 'foto', 'tanggallahir', 'jk', 'jabatan', 'umur'])
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
        $data['controller']    = $this->controller;
        $data['kelompok']      = $id;
        $data['tipe']          = ucwords((string) $this->tipe);
        $data['list_jabatan1'] = JabatanKelompokEnum::all();
        $data['list_jabatan2'] = KelompokAnggotaModel::listJabatan($id, $this->tipe);
        $data['pend']          = $this->defaultDataPend();

        if ($id_a == 0) {
            $data['form_action'] = ci_route($this->controller . '.insert', $id);
        } else {
            $anggota = $this->resolveAnggotaByIdentifier((int) $id, (int) $id_a);
            $anggota || show_404();

            $data['pend'] = array_merge($this->defaultDataPend(), collect($anggota)->toArray(), [
                'id'            => $anggota->id,
                'id_penduduk'   => $anggota->id_penduduk,
                'sumber_anggota'=> $anggota->sumber_anggota ?? 'penduduk',
                'nama'          => $anggota->nama_tampil,
                'id_sex'        => $anggota->id_sex_tampil,
                'nik'           => $anggota->nik_tampil,
                'alamat'        => $anggota->alamat_tampil,
                'foto_anggota'  => $anggota->anggota?->foto,
            ]);
            $data['form_action'] = ci_route($this->controller . '.update', [$id, $anggota->id]);
        }

        view('admin.kelompok.anggota.form', $data);
    }

    public function insert($id = 0)
    {
        isCan('u');
        $data                = $this->validasi_anggota($this->input->post(), (int) $id);
        $data['id_kelompok'] = $id;
        $redirect            = ($this->session->aksi != 1) ? ($_SERVER['HTTP_REFERER'] ?? ci_route($this->controller . '.detail', $id)) : ci_route($this->controller . '.detail', $id);

        if ($data['id_kelompok']) {
            if ($data['sumber_anggota'] === 'penduduk') {
                $validasiAnggota = KelompokAnggotaModel::whereIdPenduduk($data['id_penduduk'])->whereIdKelompok($data['id_kelompok'])->first();
                if ($validasiAnggota) {
                    redirect_with('error', 'Nama Anggota yang dipilih sudah masuk kelompok', "{$this->controller}/form/{$id}");
                }
            }

            $nomorTerdaftar = KelompokAnggotaModel::whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
            if ($nomorTerdaftar) {
                redirect_with('error', "<br/>Nomor anggota ini {$data['no_anggota']} tidak bisa digunakan. Silakan gunakan nomor anggota yang lain!", "{$this->controller}/form/{$id}");
            }
        }

        KelompokAnggotaModel::UbahJabatan($data['id_kelompok'], $data['id_penduduk'], $data['jabatan'], null);

        try {
            $result     = KelompokAnggotaModel::create($data);
            $id_anggota = $result->id;

            // Upload foto dilakukan setelah ada id, karena nama foto berisi nik
            if ($foto = $this->uploadFotoPenduduk(
                nama_file: time() . '-' . $id_anggota . '-' . random_int(10000, 999999),
                lokasi: $this->tipe == 'kelompok' ? LOKASI_FOTO_KELOMPOK : LOKASI_FOTO_LEMBAGA
            )) {
                KelompokAnggotaModel::where('id', $id_anggota)->update(['foto' => $foto]);
            }

            if ($this->session->aksi == 1) {
                $this->session->unset_userdata('aksi');
            }

            redirect_with('success', 'Anggota berhasil disimpan', $redirect);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota gagal disimpan', $redirect);
        }

        redirect("{$this->controller}/form/{$id}");
    }

    public function update($id = 0, $id_a = 0): void
    {
        isCan('u');
        $anggota = $this->resolveAnggotaByIdentifier((int) $id, (int) $id_a);
        $anggota || show_404();

        $data                = $this->validasi_anggota($this->input->post(), (int) $id, (int) $anggota->id);
        $data['id_kelompok'] = $id;
        $redirect            = ($this->session->aksi != 1) ? ($_SERVER['HTTP_REFERER'] ?? ci_route($this->controller . '.detail', $id)) : ci_route($this->controller . '.detail', $id);

        KelompokAnggotaModel::UbahJabatan($id, $data['id_penduduk'] ?? $anggota->id_penduduk, $data['jabatan'], $this->input->post('jabatan_lama'));
        if ($data['id_kelompok']) {
            $validasiAnggota = KelompokAnggotaModel::where('id', '!=', $anggota->id)->whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
            if ($anggota->no_anggota != $data['no_anggota'] && $validasiAnggota) {
                redirect_with('error', "Nomor anggota ini {$data['no_anggota']} tidak bisa digunakan. Silakan gunakan nomor anggota yang lain!", ci_route($this->controller . '.form', [$id, $anggota->id]));
            }
        }

        try {
            if ($foto = $this->uploadFotoPenduduk(
                nama_file: time() . '-' . $anggota->id . '-' . random_int(10000, 999999),
                lokasi: $this->tipe == 'kelompok' ? LOKASI_FOTO_KELOMPOK : LOKASI_FOTO_LEMBAGA
            )) {
                $data['foto'] = $foto;
            }

            $anggota->update($data);
            $this->session->unset_userdata('aksi');

            redirect_with('success', 'Anggota berhasil diubah', $redirect);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota gagal diubah', $redirect);
        }
    }

    private function validasi_anggota(array $post, int $id_kelompok = 0, int $id_anggota = 0)
    {
        $redirect    = $this->form_redirect($id_kelompok, $id_anggota);
        $validSumber = ['penduduk', 'luar_desa'];
        $sumber      = in_array($post['sumber_anggota'] ?? 'penduduk', $validSumber, true) ? $post['sumber_anggota'] : 'penduduk';
        $anggotaLama = $id_anggota > 0 ? $this->resolveAnggotaByIdentifier($id_kelompok, $id_anggota) : null;

        if ($this->tipe !== 'lembaga' && ($post['sumber_anggota'] ?? 'penduduk') === 'luar_desa') {
            redirect_with('error', 'Sumber anggota luar desa hanya tersedia untuk lembaga.', $redirect);
        }

        // V1 hanya untuk lembaga. Kelompok tetap menggunakan sumber penduduk.
        if ($this->tipe === 'lembaga' && $id_anggota > 0) {
            $sumber      = $anggotaLama?->sumber_anggota ?? $sumber;
        }
        $data['sumber_anggota'] = $this->tipe === 'lembaga' ? $sumber : 'penduduk';

        if ($data['sumber_anggota'] === 'penduduk') {
            $idPenduduk = $post['id_penduduk'] ?? $anggotaLama?->id_penduduk;
            if (empty($idPenduduk)) {
                redirect_with('error', 'Nama anggota wajib dipilih dari penduduk desa.', $redirect);
            }

            $data['id_penduduk'] = bilangan((string) $idPenduduk);
            if (empty($data['id_penduduk'])) {
                redirect_with('error', 'Data penduduk anggota tidak valid.', $redirect);
            }
            if (! Penduduk::where('id', $data['id_penduduk'])->exists()) {
                redirect_with('error', 'Penduduk yang dipilih tidak ditemukan.', $redirect);
            }
            if ($id_anggota > 0 && $anggotaLama && (int) $anggotaLama->id_penduduk !== (int) $data['id_penduduk']) {
                redirect_with('error', 'Data penduduk anggota tidak dapat diubah saat edit.', $redirect);
            }

            $data['nama_luar']        = null;
            $data['nik_luar']         = null;
            $data['sex_luar']         = null;
            $data['alamat_luar']      = null;
            $data['tempatlahir_luar'] = null;
            $data['tanggallahir_luar'] = null;
        } else {
            $namaLuar = trim((string) ($post['nama_luar'] ?? ''));
            if ($namaLuar === '') {
                redirect_with('error', 'Nama anggota luar desa wajib diisi.', $redirect);
            }

            if (($post['sex_luar'] ?? null) === null || $post['sex_luar'] === '') {
                redirect_with('error', 'Jenis kelamin anggota luar desa wajib diisi.', $redirect);
            }

            $sexLuar = (int) bilangan((string) $post['sex_luar']);
            if (! in_array($sexLuar, JenisKelaminEnum::keys(), true)) {
                redirect_with('error', 'Jenis kelamin anggota luar desa tidak valid.', $redirect);
            }

            $namaLuar = nama($namaLuar);
            if (strlen($namaLuar) > 100) {
                redirect_with('error', 'Nama anggota luar desa maksimal 100 karakter.', $redirect);
            }

            $nikLuar = empty($post['nik_luar']) ? null : bilangan((string) $post['nik_luar']);
            if ($nikLuar !== null && strlen((string) $nikLuar) > 16) {
                redirect_with('error', 'NIK anggota luar desa maksimal 16 digit.', $redirect);
            }

            $alamatLuar = empty($post['alamat_luar']) ? null : alamat((string) $post['alamat_luar']);
            if ($alamatLuar !== null && strlen($alamatLuar) > 200) {
                redirect_with('error', 'Alamat anggota luar desa maksimal 200 karakter.', $redirect);
            }

            $tempatLahirLuar = empty($post['tempatlahir_luar']) ? null : nama((string) $post['tempatlahir_luar']);
            if ($tempatLahirLuar !== null && strlen($tempatLahirLuar) > 100) {
                redirect_with('error', 'Tempat lahir anggota luar desa maksimal 100 karakter.', $redirect);
            }

            $tanggalLahirLuar = empty($post['tanggallahir_luar']) ? null : trim((string) $post['tanggallahir_luar']);
            if ($tanggalLahirLuar !== null) {
                if (! preg_match('/^\d{2}-\d{2}-\d{4}$/', $tanggalLahirLuar)) {
                    redirect_with('error', 'Format tanggal lahir anggota luar desa tidak valid.', $redirect);
                }
                [$tanggal, $bulan, $tahun] = array_map('intval', explode('-', $tanggalLahirLuar));
                if (! checkdate($bulan, $tanggal, $tahun)) {
                    redirect_with('error', 'Tanggal lahir anggota luar desa tidak valid.', $redirect);
                }
            }

            $data['id_penduduk']      = null;
            $data['nama_luar']        = $namaLuar;
            $data['nik_luar']         = $nikLuar;
            $data['sex_luar']         = $sexLuar;
            $data['alamat_luar']      = $alamatLuar;
            $data['tempatlahir_luar'] = $tempatLahirLuar;
            $data['tanggallahir_luar'] = $tanggalLahirLuar === null ? null : tgl_indo_in($tanggalLahirLuar);
        }

        $data['no_anggota']    = bilangan((string) ($post['no_anggota'] ?? ''));
        $data['jabatan']       = alfanumerik_spasi((string) ($post['jabatan'] ?? ''));
        $data['no_sk_jabatan'] = nomor_surat_keputusan((string) ($post['no_sk_jabatan'] ?? ''));
        $data['keterangan']    = htmlentities((string) ($post['keterangan'] ?? ''));
        $data['tipe']          = $this->tipe;

        if ($data['no_anggota'] === '') {
            redirect_with('error', 'Nomor anggota wajib diisi.', $redirect);
        }

        if ($data['jabatan'] === '') {
            redirect_with('error', 'Jabatan wajib diisi.', $redirect);
        }

        $isKetua = (string) $data['jabatan'] === (string) JabatanKelompokEnum::KETUA
            || strtoupper((string) $data['jabatan']) === JabatanKelompokEnum::valueOf(JabatanKelompokEnum::KETUA);
        if ($data['sumber_anggota'] === 'luar_desa' && $isKetua) {
            redirect_with('error', 'Anggota luar desa tidak dapat dijadikan ketua lembaga pada V1.', $redirect);
        }

        if ($this->tipe == 'lembaga') {
            $data['nmr_sk_pengangkatan']  = nomor_surat_keputusan((string) ($post['nmr_sk_pengangkatan'] ?? ''));
            $data['tgl_sk_pengangkatan']  = empty($post['tgl_sk_pengangkatan']) ? null : tgl_indo_in($post['tgl_sk_pengangkatan']);
            $data['nmr_sk_pemberhentian'] = nomor_surat_keputusan((string) ($post['nmr_sk_pemberhentian'] ?? ''));
            $data['tgl_sk_pemberhentian'] = empty($post['tgl_sk_pemberhentian']) ? null : tgl_indo_in($post['tgl_sk_pemberhentian']);
            $data['periode']              = htmlentities((string) ($post['periode'] ?? ''));
        }

        return $data;
    }

    private function form_redirect(int $id_kelompok = 0, int $id_anggota = 0): string
    {
        if ($id_anggota > 0) {
            return ci_route($this->controller . '.form', [$id_kelompok, $id_anggota]);
        }

        return "{$this->controller}/form/{$id_kelompok}";
    }

    private function defaultDataPend(): array
    {
        return [
            'id'                   => null,
            'id_penduduk'          => null,
            'sumber_anggota'       => 'penduduk',
            'nama'                 => null,
            'nama_luar'            => null,
            'id_sex'               => null,
            'sex_luar'             => null,
            'nik'                  => null,
            'nik_luar'             => null,
            'alamat'               => null,
            'alamat_luar'          => null,
            'tempatlahir_luar'     => null,
            'tanggallahir_luar'    => null,
            'foto'                 => null,
            'foto_anggota'         => null,
            'no_anggota'           => null,
            'jabatan'              => null,
            'no_sk_jabatan'        => null,
            'nmr_sk_pengangkatan'  => null,
            'tgl_sk_pengangkatan'  => null,
            'nmr_sk_pemberhentian' => null,
            'tgl_sk_pemberhentian' => null,
            'periode'              => null,
            'keterangan'           => null,
        ];
    }

    private function resolveAnggotaByIdentifier(int $id_kelompok, int $id_anggota): ?KelompokAnggotaModel
    {
        if ($id_kelompok <= 0 || $id_anggota <= 0) {
            return null;
        }

        $query = KelompokAnggotaModel::tipe($this->tipe)->whereIdKelompok($id_kelompok);

        return (clone $query)->where('id', $id_anggota)->first()
            ?: (clone $query)->whereIdPenduduk($id_anggota)->first();
    }

    public function delete($id = 0, $a = 0): void
    {
        isCan('h');
        $kelompok = Kelompok::find($id);

        try {
            $anggota = $this->resolveAnggotaByIdentifier((int) $id, (int) $a);
            if (! $anggota) {
                redirect_with('error', 'Anggota tidak ditemukan', ci_route($this->controller . '.detail', $id));
            }

            KelompokAnggotaModel::destroy($anggota->id);
            redirect_with('success', 'Anggota ' . ucfirst($kelompok->nama) . ' berhasil dihapus', ci_route($this->controller . '.detail', $id));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota ' . ucfirst($kelompok->nama) . ' gagal dihapus', ci_route($this->controller . '.detail', $id));
        }
    }

    public function delete_all($id_kelompok = 0): void
    {
        isCan('h');

        try {
            KelompokAnggotaModel::destroy($this->request['id_cb']);
            redirect_with('success', 'Anggota ' . ucfirst($this->lembaga) . ' berhasil dihapus', ci_route($this->controller . '.detail', $id_kelompok));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota ' . ucfirst($this->lembaga) . ' gagal dihapus', ci_route($this->controller . '.detail', $id_kelompok));
        }
    }

    public function dialog($aksi = 'cetak', $id = 0): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = ci_route($this->controller . '.daftar', [$aksi, $id]);

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function daftar($aksi = 'cetak', $id = 0): void
    {
        $post = $this->input->post();

        $kelompok     = KelompokAnggotaModel::with('anggota')->tipe($this->tipe)->where('id_kelompok', '=', $id)->orderByRaw('CAST(jabatan AS UNSIGNED) + 30 - jabatan, CAST(no_anggota AS UNSIGNED)')->get();
        $list_anggota = collect($kelompok)
            ->map(
                static fn ($item) => collect($item)->merge([
                    'nama'         => $item->nama_tampil,
                    'nik'          => $item->nik_tampil,
                    'tempatlahir'  => $item->tempatlahir_tampil,
                    'tanggallahir' => $item->tanggallahir_tampil,
                    'id_sex'       => $item->id_sex_tampil,
                    'sex'          => $item->sex_tampil,
                    'foto'         => $item->foto ?: $item->anggota?->foto,
                    'pendidikan'   => $item->anggota?->pendidikanKK,
                    'agama'        => $item->anggota?->agama?->nama,
                    'umur'         => $item->isSumberPenduduk() ? ($item->anggota?->umur ?? null) : umur($item->tanggallahir_tampil),
                    'jabatan'      => $item->nama_jabatan,
                    'dusun'        => $item->anggota?->wilayah?->dusun,
                    'rw'           => $item->anggota?->wilayah?->rw,
                    'rt'           => $item->anggota?->wilayah?->rt,
                    'alamat'       => $item->alamat_tampil,
                ])
                    ->forget('anggota')
            )
            ->toArray();
        $data['aksi']           = $aksi;
        $data['tipe']           = ucwords((string) $this->tipe);
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $post['pamong_ttd']])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $post['pamong_ketahui']])->first()->toArray();
        $data['main']           = $list_anggota;
        $kelompok               = Kelompok::find($id);
        $data['kelompok']       = collect($kelompok)->merge([
            'kategori'   => $kelompok->kelompokMaster()->first()->kelompok,
            'nama_ketua' => $kelompok->ketua()->first()->nama,
        ])->toArray();
        $data['file']      = 'Laporan Data ' . $data['tipe'] . ' ' . $data['kelompok']['nama']; // nama file
        $data['isi']       = 'admin.kelompok.anggota.cetak';
        $data['label']     = $data['tipe'];
        $data['letak_ttd'] = ['2', '3', '2'];

        view('admin.layouts.components.format_cetak', $data);
    }

    public function anggota()
    {
        $sumberAnggota = $this->input->get('sumber_anggota') ?? 'penduduk';
        $id_penduduk = $this->input->get('id_penduduk');
        $id_anggota  = $this->input->get('id_anggota');
        $kategori    = strtolower($this->input->get('kategori'));

        if ($sumberAnggota !== 'penduduk' || empty($id_penduduk)) {
            return $this->renderAnggotaResponse('', AmbilFoto('', '', JenisKelaminEnum::LAKI_LAKI, LOKASI_USER_PICT));
        }

        $individu   = Penduduk::findOrFail($id_penduduk);
        $foto       = $individu->foto;
        $lokasiFoto = LOKASI_USER_PICT;

        if ($id_anggota) {
            $anggota = KelompokAnggotaModel::find($id_anggota) ?: KelompokAnggotaModel::whereIdPenduduk($id_anggota)->first();
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

        return $this->renderAnggotaResponse($sumber['html'], $sumber['foto']);
    }

    private function renderAnggotaResponse(string $html, string $foto)
    {
        $sumber = [
            'html' => $html,
            'foto' => $foto,
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($sumber, JSON_THROW_ON_ERROR));
    }
}
