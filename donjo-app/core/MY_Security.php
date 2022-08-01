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

class MY_Security extends CI_Security
{
    protected function _sanitize_naughty_html($matches)
    {
        static $naughty_tags = [
            'alert', 'area', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound',
            'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer',
            'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object',
            'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss',
        ];

        //attribut style dihilangkan disini, dampak issue #761
        static $evil_attributes = [
            'on\w+', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime',
        ];

        // First, escape unclosed tags
        if (empty($matches['closeTag'])) {
            return '&lt;' . $matches[1];
        }
        // Is the element that we caught naughty? If so, escape it
        if (in_array(strtolower($matches['tagName']), $naughty_tags)) {
            return '&lt;' . $matches[1] . '&gt;';
        }
        // For other tags, see if their attributes are "evil" and strip those
        if (isset($matches['attributes'])) {
            // We'll store the already filtered attributes here
            $attributes = [];

            // Attribute-catching pattern
            $attributes_pattern = '#'
                . '(?<name>[^\s\042\047>/=]+)' // attribute characters
                // optional attribute-value
                . '(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))' // attribute-value separator
                . '#i';

            // Blacklist pattern for evil attribute names
            $is_evil_pattern = '#^(' . implode('|', $evil_attributes) . ')$#i';

            // Each iteration filters a single attribute
            do {
                // Strip any non-alpha characters that may precede an attribute.
                // Browsers often parse these incorrectly and that has been a
                // of numerous XSS issues we've had.
                $matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);

                if (! preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE)) {
                    // No (valid) attribute found? Discard everything else inside the tag
                    break;
                }

                if (
                    // Is it indeed an "evil" attribute?
                    preg_match($is_evil_pattern, $attribute['name'][0])
                    // Or does it have an equals sign, but no value and not quoted? Strip that too!
                    || (trim($attribute['value'][0]) === '')
                ) {
                    $attributes[] = 'xss=removed';
                } else {
                    $attributes[] = $attribute[0][0];
                }

                $matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
            } while ($matches['attributes'] !== '');

            $attributes = empty($attributes)
                ? ''
                : ' ' . implode(' ', $attributes);

            return '<' . $matches['slash'] . $matches['tagName'] . $attributes . '>';
        }

        return $matches[0];
    }

    /**
     * {@inheritDoc}
     */
    public function csrf_show_error()
    {
        // ==== Uncomment berikut untuk debugging masalah CSRF
        // print("<pre>".print_r(getallheaders(),true)."</pre>");
        // print("<pre>".print_r($_POST, true)."</pre>");
        // die();

        $heading = 'Bad Request';
        $message = "Verifikasi CSRF Gagal. <br><br>
			Kembali ke halaman sebelumnya di <a href='" . $_SERVER['HTTP_REFERER'] . "'>sini</a>, dan ulangi.<br><br>
			Kalau masih error, coba clear cache dan cookies di browser anda, dan login kembali.<br><br>
			Kalau masih bermasalah, silakan laporkan.";
        show_error($message, 400, $heading);
    }
}
