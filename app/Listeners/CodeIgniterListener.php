<?php

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
        //
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
