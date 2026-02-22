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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Http\Transformers;

use App\Models\KelompokAnggota;
use League\Fractal\TransformerAbstract;

class KelompokAnggotaTransformer extends TransformerAbstract
{
    public function transform(KelompokAnggota $kelompok)
    {
        $data = $kelompok->toArray();

        $data['sumber_anggota']      = $kelompok->sumber_anggota ?? 'penduduk';
        $data['nama_tampil']         = $kelompok->nama_tampil;
        $data['nik_tampil']          = $kelompok->nik_tampil;
        $data['sex_tampil']          = $kelompok->sex_tampil;
        $data['alamat_tampil']       = $kelompok->alamat_tampil;
        $data['tempatlahir_tampil']  = $kelompok->tempatlahir_tampil;
        $data['tanggallahir_tampil'] = $kelompok->tanggallahir_tampil;

        // Backward compatible fields used by tema/API lama.
        $data['nama']        = $kelompok->nama_tampil;
        $data['nik']         = $kelompok->nik_tampil;
        $data['id_sex']      = $kelompok->id_sex_tampil;
        $data['sex']         = strtoupper((string) $kelompok->sex_tampil);
        $data['alamat']      = $kelompok->alamat_tampil;
        $data['tempatlahir'] = $kelompok->tempatlahir_tampil;
        $data['tanggallahir'] = $kelompok->tanggallahir_tampil;
        $data['nama_penduduk'] = $kelompok->nama_tampil;

        $anggotaData = $data['anggota'] ?? [];
        if (! is_array($anggotaData)) {
            $anggotaData = [];
        }

        $data['anggota'] = array_replace([
            'nama'         => $kelompok->nama_tampil,
            'nik'          => $kelompok->nik_tampil,
            'sex'          => $kelompok->id_sex_tampil,
            'tempatlahir'  => $kelompok->tempatlahir_tampil,
            'tanggallahir' => $kelompok->tanggallahir_tampil,
            'alamat_wilayah' => $kelompok->alamat_tampil,
        ], $anggotaData);

        return $data;
    }
}
