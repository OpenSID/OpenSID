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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Console\Commands;

use App\Services\MasaAktifAkunService;
use Illuminate\Console\Command;

class DeactivateInactiveAccounts extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'opensid:deactivate-inactive-accounts';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Menonaktifkan akun pengguna yang tidak aktif berdasarkan pengaturan sistem.';

    /**
     * {@inheritDoc}
     */
    public function handle(MasaAktifAkunService $masaAktifAkunService)
    {
        if (! setting('masa_akun_pengguna')) {
            $this->info('Fitur penonaktifan akun otomatis tidak aktif.');

            return 0;
        }

        if (setting('jenis_trigger_nonaktifkan_akun') !== 'cron') {
            $this->info('Trigger penonaktifkan akun diatur ke Manual. Cron job tidak akan berjalan.');

            return 0;
        }

        $result = $masaAktifAkunService->deactivateInactiveAccounts();

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }

        return 0;
    }
}
