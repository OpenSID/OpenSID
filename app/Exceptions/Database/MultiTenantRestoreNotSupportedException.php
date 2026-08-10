<?php

declare(strict_types=1);

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

namespace App\Exceptions\Database;

use RuntimeException;

/**
 * Dilempar saat restore whole-database (format .sql, {@see \App\Libraries\Ekspor::restore()})
 * dijalankan pada database multi-desa (Database Gabungan).
 *
 * Ekspor::restore() men-DROP TABLE seluruh tabel sebelum re-import — pada Database
 * Gabungan ini menghapus data semua desa lain yang berbagi database tersebut, bukan
 * cuma desa yang me-restore. Guard ini ada di dalam Ekspor::restore() sebagai jaring
 * pengaman terakhir (berlaku untuk semua titik masuk, bukan cuma controller web).
 * Restore per-desa yang aman ada di {@see \MultiDB::restore()} (format .sid).
 */
class MultiTenantRestoreNotSupportedException extends RuntimeException
{
    public const PESAN = 'Restore lewat menu ini tidak tersedia untuk Database Gabungan. Gunakan menu Pulihkan Multi-Desa.';

    public function __construct(string $message = self::PESAN)
    {
        parent::__construct($message);
    }
}
