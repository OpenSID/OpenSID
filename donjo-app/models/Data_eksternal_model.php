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

defined('BASEPATH') || exit('No direct script access allowed');

class Data_eksternal_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * menscraping data dari website kemendes yang ada pada halaman
     * https://sid.kemendesa.go.id/home/sdgs/{$kode_desa}
     *
     * @param string $kode_desa
     *
     * @return array $data = [
     *               'uraian'	=> 'Desa Tanpa Kemiskinan #1', // mendefinisikan header
     *               'sub			=> [
     *               $sub 	=> [
     *               'uraian'	=>	'Jumlah surat miskin/SKTM yang dikeluarkan desa selama tahun 2017', // menjelaskan rincian data
     *               'value'		=> 	'124'	// isian dari uraian
     *               ]
     *               ]
     *               ]
     */
    public function sdgs_kemendes($kode_desa)
    {
        $this->load->library('data_publik');
        $url = "https://sid.kemendesa.go.id/home/sdgs/{$kode_desa}";
        if (! $this->data_publik->has_internet_connection()) {
            show_error('koneksi Gagal', 408, 'Harap periksa koneksi server anda');

            return;
        }

        $this->data_publik->set_api_url("{$url}", "sdgs_desa_{$kode_desa}")
            ->set_interval(7)
            ->set_cache_folder($this->config->item('cache_path'));

        $response = $this->data_publik->get_url_content($no_cache = false, $secure = false);

        if ($response->header->http_code != 200) {
            return false;
        }

        $selector = '.accordion-stn';
        $html     = str_get_html($response->body);
        $kiri     = $html->find($selector, 0); //pertama

        foreach ($kiri->find('.panel') as $panel) {
            $sub = [];

            foreach ($panel->find('tr') as $tr) {
                $sub[] = [
                    'uraian' => $tr->children(0)->innertext,
                    'value'  => $tr->children(1)->innertext,
                ];
            }
            $data[] = [
                'uraian' => trim(preg_replace('/\t+/', '', $panel->find('a', 0)->innertext)),
                'sub'    => $sub,
            ];
        }
        $kanan = $html->find($selector, 1);

        foreach ($kanan->find('.panel') as $panel) {
            $sub = [];

            foreach ($panel->find('tr') as $tr) {
                $sub[] = [
                    'uraian' => $tr->children(0)->innertext,
                    'value'  => $tr->children(1)->innertext,
                ];
            }
            $data[] = [
                'uraian' => trim(preg_replace('/\t+/', '', $panel->find('a', 0)->innertext)),
                'sub'    => $sub,
            ];
        }
        $this->ambil_icon($data);

        return $data;
    }

    /**
     * Icon untuk SDG 18 format .png bukan .jpg
     *
     * @param mixed $data
     */
    private function ambil_icon(&$data)
    {
        $sumber = 'https://sdgsdesa.kemendesa.go.id/wp-content/uploads/2020/12/Picture';

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['icon'] = $sumber . ($i + 1) . '.jpg';
        }
        $data[17]['icon'] = $sumber . '18.png';
    }
}
