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

defined('BASEPATH') || exit('No direct script access allowed');

enum PeristiwaKeluargaEnum: int
{
    case KELUARGA_BARU                 = 1;
    case KEPALA_KELUARGA_MATI          = 2;
    case KEPALA_KELUARGA_PINDAH        = 3;
    case KEPALA_KELUARGA_HILANG        = 4;
    case KELUARGA_BARU_DATANG          = 5;
    case KEPALA_KELUARGA_PERGI         = 6;
    case KEPALA_KELUARGA_TIDAK_VALID   = 11;
    case ANGGOTA_KELUARGA_PECAH        = 12;
    case KELUARGA_HAPUS                = 13;
    case KEPALA_KELUARGA_KEMBALI_HIDUP = 14;

    public static function labels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(static fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::KELUARGA_BARU                 => 'Baru Lahir',
            self::KEPALA_KELUARGA_MATI          => 'Kepala Keluarga Mati',
            self::KEPALA_KELUARGA_PINDAH        => 'Kepala Keluarga Pindah',
            self::KEPALA_KELUARGA_HILANG        => 'Kepala Keluarga Hilang',
            self::KELUARGA_BARU_DATANG          => 'Keluarga Baru Datang',
            self::KEPALA_KELUARGA_PERGI         => 'Kepala Keluarga Pergi',
            self::KEPALA_KELUARGA_TIDAK_VALID   => 'Kepala Keluarga Tidak Valid',
            self::ANGGOTA_KELUARGA_PECAH        => 'Anggota Keluarga Pecah',
            self::KELUARGA_HAPUS                => 'Keluarga Hapus',
            self::KEPALA_KELUARGA_KEMBALI_HIDUP => 'Kepala Keluarga Kembali Hidup',
        };
    }
}
