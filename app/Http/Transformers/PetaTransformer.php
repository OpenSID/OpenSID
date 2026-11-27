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

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class PetaTransformer extends TransformerAbstract
{
    /**
     * Kolom identitas desa yang boleh tampil di endpoint peta publik.
     */
    private const DESA_FIELDS = ['nama_desa', 'kode_desa', 'path', 'lat', 'lng', 'zoom'];

    /**
     * Whitelist kunci payload peta publik. Jangan pernah `return $peta->toArray()`
     * mentah di sini: payload bisa memuat model ber-relasi `tweb_penduduk`
     * sehingga berisiko membocorkan data pribadi warga (NIK, TTL, nama ortu,
     * telepon, koordinat rumah, dst) tanpa autentikasi.
     */
    private const ALLOWED_KEYS = [
        'id', 'desa', 'wilayah', 'dusun_gis', 'rw_gis', 'rt_gis',
        'lokasi', 'garis', 'area', 'lokasi_pembangunan', 'persil',
        'list_ref', 'list_bantuan', 'list_dusun', 'title',
        'pengaturan', 'tampilkan_cdesa', 'covid',
    ];

    public function transform($peta): array
    {
        $data = collect($peta)->only(self::ALLOWED_KEYS)->toArray();

        if (isset($data['desa'])) {
            $data['desa'] = collect($data['desa'])->only(self::DESA_FIELDS)->toArray();
        }

        return $data;
    }
}
