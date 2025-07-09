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

defined('BASEPATH') || exit('No direct script access allowed');

class Url_shortener_model extends MY_Model
{
    public function url_pendek($log_surat = [])
    {
        $url = site_url("c1/{$log_surat['id']}");
        $id  = $this->add_url($url);

        $urlData = $this->getUrlById($id);

        return [
            'isiqr'   => site_url('v/' . $urlData->alias),
            'urls_id' => $id,
        ];
    }

    public function add_url($url)
    {
        $data = [
            'config_id' => $this->config_id,
            'url'       => (string) $url,
            'alias'     => (string) $this->random_code(6),
            'created'   => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('urls', $data);

        return $this->db->insert_id();
    }

    public function getUrlById($id)
    {
        return $this->config_id()->get_where('urls', ['id' => (int) $id])->row();
    }

    public function get_url($alias)
    {
        $result = $this->config_id()
            ->select('*')
            ->from('urls')
            ->where('alias', (string) $alias)
            ->get()
            ->row_object();

        return $result ?? false;
    }

    public function random_code($length)
    {
        return substr(base_convert(sha1(uniqid(random_int(0, mt_getrandmax()))), 16, 36), 0, $length);
    }

    public function encode_id($plainText)
    {
        $key         = $this->config->item('encryption_url') . time();
        $random_code = $this->random_code(20);
        $base64      = base64_encode($random_code . ',' . $plainText . ',' . $key . ',' . $plainText);
        $base64url   = strtr($base64, '+/=', '-  ');

        return trim($base64url);
    }

    public function decode_id($plainText)
    {
        $base64url = strtr($plainText, '-  ', '+/=');
        $base64    = base64_decode($base64url, true);
        $exp       = explode(',', $base64);

        return ($exp[1] !== $exp[3]) ? $plainText : $exp[1];
    }
}
