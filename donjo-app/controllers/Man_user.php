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

use App\Models\Artikel;
use App\Models\LogNotifikasiAdmin;
use App\Models\LogSurat;
use App\Models\LogSuratDinas;
use App\Models\Pamong;
use App\Models\User;
use App\Models\UserGrup;
use App\Models\Wilayah;
use App\Services\MasaAktifAkunService;
use App\Traits\UploadFotoUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Man_user extends Admin_Controller
{
    use UploadFotoUser;

    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'pengguna';
    protected MasaAktifAkunService $masaAktifAkunService;
    private int $tab_ini = 10;

    public function __construct()
    {
        // Pastikan MasaAktifAkunService diinisialisasi di sini
        parent::__construct();
        isCan('b');
        $this->masaAktifAkunService = new MasaAktifAkunService();
        $this->form_validation->set_error_delimiters('', '');
    }

    public function index()
    {
        $data['tab_ini'] = $this->tab_ini;

        $data['status'] = [
            ['id' => '1', 'nama' => 'Aktif'],
            ['id' => '0', 'nama' => 'Tidak Aktif'],
        ];
        $data['user_group'] = UserGrup::status()->pluck('nama', 'id');

        if ($this->input->is_ajax_request()) {
            $input     = $this->input;
            $status    = $input->post('status') ?: $input->get('status');
            $isDeleted = $status === 'deleted';

            if ($isDeleted && (! is_super_admin() || ! User::isSoftDeleteReady())) {
                return datatables()->of(collect())->make();
            }

            $group = $input->post('group') ?: $input->get('group');
            $query = User::with('pamong', 'userGrup')
                ->when($isDeleted, static fn ($q) => $q->onlyTrashed())
                ->when(! $isDeleted && $status === '' && is_super_admin(), static fn ($q) => $q->withTrashed())
                ->when(! $isDeleted && $status !== '', static fn ($q) => $q->status($status))
                ->whereHas('userGrup', static function ($q) use ($group): void {
                    if ($group) {
                        $q->where('id', $group);
                    }
                });

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('ceklist', static function ($row): string {
                    if ($row->deleted_at === null && $row->id != super_admin()) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }

                    return '';
                })
                ->addColumn('aksi', static function ($row): string {
                    if ($row->deleted_at !== null) {
                        $nama = htmlspecialchars((string) $row->nama, ENT_QUOTES);
                        $url  = site_url("man_user/restore/{$row->id}");
                        $aksi = View::make('admin.layouts.components.buttons.btn', [
                            'url'        => '#',
                            'judul'      => 'Pulihkan',
                            'icon'       => 'fa fa-undo',
                            'type'       => 'bg-green',
                            'tooltip'    => 'Pulihkan',
                            'onclick'    => "konfirmasiPulihkan('{$url}', '{$nama}')",
                            'modal'      => false,
                            'buttonOnly' => true,
                            'formAction' => '',
                            'slug'       => false,
                            'file'       => false,
                            'disabled'   => false,
                            'blank'      => false,
                            'confirm'    => false,
                            'attribut'   => '',
                        ])->render() . ' ';
                        $aksi .= View::make('admin.layouts.components.buttons.confirm', [
                            'url'            => site_url("man_user/force_delete/{$row->id}"),
                            'type'           => 'bg-maroon',
                            'icon'           => 'fa fa-times',
                            'judul'          => 'Hapus Permanen',
                            'target'         => 'confirm-delete',
                            'method'         => 'POST',
                            'confirmMessage' => '',
                        ])->render();

                        return $aksi;
                    }

                    $aksi = View::make('admin.layouts.components.buttons.edit', ['url' => 'man_user/form/' . $row->id])->render();

                    if (can('u')) {
                        $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                            'url'    => $row->active == '0' ? site_url("man_user/user_unlock/{$row->id}") : site_url("man_user/user_lock/{$row->id}"),
                            'active' => $row->active,
                        ])->render();
                    }

                    if (can('h') && $row->id != super_admin()) {
                        $nama = htmlspecialchars((string) $row->nama, ENT_QUOTES);
                        $url  = site_url("man_user/delete/{$row->id}");
                        $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                            'url'           => $url,
                            'confirmDelete' => false,
                            'judul'         => 'Hapus',
                            'icon'          => 'fa fa-trash-o',
                            'type'          => 'bg-maroon',
                            'onclick'       => "konfirmasiHapus('{$url}', '{$nama}')",
                        ])->render();
                    }

                    return $aksi;
                })
                ->editColumn('url_foto', static fn ($row): string => '<img class="penduduk_kecil" src="' . $row->url_foto . '"/>')
                ->addColumn('pamong_status', static function ($row): string {
                    if ($row->deleted_at !== null) {
                        return '-';
                    }

                    return ($row->pamong && $row->pamong->pamong_status == 1)
                        ? '<span class="label label-success">Staf</span>'
                        : '<span class="label label-info">Bukan Staf</span>';
                })
                ->editColumn('last_login', static fn ($row) => tgl_indo2($row->last_login))
                ->editColumn('email_verified_at', static fn ($row) => tgl_indo2($row->deleted_at ?? $row->email_verified_at))
                ->addColumn('status_label', static function ($row): string {
                    if ($row->deleted_at !== null) {
                        return '<span class="label label-danger">Dihapus</span>';
                    }

                    return $row->active == 1
                        ? '<span class="label label-success">Aktif</span>'
                        : '<span class="label label-danger">Tidak Aktif</span>';
                })
                ->rawColumns(['ceklist', 'aksi', 'url_foto', 'pamong_status', 'status_label'])
                ->make();
        }

        $data['soft_deleted_count'] = User::isSoftDeleteReady() ? User::onlyTrashed()->count() : 0;

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

        $data['wilayah']    = Wilayah::tree();
        $data['user_group'] = UserGrup::status()->when(super_admin() == $id, static function ($query): void {
                                            $query->where('slug', UserGrup::ADMINISTRATOR);
                                        })->get(['id', 'nama']);
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

    // Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
    public function syarat_sandi($str): bool
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', (string) $str));
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

            if ($id == super_admin()) {
                $data['pamong_id'] = null;
            }

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

        $validasi = $this->validate_before_delete((int) $id);
        if (! $validasi['status']) {
            redirect_with('error', $validasi['pesan']);
        }

        $this->delete_user($id);

        redirect_with('success', 'Berhasil Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        $errors = [];

        foreach ($this->request['id_cb'] as $id) {
            $validasi = $this->validate_before_delete((int) $id);
            if (! $validasi['status']) {
                $errors[] = $validasi['pesan'];
            }
        }

        if (! empty($errors)) {
            redirect_with('error', implode('<br>', $errors));
        }

        foreach ($this->request['id_cb'] as $id) {
            $this->delete_user($id);
        }

        redirect_with('success', 'Berhasil Hapus Data');
    }

    public function user_lock($id = ''): void
    {
        isCan('u');

        $user = User::findOrFail($id);

        if ($user->id == super_admin()) {
            redirect_with('error', 'Tidak dapat menonaktifkan akun Super Admin.');
        }

        $user->update(['active' => 0]);

        try {
            $this->masaAktifAkunService->sendAccountActivatedNotification($user);
            set_session('success', 'Notifikasi aktivasi akun berhasil dikirim.');
        } catch (Exception $e) {
            log_message('error', 'Failed to send account activation notification: ' . $e->getMessage());
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function user_unlock($id = ''): void
    {
        isCan('u');

        $user = User::findOrFail($id);
        $user->update([
            'active'     => 1,
            'last_login' => Carbon::now(),
        ]);

        try {
            $this->masaAktifAkunService->sendAccountActivatedNotification($user);
            set_session('success', 'Notifikasi aktivasi akun berhasil dikirim.');
        } catch (Exception $e) {
            log_message('error', 'Failed to send account activation notification: ' . $e->getMessage());
        }
        redirect_with('success', 'Berhasil Ubah Data');

    }

    public function restore($id = ''): void
    {
        if (! is_super_admin()) {
            redirect_with('error', 'Hanya super admin yang dapat memulihkan pengguna.');
        }

        if (! User::isSoftDeleteReady()) {
            redirect_with('error', 'Fitur soft delete belum aktif, jalankan migrasi terlebih dahulu.');
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        redirect_with('success', "Pengguna {$user->nama} berhasil dipulihkan.");
    }

    public function force_delete($id = ''): void
    {
        if (! is_super_admin()) {
            redirect_with('error', 'Hanya super admin yang dapat menghapus pengguna secara permanen.');
        }

        if (! User::isSoftDeleteReady()) {
            redirect_with('error', 'Fitur soft delete belum aktif, jalankan migrasi terlebih dahulu.');
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $nama = $user->nama;
        $user->forceDelete();

        redirect_with('success', "Pengguna {$nama} berhasil dihapus secara permanen.");
    }

    public function cleanup_soft_deleted(): void
    {
        if (! is_super_admin()) {
            redirect_with('error', 'Hanya super admin yang dapat menghapus pengguna secara permanen.');
        }

        $users  = User::isSoftDeleteReady() ? User::onlyTrashed()->get() : collect();
        $jumlah = $users->count();

        foreach ($users as $user) {
            $user->forceDelete();
        }

        redirect_with('success', "Berhasil menghapus permanen {$jumlah} pengguna yang telah dihapus.");
    }

    protected function delete_user($id = ''): void
    {
        User::findOrFail($id)->delete();
    }

    protected function validate_before_delete(int $id): array
    {
        $user = User::findOrFail($id);

        $jumlah_surat = LogSurat::where('id_user', $id)->count();

        $jumlah_surat_dinas = LogSuratDinas::where(static function ($query) use ($id): void {
                $query->where('id_user', $id)
                    ->orWhere('created_by', $id)
                    ->orWhere('updated_by', $id);
            })->count();

        $jumlah_artikel = Artikel::where('id_user', $id)->count();

        $jumlah_notifikasi = LogNotifikasiAdmin::where('id_user', $id)->count();

        $total_aktivitas = $jumlah_surat + $jumlah_surat_dinas + $jumlah_artikel + $jumlah_notifikasi;

        if ($total_aktivitas > 0) {
            $detail = [];
            if ($jumlah_surat > 0) {
                $detail[] = "{$jumlah_surat} surat warga";
            }
            if ($jumlah_surat_dinas > 0) {
                $detail[] = "{$jumlah_surat_dinas} surat dinas";
            }
            if ($jumlah_artikel > 0) {
                $detail[] = "{$jumlah_artikel} artikel";
            }
            if ($jumlah_notifikasi > 0) {
                $detail[] = "{$jumlah_notifikasi} notifikasi";
            }

            return [
                'status' => false,
                'pesan'  => "Pengguna {$user->nama} tidak dapat dihapus karena sudah memiliki aktivitas: "
                          . implode(', ', $detail)
                          . '.<br>Silakan nonaktifkan pengguna ini saja.',
            ];
        }

        return ['status' => true];
    }

    protected function validate($request = [], $id = ''): array
    {
        $isSuperAdmin = $id && (int) $id === super_admin();
        $data         = [
            'active'         => $isSuperAdmin ? 1 : (int) ($request['aktif'] ?? 0),
            'username'       => isset($request['username']) ? alfanumerik($request['username']) : null,
            'nama'           => isset($request['nama']) ? strip_tags((string) nama($request['nama'])) : null,
            'phone'          => isset($request['phone']) ? htmlentities((string) $request['phone']) : null,
            'email'          => empty($request['email']) ? null : htmlentities((string) $request['email']),
            'id_grup'        => $request['id_grup'] ?? null,
            'pamong_id'      => empty($request['pamong_id']) ? null : $request['pamong_id'],
            'foto'           => isset($request['foto']) ? $this->urusFoto($id) : null,
            'notif_telegram' => (int) ($request['notif_telegram'] ?? 0),
            'id_telegram'    => (int) ($request['id_telegram'] ?? 0),
            'config_id'      => identitas('id'),
            'batasi_wilayah' => ! empty($request['akses_wilayah']) ? (int) $request['batasi_wilayah'] : 0,
            'akses_wilayah'  => $request['akses_wilayah'] ?? [],
        ];

        if (! empty($request['password'])) {
            $data['password'] = Hash::make($request['password']);
        }

        if (empty($id)) {
            $data['session'] = md5(now());
        }

        return $data;
    }

    private function set_form_validation(): void
    {
        $this->form_validation->set_rules('password', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
        $this->form_validation->set_message('syarat_sandi', 'Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');
    }
}
