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

use App\Enums\JabatanKelompokEnum;
use App\Enums\JenisKelaminEnum;
use App\Models\Kelompok;
use App\Models\KelompokAnggota as KelompokAnggotaModel;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Kelompok_anggota extends Admin_Controller
{
    public $modul_ini       = 'kependudukan';
    public $sub_modul_ini   = 'kelompok';
    public $tipe            = 'kelompok';
    public $aliasController = 'kelompok';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['kelompok_model', 'pamong_model']);
    }

    public function index(): void
    {
        redirect($this->aliasController);
    }

    public function detail($id = 0): void
    {
        $data['func']       = 'anggota/' . $id;
        $data['controller'] = $this->controller;
        $data['tipe']       = ucwords($this->tipe);
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

            return datatables()->of(KelompokAnggotaModel::with('anggota')
                ->tipe($this->tipe)
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
                        $aksi .= '<a href="' . route("{$controller}.form", ['id_kelompok' => $row->id_kelompok, 'id' => $row->id_penduduk]) . '" class="btn bg-orange btn-sm" title="Ubah Anggota"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        // s
                        $aksi .= '<a href="#" data-href="' . route("{$controller}.delete", ['id_kelompok' => $row->id_kelompok, 'id' => $row->id_penduduk]) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('foto', static fn ($row): string => '<img src="' . AmbilFoto($row->anggota->foto, '', $row->anggota->sex) . '" alt="Foto Penduduk" class="img-circle" width="50px">')
                ->editColumn('jk', static fn ($row): string => JenisKelaminEnum::valueOf($row->anggota->sex))
                ->editColumn('jabatan', static function ($row): string {
                    if ($row->jabatan != 90) {
                        return JabatanKelompokEnum::valueOf($row->jabatan) ?: strtoupper($row->jabatan);
                    }

                    return JabatanKelompokEnum::valueOf($row->jabatan);
                })
                ->editColumn('umur', static fn ($row): string => $row->anggota->umur)
                ->editColumn('tanggallahir', static fn ($row): string => strtoupper($row->anggota->tempatlahir) . ' / ' . strtoupper(tgl_indo($row->anggota->tanggallahir)))
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
        $data['tipe']          = ucwords($this->tipe);
        $data['list_jabatan1'] = JabatanKelompokEnum::all();
        $data['list_jabatan2'] = $this->kelompok_model->list_jabatan($id);

        if ($id_a == 0) {
            $data['pend']        = null;
            $data['form_action'] = route($this->controller . '.insert', $id);
        } else {
            $kelompok = KelompokAnggotaModel::whereIdKelompok($id)->whereIdPenduduk($id_a)->first();
            $pend     = Penduduk::whereId($id_a)->first();
            $penduduk = collect($pend)->merge([
                'alamat' => $pend->getAlamatWilayahAttribute() ?? '',
            ])->toArray();

            $data['pend'] = collect($kelompok)->merge([
                'nama'   => $penduduk['nama'],
                'id_sex' => $penduduk['sex'],
                'foto'   => $penduduk['foto'],
                'nik'    => $penduduk['nik'],
                'alamat' => $penduduk['alamat'],
            ])->toArray();
            $data['form_action'] = route($this->controller . '.update', ['id_kelompok' => $id, 'id' => $id_a]);
        }

        view('admin.kelompok.anggota.form', $data);
    }

    public function insert($id = 0)
    {
        isCan('u');
        $data                = $this->validasi_anggota($this->input->post());
        $data['id_kelompok'] = $id;
        KelompokAnggotaModel::UbahJabatan($data['id_kelompok'], $data['id_penduduk'], $data['jabatan'], null);

        if ($data['id_kelompok']) {
            $validasi_anggota  = KelompokAnggotaModel::whereIdPenduduk($data['id_penduduk'])->whereIdKelompok($data['id_kelompok'])->first();
            $validasi_anggota1 = KelompokAnggotaModel::where('id_penduduk', '!=', $data['id_penduduk'])->whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
            if ($validasi_anggota->id_penduduk == $data['id_penduduk']) {
                session_error('Nama Anggota yang dipilih sudah masuk kelompok');
                redirect($this->controller . "/form/{$validasi_anggota->id_kelompok}");
            }

            if ($validasi_anggota1->no_anggota == $data['no_anggota']) {
                session_error("<br/>Nomor anggota ini {$data['no_anggota']} tidak bisa digunakan. Silahkan gunakan nomor anggota yang lain!");

                return false;
            }
        }

        try {
            $result     = KelompokAnggotaModel::create($data);
            $id_anggota = $result->id;

            // Upload foto dilakukan setelah ada id, karena nama foto berisi nik
            if ($foto = upload_foto_penduduk(time() . '-' . $id_anggota . '-' . random_int(10000, 999999))) {
                Penduduk::whereId($data['id_penduduk'])->update(['foto' => $foto]);
            }
            if ($this->session->aksi != 1) {
                $redirect = $_SERVER['HTTP_REFERER'];
            } else {
                $redirect = route($this->controller . '.detail', $id);
                $this->session->unset_userdata('aksi');
            }

            redirect_with('success', 'Anggota berhasil disimpan', $redirect);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota gagal disimpan', $redirect);
        }
    }

    public function update($id = 0, $id_a = 0): void
    {
        isCan('u');
        $data                = $this->validasi_anggota($this->input->post());
        $data['id_kelompok'] = $id;
        KelompokAnggotaModel::UbahJabatan($id, $id_a, $data['jabatan'], $this->input->post('jabatan_lama'));
        if ($data['id_kelompok']) {
            // $validasi_anggota1 = KelompokAnggotaModel::whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
            $validasi_anggota1 = KelompokAnggotaModel::where('id_penduduk', '!=', $data['id_penduduk'])->whereNoAnggota($data['no_anggota'])->whereIdKelompok($data['id_kelompok'])->first();
        }
        $anggota = KelompokAnggotaModel::whereIdKelompok($data['id_kelompok'])->whereIdPenduduk($id_a)->first();
        if ($anggota->no_anggota != $data['no_anggota'] && $validasi_anggota1->no_anggota == $data['no_anggota']) {
            redirect_with('error', "Nomor anggota ini {$data['no_anggota']} tidak bisa digunakan. Silahkan gunakan nomor anggota yang lain!", route($this->controller . '.form', ['id_kelompok' => $id, 'id' => $id_a]));
        }

        try {
            $anggota->update($data);

            // Upload foto dilakukan setelah ada id, karena nama foto berisi nik
            if ($foto = upload_foto_penduduk(time() . '-' . $id_a . '-' . random_int(10000, 999999))) {
                Penduduk::whereId($id_a)->update(['foto' => $foto]);
            }
            $redirect = ($this->session->aksi != 1) ? $_SERVER['HTTP_REFERER'] : route($this->controller . '.detail', $id);

            $this->session->unset_userdata('aksi');

            redirect_with('success', 'Anggota berhasil diubah', $redirect);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota gagal diubah', $redirect);
        }
    }

    private function validasi_anggota($post)
    {
        if ($post['id_penduduk']) {
            $data['id_penduduk'] = bilangan($post['id_penduduk']);
        }

        $data['no_anggota']    = bilangan($post['no_anggota']);
        $data['jabatan']       = alfanumerik_spasi($post['jabatan']);
        $data['no_sk_jabatan'] = nomor_surat_keputusan($post['no_sk_jabatan']);
        $data['keterangan']    = htmlentities($post['keterangan']);
        $data['tipe']          = $this->tipe;

        if ($this->tipe == 'lembaga') {
            $data['nmr_sk_pengangkatan']  = nomor_surat_keputusan($post['nmr_sk_pengangkatan']);
            $data['tgl_sk_pengangkatan']  = empty($post['tgl_sk_pengangkatan']) ? null : tgl_indo_in($post['tgl_sk_pengangkatan']);
            $data['nmr_sk_pemberhentian'] = nomor_surat_keputusan($post['nmr_sk_pemberhentian']);
            $data['tgl_sk_pemberhentian'] = empty($post['tgl_sk_pemberhentian']) ? null : tgl_indo_in($post['tgl_sk_pemberhentian']);
            $data['periode']              = htmlentities($post['periode']);
        }

        return $data;
    }

    public function delete($id = 0, $a = 0): void
    {
        isCan('h');

        try {
            $anggota = KelompokAnggotaModel::whereIdPenduduk($a)->first();
            KelompokAnggotaModel::destroy($anggota->id);
            redirect_with('success', 'Anggota ' . ucfirst($this->lembaga) . ' berhasil dihapus', route($this->controller . '.detail', $id));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota ' . ucfirst($this->lembaga) . ' gagal dihapus', route($this->controller . '.detail', $id));
        }
    }

    public function delete_all($id_kelompok = 0): void
    {
        isCan('h');

        try {
            KelompokAnggotaModel::whereIn('id_penduduk', $this->request['id_cb'])->delete();
            redirect_with('success', 'Anggota ' . ucfirst($this->lembaga) . ' berhasil dihapus', route($this->controller . '.detail', $id_kelompok));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Anggota ' . ucfirst($this->lembaga) . ' gagal dihapus', route($this->controller . '.detail', $id_kelompok));
        }
    }

    public function dialog($aksi = 'cetak', $id = 0): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = route($this->controller . '.daftar', ['aksi' => $aksi, 'id' => $id]);

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function daftar($aksi = 'cetak', $id = 0): void
    {
        $post = $this->input->post();

        $kelompok     = KelompokAnggotaModel::with('anggota')->tipe($this->tipe)->where('id_kelompok', '=', $id)->orderByRaw('CAST(jabatan AS UNSIGNED) + 30 - jabatan, CAST(no_anggota AS UNSIGNED)')->get();
        $list_anggota = collect($kelompok)
            ->map(
                static fn ($item) => collect($item)->merge(
                    [
                        'nama'         => $item->anggota->nama,
                        'nik'          => $item->anggota->nik,
                        'tempatlahir'  => $item->anggota->tempatlahir,
                        'tanggallahir' => $item->anggota->tanggallahir,
                        'id_sex'       => $item->anggota->jeniskelamin->id,
                        'sex'          => $item->anggota->jeniskelamin->nama,
                        'foto'         => $item->anggota->foto,
                        'pendidikan'   => $item->anggota->pendidikankk->nama,
                        'agama'        => $item->anggota->agama->nama,
                        'umur'         => $item->anggota->umur,
                        'jabatan'      => $item->nama_jabatan,
                        'dusun'        => $item->anggota->wilayah->dusun,
                        'rw'           => $item->anggota->wilayah->rw,
                        'rt'           => $item->anggota->wilayah->rt,
                        'alamat'       => $item->anggota->alamat_wilayah,
                    ]
                )
                    ->forget('anggota')
            )
            ->toArray();
        $data['aksi']           = $aksi;
        $data['tipe']           = ucwords($this->tipe);
        $data['config']         = $this->header['desa'];
        $data['pamong_ttd']     = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
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
}
