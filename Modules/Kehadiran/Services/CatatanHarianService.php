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

namespace Modules\Kehadiran\Services;

use Illuminate\Support\Facades\DB;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Models\CatatanHarianFoto;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Service class untuk handle business logic Catatan Harian
 *
 * Tanggung jawab:
 * - Create catatan dengan foto
 * - Update catatan dengan foto
 * - Delete catatan dan relasi-relasinya
 * - Photo upload dan resize logic
 */
class CatatanHarianService
{
    /**
     * Batch insert photos (performance optimized with observers)
     */
    private static function insertPhotos(string $catatanId, array $uploadedFotos): void
    {
        foreach ($uploadedFotos as $foto) {
            CatatanHarianFoto::create([
                'catatan_harian_id' => $catatanId,
                'file_path'         => $foto['path'],
                'keterangan'        => $foto['keterangan'] ?? null,
            ]);
        }
    }

    /**
     * Delete single photo file from filesystem
     */
    private static function deletePhotoFile(string $filePath): bool
    {
        $fullPath = FCPATH . $filePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return true;
    }

    /**
     * Create new catatan with photos
     */
    public function createCatatan(array $data, array $uploadedFotos = []): CatatanHarian
    {
        return DB::transaction(static function () use ($data, $uploadedFotos) {
            $catatan = CatatanHarian::create($data);

            if (! empty($uploadedFotos)) {
                static::insertPhotos($catatan->uuid, $uploadedFotos);
            }

            return $catatan;
        });
    }

    /**
     * Update catatan with new photos
     */
    public function updateCatatan(CatatanHarian $catatan, array $data, array $uploadedFotos = [], array $fotosToDelete = []): CatatanHarian
    {
        return DB::transaction(static function () use ($catatan, $data, $uploadedFotos, $fotosToDelete) {
            $catatan->update($data);

            if (! empty($fotosToDelete)) {
                $fotoModels = CatatanHarianFoto::whereIn('uuid', $fotosToDelete)->get();

                foreach ($fotoModels as $foto) {
                    static::deletePhotoFile($foto->file_path);
                    $foto->delete();
                }
            }

            if (! empty($uploadedFotos)) {
                static::insertPhotos($catatan->uuid, $uploadedFotos);
            }

            return $catatan;
        });
    }

    /**
     * Delete catatan and all related data
     */
    public function deleteCatatan(CatatanHarian $catatan): bool
    {
        return DB::transaction(static function () use ($catatan) {
            // Delete photos (cascade)
            if ($catatan->fotos) {
                foreach ($catatan->fotos as $foto) {
                    static::deletePhotoFile($foto->file_path);
                }
                $catatan->fotos()->delete();
            }

            return $catatan->delete();
        });
    }
}
