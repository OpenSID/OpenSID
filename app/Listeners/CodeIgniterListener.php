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

namespace App\Listeners;

use App\Events\CodeIgniterEvent;
use App\Providers\ViewServiceProvider;
use Illuminate\Container\Container;

class CodeIgniterListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(CodeIgniterEvent $event): void
    {
        $ci = &$event->ci->get_instance();

        $container = Container::getInstance();

        $container->singleton('ci', static fn () => $ci);

        // Set config setelah instance ci
        $container['config']->set('mail.default', $ci?->setting?->email_protocol);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.transport", $ci?->setting?->email_protocol);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.url", $ci?->setting?->email_smtp_url);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.host", $ci?->setting?->email_smtp_host);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.port", $ci?->setting?->email_smtp_port);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.encryption", $ci?->setting?->email_smtp_encryption ?? 'tls');
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.username", $ci?->setting?->email_smtp_user);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.password", $ci?->setting?->email_smtp_pass);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.timeout", $ci?->setting?->email_smtp_timeout);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.local_domain", $ci?->setting?->email_smtp_domain);

        $container['config']->set([
            'captcha' => [
                'secret'  => $ci?->setting?->google_recaptcha_secret_key,
                'sitekey' => $ci?->setting?->google_recaptcha_site_key,
                'options' => [],
            ],
            'services' => [
                'telegram-bot-api' => [
                    'token' => $ci?->setting?->telegram_token,
                ],
            ],
        ]);

        $container->register(ViewServiceProvider::class);
    }
}
