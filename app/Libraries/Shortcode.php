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

use App\Models\Pamong;
use Illuminate\Support\Facades\Blade;

class Shortcode
{
    // Shortcode untuk isi artikel
    public function shortcode($str = '')
    {
        $regex = '/\\[\\[(.*?)\\]\\]/';

        return preg_replace_callback($regex, function (array $matches) {
            $params_explode = explode(',', $matches[1]);

            return $this->extract_shortcode($params_explode[0], $params_explode[1]);
        }, $str);
    }

    private function extract_shortcode(?string $type = '', ?string $thn = '')
    {
        if ($type == 'penerima_bantuan_penduduk_grafik') {
            return $this->penerima_bantuan_penduduk_grafik($stat = 0);
        }
        if ($type == 'penerima_bantuan_penduduk_daftar') {
            return $this->penerima_bantuan_penduduk_daftar($stat = 0);
        }
        if ($type == 'penerima_bantuan_keluarga_grafik') {
            return $this->penerima_bantuan_keluarga_grafik($stat = 0);
        }
        if ($type == 'penerima_bantuan_keluarga_daftar') {
            return $this->penerima_bantuan_keluarga_daftar($stat = 0);
        }
        if ($type == 'grafik-RP-APBD-manual') {
            return $this->grafik_rp_apbd_manual($thn);
        }
        if ($type == 'lap-RP-APBD-Bidang-manual') {
            return $this->tabel_rp_apbd_bidang_manual($thn);
        }
        if ($type == 'sotk_w_bpd') {
            return $this->sotk_w_bpd();
        }
        if ($type == 'sotk_wo_bpd') {
            return $this->sotk_wo_bpd();
        }
    }

    private function grafik_rp_apbd_manual(string $thn)
    {
        $data = (new Keuangan())->grafik_keuangan_tema($thn);

        return Blade::render('admin.keuangan.laporan.grafik_rp_apbd_chart', $data);
    }

    private function tabel_rp_apbd_bidang_manual(string $thn)
    {
        $data = (new Keuangan())->lap_rp_apbd($thn);

        return Blade::render('admin.keuangan.laporan.tabel_laporan_rp_apbd_artikel', $data);
    }

    private function penerima_bantuan_penduduk_grafik(int $stat = 0)
    {
        $heading = 'Penerima Bantuan (Penduduk)';
        $stat    = Statistik::bantuan('bantuan_penduduk');
        $lap     = 'bantuan_penduduk';
        $data    = [
            'heading' => $heading,
            'stat'    => $stat,
            'lap'     => $lap,
            'tipe'    => 0,
        ];

        return Blade::render('web.statistik.penduduk_grafik_web', $data);
    }

    private function penerima_bantuan_penduduk_daftar(int $stat = 0)
    {
        $heading = 'Penerima Bantuan (Penduduk)';
        $stat    = Statistik::bantuan('bantuan_penduduk');
        $lap     = 'bantuan_penduduk';

        $data = [
            'heading' => $heading,
            'stat'    => $stat,
            'lap'     => $lap,
        ];

        return Blade::render('web.statistik.peserta_bantuan', $data);
    }

    private function penerima_bantuan_keluarga_grafik(int $stat = 0)
    {
        $heading = 'Penerima Bantuan (Keluarga)';
        $stat    = Statistik::bantuan('bantuan_keluarga');
        $lap     = 'bantuan_keluarga';
        $data    = [
            'heading' => $heading,
            'stat'    => $stat,
            'lap'     => $lap,
            'tipe'    => 0,
        ];

        return Blade::render('web.statistik.penduduk_grafik_web', $data);
    }

    private function penerima_bantuan_keluarga_daftar(int $stat = 0)
    {
        $heading = 'Penerima Bantuan (Keluarga)';
        $stat    = Statistik::bantuan('bantuan_keluarga');
        $lap     = 'bantuan_keluarga';
        $data    = [
            'heading' => $heading,
            'stat'    => $stat,
            'lap'     => $lap,
        ];

        return Blade::render('web.statistik.peserta_bantuan', $data);
    }

