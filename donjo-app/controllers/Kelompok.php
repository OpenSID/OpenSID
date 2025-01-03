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

use App\Models\Kelompok as KelompokModel;
use App\Models\KelompokAnggota;
use App\Models\KelompokMaster;
use App\Models\Penduduk;
use App\Traits\Upload;

defined('BASEPATH') || exit('No direct script access allowed');

class Kelompok extends Admin_Controller
{
    use Upload;

    public $modul_ini            = 'kependudukan';
    public $sub_modul_ini        = 'kelompok';
    private array $_list_session = ['penerima_bantuan', 'sex', 'status_dasar'];
    protected $tipe              = 'kelompok';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['kelompok_model', 'pamong_model']);
        $this->kelompok_model->set_tipe($this->tipe);
    }

    public function clear(): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->status_dasar = 1; // Rumah Tangga Aktif

        redirect($this->controller);
    }

    public function index()
    {
        $data['list_master']          = KelompokMaster::tipe($this->tipe)->get(['id', 'kelompok']);
        $data['default_status_dasar'] = $this->input->get('default_status_dasar') ?? 1;
        $data['default_kelompok']     = $this->input->get('default_kelompok');
        if ($this->input->is_ajax_request()) {
            $controller = $this->controller;
            $status     = $this->input->get('status_dasar');
            $tipe       = $this->tipe;
            $query      = KelompokModel::with(['kelompokMaster', 'ketua'])
                ->withCount(['kelompokAnggota as jml_anggota' => static function ($query) use ($tipe) {
                    $query->where('tipe', $tipe);
                }])
                ->tipe($this->tipe)
                ->when($this->session->sex, static fn ($q) => $q->jenisKelaminKetua() )
                ->penerimaBantuan()
                ->whereHas('kelompokMaster', function ($query): void {
                    if ($filter = $this->input->get('filter')) {
                        $query->where('id_master', $filter);
                    }
                })->when($status > 0, static function ($query) use ($status) {
                    $query->whereHas('ketua', static function ($query) use ($status): void {
                        if ($status == 1) {
                            $query->where('status_dasar', 1);
                        } elseif ($status == 2) {
                            $query->where('status_dasar', null);
                        }
                    });
                });

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('ceklist', static fn ($row): string => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>')
                ->addColumn('aksi', static function ($row) use ($controller): string {
                    $aksi = '';

                    $aksi .= '<a href="' . route($row->tipe . '_anggota.detail', $row->id) . '" class="btn bg-purple btn-sm" title="Rincian"><i class="fa fa-list-ol"></i></a> ';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("{$controller}/form/{$row->id}") . '" class="btn bg-orange btn-sm" title="Ubah Kategori"><i class="fa fa-edit"></i></a> ';
                    }
                    if (can('h') && $row->jml_anggota <= 0) {
                        $aksi .= '<a href="#" data-href="' . site_url("{$controller}/delete/{$row->id}") . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('admin.kelompok.index', $data);
    }

    public function form($id = 0)
    {
        isCan('u');
        $list_master = KelompokMaster::tipe($this->tipe)->get(['id', 'kelompok']);

        if (count($list_master) <= 0) {
            redirect_with('error', "Kategori {$this->tipe} tidak tersedia, silakan tambah ketegori terlebih dahulu");
        }

        if ($id) {
            $data['kelompok']    = KelompokModel::tipe($this->tipe)->with(['kelompokMaster', 'ketua'])->find($id) ?? show_404();
            $data['form_action'] = site_url("{$this->controller}/update/{$id}");
            $data['action']      = 'Ubah';
        } else {
            $data['kelompok']    = null;
            $data['form_action'] = site_url("{$this->controller}/insert");
            $data['action']      = 'Tambah';
        }

        $data['list_master']   = $list_master;
        $data['list_penduduk'] = KelompokModel::listPenduduk();

        return view('admin.kelompok.form', $data);
    }

    public function aksi($aksi = '', $id = 0): void
    {
        $this->session->set_userdata('aksi', $aksi);

        redirect("{$this->controller}/form_anggota/{$id}");
    }

    public function apipendudukkelompok()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $tipe     = $this->input->get('tipe');
            $kelompok = $this->input->get('kelompok');
            $anggota  = KelompokAnggota::tipe($tipe)->where('id_kelompok', '=', $kelompok)->pluck('id_penduduk');
            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->whereNotIn('id', $anggota)
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun),
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    // $aksi = cetak/unduh
    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = site_url("{$this->controller}/daftar/{$aksi}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function daftar($aksi = 'cetak'): void
    {
        $post                   = $this->input->post();
        $data['aksi']           = $aksi;
        $data['tipe']           = ucwords((string) $this->tipe);
        $data['pamong_ttd']     = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
        $data['main']           = $this->kelompok_model->list_data();
        $data['file']           = 'Data ' . $data['tipe']; // nama file
        $data['isi']            = 'admin.kelompok.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];

        view('admin.layouts.components.format_cetak', $data);
    }

    public function insert(): void
    {
        isCan('u');

        $data        = $this->validate($this->input->post());
        $getKelompok = KelompokModel::tipe($this->tipe)->where('kode', $data['kode'])->exists();

        if ($getKelompok) {
            redirect_with('error', "<br/>Kode ini {$data['kode']} tidak bisa digunakan. Silahkan gunakan kode yang lain!");
        }

        // insert kelompok
        $kelompok = (new KelompokModel($data));
        $kelompok->save();

        // insert ketua kelompok
        (new KelompokAnggota([
            'id_kelompok' => $kelompok->id,
            'config_id'   => identitas('id'),
            'id_penduduk' => $data['id_ketua'],
            'no_anggota'  => 1,
            'jabatan'     => 1,
            'keterangan'  => "Ketua {$this->tipe}",
            'tipe'        => $this->tipe,
        ]))->save();

        redirect_with('success', 'Berhasil Tambah Data');
    }

    public function update($id = 0): void
    {
        isCan('u');

        $data        = $this->validate($this->input->post(), $id);
        $getKelompok = KelompokModel::tipe($this->tipe)->where('id', '!=', $id)
            ->where(static function ($query) use ($id, $data): void {
                $query->where('id', $id)->orWhere('kode', $data['kode']);
            })->exists();

        if ($getKelompok) {
            redirect_with('error', "<br/>Kode ini {$data['kode']} tidak bisa digunakan. Silahkan gunakan kode yang lain!");
        }

        KelompokModel::findOrFail($id)->update($data);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    protected function validate($request = [], $id = null)
    {
        if ($request['id_ketua']) {
            $data['id_ketua'] = bilangan($request['id_ketua']);
        }

        $data['id_master']       = bilangan($request['id_master']);
        $data['nama']            = nama_terbatas($request['nama']);
        $data['keterangan']      = htmlentities((string) $request['keterangan']);
        $data['kode']            = nomor_surat_keputusan($request['kode']);
        $data['no_sk_pendirian'] = nomor_surat_keputusan((string) $request['no_sk_pendirian']);
        $data['tipe']            = $this->tipe;

        if (null === $id) {
            $data['config_id'] = identitas('id');

            // slug hanya dibuat pertama kali saat insert, lakukan pengecekan jika nama/slug dengan tipe yang sama sudah ada maka error.
            if (KelompokModel::slugCheck($request['nama'], $this->tipe)) {
                redirect_with('error', 'Slug sudah ada, coba gunakan nama yang lain.', route($this->tipe . '.form'));
            }
        }

        if ($this->request['logo']) {
            $config['upload_path']   = LOKASI_LOGO_DESA;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['file_name']     = namafile($data['slug'] . ' - ' . time());

            $data['logo'] = $this->upload('logo', $config);
        }

        return $data;
    }

    public function delete($id = 0): void
    {
        isCan('h');

        $this->delete_kelompok($id);

        redirect_with('success', 'Berhasil hapus data');
    }

    public function delete_all(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete_kelompok($id);
        }

        redirect_with('success', 'Berhasil hapus data');
    }

    public function to_master($id = 0): void
    {
        $filter = $id;
        if ($filter != 0) {
            $this->session->filter = $filter;
        } else {
            $this->session->unset_userdata(['filter']);
        }

        redirect($this->controller);
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        if ($sex == null) {
            if ($nomor != 0) {
                $this->session->sex = $nomor;
            } else {
                $this->session->unset_userdata('sex');
            }
            $this->session->unset_userdata('judul_statistik');
            redirect($this->controller);
        }

        $this->session->unset_userdata('program_bantuan');
        $this->session->sex = ($sex == 0) ? null : $sex;

        if ($tipe === $tipe > 50) {
            $program_id                     = preg_replace('/^50/', '', $tipe);
            $this->session->program_bantuan = $program_id;
            // TODO: Sederhanakan query ini, pindahkan ke model
            $nama = $this->db
                ->select('nama')
                ->where('id', $program_id)
                ->where('config_id', identitas('id'))
                ->get('program')
                ->row()
                ->nama;
            if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                $this->session->status_dasar = null; // tampilkan semua peserta walaupun bukan hidup/aktif
                $nomor                       = $program_id;
            }
            $kategori = $nama . ' : ';
            $session  = 'penerima_bantuan';
            $tipe     = 'penerima_bantuan';
        }

        $this->session->{$session} = ($nomor != TOTAL) ? $nomor : null;

        $judul = $this->kelompok_model->get_judul_statistik($tipe, $nomor, $sex);

        $this->session->unset_userdata('judul_statistik');
        if ($judul['nama']) {
            $this->session->judul_statistik = $kategori . $judul['nama'];
        }

        redirect($this->controller);
    }

    protected function delete_kelompok($id = '')
    {
        $result = KelompokModel::tipe($this->tipe)
            ->doesntHave('kelompokAnggota')
            ->find($id);

        if (! $result) {
            redirect_with('error', "Tidak bisa menghapus {$this->tipe} yang sudah memiliki anggota");
        }

        $result->delete();
    }
}
