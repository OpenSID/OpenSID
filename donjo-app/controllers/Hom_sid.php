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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Libraries\Release;
use App\Models\Bantuan;
use App\Models\Kelompok;
use App\Models\Keluarga;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PendudukMandiri;
use App\Models\Rtm;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Hom_sid extends Admin_Controller
{
    public $isAdmin;

    public function __construct()
    {
        parent::__construct();

        $this->isAdmin = $this->session->isAdmin->pamong;
    }

    public function index()
    {
        get_pesan_opendk(); //ambil pesan baru di opendk

        $this->modul_ini = 1;

        $this->load->library('saas');

        $data = [
            'rilis'           => $this->getUpdate(),
            'bantuan'         => $this->bantuan(),
            'penduduk'        => Penduduk::status()->count(),
            'keluarga'        => Keluarga::status()->count(),
            'rtm'             => Rtm::status()->count(),
            'kelompok'        => Schema::hasColumn('kelompok', 'tipe') ? Kelompok::status()->tipe()->count() : 0,
            'dusun'           => Wilayah::dusun()->count(),
            'pendaftaran'     => Schema::hasColumn('tweb_penduduk_mandiri', 'aktif') ? PendudukMandiri::status()->count() : 0,
            'surat'           => (! $this->db->field_exists('deleted_at', 'log_surat')) ? 0 : $this->logSurat(), // jika kolom deleted_at tidak ada, kosongkan jumlah surat.
            'saas'            => $this->saas->peringatan(),
            'notif_langganan' => $this->pelanggan_model->status_langganan(),
        ];

        return view('admin.home.index', $data);
    }

    private function getUpdate()
    {
        if (cek_koneksi_internet() && ! config_item('demo_mode')) {
            $url_rilis = config_item('rilis_umum');

            $release = new Release();
            $release->setApiUrl($url_rilis)->setCurrentVersion(null);

            $info['update_available'] = $release->isAvailable();
            $info['current_version']  = 'v' . AmbilVersi();
            $info['latest_version']   = $release->getLatestVersion();
            $info['release_name']     = $release->getReleaseName();
            $info['release_body']     = $release->getReleaseBody();
            $info['url_download']     = $release->getReleaseDownload();
        }

        return $info;
    }

    private function bantuan()
    {
        $program                = Bantuan::with('peserta')->whereId($this->setting->dashboard_program_bantuan)->first();
        $bantuan['jumlah']      = $program ? $program->peserta->count() : Bantuan::status()->count();
        $bantuan['nama']        = $program ? $program->nama : 'Bantuan';
        $bantuan['link_detail'] = $program ? ('statistik/clear/50' . $this->setting->dashboard_program_bantuan) : 'program_bantuan';
        $bantuan['program']     = Bantuan::status()->pluck('nama', 'id');

        return $bantuan;
    }

    protected function logSurat()
    {
        return LogSurat::whereNull('deleted_at')
            ->when($this->isAdmin->jabatan_id == '1', static function ($q) {
                return $q->when(setting('tte') == 1, static function ($tte) {
                    return $tte->where('tte', '=', 1);
                })
                    ->when(setting('tte') == 0, static function ($tte) {
                        return $tte->where('verifikasi_kades', '=', '1');
                    })
                    ->orWhere(static function ($verifikasi) {
                        $verifikasi->whereNull('verifikasi_operator');
                    });
            })
            ->when($this->isAdmin->jabatan_id == '2', static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator');
            })
            ->when($this->isAdmin == null || ! in_array($this->isAdmin->jabatan_id, ['1', '2']), static function ($q) {
                return $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator');
            })->count();
    }
}
