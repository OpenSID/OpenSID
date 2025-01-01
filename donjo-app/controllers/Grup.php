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

use App\Enums\StatusEnum;
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
            'jenis'   => [UserGrup::SISTEM => 'Sistem', UserGrup::DESA => 'Tambahan'],
        ];

        $data['status'] = [
            ['id' => '1', 'nama' => 'Aktif'],
            ['id' => '0', 'nama' => 'Tidak Aktif'],
        ];

        return view('admin.pengaturan.grup.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');

            return datatables()->of(UserGrup::withCount('users')
                ->when($status != '', static function ($query) use ($status): void {
                    $query->status($status);
                }))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h') || can('u')) {
                        return $row->jenis == UserGrup::DESA ? '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>' : '';
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
                        if ($row->status == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('grup.lock', "{$row->id}") . '" class="btn bg-navy btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('grup.lock', "{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a> ';
                        }
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

        $data['status'] = $data['grup']['status'] ?? StatusEnum::YA;

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
                $nama = $this->input->post('nama');
                $grup = UserGrup::create([
                    'nama'   => $nama,
                    'slug'   => unique_slug('user_grup', $nama),
                    'jenis'  => UserGrup::DESA,
                    'status' => $this->input->post('status'),
                ]);
                $moduls = $this->input->post('modul');
                $this->simpanAkses($grup->id, $moduls);
                redirect_with('success', 'Grup pengguna berhasil disimpan');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    redirect_with('error', 'Nama grup pengguna sudah ada');
                }

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
        return ! preg_match('/[^a-zA-Z0-9 \\-]/', (string) $str);
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
                $grup->update([
                    'nama'   => $nama,
                    'slug'   => unique_slug('user_grup', $nama),
                    'status' => $this->input->post('status'),
                ]);
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
        cache()->forget("akses_grup_{$grupId}");
        cache()->forget("{$grupId}_admin_menu");
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

    public function lock($id = 0): void
    {
        isCan('u');

        if (UserGrup::gantiStatus($id, 'status')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    public function ekspor(): void
    {
        isCan('u');

        $id = $this->request['id_cb'];

        if (null === $id) {
            redirect_with('error', 'Tidak ada pengguna yang dipilih.');
        }

        $ekspor = UserGrup::where('jenis', UserGrup::DESA)->whereIn('id', $id)->latest('id')->get();

        foreach ($ekspor as $key => $value) {
            $ekspor[$key]['akses'] = GrupAkses::with(['modul' => static function ($query): void {
                $query->select('id', 'slug');
            }])->where('id_grup', $value->id)->select(['id_modul', 'akses'])->get();
        }

        if ($ekspor->count() === 0) {
            redirect_with('error', 'Tidak ada pengguna yang ditemukan dari pilihan anda.');
        }

        $file_name = namafile('Grup Pengguna') . '.json';
        $ekspor    = $ekspor->map(static fn ($item) => collect($item)->except('id', 'config_id', 'jenis', 'created_at', 'updated_at', 'created_by', 'updated_by')->toArray())->toArray();

        $this->output
            ->set_header("Content-Disposition: attachment; filename={$file_name}")
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($ekspor, JSON_PRETTY_PRINT));
    }

    public function impor(): void
    {
        isCan('u');
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'json';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = time() . '_template_pengguna.json';

        $this->upload->initialize($config);

        if ($this->upload->do_upload('userfile')) {
            $list_data = $this->formatImport(file_get_contents($this->upload->data()['full_path']));
            if ($list_data) {
                $this->impor_filter($list_data);
            }
        }

        redirect_with('error', 'Gagal Impor Data<br/>' . $this->upload->display_errors());
    }

    private function formatImport($list_data = null)
    {
        return collect(json_decode((string) $list_data, true))
            ->map(static fn ($item): array => [
                'config_id'  => identitas('id'),
                'nama'       => $item['nama'],
                'slug'       => $item['slug'],
                'jenis'      => UserGrup::DESA,
                'status'     => $item['status'],
                'akses'      => $item['akses'],
                'created_at' => date('Y-m-d H:i:s'),
                'creted_by'  => ci_auth()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => ci_auth()->id,
            ])
            ->filter(static fn ($item): bool => $item['nama'] && $item['slug'])
            ->toArray();
    }

    public function impor_filter($data)
    {
        set_session('data_impor_pengguna', $data);

        return view('admin.pengaturan.grup.impor_select', [
            'data' => $data,
        ]);
    }

    public function impor_store(): void
    {
        isCan('u');

        $id = $this->request['id_cb'];

        if (null === $id) {
            redirect_with('error', 'Tidak ada grup pengguna yang dipilih.');
        }

        $this->prosesImport(session('data_impor_pengguna'), $id);

        redirect_with('success', 'Berhasil Impor Data');
    }

    private function prosesImport($list_data = null, $id = null): bool
    {
        if ($list_data) {
            foreach ($list_data as $key => $value) {
                $grup = collect($value)->except('akses')->toArray();
                if ($id !== null) {
                    foreach ($id as $row) {
                        if ($row == $key) {
                            if ($user = UserGrup::where('slug', $value['slug'])->first()) {
                                $user->update($grup);
                                GrupAkses::where('id_grup', $user->id)->delete();
                            } else {
                                $user = UserGrup::create($grup);
                            }

                            foreach ($value['akses'] as $row) {
                                if ($id_modul = Modul::where('slug', $row['modul']['slug'])->first()->id) {
                                    $dataInsert = [
                                        'config_id' => identitas('id'),
                                        'id_grup'   => $user->id,
                                        'id_modul'  => $id_modul,
                                        'akses'     => $row['akses'],
                                    ];
                                }
                                GrupAkses::create($dataInsert, ['id_grup'], ['id_modul']);
                            }
                        }
                    }
                }
            }

            return true;
        }

        return false;
    }
}
