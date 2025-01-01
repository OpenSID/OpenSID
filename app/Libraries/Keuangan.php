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

use App\Enums\BidangBelanjaEnum;
use App\Enums\KeuanganRefRek1Enum;
use App\Models\Keuangan as ModelsKeuangan;
use App\Models\KeuanganManualRefRek2;
use App\Models\KeuanganManualRefRek3;
use App\Models\KeuanganTemplate;

class Keuangan
{
        public function rp_apbd_widget($tahun, $opt = false)
        {
            $excludeAkun = [KeuanganRefRek1Enum::ASET, KeuanganRefRek1Enum::NON_ANGGARAN];
            if ($opt) {
                $excludeAkun = [KeuanganRefRek1Enum::ASET, KeuanganRefRek1Enum::NON_ANGGARAN, KeuanganRefRek1Enum::KEWAJIBAN, KeuanganRefRek1Enum::EKUITAS];
            }
            $filteredAkun = array_filter(KeuanganRefRek1Enum::all(), static fn ($key) => ! in_array($key, $excludeAkun), ARRAY_FILTER_USE_KEY);

            $data['jenis_pelaksanaan'] = collect($filteredAkun)->map(static fn ($val, $key) => ['Akun' => $key, 'Nama_Akun' => $val])->toArray();

            $data['anggaran']             = ModelsKeuangan::selectRaw('LEFT(template_uuid, 2) AS jenis_pelaksanaan, SUM(anggaran) AS pagu')->whereRaw('length(template_uuid) >= 8')->where('tahun', $tahun)->groupBy('tahun')->groupBy('jenis_pelaksanaan')->get()->toArray();
            $data['realisasi_pendapatan'] = ModelsKeuangan::selectRaw('LEFT(template_uuid, 2) AS jenis_pelaksanaan, SUM(realisasi) AS realisasi')->whereRaw('length(template_uuid) >= 8')->where('tahun', $tahun)->groupBy('tahun')->groupBy('jenis_pelaksanaan')->get()->toArray();
            $data['realisasi_belanja']    = ModelsKeuangan::selectRaw('LEFT(template_uuid, 2) AS jenis_pelaksanaan, SUM(realisasi) AS realisasi')->whereRaw('length(template_uuid) >= 8')->where('tahun', $tahun)->where('template_uuid', 'like', KeuanganRefRek1Enum::BELANJA . '%')->groupBy('tahun')->groupBy('jenis_pelaksanaan')->get()->toArray();
            $data['pembiayaan_keluar']    = [['Akun' => KeuanganRefRek1Enum::PEMBIAYAAN, 'Nama_Akun' => KeuanganRefRek1Enum::valueOf(KeuanganRefRek1Enum::PEMBIAYAAN)]];

            foreach ($data['pembiayaan_keluar'] as $i => $p) {
                $data['pembiayaan'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar($p['Akun'], $tahun);
                $data['pembiayaan'][$i]['sub_pembiayaan']        = $this->get_subval_pembiayaan($p['Akun'], $tahun);
            }

            return $data;
        }

        public function r_pd_widget($tahun, $opt = false)
        {
            $obj = KeuanganManualRefRek3::select(['Jenis', 'Nama_Jenis']);

            if ($opt) {
                $obj->whereRaw("Jenis LIKE '4.%'");
            } else {
                $obj->whereRaw("Jenis NOT LIKE '1.%'");
                $obj->whereRaw("Jenis NOT LIKE '5.%'");
                $obj->whereRaw("Jenis NOT LIKE '6.%'");
                $obj->whereRaw("Jenis NOT LIKE '7.%'");
            }

            $obj->whereRaw("Nama_Jenis NOT LIKE '%Hutang%'");
            $obj->whereRaw("Nama_Jenis NOT LIKE '%Ekuitas SAL%'");

            $obj->orderBy('Jenis', 'asc');
            $data['jenis_pendapatan'] = $obj->get()->toArray();

            $data['anggaran']             = ModelsKeuangan::selectRaw('LEFT(template_uuid, 6) AS jenis_pendapatan, SUM(anggaran) AS pagu')->whereRaw('length(template_uuid) >= 8')->where('template_uuid', 'like', '4.%')->where('anggaran', '>', 0)->groupBy('jenis_pendapatan')->where('tahun', $tahun)->get()->toArray();
            $data['realisasi_pendapatan'] = ModelsKeuangan::selectRaw('LEFT(template_uuid, 6) AS jenis_pendapatan, SUM(realisasi) AS realisasi')->whereRaw('length(template_uuid) >= 8')->where('template_uuid', 'like', '4.%')->where('realisasi', '>', 0)->groupBy('jenis_pendapatan')->where('tahun', $tahun)->get()->toArray();

            return $data;
        }

        public function r_bd_widget($tahun, $opt = false)
        {
            $obj = KeuanganTemplate::select(['uuid as Kd_Bid', 'uraian as Nama_Bidang']);
            // if ($opt) {
            //     $obj->whereNotIn('uuid',['5.1', '5.2', '5.3']);
            // } else {
            //     $obj->whereNotIn('uuid',['5.1']);
            // }
            $obj->where('parent_uuid', '5');
            $obj->orderBy('uuid', 'asc');
            $data['jenis_belanja'] = $obj->get()->map(static function ($item) {
                $item->Nama_Bidang = BidangBelanjaEnum::valueOf(substr($item->Kd_Bid, -1));

                return $item;
            })->toArray();
            // Perlu ditambahkan baris berikut untuk memaksa menampilkan semua bidang di grafik keuangan
            // TODO: lihat apakah bisa diatasi langsung di script penampilan
            if (! $opt) {
                array_unshift($data['jenis_belanja'], ['Kd_Bid' => '03', 'Nama_Bidang' => 'ROW_SPACER']);
                array_unshift($data['jenis_belanja'], ['Kd_Bid' => '02', 'Nama_Bidang' => 'ROW_SPACER']);
            }

            $data['anggaran']          = ModelsKeuangan::selectRaw('LEFT(template_uuid, 3) AS jenis_belanja, SUM(anggaran) AS pagu')->whereRaw('length(template_uuid) >= 8 and template_uuid like \'5.%\'')->groupBy('jenis_belanja')->where('tahun', $tahun)->get()->toArray();
            $data['realisasi_belanja'] = ModelsKeuangan::selectRaw('LEFT(template_uuid, 3) AS jenis_belanja, SUM(realisasi) AS realisasi')->whereRaw('length(template_uuid) >= 8 and template_uuid like \'5.%\'')->groupBy('jenis_belanja')->where('tahun', $tahun)->get()->toArray();

            return $data;
        }

        private function data_widget_pendapatan($tahun, bool $opt = false)
        {
            if ($opt) {
                $raw_data       = $this->r_pd_widget($tahun, $opt = true);
                $res_pendapatan = [];
                $tmp_pendapatan = [];

                foreach ($raw_data['jenis_pendapatan'] as $r) {
                    $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
                }

                foreach ($raw_data['anggaran'] as $r) {
                    $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['pagu'] ?: 0);
                }

                foreach ($raw_data['realisasi_pendapatan'] as $r) {
                    $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi'] = ($r['realisasi'] ?: 0);
                }
            } else {
                $raw_data       = $this->r_pd_widget($tahun, $opt = false);
                $res_pendapatan = [];
                $tmp_pendapatan = [];

                foreach ($raw_data['jenis_pendapatan'] as $r) {
                    $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
                }

                foreach ($raw_data['anggaran'] as $r) {
                    $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['pagu'] ?: 0);
                }

                foreach ($raw_data['realisasi_pendapatan'] as $r) {
                    $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi_pendapatan'] = ($r['realisasi'] ?: 0);
                }
            }

