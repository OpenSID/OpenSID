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

class Analisis_indikator_model extends MY_Model
{
    public function get_analisis_indikator_by_id_master($id = 0)
    {
        $list_indikator = [];
        $list_parameter = [];

        $raw_indikator = $this->config_id('i')
            ->select('i.*')
            ->from('analisis_indikator i')
            ->where('i.id_master', $id)
            ->get()
            ->result_array();

        // Setting key array sesuai id
        foreach ($raw_indikator as $val_indikator) {
            $list_indikator[$val_indikator['id']] = $val_indikator['pertanyaan'];

            $temp_parameter = [];

            $raw_parameter = $this->config_id()->where('id_indikator', $val_indikator['id'])->get('analisis_parameter')->result_array();

            foreach ($raw_parameter as $val_parameter) {
                $temp_parameter[$val_parameter['id']] = $val_parameter['jawaban'];
            }

            $list_parameter[$val_indikator['id']] = $temp_parameter;
        }

        return [
            'indikator' => $list_indikator,
            'parameter' => $list_parameter,
        ];
    }
}
