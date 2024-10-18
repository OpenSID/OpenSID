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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\LogSuratDinas;

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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Verifikasi_surat extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['keluar_model', 'url_shortener_model', 'stat_shortener_model']);
    }

    public function cek($alias = null): void
    {
        $cek = $this->url_shortener_model->get_url($alias);
        if (! $cek) {
            show_404();
        }

        $this->stat_shortener_model->add_log($cek->id);

        redirect($cek->url);
    }

    public function encode($id_dokumen = null, $tipe = null): void
    {
        $id_encoded = $this->url_shortener_model->encode_id($id_dokumen);
        if ($tipe == 'surat_dinas') {
            redirect('verifikasi-surat-dinas/' . $id_encoded);
        }
        redirect('verifikasi-surat/' . $id_encoded);
    }

    public function decode($id_encoded = null): void
    {
        $id_decoded = $this->url_shortener_model->decode_id($id_encoded);

        $data['config'] = $this->header;
        $data['surat']  = $this->keluar_model->verifikasi_data_surat($id_decoded, $this->header['kode_desa']);

        if (! $data['surat']) {
            show_404();
        }

        $this->load->view("{$this->includes['folder_themes']}/partials/surat/index", $data);
    }

    public function decodeSuratDinas($id_encoded = null): void
    {
        $id_decoded               = $this->url_shortener_model->decode_id($id_encoded);
        $suratDinas               = LogSuratDinas::withOnly(['suratDinas'])->findOrFail($id_decoded);
        $data['config']           = $this->header;
        $objSurat                 = new stdClass();
        $objSurat->nomor_surat    = $suratDinas->formatPenomoranSurat;
        $objSurat->tanggal        = $suratDinas->tanggal;
        $objSurat->perihal        = $suratDinas->suratDinas->nama;
        $objSurat->nama_penduduk  = '';
        $objSurat->pamong_nama    = $suratDinas->nama_pamong;
        $objSurat->pamong_jabatan = $suratDinas->nama_jabatan;

        $data['surat'] = $objSurat;
        $this->load->view("{$this->includes['folder_themes']}/partials/surat/index", $data);
    }
}
