<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $ci = &get_instance();

        $this->app->singleton('ci', fn() => $ci);

        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    public function boot(): void
    {
        if (! $this->app['ci']->session->instalasi) {
            try {
                $desa = identitas();
            } catch (\Exception $e) {
            }
        }

        if ($this->app['ci']->session->db_error['code'] === 1049) {
            $this->app['ci']->session->error_db = null;
            $this->app['ci']->session->unset_userdata(['db_error', 'message', 'heading', 'message_query', 'message_exception', 'sudah_mulai']);
        } else {
            View::share([
                'ci'           => $this->app['ci'],
                'auth'         => $this->app['ci']->session->isAdmin,
                'controller'   => $this->app['ci']->controller,
                'desa'         => $desa ?? null,
                'list_setting' => $this->app['ci']->list_setting,
                'modul'        => $this->app['ci']->header['modul'],
                'modul_ini'    => $this->app['ci']->modul_ini,
                'notif'        => [
                    'surat'           => $this->app['ci']->header['notif_permohonan_surat'],
                    'opendkpesan'     => $this->app['ci']->header['notif_pesan_opendk'],
                    'inbox'           => $this->app['ci']->header['notif_inbox'],
                    'komentar'        => $this->app['ci']->header['notif_komentar'],
                    'langganan'       => $this->app['ci']->header['notif_langganan'],
                    'pengumuman'      => $this->app['ci']->header['notif_pengumuman'],
                    'permohonansurat' => $this->app['ci']->header['notif_permohonan'],
                ],
                'kategori'             => $this->app['ci']->header['kategori'],
                'sub_modul_ini'        => $this->app['ci']->sub_modul_ini,
                'akses_modul'          => $this->app['ci']->akses_modul,
                'session'              => $this->app['ci']->session,
                'setting'              => $this->app['ci']->setting,
                'token'                => $this->app['ci']->security->get_csrf_token_name(),
                'perbaharui_langganan' => $this->app['ci']->header['perbaharui_langganan'] ?? null,
            ]);
        }
    }

    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        $bladeCompiler->directive('selected', static fn ($condition): string => "<?= ({$condition}) ? 'selected' : ''; ?>");

        $bladeCompiler->directive('checked', static fn ($condition): string => "<?= ({$condition}) ? 'checked' : ''; ?>");

        $bladeCompiler->directive('disabled', static fn ($condition): string => "<?= ({$condition}) ? 'disabled' : ''; ?>");

        $bladeCompiler->directive('active', static fn ($condition): string => "<?= ({$condition}) ? 'active' : ''; ?>");

        $bladeCompiler->directive('display', static fn ($condition): string => "<?= ({$condition}) ? 'show' : 'hide'; ?>");

        $bladeCompiler->directive('can', static fn ($condition): string => "<?= can({$condition}) ?>");
    }
}