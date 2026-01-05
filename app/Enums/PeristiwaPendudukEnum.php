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

namespace App\Enums;

enum PeristiwaPendudukEnum: int
{
    case BARU_LAHIR        = 1;
    case MATI              = 2;
    case PINDAH_KELUAR     = 3;
    case HILANG            = 4;
    case BARU_PINDAH_MASUK = 5;
    case TIDAK_TETAP_PERGI = 6;

    public static function labels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(static fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function peristiwa(): array
    {
        return [
            self::BARU_LAHIR->value,
            self::MATI->value,
            self::PINDAH_KELUAR->value,
            self::HILANG->value,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::BARU_LAHIR        => 'Baru Lahir',
            self::MATI              => 'Mati',
            self::PINDAH_KELUAR     => 'Pindah Keluar',
            self::HILANG            => 'Hilang',
            self::BARU_PINDAH_MASUK => 'Baru Pindah Masuk',
            self::TIDAK_TETAP_PERGI => 'Tidak Tetap Pergi',
        };
    }
}
