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

use App\Enums\StatusDasarEnum;
use App\Models\LogPenduduk;

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

        // $logs = LogPenduduk::where('id_pend', $penduduk['id'])->get();
        $logs          = $this->db->where('id_pend', $penduduk['id'])->get('log_penduduk')->result_array();
        $nik           = $penduduk['nik'];
        $nama          = $penduduk['nama'];
        $statusDasar   = $penduduk['status_dasar'];
        $kodePeristiwa = $penduduk['kode_peristiwa'];

        return view('periksa.log', ['logs' => $logs, 'kodePeristiwa' => $kodePeristiwa, 'statusDasar' => $statusDasar, 'nik' => $nik, 'nama' => $nama]);
    }

    public function hapusLog()
    {
        $idLog    = $this->input->post('id');
        $idPend   = $this->db->where('id', $idLog)->get('log_penduduk')->row_array()['id_pend'];
        $penduduk = $this->db->where('id', $idPend)->get('tweb_penduduk')->row_array();
        $status   = 0;
        if ($this->db->where('id', $idLog)->delete('log_penduduk')) {
            log_message('notice', 'Hapus log penduduk NIK : ' . $penduduk['nik']);
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
        $idLog       = $this->input->post('id');
        $log         = $this->db->where('id', $idLog)->get('log_penduduk')->row_array();
        $penduduk    = $this->db->where('id', $log['id_pend'])->get('tweb_penduduk')->row_array();
        $key         = $log['kode_peristiwa'];
        $statusDasar = in_array($key, [LogPenduduk::BARU_LAHIR, LogPenduduk::BARU_PINDAH_MASUK]) ? StatusDasarEnum::HIDUP : $key;
        $this->db->where('id', $log['id_pend'])->update('tweb_penduduk', ['status_dasar' => $statusDasar]);

        $status = 0;
        if ($this->db->affected_rows() > 0) {
            log_message('notice', 'Update status dasar penduduk NIK : ' . $penduduk['nik'] . ' dari ' . $penduduk['status_dasar'] . ' menjadi ' . $statusDasar);
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
