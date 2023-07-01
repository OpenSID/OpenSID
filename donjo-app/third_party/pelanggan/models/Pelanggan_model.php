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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use Esyede\Curly;

require_once 'donjo-app/libraries/Curly.php';

class Pelanggan_model extends MY_Model
{
    /**
     * @var Esyede\Curly
     */
    protected $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Curly();
    }

    public function status_langganan()
    {
        if (empty($response = $this->api_pelanggan_pemesanan())) {
            return null;
        }

        $tgl_akhir = $response->body->tanggal_berlangganan->akhir;

        if (empty($tgl_akhir)) { // pemesanan bukan premium
            if ($response->body->pemesanan) {
                foreach ($response->body->pemesanan as $pemesanan) {
                    $akhir[] = $pemesanan->tgl_akhir;
                }

                $masa_berlaku = calculate_date_intervals($akhir);
            }
        } else { // pemesanan premium
            $tgl_akhir    = strtotime($tgl_akhir);
            $masa_berlaku = round(($tgl_akhir - time()) / (60 * 60 * 24));
        }

        switch (true) {
            case $masa_berlaku > 30:
                $status = ['status' => 1, 'warna' => 'lightgreen', 'ikon' => 'fa-battery-full'];
                break;

            case $masa_berlaku > 10:
                $status = ['status' => 2, 'warna' => 'orange', 'ikon' => 'fa-battery-half'];
                break;

            default:
                $status = ['status' => 3, 'warna' => 'pink', 'ikon' => 'fa-battery-empty'];
        }
        $status['masa'] = $masa_berlaku;

        return $status;
    }

    /**
     * Ambil data pemesanan dari api layanan.opendeda.id
     *
     * @return mixed
     */
    public function api_pelanggan_pemesanan()
    {
        if (empty($this->setting->layanan_opendesa_token)) {
            $this->session->set_userdata('error_status_langganan', 'Token Pelanggan Kosong.');

            return null;
        }

        if ($cache = $this->cache->file->get('status_langganan')) {
            $this->session->set_userdata('error_status_langganan', 'Tunggu sebentar, halaman akan dimuat ulang.');

            return $cache;
        }
    }
}
