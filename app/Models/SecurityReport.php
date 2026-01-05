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

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SecurityReport extends Model
{
    use ConfigId;

    protected $table    = 'security_reports';
    protected $fillable = [
        'filename',
        'type',
        'data',
        'config_id',
    ];
    protected $casts = [
        'data' => 'encrypted:array',
    ];

    /**
     * Scope untuk tipe tertentu
     *
     * @param mixed $query
     * @param mixed $type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accessor untuk tanggal scan
     */
    protected function scanDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->data['checked_at']
                ?? $this->data['scan_date']
                ?? $this->created_at->format('Y-m-d H:i:s')
        );
    }

    /**
     * Accessor untuk tipe scan
     */
    protected function scanType(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->data['scan_type'] ?? $this->type
        );
    }

    /**
     * Accessor untuk total files
     */
    protected function totalFiles(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->data['total_scanned']
                ?? $this->data['statistics']['total_checked']
                ?? $this->data['statistics']['total_files']
                ?? 0
        );
    }

    /**
     * Accessor untuk suspicious count
     */
    protected function suspiciousCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->data['suspicious_count']
                ?? $this->data['statistics']['suspicious_count']
                ?? 0
        );
    }

    /**
     * Accessor untuk max risk level
     */
    protected function maxRisk(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getMaxRiskLevel($this->data)
        );
    }

    /**
     * Get maximum risk level from report data
     *
     * @param mixed $reportData
     */
    private function getMaxRiskLevel($reportData)
    {
        // Handle different JSON formats (new format uses 'files', old format uses 'suspicious_files')
        $files = $reportData['files'] ?? $reportData['suspicious_files'] ?? [];

        if (empty($files)) {
            return 'SAFE';
        }

        $riskLevels   = ['SAFE', 'LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];
        $maxRiskIndex = 0;

        foreach ($files as $filePath => $fileData) {
            // Handle both array formats (with numeric keys and string keys)
            $riskLevel = is_array($fileData) ? ($fileData['risk_level'] ?? 'SAFE') : 'SAFE';
            $riskIndex = array_search($riskLevel, $riskLevels);

            if ($riskIndex !== false && $riskIndex > $maxRiskIndex) {
                $maxRiskIndex = $riskIndex;
            }
        }

        return $riskLevels[$maxRiskIndex];
    }
}
