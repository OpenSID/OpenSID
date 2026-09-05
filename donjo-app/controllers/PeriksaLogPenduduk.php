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

use App\Enums\PeristiwaPendudukEnum;
use App\Enums\StatusDasarEnum;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class PeriksaLogPenduduk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->cek_user();
    }

    public function index()
    {
        $penduduk = $this->input->get('penduduk');

        $logs = DB::table('log_penduduk')
            ->where('id_pend', $penduduk['id'])
            ->where('config_id', identitas('id'))
            ->get()
            ->toArray();

        $nik           = $penduduk['nik'];
        $nama          = $penduduk['nama'];
        $statusDasar   = $penduduk['status_dasar'];
        $kodePeristiwa = $penduduk['kode_peristiwa'];

        return view('periksa.log', ['logs' => $logs, 'kodePeristiwa' => $kodePeristiwa, 'statusDasar' => $statusDasar, 'nik' => $nik, 'nama' => $nama]);
    }

    public function hapusLog()
    {
        $idLog = $this->input->post('id');

        $idPend = DB::table('log_penduduk')
            ->where('id', $idLog)
            ->where('config_id', identitas('id'))
            ->value('id_pend');

        $penduduk = DB::table('tweb_penduduk')
            ->where('id', $idPend)
            ->first();

        $status = 0;
        if (DB::table('log_penduduk')->where('id', $idLog)->where('config_id', identitas('id'))->delete()) {
            log_message('notice', 'Hapus log penduduk NIK : ' . $penduduk->nik);
            $status = 1;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => $status,
            ], JSON_THROW_ON_ERROR));
    }

    public function updateStatusDasar()
    {
        $idLog = $this->input->post('id');

        $log = DB::table('log_penduduk')
            ->where('id', $idLog)
            ->where('config_id', identitas('id'))
            ->first();

        $penduduk = DB::table('tweb_penduduk')
            ->where('id', $log->id_pend)
            ->where('config_id', identitas('id'))
            ->first();

        $key         = $log->kode_peristiwa;
        $statusDasar = in_array($key, [PeristiwaPendudukEnum::BARU_LAHIR->value, PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value]) ? StatusDasarEnum::HIDUP : $key;

        $affected = DB::table('tweb_penduduk')
            ->where('id', $log->id_pend)
            ->where('config_id', identitas('id'))
            ->update(['status_dasar' => $statusDasar]);

        $status = 0;
        if ($affected > 0) {
            log_message('notice', 'Update status dasar penduduk NIK : ' . $penduduk->nik . ' dari ' . $penduduk->status_dasar . ' menjadi ' . $statusDasar);
            $status = 1;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => $status,
            ], JSON_THROW_ON_ERROR));
    }

    private function cek_user(): void
    {
        if (! auth('admin_periksa')->check()) {
            redirect('periksa/login');
        }
    }
}
