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

namespace App\Libraries\TinyMCE;

use App\Models\LogSurat;
use App\Models\Penduduk;

class KodeIsianGambar
{
    private $request;
    private $result;
    private $surat;
    private $urls_id;

    /**
     * @var CI_Controller
     */
    protected $ci;

    public function __construct($request, $result, $surat = null)
    {
        $this->ci = &get_instance();
        $this->ci->load->model('surat_model');
        $this->request = $request;
        $this->result  = $result;
        $this->surat   = $surat;
    }

    public static function set($request, $result, $surat = null): array
    {
        return (new self($request, $result, $surat))->setKodeIsianGambar();
    }

    public function setKodeIsianGambar(): array
    {
        // Logo Surat
        $file_logo    = ($this->request['logo_garuda'] ? FCPATH . LOGO_GARUDA : gambar_desa(identitas()->logo, false, true));
        $logo         = (is_file($file_logo)) ? '<img src="' . $file_logo . '" width="90" height="90" alt="logo-surat" />' : '';
        $this->result = str_ireplace('[logo]', $logo, $this->result);

        // Logo BSrE
        $file_logo_bsre = FCPATH . LOGO_BSRE;
        $bsre           = (is_file($file_logo_bsre) && setting('tte') == 1) ? '<img src="' . $file_logo_bsre . '" height="90" alt="logo-bsre" />' : '';
        $this->result   = str_ireplace('[logo_bsre]', $bsre, $this->result);

        // Foto Penduduk
        // TODO:: Sederhanakan cara ini, seharusnya key dan value dari kode isian berada di 1 tempat yang sama
        $foto = Penduduk::find($this->surat['id_pend'])->foto;
        if (file_exists(FCPATH . LOKASI_USER_PICT . $foto)) {
            $file_foto     = FCPATH . LOKASI_USER_PICT . $foto;
            $foto_penduduk = '<img src="' . $file_foto . '" width="90" height="auto" alt="foto-penduduk" />';
            $this->result  = str_ireplace('[foto_penduduk]', $foto_penduduk, $this->result);
        } else {
            $this->result = str_ireplace('[foto_penduduk]', '', $this->result);
        }

        // QR_Code Surat
        if ($this->surat) {
            $qrCodeCondition = $this->request['qr_code'] && (setting('tte') == 1 && $this->surat->verifikasi_kades == LogSurat::TERIMA) || (setting('tte') == 0);
            if ($qrCodeCondition) {
                // TODO:: pindahkan fuction buatQrCode
                $cek           = $this->surat_model->buatQrCode($this->surat->nama_surat);
                $qrcode        = ($cek['viewqr']) ? '<img src="' . $cek['viewqr'] . '" width="90" height="90" alt="qrcode-surat" />' : '';
                $this->result  = str_replace('[qr_code]', $qrcode, $this->result);
                $this->urls_id = $cek['urls_id'];
            }
        } else {
            $this->result = str_replace('[qr_code]', '', $this->result);
        }

        return [
            'result'  => $this->result,
            'urls_id' => $this->urls_id,
        ];
    }

    public function __get($name)
    {
        return $this->ci->{$name};
    }

    public function __call($method, $arguments)
    {
        return $this->ci->{$method}(...$arguments);
    }
}