            foreach ($tmp_pendapatan as $value) {
                $res_pendapatan[] = $value;
            }

            return $res_pendapatan;
        }

        private function data_widget_belanja($tahun, bool $opt = false)
        {
            if ($opt) {
                $raw_data    = $this->r_bd_widget($tahun, $opt = true);
                $res_belanja = [];
                $tmp_belanja = [];

                foreach ($raw_data['jenis_belanja'] as $r) {
                    $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
                }

                foreach ($raw_data['anggaran'] as $r) {
                    $tmp_belanja[$r['jenis_belanja']]['anggaran'] = ($r['pagu'] ?: 0);
                }

                foreach ($raw_data['realisasi_belanja'] as $r) {
                    $tmp_belanja[$r['jenis_belanja']]['realisasi'] = ($r['realisasi'] ?: 0);
                }
            } else {
                $raw_data    = $this->r_bd_widget($tahun, $opt = false);
                $res_belanja = [];
                $tmp_belanja = [];

                foreach ($raw_data['jenis_belanja'] as $r) {
                    $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
                }

                foreach ($raw_data['anggaran'] as $r) {
                    $tmp_belanja[$r['jenis_belanja']]['anggaran'] = ($r['pagu'] ?: 0);
                }

                foreach ($raw_data['realisasi_belanja'] as $r) {
                    $tmp_belanja[$r['jenis_belanja']]['realisasi_belanja'] = ($r['realisasi'] ?: 0);
                }
            }

            foreach ($tmp_belanja as $value) {
                $res_belanja[] = $value;
            }

            return $res_belanja;
        }

        private function data_widget_pelaksanaan($tahun, bool $opt = false)
        {
            if ($opt) {
                $raw_data = $this->rp_apbd_widget($tahun, $opt = true);

                $res_pelaksanaan = [];
                $tmp_pelaksanaan = [];

                foreach ($raw_data['jenis_pelaksanaan'] as $r) {
                    $tmp_pelaksanaan[$r['Akun']]['nama'] = $r['Nama_Akun'];
                }

                foreach ($raw_data['anggaran'] as $r) {
                    $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['anggaran'] = ($r['pagu'] ?: 0);
                }

                foreach ($raw_data['realisasi_pendapatan'] as $r) {
                    $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
                }
            } else {
                $raw_data        = $this->rp_apbd_widget($tahun, $opt = false);
                $res_pelaksanaan = [];
                $tmp_pelaksanaan = [];

                foreach ($raw_data['jenis_pelaksanaan'] as $r) {
                    $tmp_pelaksanaan[$r['Akun']]['nama'] = $r['Nama_Akun'];
                }

                foreach ($raw_data['anggaran'] as $r) {
                    $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['anggaran'] = ($r['pagu'] ?: 0);
                }

                foreach ($raw_data['realisasi_pendapatan'] as $r) {
                    $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_pendapatan'] = ($r['realisasi'] ?: 0);
                }
            }

            foreach ($tmp_pelaksanaan as $value) {
                if ($value['nama'] == 'PEMBIAYAAN') {
                    $value['anggaran']             = $raw_data['pembiayaan'][0]['sub_pembiayaan'][0]['anggaran'][0]['pagu'] - $raw_data['pembiayaan'][0]['sub_pembiayaan_keluar'][0]['anggaran'][0]['pagu'];
                    $value['realisasi_pendapatan'] = $raw_data['pembiayaan'][0]['sub_pembiayaan'][0]['realisasi'][0]['realisasi'] - $raw_data['pembiayaan'][0]['sub_pembiayaan_keluar'][0]['realisasi'][0]['realisasi'];
                    // $value['realisasi'] = $raw_data['pembiayaan'][0]['sub_pembiayaan'][0]['realisasi'][0]['realisasi'] - $raw_data['pembiayaan'][0]['sub_pembiayaan_keluar'][0]['realisasi'][0]['realisasi'];
                }
                $res_pelaksanaan[] = $value;
            }

            return $res_pelaksanaan;
        }

        public function widget_keuangan()
        {
            $listTahun = ModelsKeuangan::tahunAnggaran()->get();

            foreach ($listTahun as $tahun) {
                $res[$tahun]['res_pendapatan']  = $this->data_widget_pendapatan($tahun, $opt = true);
                $res[$tahun]['res_belanja']     = $this->data_widget_belanja($tahun, $opt = true);
                $res[$tahun]['res_pelaksanaan'] = $this->data_widget_pelaksanaan($tahun, $opt = true);
            }

            return [
                //Encode ke JSON
                'data'  => json_encode($res, JSON_THROW_ON_ERROR),
                'tahun' => $listTahun,
                //Cari tahun anggaran terbaru (terbesar secara value)
                'tahun_terbaru' => $listTahun->first()->toArray(),
            ];
        }

        private function data_keuangan_tema($tahun)
        {
            $data['res_pelaksanaan']            = $this->data_widget_pelaksanaan($tahun, $opt = false);
            $data['res_pelaksanaan']['laporan'] = 'APBDes ' . $tahun . ' Pelaksanaan';
            $data['res_pendapatan']             = $this->data_widget_pendapatan($tahun, $opt = false);
            $data['res_pendapatan']['laporan']  = 'APBDes ' . $tahun . ' Pendapatan';
            $data['res_belanja']                = $this->data_widget_belanja($tahun, $opt = false);
            $data['res_belanja']['laporan']     = 'APBDes ' . $tahun . ' Pembelanjaan';

            return $data;
        }

        public function grafik_keuangan_tema($tahun = null)
        {
            if (! $tahun) $tahun = date('Y');
            $raw_data            = $this->data_keuangan_tema($tahun);

            foreach ($raw_data as $keys => $raws) {
                foreach ($raws as $key => $raw) {
                    if ($key == 'laporan') {
                        $result['data_widget'][$keys]['laporan'] = $raw;

                        continue;
                    }

                    $data          = $this->raw_perhitungan($raw);
                    $data['judul'] = $raw['nama'];

                    $result['data_widget'][$keys][] = $data;
                }
            }
            $result['tahun'] = $tahun;

            return $result;
        }

        /*
          lap_rp_apbd merupakan fungsi Akhir (Main) dari semua sub dan sub-sub fungsi :

          Sub fungsi Pendapatan
          1.1 sub-sub fungsi : Pagu Pendapatan
          1.2 sub-sub fungsi : Realisasi Pendapatan

          Sub fungsi Belanja
          2.1 sub-sub fungsi : Pagu Belanja
          2.2 sub-sub fungsi : Realisasi Belanja

          Sub fungsi Pembiayaan Masuk
          3.1 sub-sub fungsi : Pagu Pembiayaan Masuk
          3.1 sub-sub fungsi : Realisasi Pembiayaan Masuk

          Sub fungsi Pembiayaan Keluar
          4.1 sub-sub fungsi : Pagu Pembiayaan Keluar
          4.2 sub-sub fungsi : Realisasi Pembiayaan Keluar
        */

        //Table Laporan Pelaksanaan Realisasi
        public function lap_rp_apbd($tahun = null)
        {
            if (! $tahun) $tahun = date('Y');
            $data['pendapatan']  = [['Akun' => KeuanganRefRek1Enum::PENDAPATAN, 'Nama_Akun' => KeuanganRefRek1Enum::valueOf(KeuanganRefRek1Enum::PENDAPATAN)]];

            foreach ($data['pendapatan'] as $i => $p) {
                $data['pendapatan'][$i]['anggaran']       = $this->pagu_akun($p['Akun'], $tahun);
                $data['pendapatan'][$i]['realisasi']      = $this->realisasi_akun($p['Akun'], $tahun);
                $data['pendapatan'][$i]['sub_pendapatan'] = $this->get_subval_pendapatan($p['Akun'], $tahun);
            }

            $data['belanja'] = [['Akun' => KeuanganRefRek1Enum::BELANJA, 'Nama_Akun' => KeuanganRefRek1Enum::valueOf(KeuanganRefRek1Enum::BELANJA)]];

            foreach ($data['belanja'] as $i => $p) {
                $data['belanja'][$i]['anggaran']    = $this->pagu_akun($p['Akun'], $tahun);
                $data['belanja'][$i]['realisasi']   = $this->realisasi_akun($p['Akun'], $tahun);
                $data['belanja'][$i]['sub_belanja'] = $this->get_subval_belanja($p['Akun'], $tahun);
            }

            $data['belanja_bidang'] = KeuanganTemplate::select(['uuid as Kd_Bid', 'uraian as Nama_Bidang'])->where('parent_uuid', '5')->get()->toArray();

            foreach ($data['belanja_bidang'] as $i => $p) {
                $data['belanja_bidang'][$i]['anggaran']  = $this->pagu_akun_bidang($p['Kd_Bid'], $tahun);
                $data['belanja_bidang'][$i]['realisasi'] = $this->real_akun_belanja_bidang($p['Kd_Bid'], $tahun);
            }

            $data['pembiayaan'] = [['Akun' => KeuanganRefRek1Enum::PEMBIAYAAN, 'Nama_Akun' => KeuanganRefRek1Enum::valueOf(KeuanganRefRek1Enum::PEMBIAYAAN)]];

            foreach ($data['pembiayaan'] as $i => $p) {
                $data['pembiayaan'][$i]['anggaran']       = $this->pagu_akun($p['Akun'], $tahun);
                $data['pembiayaan'][$i]['realisasi']      = $this->realisasi_akun($p['Akun'], $tahun);
                $data['pembiayaan'][$i]['sub_pembiayaan'] = $this->get_subval_pembiayaan($p['Akun'], $tahun);
            }

            $data['pembiayaan_keluar'] = [['Akun' => KeuanganRefRek1Enum::PEMBIAYAAN, 'Nama_Akun' => KeuanganRefRek1Enum::valueOf(KeuanganRefRek1Enum::PEMBIAYAAN)]];

            foreach ($data['pembiayaan_keluar'] as $i => $p) {
                $data['pembiayaan_keluar'][$i]['anggaran']              = $this->pagu_akun($p['Akun'], $tahun);
                $data['pembiayaan_keluar'][$i]['realisasi']             = $this->realisasi_akun($p['Akun'], $tahun);
                $data['pembiayaan_keluar'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar($p['Akun'], $tahun);
            }

            return $data;
        }

        private function pagu_akun($akun, $tahun)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 2) AS Akun, SUM(anggaran) AS pagu')
                ->where('template_uuid', 'like', $akun . '%')
                ->whereRaw('length(template_uuid) >= 8')
                ->where('tahun', $tahun)
                ->groupBy('Akun')
                ->get()->toArray();
        }

        private function pagu_akun_bidang($akun, $tahun)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 3) AS Akun, SUM(anggaran) AS pagu')->whereRaw('length(template_uuid) >= 8 and template_uuid like \'' . $akun . '%\'')->groupBy('Akun')->where('tahun', $tahun)->get()->toArray();
        }

        private function realisasi_akun($akun, $tahun = false)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 2) AS Akun, SUM(realisasi) AS realisasi')
                ->where('template_uuid', 'like', $akun . '%')
                ->whereRaw('length(template_uuid) >= 8')
                ->where('tahun', $tahun)
                ->groupBy('Akun')
                ->get()->toArray();
        }

        private function real_akun_belanja_bidang($akun, $tahun = false)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 3) AS Akun, SUM(realisasi) AS realisasi')->whereRaw('length(template_uuid) >= 8 and template_uuid like \'' . $akun . '%\'')->groupBy('Akun')->where('tahun', $tahun)->get()->toArray();
        }

        private function get_subval_pendapatan($akun, $tahun = false)
        {
            $data = KeuanganManualRefRek2::select(['Kelompok', 'Nama_Kelompok'])->where('Akun', $akun)->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']        = $this->jumlah_pagu_subval($d['Kelompok'], $tahun);
                $data[$i]['realisasi']       = $this->jumlah_realisasi_subval($d['Kelompok'], $tahun);
                $data[$i]['sub_pendapatan2'] = $this->sub_pendapatan2($d['Kelompok'], $tahun);
            }

            return $data;
        }

        private function get_subval_belanja($akun, $tahun = false)
        {
            $data = KeuanganManualRefRek2::select(['Kelompok', 'Nama_Kelompok'])->where('Akun', $akun)->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']     = $this->jumlah_pagu_subval($d['Kelompok'], $tahun);
                $data[$i]['realisasi']    = $this->jumlah_realisasi_subval($d['Kelompok'], $tahun);
                $data[$i]['sub_belanja2'] = $this->sub_belanja2($d['Kelompok'], $tahun);
            }

            return $data;
        }

        private function get_subval_pembiayaan($akun, $tahun = false)
        {
            $data = KeuanganManualRefRek2::select(['Kelompok', 'Nama_Kelompok'])->where('Akun', $akun)->where('Kelompok', '6.1.')->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']        = $this->jumlah_pagu_subval($d['Kelompok'], $tahun);
                $data[$i]['realisasi']       = $this->jumlah_realisasi_subval($d['Kelompok'], $tahun);
                $data[$i]['sub_pembiayaan2'] = $this->sub_pembiayaan2($d['Kelompok'], $tahun);
            }

            return $data;
        }

        private function get_subval_pembiayaan_keluar($akun, $tahun = false)
        {
            $data = KeuanganManualRefRek2::select(['Kelompok', 'Nama_Kelompok'])->where('Akun', $akun)->where('Kelompok', '6.2.')->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']               = $this->jumlah_pagu_subval($d['Kelompok'], $tahun);
                $data[$i]['realisasi']              = $this->jumlah_realisasi_subval($d['Kelompok'], $tahun);
                $data[$i]['sub_pembiayaan_keluar2'] = $this->sub_pembiayaan_keluar2($d['Kelompok'], $tahun);
            }

            return $data;
        }

        private function jumlah_pagu_subval($kelompok, $tahun)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 4) AS Kelompok, SUM(anggaran) AS pagu')
                ->where('template_uuid', 'like', $kelompok . '%')
                ->whereRaw('length(template_uuid) >= 8')
                ->where('tahun', $tahun)
                ->groupBy('Kelompok')
                ->get()->toArray();
        }

        private function jumlah_realisasi_subval($kelompok, $tahun = false)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 4) AS Kelompok, SUM(realisasi) AS realisasi')
                ->where('template_uuid', 'like', $kelompok . '%')
                ->whereRaw('length(template_uuid) >= 8')
                ->where('tahun', $tahun)
                ->groupBy('Kelompok')
                ->get()->toArray();
        }

        private function sub_pendapatan2($kelompok, $tahun = false)
        {
            $data = KeuanganManualRefRek3::select(['Kelompok', 'Jenis', 'Nama_Jenis'])->where('Kelompok', $kelompok)->get()->toArray();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $tahun);
                $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $tahun);
            }

            return $data;
        }

        private function sub_belanja2($kelompok, $tahun = false)
        {
            $data = KeuanganManualRefRek3::select(['Kelompok', 'Jenis', 'Nama_Jenis'])->where('Kelompok', $kelompok)->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $tahun);
                $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $tahun);
            }

            return $data;
        }

        private function sub_pembiayaan2($kelompok, $tahun = false)
        {
            $data = KeuanganManualRefRek3::select(['Kelompok', 'Jenis', 'Nama_Jenis'])->where('Kelompok', '6.1.')->where('Kelompok', $kelompok)->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $tahun);
                $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $tahun);
            }

            return $data;
        }

        private function sub_pembiayaan_keluar2($kelompok, $tahun = false)
        {
            $data = KeuanganManualRefRek3::select(['Kelompok', 'Jenis', 'Nama_Jenis'])->where('Kelompok', '6.2.')->where('Kelompok', $kelompok)->get();

            foreach ($data as $i => $d) {
                $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $tahun);
                $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $tahun);
            }

            return $data;
        }

        private function jumlah_pagu($jenis, $tahun)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 6) AS Jenis, SUM(anggaran) AS pagu')
                ->where('template_uuid', 'like', $jenis . '%')
                ->whereRaw('length(template_uuid) >= 8')
                ->where('tahun', $tahun)
                ->groupBy('Jenis')
                ->get()->toArray();

        }

        private function jumlah_realisasi($jenis, $tahun = false)
        {
            return ModelsKeuangan::selectRaw('LEFT(template_uuid, 6) AS Jenis, SUM(realisasi) AS realisasi')
                ->where('template_uuid', 'like', $jenis . '%')
                ->whereRaw('length(template_uuid) >= 8')
                ->where('tahun', $tahun)
                ->groupBy('Jenis')
                ->get()->toArray();
        }

        private function raw_perhitungan($raw)
        {
            if (! is_array($raw)) {
                return;
            }
            if ($raw['nama'] === 'PEMBIAYAAN') {
                // $penerimaan_pembiayaan   = $raw['realisasi'] + $raw['realisasi_pendapatan'] + ($raw['realisasi_belanja'] - $raw['realisasi_belanja_um']) + $raw['realisasi_belanja_spj'] + $raw['realisasi_bunga'] + $raw['realisasi_jurnal'] + $raw['realisasi_biaya'];
                // $pengeluaraan_pembiayaan = $raw['anggaran'] - $penerimaan_pembiayaan;

                $pembiayaan_keluar = $raw['pembiayaan_keluar'][0]['sub_pembiayaan_keluar'][0];

                $data['anggaran']  = $raw['anggaran'] - $pembiayaan_keluar['anggaran'][0]['pagu'];
                $data['realisasi'] = $raw['realisasi_pendapatan'] - $pembiayaan_keluar['realisasi'][0]['realisasi'];
            } else {
                $data['anggaran']  = $raw['anggaran'];
                $data['realisasi'] = $raw['realisasi'] + $raw['realisasi_pendapatan'] + ($raw['realisasi_belanja'] - $raw['realisasi_belanja_um']) + $raw['realisasi_belanja_spj'] + $raw['realisasi_bunga'] + $raw['realisasi_jurnal'] + $raw['realisasi_biaya'] + $raw['realisasi_belanja_jurnal'];
            }

            if ($data['anggaran'] != 0 && $data['realisasi'] != 0) {
                $data['persen'] = $data['realisasi'] / $data['anggaran'] * 100;
            } elseif ($data['realisasi'] != 0) {
                $data['persen'] = 100;
            } else {
                $data['persen'] = 0;
            }
            $data['persen'] = round($data['persen'], 2);

            return $data;
        }
}
