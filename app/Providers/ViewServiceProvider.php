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

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    public function boot(): void
    {
        if (! $this->app['ci']->session->instalasi) {
            try {
                $desa = identitas();
            } catch (Exception $e) {
            }
        }

        if ($this->app['ci']->session->db_error['code'] === 1049) {
            $this->app['ci']->session->error_db = null;
            $this->app['ci']->session->unset_userdata(['db_error', 'message', 'heading', 'message_query', 'message_exception', 'sudah_mulai']);
        } else {
            View::share([
                'ci'           => $this->app['ci'],
                'auth'         => $this->app['ci']->session->isAdmin,
                'controller'   => $this->app['ci']->controller ?? $this->app['ci']->aliasController,
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
                'kategori_pengaturan'  => $this->app['ci']->kategori_pengaturan,
                'sub_modul_ini'        => $this->app['ci']->sub_modul_ini,
                'akses_modul'          => $this->app['ci']->sub_modul_ini ?? $this->app['ci']->modul_ini,
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
