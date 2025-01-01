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

use App\Models\Keluarga;
use App\Models\PendudukSaja;

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\SHDKEnum;
use App\Models\Penduduk;

class PeriksaKepalaKeluargaGanda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cek_user();
    }

    public function index()
    {
        $excludeSHDK = [SHDKEnum::KEPALA_KELUARGA];
        $validSHDK   = collect(SHDKEnum::all())->filter(static fn ($key, $item) => ! in_array($item, $excludeSHDK ))->all();
        $id          = $this->input->get('id');

        return view('periksa.kepala_keluarga_ganda', ['hubungan' => $validSHDK, 'id' => $id]);
    }

    public function ubahShdk()
    {
        $id      = $this->input->post('id');
        $kkLevel = $this->input->post('kk_level');
        $status  = 0;

        $penduduk = PendudukSaja::find($id);
        if ($penduduk) {
            // Periksa apakah masih ada yang berkedudukan sebagai kepala keluarga di keluarga tersebut selain penduduk ini
            $kepalaKeluargaLain = PendudukSaja::where(['id_kk' => $penduduk->id_kk, 'kk_level' => SHDKEnum::KEPALA_KELUARGA])->where('id', '!=', $id)->get();
            if (! $kepalaKeluargaLain->isEmpty()) {
                $penduduk->kk_level = $kkLevel;
                $penduduk->save();

                if ($kepalaKeluargaLain->count() == 1) {
                    Keluarga::where('id', $kepalaKeluargaLain->first()->id_kk)->update(['nik_kepala' => $kepalaKeluargaLain->first()->id]);
                }
                log_message('notice', 'Ubah shdk penduduk dengan nik ' . $penduduk->nik . 'berhasil' );
                $status = 1;
            }
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
