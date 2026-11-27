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

namespace App\Repositories;

use App\Models\LogSurat;

class LogSuratRepository
{
    /**
     * Ambil SATU surat untuk keperluan verifikasi publik.
     *
     * Endpoint verifikasi surat WAJIB menyertakan id surat spesifik. Sebelumnya method
     * ini (list()) mengembalikan seluruh arsip surat desa (paginasi) lengkap dengan
     * nama warga dan link unduh PDF berisi PII (NIK, alamat, keperluan surat) ketika
     * dipanggil tanpa filter id. Sekarang: id wajib, hasil maksimal 1 record, dan hanya
     * surat yang sudah terbit/dicetak (bukan konsep/ditolak). Tanpa paginasi karena
     * datanya otomatis paling banyak 1. Lihat issue #7002.
     *
     * @param int|string|null $id
     */
    public function detail($id)
    {
        return LogSurat::withOnly(['formatSurat'])
            ->where('status', LogSurat::CETAK)
            ->where('id', is_numeric($id) ? (int) $id : 0)
            ->get();
    }
}
