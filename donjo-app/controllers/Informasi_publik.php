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

class Informasi_publik extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('web_dokumen_model');
        $this->load->model('log_ekspor_model');
    }

    public function ekspor(): void
    {
        $data['form_action']   = site_url('informasi_publik/ekspor_csv');
        $data['log_semua']     = $this->log_ekspor_model->log_terakhir('informasi_publik', 1);
        $data['log_perubahan'] = $this->log_ekspor_model->log_terakhir('informasi_publik', 2);
        $this->load->view('dokumen/dialog_ekspor', $data);
    }

    public function ekspor_csv(): void
    {
        $filename = 'informasi_publik_' . date('Ymd') . '.csv';
        // Gunakan file temporer
        $tmpfname = tempnam(sys_get_temp_dir(), '');
        // Siapkan daftar berkas untuk dimasukkan ke zip
        $berkas   = [];
        $berkas[] = [
            'nama' => $filename,
            'file' => $tmpfname,
        ];
        // Folder untuk berkas dokumen dalam zip
        $berkas[] = [
            'nama' => 'dir',
            'file' => 'berkas',
        ];

        // Ambil data dan berkas infoemasi publik
        $file = fopen($tmpfname, 'wb');
        $data = $this->web_dokumen_model->ekspor_informasi_publik($this->input->post('data_ekspor'), $this->input->post('tgl_dari'));

        $header = array_keys($data[0]);
        fputcsv($file, $header);

        foreach ($data as $baris) {
            fputcsv($file, array_values($baris));
            // Masukkan berkas ke dalam folder dalam zip
            $berkas[] = [
                'nama' => 'berkas/' . $baris['satuan'],
                'file' => FCPATH . LOKASI_DOKUMEN . $baris['satuan'],
            ];
        }
        fclose($file);

        // Tulis log ekspor
        $log = [
            'kode_ekspor' => 'informasi_publik',
            'semua'       => $this->input->post('data_ekspor'),
            'total'       => count($data),
        ];
        $this->log_ekspor_model->tulis_log($log);

        // Masukkan semua berkas ke dalam zip
        $berkas_zip = masukkan_zip($berkas);
        // Unduh berkas zip
        $data = $this->header['desa'];
        header('Content-Description: File Transfer');
        header('Content-disposition: attachment; filename=informasi_publik_' . $data['kode_desa'] . '_' . date('d-m-Y') . '.zip');
        header('Content-type: application/zip');
        readfile($berkas_zip);
    }
}
