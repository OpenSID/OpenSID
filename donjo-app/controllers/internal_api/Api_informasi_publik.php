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

/*
 * Untuk menyediakan data informasi publik bagi pengguna eksternal.
 * Data informasi publik bebas diakses umum
 */
class Api_informasi_publik extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('web_dokumen_model');
    }

    public function index(): void
    {
        redirect('ppid');
    }

    public function ppid(): void
    {
        $this->log_request();
        $get      = $this->input->get();
        $tgl_dari = $get['tgl_dari'];
        if (! empty($tgl_dari) && ! validate_date($tgl_dari)) {
            $json_send = ['status' => 'fail',
                'data'             => ['tgl_dari' => 'tgl_dari harus tanggal dalam format d-m-Y',
                ],
            ];
        } else {
            $jenis_kirim = empty($get['tgl_dari']) ? 'semua' : 'perubahan';
            $data        = $this->web_dokumen_model->data_ppid($tgl_dari);
            $json_send   = ['status' => 'success',
                'data'               => ['ppid' => $data,
                    'tanggal'                   => date('d-m-Y h:i:s', time()),
                    'pengiriman'                => $jenis_kirim,
                    'tgl_dari'                  => $tgl_dari,
                    'total data'                => count($data),
                ],
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($json_send, JSON_THROW_ON_ERROR);
    }
}
