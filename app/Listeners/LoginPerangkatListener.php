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

namespace App\Listeners;

use App\Enums\StatusEnum;
use Illuminate\Auth\Events\Login;
use Illuminate\Container\Container;

class LoginPerangkatListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Login $login): void
    {
        if ($login->guard !== 'perangkat') {
            return;
        }

        $this->app['ci']->session->set_userdata('masuk', [
            'pamong_id'   => $login->user->pamong_id,
            'pamong_nama' => $login->user->pamong->penduduk->nama ?? $login->user->pamong->pamong_nama ?? $login->user->nama,
            'jabatan'     => $login->user->pamong->status_pejabat == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $login->user->pamong->jabatan->nama : $login->user->pamong->jabatan->nama,
            'sex'         => $login->user->pamong->penduduk->sex ?? $login->user->pamong->pamong_sex,
            'foto'        => $login->user->pamong->penduduk->foto ?? $login->user->pamong->foto ?? $login->user->foto,
        ]);
    }
}
