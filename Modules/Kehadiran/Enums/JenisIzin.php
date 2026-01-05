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

namespace Modules\Kehadiran\Enums;

use App\Enums\BaseEnum;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Enum untuk jenis izin tidak masuk kantor
 */
class JenisIzin extends BaseEnum
{
    public const IZIN            = 'izin';
    public const SAKIT           = 'sakit';
    public const DINAS_LUAR_KOTA = 'dinas_luar_kota';
    public const CUTI            = 'cuti';
    public const LAINNYA         = 'lainnya';

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::IZIN            => 'Izin',
            self::SAKIT           => 'Sakit',
            self::DINAS_LUAR_KOTA => 'Dinas Luar Kota',
            self::CUTI            => 'Cuti',
            self::LAINNYA         => 'Lainnya',
        ];
    }

    /**
     * Get description for each leave type
     */
    public static function description(string $jenis): string
    {
        return match ($jenis) {
            self::IZIN            => 'Izin karena keperluan pribadi atau keluarga',
            self::SAKIT           => 'Tidak masuk karena sakit atau kondisi kesehatan',
            self::DINAS_LUAR_KOTA => 'Perjalanan dinas ke luar kota atau wilayah',
            self::CUTI            => 'Cuti tahunan atau cuti khusus',
            self::LAINNYA         => 'Alasan lain yang sah',
            default               => '',
        };
    }

    /**
     * Get icon class for each leave type
     */
    public static function icon(string $jenis): string
    {
        return match ($jenis) {
            self::IZIN            => 'fa fa-user-times',
            self::SAKIT           => 'fa fa-heartbeat',
            self::DINAS_LUAR_KOTA => 'fa fa-car',
            self::CUTI            => 'fa fa-calendar-times-o',
            self::LAINNYA         => 'fa fa-question-circle',
            default               => 'fa fa-question',
        };
    }

    /**
     * Get color class for each leave type
     */
    public static function color(string $jenis): string
    {
        return match ($jenis) {
            self::IZIN            => 'info',
            self::SAKIT           => 'danger',
            self::DINAS_LUAR_KOTA => 'warning',
            self::CUTI            => 'success',
            self::LAINNYA         => 'default',
            default               => 'default',
        };
    }

    /**
     * Check if this leave type requires approval
     */
    public static function requiresApproval(string $jenis): bool
    {
        return match ($jenis) {
            self::IZIN            => true,
            self::SAKIT           => false, // Usually medical certificate is enough
            self::DINAS_LUAR_KOTA => true,
            self::CUTI            => true,
            self::LAINNYA         => true,
            default               => true,
        };
    }

    /**
     * Check if this leave type requires medical document
     */
    public static function requiresMedicalDocument(string $jenis): bool
    {
        return match ($jenis) {
            self::SAKIT => true,
            default     => false,
        };
    }

    /**
     * Get maximum allowed days for this leave type
     */
    public static function maxDays(string $jenis): ?int
    {
        return match ($jenis) {
            self::IZIN            => 3, // Max 3 days without special permission
            self::SAKIT           => null, // No limit with medical certificate
            self::DINAS_LUAR_KOTA => null, // Depends on assignment
            self::CUTI            => 12, // Annual leave quota
            self::LAINNYA         => 1, // Max 1 day unless special case
            default               => null,
        };
    }

    /**
     * Get detailed options with descriptions for forms
     */
    public static function detailedOptions(): array
    {
        $options = [];

        foreach (static::all() as $value => $label) {
            $options[$value] = [
                'label'                     => $label,
                'description'               => static::description($value),
                'icon'                      => static::icon($value),
                'color'                     => static::color($value),
                'requires_approval'         => static::requiresApproval($value),
                'requires_medical_document' => static::requiresMedicalDocument($value),
                'max_days'                  => static::maxDays($value),
            ];
        }

        return $options;
    }

    /**
     * Get leave types that require approval
     */
    public static function requireApproval(): array
    {
        return array_filter(static::keys(), static fn ($jenis) => static::requiresApproval($jenis));
    }

    /**
     * Get leave types that require medical documents
     */
    public static function requireMedicalDocument(): array
    {
        return array_filter(static::keys(), static fn ($jenis) => static::requiresMedicalDocument($jenis));
    }
}
