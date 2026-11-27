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

use App\Bootstrap\CodeIgniterBootstrap;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property CI_Benchmark        $benchmark
 * @property CI_Config           $config
 * @property CI_DB_query_builder $db
 * @property CI_Form_validation  $form_validation
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Loader           $loader
 * @property CI_Log              $log
 * @property CI_Output           $output
 * @property CI_Router           $router
 * @property CI_Security         $security
 * @property CI_Session          $session
 * @property CI_URI              $uri
 * @property CI_Utf8             $utf8
 */
class Install extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('installer');
        $this->folder_lainnya();
    }

    /**
     * Step 1
     */
    public function index()
    {
        $this->session->instalasi = true;

        // disable install
        if (file_exists(DESAPATH)) {
            show_404();
        }

        return view('installer.steps.welcome');
    }

    /**
     * Step 2
     */
    public function server()
    {
        // disable install
        if (file_exists(DESAPATH)) {
            show_404();
        }

        return view('installer.steps.server', [
            'result' => $this->check_server(),
        ]);
    }

    /**
     * Step 3
     */
    public function folders()
    {
        // disable install
        if (file_exists(DESAPATH)) {
            show_404();
        }

        if (! $this->check_server()) {
            return redirect('install/server');
        }

        return view('installer.steps.folders', [
            'result' => $this->check_folders(),
        ]);
    }

    /**
     * Step 4
     */
    public function database()
    {
        // disable install
        if (file_exists(DESAPATH)) {
            show_404();
        }

        if (! $this->check_server() || ! $this->check_folders()) {
            return redirect('install/folders');
        }

        if ($this->input->method() === 'get') {
            return view('installer.steps.database');
        }

        $this->form_validation->set_error_delimiters(
            '<span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">',
            '</span>'
        );

        $this->form_validation
            ->set_rules('database_hostname', 'Database host', 'required')
            ->set_rules('database_port', 'Database port', 'required|integer')
            ->set_rules('database_name', 'Database name', 'required')
            ->set_rules('database_username', 'Database username', 'required');

        if (! $this->form_validation->run()) {
            return view('installer.steps.database');
        }

        try {
            $connection = new PDO(
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    $this->input->post('database_hostname'),
                    $this->input->post('database_port'),
                    $this->input->post('database_name')
                ),
                $this->input->post('database_username'),
                $this->input->post('database_password')
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            log_message('error', $e);
            $this->session->set_flashdata('errors', 'Tidak berhasil terkoneksi ke database, mohon periksa konfigurasi database di server Anda!');

            return redirect('install/database');
        }

        try {
            $this->load->database(
                $this->config_database($this->input->post()),
                true
            );
        } catch (Exception $e) {
            log_message('error', $e);
            $this->session->set_flashdata('errors', $e->getMessage());

            return redirect('install/database');
        }

        return redirect('install/migrations');
    }

    /**
     * Step 5
     */
    public function migrations()
    {
        // disable install
        if (file_exists(DESAPATH)) {
            show_404();
        }
        $this->load->database($this->config_database());

        if (
            ! $this->db
            || ! $this->check_server()
            || ! $this->check_folders()
        ) {
            return redirect('install/database');
        }

        if ($this->input->method() === 'get') {
            return view('installer.steps.migrations');
        }

        try {
            folder_desa();

            // Titik yang tepat untuk symlink publik: folder desa/* baru saja
            // dibuat oleh folder_desa(), jadi target symlink pasti sudah ada.
            //
            // storage:link tidak membuat folder induk; bila public/desa belum ada
            // symlink() gagal DIAM-DIAM dan command tetap melaporkan berhasil.
            File::ensureDirectoryExists(public_path('desa'));
            Artisan::call('storage:link', ['--relative' => true]);

            CodeIgniterBootstrap::configureConfig(app());
            DB::purge('default');
            DB::reconnect('default');

            $this->load->model('seeders/seeder');

            return redirect('install/user');
        } catch (Exception $e) {
            log_message('error', $e);
            $this->session->set_flashdata('errors', $e->getMessage());

            return redirect('install/migrations');
        }
    }

    /**
     * Step 6
     */
    public function user()
    {
        $this->load->database();

        if (
            ! $this->db
            || ! file_exists(DESAPATH)
            || ! $this->check_server()
            || ! $this->check_folders()
        ) {
            return redirect('install/migrations');
        }

        // disable install jika sudah mengubah password default
        if (! Hash::check('sid304', User::first()->password)) {
            show_404();
        }

        if ($this->input->method() === 'get') {
            return view('installer.steps.user');
        }

        $this->form_validation->set_error_delimiters(
            '<span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">',
            '</span>'
        );

        $this->form_validation
            ->set_rules('username', 'Username', 'required')
            ->set_rules('password', 'Password', 'required|callback_syarat_sandi')
            ->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');

        if (! $this->form_validation->run()) {
            return view('installer.steps.user');
        }

        User::where('username', 'admin')
            ->update([
                'username' => $this->input->post('username'),
                'password' => Hash::make($this->input->post('password')),
            ]);

        return redirect('install/finish');
    }

    /**
     * Step 7
     */
    public function finish(): void
    {
        $this->session->unset_userdata([
            'errors',
            'hostname',
            'port',
            'username',
            'password',
            'database',
            'instalasi',
        ]);

        redirect('/');
    }

    public function syarat_sandi($password)
    {
        if (! preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', (string) $password)) {
            $this->form_validation->set_message('syarat_sandi', SYARAT_SANDI);

            return false;
        }

        return true;
    }

    /**
     * Menyiapkan folder yang TIDAK bergantung pada folder desa.
     *
     * Berjalan di konstruktor, sehingga hanya boleh mengerjakan hal yang
     * syaratnya sudah terpenuhi pada setiap langkah wizard. Symlink folder
     * publik desa sengaja TIDAK dibuat di sini: folder desa/* baru ada setelah
     * folder_desa() pada langkah migrasi, jadi pembuatannya diletakkan di sana.
     */
    public function folder_lainnya(): void
    {
        foreach (config_item('lainnya') as $folder => $lainnya) {
            folder($folder, $lainnya[0], $lainnya[1], $lainnya[2] ?? []);
        }

        copyFavicon();
    }

    private function check_server(): bool
    {
        return $this->semua_syarat_wajib_terpenuhi($this->config->item('server'));
    }

    private function check_folders(): bool
    {
        return $this->semua_syarat_wajib_terpenuhi($this->config->item('folders'));
    }

    /**
     * Pemeriksaan bertanda 'peringatan' hanya ditampilkan, tidak menghalangi
     * instalasi.
     *
     * Contohnya document root yang belum diarahkan ke folder public dan symlink
     * folder publik desa. Keduanya penting untuk keamanan dan tampilan, tetapi
     * ada hosting yang memang tidak mengizinkannya (document root cPanel yang
     * terkunci, atau symlink yang dilarang). Memblokir instalasi pada kondisi itu
     * membuat OpenSID sama sekali tidak dapat dipasang, sehingga pengguna
     * diberi peringatan dan tetap dipersilakan melanjutkan.
     *
     * @param array<string, array<string, mixed>> $daftar
     */
    private function semua_syarat_wajib_terpenuhi(array $daftar): bool
    {
        foreach ($daftar as $check) {
            if (! empty($check['peringatan'])) {
                continue;
            }

            if (! $check['check']()) {
                return false;
            }
        }

        return true;
    }

    private function config_database(array $request = []): array
    {
        if (! $this->session->has_userdata('hostname') && isset($request['database_hostname'])) {
            $this->session->set_userdata([
                'hostname' => $request['database_hostname'],
                'port'     => $request['database_port'],
                'username' => $request['database_username'],
                'password' => $request['database_password'],
                'database' => $request['database_name'],
            ]);
        }

        $db = '$db';

        config([
            'installer.database' => <<<EOS
                <?php
                // -------------------------------------------------------------------------
                //
                // Letakkan username, password dan database sebetulnya di file ini.
                // File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
                // -------------------------------------------------------------------------

                // Data Konfigurasi MySQL yang disesuaikan

                {$db}['default']['hostname'] = '{$this->session->hostname}';
                {$db}['default']['username'] = '{$this->session->username}';
                {$db}['default']['password'] = '{$this->session->password}';
                {$db}['default']['port']     = {$this->session->port};
                {$db}['default']['database'] = '{$this->session->database}';
                {$db}['default']['dbcollat'] = 'utf8mb4_general_ci';

                /*
                | Untuk setting koneksi database 'Strict Mode'
                | Sesuaikan dengan ketentuan hosting
                */
                {$db}['default']['stricton'] = true;

                /*
                | Konfigurasi options digunakan untuk menyisipkan opsi tambahan
                | saat mengatur koneksi ke database.
                */
                {$db}['default']['options'] = [
                    // PDO::ATTR_EMULATE_PREPARES => true,
                ];
                EOS
        ]);

        return [
            'dsn'      => '',
            'hostname' => $this->session->hostname,
            'port'     => $this->session->port,
            'username' => $this->session->username,
            'password' => $this->session->password,
            'database' => $this->session->database,
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => true,
            'db_debug' => true,
            'cache_on' => false,
            'cachedir' => '',
            'char_set' => 'utf8mb4',
            'dbcollat' => 'utf8mb4_general_ci',
            'swap_pre' => '',
            'encrypt'  => false,
            'compress' => false,
            'stricton' => false,
            'failover' => [],
        ];
    }
}
