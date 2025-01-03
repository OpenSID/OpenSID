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

namespace App\Repositories;

use App\Enums\SasaranEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BantuanPesertaRepository
{
    private $bantuan;

    public function __construct($bantuan)
    {
        $this->bantuan = $bantuan;
    }

    public function list()
    {
        $bantuan = BantuanPeserta::join('program', 'program.id', '=', 'program_peserta.program_id');

        switch($this->bantuan) {
            case 'bantuan_penduduk':
                $sasaran = SasaranEnum::PENDUDUK;
                break;

            case 'bantuan_keluarga':
                $sasaran = SasaranEnum::KELUARGA;
                break;

            default:
                $programId = preg_replace('/^50/', '', $this->bantuan);
                $sasaran   = Bantuan::find($programId)->sasaran;
                $bantuan->where('program.id', $programId);
        }
        $bantuan->whereSasaran($sasaran);

        return QueryBuilder::for($bantuan)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::callback('tahun', static fn ($query, $value) => $query->when($value, static fn ($r) => $r->whereRaw("YEAR(sdate) <= {$value}")->whereRaw("YEAR(edate) >= {$value}"))),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->when($value, static function ($r) use ($value) {
                        $r->where(static function ($s) use ($value) {
                            $s->where('program.nama', 'LIKE', '%' . $value . '%')
                                ->orWhere('kartu_nama', 'LIKE', '%' . $value . '%');
                        });
                    });
                }),
                AllowedFilter::callback('cluster', static function ($query, $cluster) use ($sasaran) {
                    switch($sasaran) {
                        case SasaranEnum::PENDUDUK:
                            $query->when($cluster, static fn ($r) => $r->whereHas('penduduk', static fn ($s) => $s->whereIn('id_cluster', $cluster)));
                            break;

                        case SasaranEnum::KELUARGA:
                            $query->when($cluster, static fn ($r) => $r->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($r) => $r->whereIn('id_cluster', $cluster))));
                            break;

                        case SasaranEnum::RUMAH_TANGGA:
                            $query->when($cluster, static fn ($r) => $r->whereHas('rtm', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($r) => $r->whereIn('id_cluster', $cluster))));
                            break;

                        case SasaranEnum::KELOMPOK:
                            break;
                    }
                }),
            ])
            ->allowedSorts(['nama', 'kartu_nama', 'kartu_alamat', 'sdate', 'edate', 'id'])->jsonPaginate();
    }
}
