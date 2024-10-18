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

use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;

defined('BASEPATH') || exit('No direct script access allowed');

class Grup extends Admin_Controller
{
    public $modul_ini       = 'pengaturan';
    public $sub_modul_ini   = 'pengguna';
    private int $tab_ini    = 11;
    private bool $view_only = false;
    private $ref_grup;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data = [
            'tab_ini' => $this->tab_ini,
            'jenis'   => [UserGrup::SISTEM => 'System', UserGrup::DESA => 'Tambahan'],
        ];

        return view('admin.pengaturan.grup.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(UserGrup::query()->withCount('users'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return $row->jenis == UserGrup::DESA && $row->users_count <= 0 ? '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>' : '';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if ($row->id == 1) {
                        return $aksi;
                    }
                    $aksi .= '<a href="' . ci_route('grup.viewForm', $row->id) . '" class="btn bg-info btn-sm" title="Lihat"><i class="fa fa-eye fa-sm"></i></a> ';

                    if (can('u')) {
                        if ($row->jenis == UserGrup::DESA) {
                            $aksi .= '<a href="' . ci_route('grup.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a> ';
                        }
                        $aksi .= '<a href="' . ci_route('grup.salin', $row->id) . '" class="btn bg-olive btn-sm" title="Salin"><i class="fa fa-copy"></i></a> ';
                    }
                    if (can('h') && $row->jenis == UserGrup::DESA && $row->users_count <= 0) {
                        $aksi .= '<a href="#" data-href="' . ci_route('grup.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })
                ->rawColumns(['aksi', 'ceklist'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        if (! $this->view_only) {
            isCan('u');
        }

        $data['form_action'] = ci_route('grup.insert');
        $data['view']        = $this->view_only;
        $data['grup']        = [];

        $data['moduls']     = Modul::with(['children' => static fn ($q) => $q->isActive()->orderBy('urut')])->isActive()->isParent()->orderBy('urut')->get();
        $idGrup             = $this->ref_grup ?? $id;
        $data['grup_akses'] = $idGrup ? GrupAkses::select(['id_modul', 'akses'])->whereIdGrup($idGrup)->get()->keyBy('id_modul') : collect([]);

        if ($id) {
            $data['grup'] = UserGrup::findOrFail($id)->toArray();
            if (! $this->ref_grup) {
                if (! $this->view_only && $data['grup']['jenis'] == UserGrup::SISTEM) {
                    redirect_with('error', 'Grup Pengguna Tidak Dapat Diubah');
                }
                $data['form_action'] = ci_route('grup.update', $id);
            }
        }

        return view('admin.pengaturan.grup.form', $data);
    }

    public function viewForm($id): void
    {
        $this->view_only = true;
        $this->form($id);
    }

    public function salin($id): void
    {
        $this->ref_grup = $id;
        $this->form();
    }

    public function insert(): void
    {
        isCan('u');
        $this->set_form_validation();
        if ($this->form_validation->run() !== true) {
            redirect_with('error', trim(validation_errors()));
        } else {
            try {
                $nama   = $this->input->post('nama');
                $grup   = UserGrup::create(['nama' => $nama, unique_slug('user_grup', $nama), 'jenis' => UserGrup::DESA]);
                $moduls = $this->input->post('modul');
                $this->simpanAkses($grup->id, $moduls);
                redirect_with('success', 'Grup pengguna berhasil disimpan');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                redirect_with('error', 'Grup pengguna gagal disimpan');
            }
        }
    }

    private function set_form_validation(): void
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nama', 'Nama Grup', 'required|callback_syarat_nama');
        $this->form_validation->set_message('nama', 'Hanya boleh berisi karakter alfanumerik, spasi dan strip');
        $this->form_validation->set_rules('modul[]', 'Akses Modul', 'required');
    }

    public function syarat_nama($str)
    {
        return ! preg_match('/[^a-zA-Z0-9 \\-]/', $str);
    }

    public function update($id): void
    {
        isCan('u');
        $this->set_form_validation();

        if ($this->form_validation->run() !== true) {
            redirect_with('error', trim(validation_errors()));
        } else {
            try {
                $nama = $this->input->post('nama');
                $grup = UserGrup::findOrFail($id);
                if ($grup->jenis == UserGrup::SISTEM) {
                    redirect_with('error', 'Grup pengguna dari sistem tidak boleh dirubah');
                }
                $grup->update(['nama' => $nama, unique_slug('user_grup', $nama)]);
                $moduls = $this->input->post('modul');
                $this->simpanAkses($grup->id, $moduls);
                redirect_with('success', 'Grup pengguna berhasil disimpan');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                redirect_with('error', 'Grup pengguna gagal disimpan');
            }
        }
    }

    private function simpanAkses(string $grupId, array $moduls): void
    {
        $grupAkses = [];
        $configId  = identitas()->id;
        GrupAkses::whereIdGrup($grupId)->delete();

        foreach ($moduls['id'] as $mod) {
            $baca  = $moduls['akses_baca'][$mod] ? 1 : 0;
            $ubah  = $moduls['akses_ubah'][$mod] ? 2 : 0;
            $hapus = $moduls['akses_hapus'][$mod] ? 4 : 0;

            $akses       = $baca + $ubah + $hapus;
            $grupAkses[] = ['config_id' => $configId, 'akses' => $akses, 'id_grup' => $grupId, 'id_modul' => $mod];
        }
        if ($grupAkses) {
            GrupAkses::insert($grupAkses);
        }
        cache()->forget('akses_grup_' . $grupId);
        $this->cache->hapus_cache_untuk_semua('_cache_modul');
    }

    public function delete($id = null): void
    {
        isCan('h');

        try {
            // cek apakah sudah ada user untuk grup tersebut
            $adaUser = UserGrup::whereHas('users')->whereIn('id', $this->request['id_cb'] ?? [$id])->get();
            if (! $adaUser->isEmpty()) {
                redirect_with('error', 'Grup ' . $adaUser->implode('nama', ',') . ' sudah memiliki pengguna, tidak boleh dihapus');
            }
            $adaGrupSistem = UserGrup::where(['jenis' => UserGrup::SISTEM])->whereIn('id', $this->request['id_cb'] ?? [$id])->count();
            if ($adaGrupSistem) {
                redirect_with('error', 'Grup pengguna dari sistem tidak boleh dihapus');
            }
            GrupAkses::whereIn('id_grup', $this->request['id_cb'] ?? [$id])->delete();
            UserGrup::destroy($this->request['id_cb'] ?? $id);
            // cache()->flush();
            $this->cache->hapus_cache_untuk_semua('_cache_modul');
            redirect_with('success', 'Grup pengguna berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('success', 'Grup pengguna gagal dihapus');
        }
    }
}
