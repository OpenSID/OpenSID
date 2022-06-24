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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Carbon\Carbon;

class Saas
{
    /**
     * @var CI_Controller
     */
    protected $ci;

    public function __construct()
    {
        $this->ci = get_instance();
    }

    /**
     * Peringatatan informasi layanan Saas.
     *
     * @return mixed
     */
    public function peringatan()
    {
        if ($layanan = $this->ci->cache->file->get('status_langganan')) {
            return collect($layanan->body->pemesanan)
                ->map(static function ($data) {
                    $saas                   = collect($data->layanan)->where('nama', 'Langganan SaaS')->first();
                    $saas->tgl_mulai        = Carbon::parse($data->tgl_mulai);
                    $saas->tgl_akhir        = Carbon::parse($data->tgl_akhir);
                    $saas->status_pemesanan = $data->status_pemesanan;
                    $saas->sisa_aktif       = $saas->tgl_akhir->diffInDays(Carbon::now()) + 1;

                    return $saas;
                })
                ->filter(static function ($data) {
                    if (isset($data->nama)) {
                        return $data;
                    }
                });
        }

        return collect();
    }
}
