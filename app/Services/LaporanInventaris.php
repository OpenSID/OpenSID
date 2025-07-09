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

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LaporanInventaris
{
    public static function all($tahun = null, $mutasi = false): array
    {
        $status = 0;
        if ($mutasi) {
            $status = 1;
        }
        $laporan_inventaris = [
            'inventaris_tanah' => [
                ['pribadi', 'inventaris_tanah', 'Pembelian Sendiri'],
                ['pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah'],
                ['provinsi', 'inventaris_tanah', 'Bantuan Provinsi'],
                ['kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten'],
                ['sumbangan', 'inventaris_tanah', 'Sumbangan'],
            ],

            'inventaris_peralatan' => [
                ['pribadi', 'inventaris_peralatan', 'Pembelian Sendiri'],
                ['pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah'],
                ['provinsi', 'inventaris_peralatan', 'Bantuan Provinsi'],
                ['kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten'],
                ['sumbangan', 'inventaris_peralatan', 'Sumbangan'],
            ],

            'inventaris_gedung' => [
                ['pribadi', 'inventaris_gedung', 'Pembelian Sendiri'],
                ['pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah'],
                ['provinsi', 'inventaris_gedung', 'Bantuan Provinsi'],
                ['kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten'],
                ['sumbangan', 'inventaris_gedung', 'Sumbangan'],
            ],

            'inventaris_jalan' => [
                ['pribadi', 'inventaris_jalan', 'Pembelian Sendiri'],
                ['pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah'],
                ['provinsi', 'inventaris_jalan', 'Bantuan Provinsi'],
                ['kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten'],
                ['sumbangan', 'inventaris_jalan', 'Sumbangan'],
            ],

            'inventaris_asset' => [
                ['pribadi', 'inventaris_asset', 'Pembelian Sendiri'],
                ['pemerintah', 'inventaris_asset', 'Bantuan Pemerintah'],
                ['provinsi', 'inventaris_asset', 'Bantuan Provinsi'],
                ['kabupaten', 'inventaris_asset', 'Bantuan Kabupaten'],
                ['sumbangan', 'inventaris_asset', 'Sumbangan'],
            ],

            'inventaris_kontruksi' => [
                ['pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri'],
                ['pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah'],
                ['provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi'],
                ['kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten'],
                ['sumbangan', 'inventaris_kontruksi', 'Sumbangan'],
            ],
        ];

        $result = [];

        $dateColumn = null;

        foreach ($laporan_inventaris as $key => $inventaris) {
            switch ($key) {
                case 'inventaris_tanah':
                    $dateColumn            = 'tahun_pengadaan';
                    $result[$key]['jenis'] = 'Tanah Kas Desa';
                    $result[$key]['ket']   = 'Informasi mengenai segala yang menyangkut dengan tanah (dalam hal ini tanah yang digunakan dalam instansi tersebut).';
                    $result[$key]['name']  = $key;
                    $result[$key]['url']   = ci_route('inventaris.tanah');
                    break;

                case 'inventaris_peralatan':
                    $dateColumn            = 'tahun_pengadaan';
                    $result[$key]['jenis'] = 'Peralatan dan Mesin';
                    $result[$key]['ket']   = 'Informasi mengenai peralatan dan mesin';
                    $result[$key]['name']  = $key;
                    $result[$key]['url']   = ci_route('inventaris.peralatan-dan-mesin');
                    break;

                case 'inventaris_gedung':
                    $dateColumn            = 'tanggal_dokument';
                    $result[$key]['jenis'] = 'Gedung dan Bangunan';
                    $result[$key]['ket']   = 'Informasi mengenai gedung dan bangunan yang dimiliki.';
                    $result[$key]['name']  = $key;
                    $result[$key]['url']   = ci_route('inventaris.gedung-dan-bangunan');
                    break;

                case 'inventaris_jalan':
                    $dateColumn            = 'tanggal_dokument';
                    $result[$key]['jenis'] = 'Jalan Irigasi dan Jaringan';
                    $result[$key]['ket']   = 'Informasi mengenai jaringan, seperti listrik atau Internet.';
                    $result[$key]['name']  = $key;
                    $result[$key]['url']   = ci_route('inventaris.jalan-irigasi-dan-jaringan');
                    break;

                case 'inventaris_asset':
                    $dateColumn            = 'tahun_pengadaan';
                    $result[$key]['jenis'] = 'Asset Tetap Lainnya';
                    $result[$key]['ket']   = 'Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.';
                    $result[$key]['name']  = $key;
                    $result[$key]['url']   = ci_route('inventaris.asset-tetap-lainnya');
                    break;

                case 'inventaris_kontruksi':
                    $dateColumn            = 'tanggal_dokument';
                    $result[$key]['jenis'] = 'Konstruksi Dalam Pengerjaan';
                    $result[$key]['ket']   = 'Informasi mengenai bangunan yang masih dalam pengerjaan.';
                    $result[$key]['name']  = $key;
                    $result[$key]['url']   = ci_route('inventaris.konstruksi-dalam-pengerjaan');
                    break;

                default:
                    break;
            }

            foreach ($inventaris as $inv) {
                $hasil = DB::table($key)
                    ->select(DB::raw('count(' . $key . '.asal) as total'))
                    ->where($key . '.status', $status)
                    ->where($key . '.asal', $inv[2])
                    ->where($key . '.visible', 1)
                    ->when($tahun, static fn ($query) => $query->whereYear($dateColumn, $tahun))
                    ->first();

                $result[$key][$inv[0]] = empty($hasil) ? 0 : $hasil->total;
                // tambah total
                if (isset($result[$key]['total'])) {
                    $result[$key]['total'] += $result[$key][$inv[0]];
                } else {
                    $result[$key]['total'] = $result[$key][$inv[0]];
                }
            }
        }

        return array_values($result);
    }

    public static function detail()
    {
        $data = collect(self::all());

        $result = $data->flatMap(static function ($item) {
            return [
                "{$item['name']}_pribadi"    => (object) ['total' => $item['pribadi']],
                "{$item['name']}_pemerintah" => (object) ['total' => $item['pemerintah']],
                "{$item['name']}_provinsi"   => (object) ['total' => $item['provinsi']],
                "{$item['name']}_kabupaten"  => (object) ['total' => $item['kabupaten']],
                "{$item['name']}_sumbangan"  => (object) ['total' => $item['sumbangan']],
            ];
        });

        return $result->all();

    }
}
