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

namespace App\Libraries;

use App\Enums\JenisKelaminEnum;
use App\Enums\SasaranEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Enums\StatusDasarEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Kelompok;
use App\Models\KeluargaAktif;
use App\Models\PendudukHidup;
use App\Models\Rtm;

class Statistik
{
    public static function bantuan($lap, $filter = [])
    {
        $program = false;
        $sasaran = SasaranEnum::PENDUDUK;

        if (in_array($lap, array_keys(StatistikJenisBantuanEnum::allKeyLabel()))) {
            if ($lap == StatistikJenisBantuanEnum::KELUARGA['key']) {
                $sasaran = SasaranEnum::KELUARGA;
            }
        } else {
            $lap     = preg_replace('/^50/', '', $lap);
            $sasaran = Bantuan::find($lap)?->sasaran;
            $program = true;
        }

        $bantuan = (new Bantuan())->whereSasaran($sasaran);
        $label   = 'PENERIMA';

        $cluster = $filter['cluster'];
        if ($filter['tahun']) {
            $bantuan->whereRaw("YEAR(sdate) <= {$filter['tahun']}")->whereRaw("YEAR(edate) >= {$filter['tahun']}");
        }
        if ($filter['status']) {
            $bantuan->whereStatus($filter['status']);
        }

        if ($program) {
            $bantuan->where('id', $lap);
            $label = 'PESERTA';
        }
        $data = $bantuan->withCount(['peserta as peserta_lakilaki_count' => static function ($query) use ($sasaran, $cluster) {
            $query->when($sasaran == SasaranEnum::PENDUDUK, static fn ($query) => $query->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->when($sasaran == SasaranEnum::KELUARGA, static fn ($query) => $query->whereHas('keluarga', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::RUMAH_TANGGA, static fn ($query) => $query->whereHas('rtm', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::KELOMPOK, static fn ($query) => $query->whereHas('kelompok', static fn ($q) => $q->whereHas('ketua', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))));
        }, 'peserta as peserta_perempuan_count' => static function ($query) use ($sasaran, $cluster) {
            $query->when($sasaran == SasaranEnum::PENDUDUK, static fn ($query) => $query->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->when($sasaran == SasaranEnum::KELUARGA, static fn ($query) => $query->whereHas('keluarga', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::RUMAH_TANGGA, static fn ($query) => $query->whereHas('rtm', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::KELOMPOK, static fn ($query) => $query->whereHas('kelompok', static fn ($q) => $q->whereHas('ketua', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))));
        }])->get();

        $total  = self::getTotal($sasaran);
        $result = $data->map(static fn ($item) => ['id' => $item->id, 'nama' => $item->nama, 'jumlah' => $item->peserta_lakilaki_count + $item->peserta_perempuan_count, 'persen' => persen2($item->peserta_lakilaki_count + $item->peserta_perempuan_count, $total['lk'] + $total['pr']), 'laki' => $item->peserta_lakilaki_count, 'persen1' => persen2($total['lk'], $item->peserta_lakilaki_count), 'perempuan' => $item->peserta_perempuan_count, 'persen2' => persen2($item->peserta_perempuan_count, $total['lk'] + $total['pr'])]);

        $resume = [
            ['id' => JUMLAH, 'nama' => $label, 'jumlah' => $result->sum('jumlah'), 'persen' => persen2($result->sum('jumlah'), $total['lk'] + $total['pr']), 'laki' => $result->sum('laki'), 'persen1' => persen2($result->sum('laki'), $total['lk'] + $total['pr']), 'perempuan' => $result->sum('perempuan'), 'persen2' => persen2($result->sum('perempuan'), $total['lk'] + $total['pr'])],
            ['id' => BELUM_MENGISI, 'nama' => 'BUKAN ' . $label, 'jumlah' => $total['lk'] + $total['pr'] - $result->sum('jumlah'), 'persen' => persen2($total['lk'] + $total['pr'] - $result->sum('jumlah'), $total['lk'] + $total['pr']), 'laki' => $total['lk'] - $result->sum('laki'), 'persen1' => persen2($total['lk'] - $result->sum('laki'), $total['lk']), 'perempuan' => $total['pr'] - $result->sum('perempuan'), 'persen2' => persen2($total['pr'] - $result->sum('perempuan'), $total['lk'] + $total['pr'])],
            ['id' => TOTAL, 'nama' => 'TOTAL', 'jumlah' => $total['lk'] + $total['pr'], 'persen' => persen2($total['lk'] + $total['pr'], $total['lk'] + $total['pr']), 'laki' => $total['lk'], 'persen1' => persen2($total['lk'], $total['lk'] + $total['pr']), 'perempuan' => $total['pr'], 'persen2' => persen2($total['pr'], $total['lk'] + $total['pr'])],
        ];

        if ($program) {
            $result = collect($resume);
        } else {
            // untuk total sasaran penerima bantuan, harus dihitung ulang karena satu pihak bisa menerima lebih dari satu bantuan
            $penerimaBantuanLaki              = 0;
            $penerimaBantuanPerempuan         = 0;
            $penerimaBantuanLakiNonAktif      = 0;
            $penerimaBantuanPerempuanNonAktif = 0;

            switch($sasaran) {
                case SasaranEnum::PENDUDUK:
                    $penerimaBantuanLaki              = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    $penerimaBantuanPerempuan         = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    $penerimaBantuanLakiNonAktif      = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    $penerimaBantuanPerempuanNonAktif = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    break;

                case SasaranEnum::KELUARGA:
                    $penerimaBantuanLaki              = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    $penerimaBantuanPerempuan         = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    $penerimaBantuanLakiNonAktif      = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    $penerimaBantuanPerempuanNonAktif = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    break;
            }

            $resume[0]['jumlah']    = $penerimaBantuanLaki + $penerimaBantuanPerempuan;
            $resume[0]['laki']      = $penerimaBantuanLaki;
            $resume[0]['perempuan'] = $penerimaBantuanPerempuan;
            $resume[0]['persen']    = persen2($penerimaBantuanLaki + $penerimaBantuanPerempuan, $total['lk'] + $total['pr']);
            $resume[0]['persen1']   = persen2($penerimaBantuanLaki, $total['lk'] + $total['pr']);
            $resume[0]['persen2']   = persen2($penerimaBantuanPerempuan, $total['lk'] + $total['pr']);

            $resume[1]['jumlah']    = $total['lk'] + $total['pr'] - $resume[0]['jumlah'] + $penerimaBantuanLakiNonAktif + $penerimaBantuanPerempuanNonAktif;
            $resume[1]['laki']      = $total['lk'] - $resume[0]['laki'] + $penerimaBantuanLakiNonAktif;
            $resume[1]['perempuan'] = $total['pr'] - $resume[0]['perempuan'] + $penerimaBantuanPerempuanNonAktif;
            $resume[1]['persen']    = persen2($resume[1]['jumlah'], $total['lk'] + $total['pr']);
            $resume[1]['persen1']   = persen2($resume[1]['laki'], $total['lk'] + $total['pr']);
            $resume[1]['persen2']   = persen2($resume[1]['perempuan'], $total['lk'] + $total['pr']);

            $result = $result ? collect(array_merge($result->toArray(), $resume)) : collect($resume);
        }

        return $result;
    }

    private static function getTotal($sasaran)
    {
        switch($sasaran) {
            case SasaranEnum::PENDUDUK:
                $pr = PendudukHidup::where(['sex' => JenisKelaminEnum::PEREMPUAN])->count();
                $lk = PendudukHidup::where(['sex' => JenisKelaminEnum::LAKI_LAKI])->count();
                break;

            case SasaranEnum::KELUARGA:
                $pr = KeluargaAktif::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN]))->count();
                $lk = KeluargaAktif::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI]))->count();
                break;

            case SasaranEnum::RUMAH_TANGGA:
                $pr = Rtm::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN]))->count();
                $lk = Rtm::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI]))->count();
                break;

            case SasaranEnum::KELOMPOK:
                $pr = Kelompok::whereHas('ketua', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN]))->count();
                $lk = Kelompok::whereHas('ketua', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI]))->count();
                break;
        }

        return ['pr' => $pr, 'lk' => $lk];
    }
}
