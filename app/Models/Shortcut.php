<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Shortcut extends BaseModel
{
    const ACTIVE   = 1;
    const INACTIVE = 0;

    // use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shortcut';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * guarded
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes appended to the model.
     *
     * @var array
     */
    protected $appends = ['count'];

    public function scopeStatus($query, $status = null)
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }


    public static function listIcon()
    {
        $list_icon = [];

        $file = FCPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);

            return array_map(static function ($a) { return explode(':', $a)[0]; }, $list_icon);
        }

        return null;
    }

    public function getCountAttribute()
    {
        try {
            if ($this->jenis_query == 0) {
                return $this->querys()[$this->raw_query];
            } else {
                if (preg_match('/^select/i', $this->raw_query)) {
                    return DB::statement($this->raw_query);
                }
            }

        } catch (\Exception $e) {
            log_message('error', "Query : $this->raw_query. Error : " . $e->getMessage());
            return 0;
        }
    }

    public static function querys() {
        $isAdmin = get_instance()->session->isAdmin->pamong->jabatan_id;

        return [
            // Wilayah
            'Dusun' => \App\Models\Wilayah::dusun()->count(),
            'RW'    => \App\Models\Wilayah::rw()->count(),
            'RT'    => \App\Models\Wilayah::rt()->count(),

            // Penduduk
            'Penduduk' => \App\Models\Penduduk::status()->count(),
            'Penduduk Laki-laki' => \App\Models\Penduduk::status()->where('sex', 1)->count(),
            'Penduduk Perempuan' => \App\Models\Penduduk::status()->where('sex', 2)->count(),
            'Penduduk TagID' => \App\Models\Penduduk::status()->whereNotNull('tag_id')->count(),

            // Keluarga
            'Keluarga' => \App\Models\Keluarga::count(),
            'Kepala Keluarga' => \App\Models\Penduduk::status()->where('kk_level', 1)->count(),
            'Kepala Keluarga Laki-laki' => \App\Models\Penduduk::status()->where('kk_level', 1)->where('sex', 1)->count(),
            'Kepala Keluarga Perempuan' => \App\Models\Penduduk::status()->where('kk_level', 1)->where('sex', 2)->count(),

            // RTM
            'RTM' => \App\Models\RTM::count(),
            'Kepala RTM' => \App\Models\Penduduk::status()->where('rtm_level', 1)->count(),
            'Kepala RTM Laki-laki' => \App\Models\Penduduk::status()->where('rtm_level', 1)->where('sex', 1)->count(),
            'Kepala RTM Perempuan' => \App\Models\Penduduk::status()->where('rtm_level', 1)->where('sex', 1)->count(),

            // Kelompok
            'Kelompok' => \App\Models\Kelompok::status()->tipe()->count(),

            // Lembaga
            'Lembaga' => \App\Models\Kelompok::status()->tipe('lembaga')->count(),

            // Bantuan
            'Bantuan' => \App\Models\Bantuan::count(),

            // Pembangunan
            'Pembangunan' => \App\Models\Pembangunan::count(),

            // Pengaduan
            'Pengaduan' => \App\Models\Pengaduan::count(),
            'Pengaduan Menunggu Diproses' => \App\Models\Pengaduan::where('status', 1)->count(),
            'Pengaduan Sedang Diproses' => \App\Models\Pengaduan::where('status', 2)->count(),
            'Pengaduan Selesai Diproses' => \App\Models\Pengaduan::where('status', 3)->count(),

            // Pengguna
            'Pengguna' => \App\Models\User::count(),
            'Grup Pengguna' => \App\Models\UserGrup::count(),

            // Surat
            'Surat' => LogSurat::whereNull('deleted_at')->count(),
            'Surat Tercetak' => LogSurat::whereNull('deleted_at')
                ->when($isAdmin->jabatan_id == kades()->id, static function ($q) {
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
                ->when($isAdmin->jabatan_id == sekdes()->id, static function ($q) {
                    return $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator');
                })
                ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                    return $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator');
                })->count(),

            // Layanan Mandiri
            'Verifikasi Layanan Mandiri' => \App\Models\PendudukMandiri::status()->count(),

            // Lapak
            'Produk'          => DB::table('produk')->count(),
            'Pelapak'         => DB::table('pelapak')->count(),
            'Kategori Produk' => DB::table('produk_kategori')->count(),
        ];
    }
}
