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

use App\Enums\Statistik\StatistikEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Enums\StatusEnum;
use App\Http\Transformers\StatistikTransformer;
use App\Repositories\StatistikRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik extends Api_Controller
{
    public function index($slug)
    {
        $key = $this->getKeyFromSlug($slug);
        if ($key === null) {
            return json([
                'status'  => 'error',
                'message' => 'Kategori statistik tidak ditemukan',
            ], 404);
        }

        $statistik = new StatistikRepository();
        $tahun     = request('tahun');
        $filter    = [
            'status' => request('status', StatusEnum::YA),
        ];

        if ($tahun) {
            $filter['tahun'] = $tahun;
        }

        $data = $statistik->sumberData($key, $filter);

        return json($this->fractal($data, new StatistikTransformer(), 'statistik'));
    }

    private function getKeyFromSlug($slug)
    {
        $key = StatistikEnum::keyFromSlug($slug) ?? StatistikJenisBantuanEnum::keyFromSlug($slug);
        if ($key !== null && $key !== '') {
            return $key;
        }

        // Cek jika parameter sudah berupa key terdaftar (numerik atau string key)
        if (in_array((string) $slug, array_map('strval', array_keys(StatistikEnum::allStatistikMerge())), true)) {
            return $slug;
        }

        if (in_array((string) $slug, array_map('strval', array_keys(StatistikJenisBantuanEnum::allKeyLabel())), true)) {
            return $slug;
        }

        // Program bantuan berbentuk '50<program_id>'
        if ((int) $slug > 50 && substr((string) $slug, 0, 2) === '50') {
            return $slug;
        }

        return null;
    }
}
