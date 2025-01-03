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

use App\Models\Statistics;
use App\Models\Urls;

defined('BASEPATH') || exit('No direct script access allowed');

class Verifikasi_surat extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cek($alias = null): void
    {
        $cek = Urls::select(['id', 'url'])->where('alias', (string) $alias)->first();
        if (! $cek) {
            show_404();
        }

        $data = [
            'url_id'  => (int) $cek->id,
            'created' => date('Y-m-d H:i:s'),
        ];
        Statistics::create($data);

        redirect($cek->url);
    }

    public function encode($id_dokumen = null, $tipe = null): void
    {
        $id_encoded = encodeId($id_dokumen);
        if ($tipe == 'surat_dinas') {
            redirect('verifikasi-surat-dinas/' . $id_encoded);
        }
        redirect('verifikasi-surat/' . $id_encoded);
    }

    public function decode($id_encoded = null): void
    {
        $id = decodeId($id_encoded);

        view('theme::partials.surat.index', ['id' => $id]);
    }

    public function decodeSuratDinas($id_encoded = null): void
    {
        $id = decodeId($id_encoded);
        view('theme::partials.surat_dinas.index', ['id' => $id]);
    }
}
