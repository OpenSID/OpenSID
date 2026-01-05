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

use Illuminate\Auth\Events\Login;
use Illuminate\Container\Container;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginPendudukListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Login $login): void
    {
        if ($login->guard === 'penduduk') {
            $data = DB::table('tweb_penduduk_mandiri', 'pm')
                ->select('pm.*', 'p.nama', 'p.nik', 'p.tag_id_card', 'p.foto', 'p.kk_level', 'p.id_kk', 'p.sex', 'k.no_kk')
                ->join('penduduk_hidup as p', 'pm.id_pend', 'p.id')
                ->leftJoin('tweb_keluarga as k', 'p.id_kk', 'k.id')
                ->leftJoin('tweb_wil_clusterdesa as c', 'p.id_cluster', 'c.id')
                ->where('pm.id_pend', $login->user->id_pend)
                ->where('pm.config_id', identitas('id'))
                ->first();

            if (akun_demo($data->id_pend, false)) {
                $data->pin       = Hash::driver('md5')->make(config_item('demo_akun')[$data->id_pend]);
                $data->ganti_pin = 1;
            }

            $this->app['ci']->session->set_userdata([
                'mandiri'      => 1,
                'is_anjungan'  => $this->app['ci']?->cek_anjungan,
                'is_login'     => $data,
                'auth_mandiri' => $login->user->penduduk,
            ]);

            $login->user->last_login = Carbon::now();
            $login->user->save();
        }

        if ($login->guard === 'pendudukGuest') {
            $this->app['ci']->session->set_userdata([
                'mandiri'     => 1,
                'is_anjungan' => $this->app['ci']?->cek_anjungan,
                'is_login'    => (object) [
                    'id_pend'     => $login->user->id,
                    'nama'        => $login->user->nama,
                    'nik'         => $login->user->nik,
                    'tag_id_card' => $login->user->tag_id_card,
                    'foto'        => $login->user->foto,
                    'kk_level'    => $login->user->kk_level,
                    'id_kk'       => $login->user->id_kk,
                    'sex'         => $login->user->sex,
                    'no_kk'       => $login->user->no_kk,
                ],
                'auth_mandiri' => $login->user,
            ]);

            activity()
                ->causedBy($login->user)
                ->inLog('Login')
                ->event('Login Penduduk Guest')
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'referer'    => request()->headers->get('referer'),
                ])
                ->log('Login berhasil sebagai Pengguna Anjungan Mandiri (tanpa akun)');
        }
    }
}
