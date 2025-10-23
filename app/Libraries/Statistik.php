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
use App\Models\Bantuan;
use App\Models\Kelompok;
use App\Models\KeluargaAktif;
use App\Models\PendudukHidup;
use App\Models\Rtm;

class Statistik
{
    public static function bantuan($lap, $filter = [])
    {
        $sasaran = SasaranEnum::PENDUDUK;
        $program = false;

        if (array_key_exists($lap, StatistikJenisBantuanEnum::allKeyLabel())) {
            if ($lap == StatistikJenisBantuanEnum::KELUARGA['key']) {
                $sasaran = SasaranEnum::KELUARGA;
            }
        } else {
            $sasaran = Bantuan::whereSlug($lap)->first()?->sasaran;
            $program = true;
        }

        $bantuan = (new Bantuan())->whereSasaran($sasaran);
        $label   = $program ? 'PESERTA' : 'PENERIMA';

        if (! empty($filter['tahun'])) {
            $bantuan->whereYear('sdate', '<=', $filter['tahun'])
                ->whereYear('edate', '>=', $filter['tahun']);
        }

        $bantuan->status($filter['status']);

        if ($program) {
            $bantuan->where('slug', $lap);
        }

        $cluster = $filter['cluster'];

        $bantuan->withCount([
            'peserta as peserta_lakilaki_count'  => static fn ($query) => self::filterPesertaByGender($query, $sasaran, JenisKelaminEnum::LAKI_LAKI, $cluster),
            'peserta as peserta_perempuan_count' => static fn ($query) => self::filterPesertaByGender($query, $sasaran, JenisKelaminEnum::PEREMPUAN, $cluster),
        ]);

        $data  = $bantuan->get();
        $total = self::getTotal($sasaran);

        $result = $data->map(static fn ($item) => [
            'id'        => $item->id,
            'slug'      => $item->slug,
            'nama'      => $item->nama,
            'jumlah'    => $item->peserta_lakilaki_count + $item->peserta_perempuan_count,
            'persen'    => persen2($item->peserta_lakilaki_count + $item->peserta_perempuan_count, $total['lk'] + $total['pr']),
            'laki'      => $item->peserta_lakilaki_count,
            'persen1'   => persen2($item->peserta_lakilaki_count, $item->peserta_lakilaki_count + $item->peserta_perempuan_count),
            'perempuan' => $item->peserta_perempuan_count,
            'persen2'   => persen2($item->peserta_perempuan_count, $total['lk'] + $total['pr']),
        ]);

        $totalJumlah       = $total['lk'] + $total['pr'];
        $jumlahPenerima    = $result->sum('jumlah');
        $lakiPenerima      = $result->sum('laki');
        $perempuanPenerima = $result->sum('perempuan');

        $jumlahBukanPenerima    = $totalJumlah - $jumlahPenerima;
        $lakiBukanPenerima      = $total['lk'] - $lakiPenerima;
        $perempuanBukanPenerima = $total['pr'] - $perempuanPenerima;

        $resume = [
            [
                'id'        => JUMLAH,
                'nama'      => $label,
                'slug'      => JUMLAH,
                'jumlah'    => $jumlahPenerima,
                'persen'    => persen2($jumlahPenerima, $totalJumlah),
                'laki'      => $lakiPenerima,
                'persen1'   => persen2($lakiPenerima, $jumlahPenerima),
                'perempuan' => $perempuanPenerima,
                'persen2'   => persen2($perempuanPenerima, $jumlahPenerima),
            ],
            [
                'id'        => BELUM_MENGISI,
                'nama'      => 'BUKAN ' . $label,
                'slug'      => BELUM_MENGISI,
                'jumlah'    => $jumlahBukanPenerima,
                'persen'    => persen2($jumlahBukanPenerima, $totalJumlah),
                'laki'      => $lakiBukanPenerima,
                'persen1'   => persen2($lakiBukanPenerima, $jumlahBukanPenerima),
                'perempuan' => $perempuanBukanPenerima,
                'persen2'   => persen2($perempuanBukanPenerima, $jumlahBukanPenerima),
            ],
            [
                'id'        => TOTAL,
                'nama'      => 'TOTAL',
                'slug'      => TOTAL,
                'jumlah'    => $totalJumlah,
                'persen'    => persen2($totalJumlah, $totalJumlah),
                'laki'      => $total['lk'],
                'persen1'   => persen2($total['lk'], $totalJumlah),
                'perempuan' => $total['pr'],
                'persen2'   => persen2($total['pr'], $totalJumlah),
            ],
        ];

        return $result ? collect(array_merge($result->toArray(), $resume)) : collect($resume);
    }

    private static function filterPesertaByGender($query, $sasaran, $gender, $cluster)
    {
        return $query
            ->when($sasaran == SasaranEnum::PENDUDUK, static fn ($query) => $query->whereHas('penduduk', static fn ($q) => $q->where('sex', $gender)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))
            ->when($sasaran == SasaranEnum::KELUARGA, static fn ($query) => $query->whereHas('keluarga', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where('sex', $gender)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))
            ->when($sasaran == SasaranEnum::RUMAH_TANGGA, static fn ($query) => $query->whereHas('rtm', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where('sex', $gender)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))
            ->when($sasaran == SasaranEnum::KELOMPOK, static fn ($query) => $query->whereHas('kelompok', static fn ($q) => $q->whereHas('ketua', static fn ($t) => $t->where('sex', $gender)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))));
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