    private function sotk_w_bpd()
    {
        $adaBpd = true;

        return $this->sotk($adaBpd);
    }

    private function sotk_wo_bpd()
    {
        $adaBpd = false;

        return $this->sotk($adaBpd);
    }

    private function sotk($adaBpd = false)
    {
        $data['ada_bpd'] = $adaBpd;
        $atasan          = Pamong::select('atasan', 'pamong_id')
            ->where('atasan', '!=', null)->status()
            ->get()->toArray();

        $data['bagan']['struktur'] = [];

        foreach ($atasan as $pamong) {
            $data['bagan']['struktur'][] = [$pamong['atasan'] => $pamong['pamong_id']];
        }
        $data['bagan']['nodes'] = Pamong::status()->get()->toArray();

        return Blade::render('admin.pengurus.bagan_sisip', $data);
    }

    // Shortcode untuk list artikel
    public function convert_sc_list($str = '')
    {
        $regex = '/\\[\\[(.*?)\\]\\]/';

        return preg_replace_callback($regex, function (array $matches) {
            $params_explode = explode(',', $matches[1]);

            return $this->converted_sc_list($params_explode[0] ?? '', $params_explode[1] ?? '');
        }, $str);
    }

    private function converted_sc_list(?string $type = '', ?string $thn = '')
    {
        if ($type == 'lap-RP-APBD-sm1') {
            return "<i class='fa fa-table'></i> Tabel Laporan APBDes Smt. 1 TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-sm2') {
            return "<i class='fa fa-table'></i> Tabel Laporan APBDes Smt. 2 TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-Bidang-sm1') {
            return "<i class='fa fa-table'></i> Tabel Laporan APBDes Smt. 1 TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-Bidang-sm2') {
            return "<i class='fa fa-table'></i> Tabel Laporan APBDes Smt. 2 TA. " . $thn . ', ';
        }
        if ($type == 'grafik-RP-APBD') {
            return "<i class='fa fa-bar-chart'></i> Grafik APBDes TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-Bidang-manual') {
            return "<i class='fa fa-table'></i> Tabel Laporan APBDes TA. " . $thn . ', ';
        }
        if ($type == 'grafik-RP-APBD-manual') {
            return "<i class='fa fa-bar-chart'></i> Grafik APBDes TA. " . $thn . ', ';
        }
        if ($type == 'penerima_bantuan_penduduk_grafik') {
            return "<i class='fa fa-bar-chart'></i> Penerima Bantuan (Penduduk)";
        }
        if ($type == 'penerima_bantuan_penduduk_daftar') {
            return "<i class='fa fa-table'></i> Penerima Bantuan (Penduduk)";
        }
        if ($type == 'penerima_bantuan_keluarga_grafik') {
            return "<i class='fa fa-bar-chart'></i> Penerima Bantuan (Keluarga)";
        }
        if ($type == 'penerima_bantuan_keluarga_daftar') {
            return "<i class='fa fa-table'></i> Penerima Bantuan (Keluarga)";
        }
        if ($type == 'sotk_w_bpd') {
            return "<i class='fa fa-table'></i> Struktur Organisasi (BPD)";
        }
        if ($type == 'sotk_wo_bpd') {
            return "<i class='fa fa-table'></i> Struktur Organisasi";
        }
        if ($type == 'lap-RP-APBD-sm1-dd') {
            return "<i class='fa fa-table'></i> Tabel Laporan Dana Desa Smt. 1 TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-sm2-dd') {
            return "<i class='fa fa-table'></i> Tabel Laporan Dana Desa Smt. 2 TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-Bidang-sm1-dd') {
            return "<i class='fa fa-table'></i> Tabel Laporan Dana Desa Smt. 1 TA. " . $thn . ', ';
        }
        if ($type == 'lap-RP-APBD-Bidang-sm2-dd') {
            return "<i class='fa fa-table'></i> Tabel Laporan Dana Desa Smt. 2 TA. " . $thn . ', ';
        }
        if ($type == 'grafik-RP-APBD-DD') {
            return "<i class='fa fa-bar-chart'></i> Grafik Dana Desa TA. " . $thn . ', ';
        }
    }
}
