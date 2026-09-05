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

namespace App\Actions\Modul;

use App\Actions\GrupAkses\UpsertGrupAkses;
use App\Enums\StatusEnum;
use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;

class UpsertModul
{
    public function handle(array $item): void
    {
        $slug = $item['slug'] ?? null;
        if (! $slug) {
            return;
        }

        if (! isset($item['aktif'])) {
            $item['aktif'] = StatusEnum::YA;
        }

        if (! isset($item['hidden'])) {
            $item['hidden'] = 0;
        }

        $item['ikon_kecil'] ??= $item['ikon'];

        // Tentukan parent
        if (($item['parent'] ?? 0) != 0 || isset($item['parent_slug'])) {
            $parent = Modul::where('slug', $item['parent_slug'] ?? null)->first();
            if (! $parent) {
                return;
            }

            $item['parent'] = $parent->id;
        }

        $existing = Modul::where('slug', $slug)->first();

        if ($existing) {
            unset($item['modul'], $item['ikon'], $item['ikon_kecil'],  $item['aktif'], $item['parent_slug']);

            $existing->update($item);

            return;
        }

        unset($item['parent_slug']);

        $modul = Modul::create($item);

        $this->assignDefaultAkses($modul->id);
    }

    /* ===============================
     * DEFAULT HAK AKSES
     * =============================== */
    protected function assignDefaultAkses(int $modulId): void
    {
        $upsert = new UpsertGrupAkses();

        $upsert->handle([
            'id_grup'  => UserGrup::getGrupId(UserGrup::ADMINISTRATOR),
            'id_modul' => $modulId,
            'akses'    => GrupAkses::HAPUS,
        ]);

        $upsert->handle([
            'id_grup'  => UserGrup::getGrupId(UserGrup::OPERATOR),
            'id_modul' => $modulId,
            'akses'    => GrupAkses::UBAH,
        ]);
    }
}
