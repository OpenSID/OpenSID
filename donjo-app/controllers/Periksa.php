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

use App\Libraries\Periksa as LibrariesPeriksa;
use App\Models\Config;
use App\Models\Menu;
use App\Models\Penduduk;
use App\Models\SuplemenTerdata;
use App\Models\User;
use App\Models\UserGrup;
use App\Models\Wilayah;
use App\Repositories\SettingAplikasiRepository;
use App\Services\Auth\Traits\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Periksa extends MY_Controller
{
    use LoginRequest;

    public $setting;
    public $header;
    public $latar_login;
    protected $guard = 'admin_periksa';
    private string $collate;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->db_error['code'] === 1049) {
            redirect('koneksi-database');
        }

        $this->collate = DB::connection('default')->getConfig('collation');

        $this->header      = Config::appKey()->first();
        $latar_login       = (new SettingAplikasiRepository())->firstByKey('latar_login');
        $this->latar_login = default_file(LATAR_LOGIN . $latar_login, DEFAULT_LATAR_SITEMAN);

        view()->share('list_setting', $this->list_setting);
    }

    public function index()
    {
        $this->cekUser();

        if ($this->session->message_query || $this->session->message_exception) {
            log_message('error', $this->session->message_query);
            log_message('error', $this->session->message_exception);
        }

        return view('periksa.index', array_merge((new LibrariesPeriksa())->getPeriksa(), ['header' => $this->header, 'collation' => $this->collate]));
    }

    public function perbaiki(): void
    {
        $this->cekUser();
        (new LibrariesPeriksa())->perbaiki();
        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        redirect('/');
    }

    public function perbaikiSebagian($masalah): void
    {
        $this->cekUser();
        (new LibrariesPeriksa())->perbaikiSebagian($masalah);
        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        redirect('/');
    }

    public function perbaikiPilihan()
    {
        $this->cekUser();

        $pilihan = $this->input->post('pilihan');

        try {
            foreach ($pilihan as $masalah) {
                (new LibrariesPeriksa())->perbaikiSebagian($masalah);
            }
            $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);
        } catch (Exception $e) {
            logger()->error($e);

            return json(['status' => 0, 'message' => 'Terjadi kesalahan saat memproses permintaan.']);
        }

        return json(['status' => 1]);
    }

    public function lepas_kaitan_kk_lama($id)
    {
        $this->cekUser();
        (new LibrariesPeriksa())->lepasKaitanKkLama((int) $id);

        redirect('periksa');
    }

    // Login khusus untuk periksa
    public function login()
    {
        if (Auth::guard($this->guard)->check()) {
            redirect('periksa');
        }

        return view('admin.auth.login', [
            'header'      => $this->header,
            'form_action' => site_url('periksa/auth'),
            'logo_bsre'   => default_file(LOGO_BSRE, false),
            'latar_login' => $this->latar_login,
        ]);
    }

    // Login khusus untuk periksa
    public function auth(): void
    {
        // Check demo mode
        $isDemoMode      = config_item('demo_mode');
        $demoUser        = config_item('demo_user');
        $requestUsername = request('username');
        $requestPassword = request('password');

        if ($isDemoMode && $requestUsername == $demoUser['username'] && $requestPassword == $demoUser['password']) {
            $this->validated(request(), $this->rules());

            // Log in as the first admin user
            $user = User::superAdmin()->first();
            Auth::guard($this->guard)->login($user);
        } else {
            $this->authenticate(['active' => 1, 'id_grup' => UserGrup::getGrupId(UserGrup::ADMINISTRATOR)]);
        }

        $this->session->sess_regenerate();

        redirect('periksa');
    }

    // Periksa tanggal lahir null atau kosong
    public function tanggallahir()
    {
        $this->cekUser();

        $dataPenduduk = array_combine($this->input->post('id'), $this->input->post('tanggallahir'));

        foreach ($dataPenduduk as $id => $tanggallahir) {
            Penduduk::where('id', $id)->update(['tanggallahir' => $tanggallahir]);
        }

        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        return json(['status' => 1]);
    }

    public function datanull()
    {
        $this->cekUser();

        $fields = [
            'nama', 'nik', 'sex', 'kk_level', 'tempatlahir', 'tanggallahir',
            'agama_id', 'pendidikan_kk_id', 'pekerjaan_id', 'golongan_darah_id',
            'status_kawin', 'warganegara_id', 'nama_ayah', 'nama_ibu',
            'dokumen_pasport', 'dokumen_kitas',
        ];

        $updateData = [];

        foreach ($fields as $field) {
            $value = $this->input->post($field);
            if (! empty($value)) {
            $updateData[$field] = $value;
            }
        }

        if (! empty($updateData)) {
            Penduduk::where('id', $this->input->post('id'))->update($updateData);
        }

        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        return json(['status' => 1]);
    }

    public function datacluster()
    {
        $this->cekUser();
        $dusun = $this->input->post('dusun');

        if (! empty($dusun)) {
            $duplikat_sama = DB::table('tweb_wil_clusterdesa as w1')
                ->where('w1.config_id', identitas('id'))
                ->join('tweb_wil_clusterdesa as w2', static function ($join) {
                    $join->on(DB::raw('LOWER(TRIM(w1.dusun))'), '=', DB::raw('LOWER(TRIM(w2.dusun))'))
                        ->whereRaw('BINARY TRIM(w1.dusun) <> BINARY TRIM(w2.dusun)')
                        ->whereColumn('w1.rw', '=', 'w2.rw')
                        ->whereColumn('w1.rt', '=', 'w2.rt');
                })
                ->whereRaw('LOWER(TRIM(w1.dusun)) = LOWER(TRIM(?))', [$dusun])
                ->whereRaw('BINARY TRIM(w1.dusun) <> BINARY TRIM(?)', [$dusun])
                ->select('w1.id', 'w1.dusun', 'w1.rw', 'w1.rt')
                ->distinct()
                ->orderByRaw('TRIM(w1.dusun)')
                ->get()
                ->map(static fn ($i) => (array) $i)->toArray();

            foreach ($duplikat_sama as $item) {
                if (Penduduk::where('id_cluster', $item['id'])->count() == 0) {
                    Wilayah::where('id', $item['id'])->delete();
                } else {
                    $id_cluster = Wilayah::whereRaw('BINARY dusun = ?', [$dusun])->where('rw', $item['rw'])->where('rt', $item['rt'])->first()->id;
                    Penduduk::where('id_cluster', $item['id'])->update(['id_cluster' => $id_cluster]);
                    Wilayah::where('id', $item['id'])->delete();
                }
            }

            $duplikat_tidak_sama = DB::table('tweb_wil_clusterdesa as w1')
                ->where('w1.config_id', identitas('id'))
                ->join('tweb_wil_clusterdesa as w2', static function ($join) {
                    $join->on(DB::raw('LOWER(TRIM(w1.dusun))'), '=', DB::raw('LOWER(TRIM(w2.dusun))'))
                        ->whereRaw('BINARY TRIM(w1.dusun) <> BINARY TRIM(w2.dusun)');
                })
                ->whereRaw('LOWER(TRIM(w1.dusun)) = LOWER(TRIM(?))', [$dusun])
                ->whereRaw('BINARY TRIM(w1.dusun) <> BINARY TRIM(?)', [$dusun])
                ->select('w1.id', 'w1.dusun', 'w1.rw', 'w1.rt')
                ->distinct()
                ->orderByRaw('TRIM(w1.dusun)')
                ->get()
                ->map(static fn ($i) => (array) $i)->toArray();

            foreach ($duplikat_tidak_sama as $item) {
                Wilayah::where('id', $item['id'])->update(['dusun' => $dusun]);
            }
        }

        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        return json(['status' => 1]);
    }

    public function menuTanpaParent()
    {
        $this->cekUser();

        $ids     = (array) $this->input->post('id');
        $parents = (array) $this->input->post('parrent');

        // pastikan jumlah sama
        if (! empty($ids) && ! empty($parents) && count($ids) === count($parents)) {
            $dataMenu = array_combine($ids, $parents);

            foreach ($dataMenu as $id => $parrent) {
                if (! empty($parrent)) {
                    Menu::where('id', $id)->update(['parrent' => $parrent]);
                }
            }
        }

        $this->session->unset_userdata([
            'db_error', 'message', 'message_query',
            'heading', 'message_exception',
        ]);

        return json(['status' => 1]);
    }

    // Periksa tanggal lahir null atau kosong
    public function suplemenTerdata()
    {
        $this->cekUser();

        $suplemenTerdataSasaran = $this->input->post('suplemen_terdata');
        $listIdTerdata          = [];

        foreach ($suplemenTerdataSasaran as $sasaran => $suplemenTerdata) {
            foreach ($suplemenTerdata as $id => $idTerdata) {
                if ($idTerdata) {
                    $updateData = ['id_terdata' => $idTerdata];
                    if ($sasaran == SuplemenTerdata::PENDUDUK) {
                        $updateData['penduduk_id'] = $idTerdata;
                    }
                    if ($sasaran == SuplemenTerdata::KELUARGA) {
                        $updateData['keluarga_id'] = $idTerdata;
                    }
                    SuplemenTerdata::where('id', $id)->update($updateData);
                }
            }
        }

        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        return json(['status' => 1]);
    }

    protected function rules()
    {
        $captcha = [];

        if (setting('google_recaptcha')) {
            $captcha = [
                'g-recaptcha-response' => 'required|captcha',
            ];
        }

        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            ...$captcha,
        ];
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey()
    {
        return Str::transliterate(Str::lower(request('username')) . '|' . request()->ip());
    }

    private function cekUser(): void
    {
        if (! Auth::guard($this->guard)->check()) {
            redirect('periksa/login');
        }
    }
}
