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

use App\Libraries\Release;
use App\Models\Bantuan;
use App\Models\Kelompok;
use App\Models\Keluarga;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PendudukMandiri;
use App\Models\RefJabatan;
use App\Models\Rtm;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Beranda extends Admin_Controller
{
    public $isAdmin;
    public $modul_ini = 'beranda';

    public function __construct()
    {
        parent::__construct();
        $this->isAdmin = $this->session->isAdmin->pamong;
    }

    public function index()
    {
        get_pesan_opendk(); //ambil pesan baru di opendk

        $this->load->library('saas');
        $configId = identitas('id');
        $data     = [
            'rilis'           => $this->getUpdate(),
            'bantuan'         => $this->bantuan(),
            'penduduk'        => Penduduk::status()->count(),
            'keluarga'        => Keluarga::status()->logTerakhir($configId, date('Y-m-d'))->count(),
            'rtm'             => Rtm::status()->count(),
            'kelompok'        => Kelompok::status()->tipe()->count(),
            'dusun'           => Wilayah::dusun()->count(),
            'pendaftaran'     => PendudukMandiri::status()->count(),
            'surat'           => $this->logSurat(),
            'saas'            => $this->saas->peringatan(),
            'notif_langganan' => $this->pelanggan_model->status_langganan(),
        ];

        return view('admin.home.index', $data);
    }

    private function getUpdate(): array
    {
        $info = [];

        if (cek_koneksi_internet() && ! config_item('demo_mode')) {
            $url_rilis = config_item('rilis_umum');

            $release = new Release();
            $release->setApiUrl($url_rilis)->setCurrentVersion();

            if ($release->isAvailable()) {
                $info['update_available'] = $release->isAvailable();
                $info['current_version']  = 'v' . AmbilVersi();
                $info['latest_version']   = $release->getLatestVersion() . (PREMIUM ? '-premium' : '');
                $info['release_name']     = $release->getReleaseName();
                $info['release_body']     = $release->getReleaseBody();
                $info['url_download']     = $release->getReleaseDownload();
            } else {
                $info['update_available'] = false;
            }
        }

        return $info;
    }

    public function hapus_foreign_key($tabel, $nama_constraint, $drop): bool
    {
        $query = $this->db
            ->from('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $this->db->database)
            ->where('REFERENCED_TABLE_NAME', $tabel)
            ->where('CONSTRAINT_NAME', $nama_constraint)
            ->get();

        $hasil = true;
        if ($query->num_rows() > 0) {
            return $hasil && $this->db->query("ALTER TABLE `{$drop}` DROP FOREIGN KEY `{$nama_constraint}`");
        }

        return $hasil;
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

    private function logSurat()
    {
        return LogSurat::whereNull('deleted_at')
            ->when($this->isAdmin->jabatan_id == kades()->id, static fn($q) => $q->when(setting('tte') == 1, static fn($tte) => $tte->where('tte', '=', 1))
                ->when(setting('tte') == 0, static fn($tte) => $tte->where('verifikasi_kades', '=', '1'))
                ->orWhere(static function ($verifikasi): void {
                    $verifikasi->whereNull('verifikasi_operator');
                }))
            ->when($this->isAdmin->jabatan_id == sekdes()->id, static fn($q) => $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator'))
            ->when($this->isAdmin == null || ! in_array($this->isAdmin->jabatan_id, RefJabatan::getKadesSekdes()), static fn($q) => $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator'))->count();
    }
}
