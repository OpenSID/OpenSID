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

class Inventaris extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->hak_akses_menu('inventaris');
    }

    public function index()
    {
        return view('theme::partials.inventaris.index');
    }

    public function detail($slug = null)
    {
        switch ($slug) {
            case 'tanah':
                $judul    = 'Inventaris Tanah';
                $template = 'tanah';
                break;

            case 'peralatan-dan-mesin':
                $judul    = 'Inventaris Peralatan dan Mesin';
                $template = 'peralatan';
                break;

            case 'gedung-dan-bangunan':

                $judul    = 'Inventaris Gedung dan Bangunan';
                $template = 'gedung';
                break;

            case 'jalan-irigasi-dan-jaringan':
                $judul    = 'Inventaris Jalan, Irigasi dan Jaringan';
                $template = 'jalan';
                break;

            case 'asset-tetap-lainnya':
                $judul    = 'Inventaris Asset Tetap Lainnya';
                $template = 'asset';
                break;

            case 'konstruksi-dalam-pengerjaan':
                $judul    = 'Inventaris Konstruksi dalam Pengerjaan';
                $template = 'konstruksi';
                break;

            default:
                show_404();
                break;
        }

        return view("theme::partials.inventaris.{$template}", [
            'judul' => $judul,
        ]);
    }
}
