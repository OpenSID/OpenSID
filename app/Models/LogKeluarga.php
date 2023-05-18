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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

defined('BASEPATH') || exit('No direct script access allowed');

class LogKeluarga extends BaseModel
{
    /**
     * KETERANGAN id_peristiwa di log_keluarga
     * 1 - keluarga baru
     * 2 - kepala keluarga status dasar 'mati'
     * 3 - kepala keluarga status dasar 'pindah'
     * 4 - kepala keluarga status dasar 'hilang'
     * 5 - keluarga baru datang
     * 6 - kepala keluarga status dasar 'pergi' (seharusnya tidak ada)
     * 11- kepala keluarga status dasar 'tidak valid' (seharusnya tidak ada)
     * 12- anggota keluarga keluar atau pecah dari keluarga
     * 13 - keluarga dihapus
     * 14 - kepala keluarga status dasar kembali 'hidup' (salah mengisi di log_penduduk)
     */
    public const KELUARGA_BARU = 1;

    public const KEPALA_KELUARGA_MATI          = 2;
    public const KEPALA_KELUARGA_PINDAH        = 3;
    public const KEPALA_KELUARGA_HILANG        = 4;
    public const KELUARGA_BARU_DATANG          = 5;
    public const KEPALA_KELUARGA_PERGI         = 6;
    public const KEPALA_KELUARGA_TIDAK_VALID   = 11;
    public const ANGGOTA_KELUARGA_PECAH        = 12;
    public const KELUARGA_HAPUS                = 13;
    public const KEPALA_KELUARGA_KEMBALI_HIDUP = 14;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_keluarga';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];
}
