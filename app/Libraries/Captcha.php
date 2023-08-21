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

namespace App\Libraries;

use Laminas\Captcha\Image;

defined('BASEPATH') || exit('No direct script access allowed');

class Captcha extends Image
{
    /**
     * Directory for generated images
     *
     * @var string
     */
    protected $imgDir = DESAPATH . 'secureimages/';

    /**
     * URL for accessing images
     *
     * @var string
     */
    protected $imgUrl = DESAPATH . 'secureimages';

    /**
     * Image's alt tag content
     *
     * @var string
     */
    protected $imgAlt = '';

    /**
     * Image suffix (including dot)
     *
     * @var string
     */
    protected $suffix = '.png';

    /**
     * Image width
     *
     * @var int
     */
    protected $width = 300;

    /**
     * Image height
     *
     * @var int
     */
    protected $height = 100;

    /**
     * Font size
     *
     * @var int
     */
    protected $fsize = 45;

    /**
     * Image font file
     *
     * @var string
     */
    protected $font = FCPATH . 'assets/fonts/SansSerif.ttf';

    protected $dotNoiseLevel  = 13;            // Noise level for dots
    protected $lineNoiseLevel = 4;
    protected $wordlen        = 6;
    protected $name           = 'captcha_code';
    protected $useNumbers     = false;

    public function __construct()
    {
        if (! file_exists($this->getImgDir())) {
            mkdir($this->imgDir, 0755);
        }
        parent::__construct();
    }

    public function show()
    {
        $this->generate();
        // save id in session
        $_SESSION['securimage_laminas_id'] = $this->getId();
        // This will output a Figlet string:
        $filename = $this->getImgDir() . $this->getId() . $this->getSuffix();
        if (file_exists($filename)) {
            $mime = mime_content_type($filename); //<-- detect file type
            header('Content-Length: ' . filesize($filename)); //<-- sends filesize header
            header("Content-Type: {$mime}"); //<-- send mime-type header
            header('Content-Disposition: inline; filename="' . $filename . '";'); //<-- sends filename header
            readfile($filename); //<--reads and outputs the file onto the output buffer

            exit(); // or die()
        }
    }

    public function check($code)
    {
        return $this->isValid(['input' => $code, 'id' => $_SESSION['securimage_laminas_id']]);
    }
}
