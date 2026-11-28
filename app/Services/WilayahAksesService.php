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

namespace App\Services;

use App\Models\Wilayah;
use Illuminate\Support\Collection;

/**
 * Membungkus Wilayah::treeAccess() (pohon dusun -> rw -> baris RT yang boleh
 * diakses pengguna, sesuai batasi_wilayah/akses_wilayah) supaya query-nya
 * hanya dijalankan sekali per instance, dan dipakai konsisten di seluruh
 * modul Wilayah Administrasi.
 */
class WilayahAksesService
{
    private ?Collection $tree = null;

    public function tree(): Collection
    {
        return $this->tree ??= Wilayah::treeAccess();
    }

    public function bolehDusun(string $dusun): bool
    {
        return $this->tree()->has($dusun);
    }

    public function bolehRw(string $dusun, string $rw): bool
    {
        return (bool) $this->tree()->get($dusun)?->has($rw);
    }

    public function bolehRt(string $dusun, string $rw, string $rt): bool
    {
        $rtList = $this->tree()->get($dusun)?->get($rw);

        return (bool) $rtList?->where('rt', $rt)->isNotEmpty();
    }

    public function bolehWilayah(Wilayah $wilayah): bool
    {
        if ($wilayah->isRt()) {
            return $this->bolehRt($wilayah->dusun, $wilayah->rw, $wilayah->rt);
        }

        if ($wilayah->isRw()) {
            return $this->bolehRw($wilayah->dusun, $wilayah->rw);
        }

        return $this->bolehDusun($wilayah->dusun);
    }

    public function dusunKeys(): Collection
    {
        return $this->tree()->keys();
    }

    public function rwKeys(string $dusun): Collection
    {
        return $this->tree()->get($dusun, collect())->keys();
    }

    public function rtValues(string $dusun, string $rw): Collection
    {
        return $this->tree()->get($dusun, collect())->get($rw, collect())->pluck('rt');
    }

    /** Data dusun/rw/rt untuk peta, dibatasi sesuai akses_wilayah pengguna (lihat Gis::index()) */
    public function gisData(): array
    {
        return [
            'dusun_gis' => Wilayah::dusun()->whereIn('dusun', $this->dusunKeys())->get()->toArray(),
            'rw_gis'    => Wilayah::rw()->get()->filter(fn ($q): bool => $this->bolehRw($q->dusun, $q->rw))->toArray(),
            'rt_gis'    => Wilayah::rt()->get()->filter(fn ($q): bool => $this->bolehRt($q->dusun, $q->rw, $q->rt))->toArray(),
        ];
    }
}
