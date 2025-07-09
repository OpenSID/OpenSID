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

namespace App\Libraries\TinyMCE;

use App\Models\Wilayah;

class KodeIsianWilayah
{
    // Jumlah dusun normal saat cetak surat
    private const MAX_DUSUN = 27;

    private $wilayah;
    private $sebutanDusun;

    public function __construct()
    {
        $wilayah = Wilayah::with('kepala')->dusun();
        if ($wilayah->count() > self::MAX_DUSUN) {
            log_message('notice', 'Jumlah dusun melebihi batas normal, hanya akan ditampilkan ' . self::MAX_DUSUN . ' dusun');
        }

        $this->wilayah      = $wilayah->take(self::MAX_DUSUN)->get();
        $this->sebutanDusun = setting('sebutan_dusun');
    }

    public static function get(): array
    {
        return (new self())->kodeIsian();
    }

    /**
     * @return array<mixed, array<'data'|'isian'|'judul', mixed>>
     */
    public function kodeIsian(): array
    {
        $data = [];

        foreach ($this->wilayah as $wil) {
            $namaDusun   = ucwords($this->sebutanDusun . ' ' . $wil->dusun);
            $kepalaDusun = ucwords(setting('sebutan_kepala_dusun') . ' ' . $wil->dusun);

            $data[] = [
                'judul' => $namaDusun,
                'isian' => $namaDusun,
                'data'  => $wil->dusun,
            ];
            $data[] = [
                'judul' => $kepalaDusun,
                'isian' => $kepalaDusun,
                'data'  => $wil->kepala->nama,
            ];
        }

        return $data;
    }
}
