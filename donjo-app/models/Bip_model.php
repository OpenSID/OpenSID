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

class Bip_model extends CI_Model
{
    public function __construct($data)
    {
        parent::__construct();
        // Sediakan memory paling sedikit 512M
        preg_match('/^(\d+)(M)$/', ini_get('memory_limit'), $matches);
        $memory_limit = $matches[1] ?: 0;
        if ($memory_limit < 512) {
            ini_set('memory_limit', '512M');
        }
        set_time_limit(3600);
        // $this->load->library('Spreadsheet_Excel_Reader');
        $this->format_bip = $this->cari_format_bip($data);
        $this->data       = $data;
    }

    /**
     * Tentunkan format BIP yang akan digunakan
     *
     * @param sheet		data excel berisi bip
     * @param mixed $data
     *
     * @return model format BIP yang akan digunakan
     */
    private function cari_format_bip($data)
    {
        $data_sheet = $data->sheets[0]['cells'];
        if (strtolower($data_sheet[1][1]) == 'nomor kk' && strtolower($data_sheet[1][34]) == 'petugas registrasi') {
            require_once APPPATH . '/models/Siak_model.php';

            return new Siak_Model();
        }
        if ($data_sheet[1][1] == 'BUKU INDUK PENDUDUK WNI') {
            require_once APPPATH . '/models/Bip2016_model.php';

            return new BIP2016_Model();
        }
        if (strpos($data_sheet[1][2], 'BUKU INDUK KEPENDUDUKAN') !== false && strpos($data_sheet[1][2], '(DAFTAR  KELUARGA)') !== false) {
            require_once APPPATH . '/models/Bip2016_luwutimur_model.php';

            return new BIP2016_Luwutimur_Model();
        }
        if (strpos($data_sheet[1][16], 'Wjb KTP') !== false && strpos($data_sheet[1][17], 'KTP-eL') !== false) {
            require_once APPPATH . '/models/Bip_ektp_model.php';

            return new BIP_Ektp_Model();
        }

        require_once APPPATH . '/models/Bip2012_model.php';

        return new BIP2012_Model();
    }

    public function impor_bip(): void
    {
        $this->format_bip->impor_data_bip($this->data);
    }
}
