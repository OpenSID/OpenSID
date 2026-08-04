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

namespace App\Services\Module;

/**
 * Sumber berkas add-on: dari mana ZIP paket diperoleh sebelum dipasang.
 *
 * Port ini memisahkan {@see ModuleManager} dari _cara_ paket diambil. Adapter
 * default {@see LayananHttpSource} mengunduhnya dari server Layanan (klien
 * Layanan — komponen terbuka, bukan add-on berbayar); adapter pengembangan
 * dapat di-bind oleh modul dev sehingga instalasi bisa diuji tanpa Layanan.
 *
 * Core tidak menyimpan pengetahuan modul tertentu maupun endpoint Layanan pada
 * waktu-kompilasi: yang dikirim ke rilis hanyalah antarmuka generik ini plus
 * klien Layanan terbuka. Setiap adapter WAJIB menegakkan kepercayaan-asal-nya
 * sendiri (mis. Layanan memvalidasi HTTPS + host).
 */
interface ModuleSource
{
    /**
     * Sediakan berkas ZIP add-on `$name` dari sumber ini.
     *
     * @param string $name    Nama modul (mis. `Anjungan`).
     * @param string $locator Petunjuk lokasi khusus-sumber (mis. URL unduh
     *                        Layanan). Boleh diabaikan sumber yang meresolusi
     *                        sendiri (mis. dari repo lokal).
     *
     * @return string Path absolut ke berkas ZIP lokal siap-ekstrak.
     *
     * @throws \RuntimeException bila paket tak bisa diperoleh / asal tak tepercaya.
     */
    public function fetch(string $name, string $locator = ''): string;
}
