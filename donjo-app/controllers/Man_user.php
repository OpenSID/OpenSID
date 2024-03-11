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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Pamong;
use App\Models\User;
use App\Models\UserGrup;

class Man_user extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'pengguna';
    private int $tab_ini  = 10;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function index()
    {
        $data['tab_ini'] = $this->tab_ini;

        $data['status'] = [
            ['id' => '1', 'nama' => 'Aktif'],
            ['id' => '0', 'nama' => 'Tidak Aktif'],
        ];
        $data['user_group'] = UserGrup::pluck('nama', 'id');

        if ($this->input->is_ajax_request()) {
            $input  = $this->input;
            $status = $input->get('status');

            return datatables()->of(
                User::with('pamong', 'userGrup')
                    ->when($status != '', static function ($query) use ($status): void {
                        $query->status($status);
                    })
                    ->whereHas('userGrup', function ($query): void {
                        if ($group = $this->input->get('group')) {
                            $query->where('id', $group);
                        }
                    })
            )
                ->addIndexColumn()
                ->addColumn('ceklist', static function ($row) {
                    if ($row->id != super_admin()) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("man_user/form/{$row->id}") . '" class="btn bg-orange btn-sm" title="Ubah"><i class="fa fa-edit"></i></a> ';
                    }
                    if ($row->id != super_admin()) {
                        if (can('u')) {
                            if ($row->active == '0') {
                                $aksi .= '<a href="' . site_url("man_user/user_unlock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan Pengguna"><i class="fa fa-lock"></i></a> ';
                            } elseif ($row->active == '1') {
                                $aksi .= '<a href="' . site_url("man_user/user_lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Non Aktifkan Pengguna"><i class="fa fa-unlock"></i></a> ';
                            }
                        }
                        if (can('h')) {
                            $aksi .= '<a href="#" data-href="' . site_url("man_user/delete/{$row->id}") . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->addColumn('pamong_status', static fn ($row): string => $row->pamong->pamong_status == 1
                    ? '<span class="label label-success">Staf</span>'
                    : '<span class="label label-info">Bukan Staf</span>')
                ->editColumn('last_login', static fn ($row) => tgl_indo2($row->last_login))
                ->editColumn('email_verified_at', static fn ($row) => tgl_indo2($row->email_verified_at))
                ->rawColumns(['ceklist', 'aksi', 'pamong_status'])
                ->make();
        }

        return view('admin.pengaturan.pengguna.index', $data);
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['user']        = User::findOrFail($id);
            $data['form_action'] = site_url("man_user/update/{$id}");
            $data['action']      = 'Ubah';
        } else {
            $data['user']        = null;
            $data['form_action'] = site_url('man_user/insert');
            $data['action']      = 'Tambah';
        }

        $data['user_group']          = UserGrup::get(['id', 'nama']);
        $data['akses']               = (new UserGrup())->getGrupSistem();
        $data['pamong']              = Pamong::selectData()->aktif()->bukanPengguna($id)->get();
        $data['notifikasi_telegram'] = setting('telegram_notifikasi');

        return view('admin.pengaturan.pengguna.form', $data);
    }

    public function insert(): void
    {
        isCan('u');
        $this->set_form_validation();
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'is_unique[user.email]');
        $this->form_validation->set_rules([
            [
                'field'  => 'pamong_id',
                'label'  => 'Pamong',
                'rules'  => 'is_unique[user.pamong_id]',
                'errors' => [
                    'is_unique' => 'pengguna tersebut sudah ada',
                ],
            ],
        ]);

        if ($this->form_validation->run() !== true) {
            redirect_with('error', trim(validation_errors()), 'man_user/form');
        } else {
            $data = $this->validate($this->input->post());

            (new User($data))->save();

            redirect_with('success', 'Berhasil Tambah Data');
        }
    }

    private function set_form_validation(): void
    {
        $this->form_validation->set_rules('password', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
        $this->form_validation->set_message('syarat_sandi', 'Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');
    }

    // Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
    public function syarat_sandi($str): bool
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str));
    }

    public function update($id = ''): void
    {
        isCan('u');
        if ($this->input->post('password') != '') {
            $this->set_form_validation();
        }
        $this->form_validation->set_rules('username', 'Username', "required|is_unique[user.username,id,{$id}]");
        $this->form_validation->set_rules('email', 'Email', "is_unique[user.email,id,{$id}]");
        $this->form_validation->set_rules([
            [
                'field'  => 'pamong_id',
                'label'  => 'Pamong',
                'rules'  => "is_unique[user.pamong_id,id,{$id}]",
                'errors' => [
                    'is_unique' => 'pengguna tersebut sudah ada',
                ],
            ],
        ]);

        if ($this->form_validation->run() !== true) {
            redirect_with('error', trim(validation_errors()), "man_user/form/{$id}");
        } else {
            $data = $this->validate($this->input->post(), $id);

            // Untuk demo jangan ubah username atau password
            if ($id == UserGrup::where('slug', UserGrup::ADMINISTRATOR)->first()->id && (config_item('demo_mode') || ENVIRONMENT === 'development')) {
                unset($data['username'], $data['password']);
            }

            User::findOrFail($id)->update($data);

            // perbaharui session login
            if ((string) $id === (string) $this->session->isAdmin->id) {
                $this->session->isAdmin = User::find($id);
            }

            $this->cache->file->delete("{$id}_cache_modul");

            redirect_with('success', 'Berhasil Ubah Data');
        }
    }

    public function delete($id = ''): void
    {
        isCan('h');

        $this->delete_user($id);

        redirect_with('success', 'Berhasil Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete_user($id);
        }

        redirect_with('success', 'Berhasil Hapus Data');
    }

    public function user_lock($id = ''): void
    {
        isCan('u');

        User::findOrFail($id)->update(['active' => 0]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function user_unlock($id = ''): void
    {
        isCan('u');

        User::findOrFail($id)->update(['active' => 1]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    protected function delete_user($id = '')
    {
        $user = User::findOrFail($id);

        if ($user->foto != 'kuser.png') {
            // Ambil nama foto
            $foto = basename(AmbilFoto($user->foto));
            unlink(LOKASI_USER_PICT . $foto);
        }

        $user->delete();
    }

    protected function validate($request = [], $id = ''): array
    {
        $data = [
            'active'         => (int) ($request['aktif'] ?? 0),
            'username'       => isset($request['username']) ? alfanumerik($request['username']) : null,
            'nama'           => isset($request['nama']) ? strip_tags(nama($request['nama'])) : null,
            'phone'          => isset($request['phone']) ? htmlentities($request['phone']) : null,
            'email'          => empty($request['email']) ? null : htmlentities($request['email']),
            'id_grup'        => $request['id_grup'] ?? null,
            'pamong_id'      => empty($request['pamong_id']) ? null : $request['pamong_id'],
            'foto'           => isset($request['foto']) ? $this->user_model->urusFoto($id) : null,
            'notif_telegram' => (int) ($request['notif_telegram'] ?? 0),
            'id_telegram'    => (int) ($request['id_telegram'] ?? 0),
            'config_id'      => identitas('id'),
        ];

        if (! empty($request['password'])) {
            $data['password'] = generatePasswordHash($request['password']);
        }

        if (empty($id)) {
            $data['session'] = md5(now());
        }

        return $data;
    }
}
