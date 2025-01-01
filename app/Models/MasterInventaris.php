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

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class MasterInventaris extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the models.
     *
     * @var string
     */
    protected $table = 'master_inventaris';

    /**
     * The guarded with the models.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopePermen47($query, $tahun = null, $jns_asset = null)
    {
        $kondisi = [
            'Baik'                      => 1,
            'Perbaiki'                  => 2,
            'Rusak'                     => 2,
            'Barang Rusak Dijual'       => 2,
            'Masih Baik Dijual'         => 1,
            'Masih Baik Disumbangkan'   => 1,
            'Barang Rusak Disumbangkan' => 2,
            'null'                      => 1,
        ];

        $rekap_mutasi_hapus = DB::table('rekap_mutasi_inventaris as a')
            ->whereNotIn(DB::raw('concat(a.asset, a.id_inventaris_asset)'), static function ($query) use ($tahun): void {
                $query->select(DB::raw('concat(b.asset, b.id_inventaris_asset)'))
                    ->from('rekap_mutasi_inventaris as b')
                    ->where('b.status_mutasi', 'Hapus')
                    ->whereYear('tahun_mutasi', '<', $tahun);
            })
            ->where('tahun_mutasi', static function ($query) use ($tahun): void {
                $query->select(DB::raw('MAX(c.tahun_mutasi)'))
                    ->from('rekap_mutasi_inventaris as c')
                    ->whereYear('tahun_mutasi', '<', $tahun)
                    ->whereColumn('a.asset', 'c.asset')
                    ->whereColumn('a.id_inventaris_asset', 'c.id_inventaris_asset');
            })->get();

        foreach ($rekap_mutasi_hapus as $asset) {
            $akhir_tahun[$asset->asset][$asset->id_inventaris_asset] = $asset;
            $awal_tahun[$asset->asset][$asset->id_inventaris_asset]  = $asset;
        }
        // dd($akhir_tahun, $awal_tahun);

        $rekap_mutasi = DB::table('rekap_mutasi_inventaris')->get();

        foreach ($rekap_mutasi as $asset) {
            if ($asset->status_mutasi == null) {
                $asset->kondisi = 2;
            } elseif ($asset->status_mutasi == 'Hapus') {
                $asset->kondisi = $kondisi[$asset->jenis_mutasi];
            } else {
                $asset->kondisi = $kondisi[$asset->status_mutasi];
            }
            if ($asset->status_mutasi == null) {
                $asset->status_mutasi = 'Hapus';
            }
            $akhir_tahun[$asset->asset][$asset->id_inventaris_asset] = $asset; // memperbarui data akhir tahun
        }
        // dd($akhir_tahun['inventaris_peralatan']);

        if ($jns_asset !== null) {
            $query->where('asset', $jns_asset);
        }

        $inventaris    = $query->where('tahun_pengadaan', '<=', $tahun)->get();
        $inventarisnew = collect($inventaris)->map(static function ($asset) use ($akhir_tahun, $awal_tahun, $kondisi) {
            if (isset($akhir_tahun[$asset->asset][$asset->id])) {
                $asset->akhir_tahun   = $akhir_tahun[$asset->asset][$asset->id]->kondisi;
                $asset->tahun_mutasi  = $akhir_tahun[$asset->asset][$asset->id]->tahun_mutasi;
                $asset->status_mutasi = $akhir_tahun[$asset->asset][$asset->id]->status_mutasi;
                $asset->jenis_mutasi  = $akhir_tahun[$asset->asset][$asset->id]->jenis_mutasi;
            } else {
                $asset->akhir_tahun = $kondisi[$asset->kondisi];
            }

            if (isset($awal_tahun[$asset->asset][$asset->id])) {
                $asset->awal_tahun = $akhir_tahun[$asset->asset][$asset->id]->kondisi;
            } else {
                $asset->awal_tahun = $kondisi[$asset->kondisi];
            }

            return $asset;
        });

        return collect($inventarisnew)->groupBy('nama_barang')->map(static function ($items): array {
            $result = [
                'Bantuan Kabupaten'  => [],
                'Bantuan Pemerintah' => [],
                'Bantuan Provinsi'   => [],
                'Pembelian Sendiri'  => [],
                'Sumbangan'          => [],
                'awal_baik'          => [],
                'awal_rusak'         => [],
                'hapus_rusak'        => [],
                'hapus_jual'         => [],
                'hapus_sumbang'      => [],
                'akhir_baik'         => [],
                'akhir_rusak'        => [],
                'keterangan'         => [],
            ];

            foreach ($items as $value) {
                $result['nama_barang']  = $value->nama_barang;
                $result[$value->asal][] = 1;

                if (isset($value->tahun_mutasi)) {
                    $result['tahun_mutasi'] = $value->tahun_mutasi;
                }

                if ($value->awal_tahun == 1) {
                    $result['awal_baik'][] = 1;
                }

                if ($value->awal_tahun == 2) {
                    $result['awal_rusak'][] = 1;
                }

                if ($value->status_mutasi == 'Hapus') {
                    if ($value->jenis_mutasi == 'Rusak') {
                        $result['hapus_rusak'][] = 1;
                    }

                    if (in_array($value->jenis_mutasi, ['Masih Baik Disumbangkan', 'Barang Rusak Disumbangkan'])) {
                        $result['hapus_sumbang'][] = 1;
                    }

                    if (in_array($value->jenis_mutasi, ['Barang Rusak Dijual', 'Masih Baik Dijual'])) {
                        $result['hapus_jual'][] = 1;
                    }

                    $result['tgl_hapus'] = $value->tahun_mutasi;
                } else {
                    if ($value->akhir_tahun == 1) {
                        $result['akhir_baik'][] = 1;
                    }

                    if ($value->akhir_tahun == 2) {
                        $result['akhir_rusak'][] = 1;
                    }
                }

                if ($value->keterangan != '') {
                    $result['keterangan'][] = $value->keterangan;
                }
            }

            return $result;
        })->toArray();
    }

    public function scopeMinTahun($query)
    {
        return $query->min('tahun_pengadaan');
    }
}
