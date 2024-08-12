<?php

namespace App\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Hash;

class LoginPendudukListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Login $login)
    {
        if ($login->guard !== 'penduduk') {
            return;
        }

        $data = DB::table('tweb_penduduk_mandiri', 'pm')
            ->select('pm.*', 'p.nama', 'p.nik', 'p.tag_id_card', 'p.foto', 'p.kk_level', 'p.id_kk', 'k.no_kk')
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
}