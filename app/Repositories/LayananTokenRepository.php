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

use App\Services\Layanan\Exceptions\TokenTidakValidException;
use App\Services\Layanan\TokenDecoder;

/**
 * Satu-satunya penulis yang disahkan untuk setting `layanan_opendesa_token`.
 *
 * Semua penulisan token langganan WAJIB melalui method simpan() di kelas ini,
 * yang memverifikasi token via TokenDecoder sebelum menyimpan ke database.
 * Penulisan langsung ke setting `layanan_opendesa_token` di luar kelas ini
 * dilarang oleh LayananTokenWriteRule (mencegah regresi CWE-345/CWE-347, #6663).
 */
class LayananTokenRepository
{
    /**
     * Simpan token langganan setelah memverifikasi validitasnya via TokenDecoder.
     *
     * @param  string  $token  Token JWT mentah dari server layanan.
     * @return bool True jika token berhasil disimpan, false jika gagal.
     *
     * @throws TokenTidakValidException Jika token tidak lolos verifikasi.
     */
    public function simpan(string $token): bool
    {
        // Verifikasi token sebelum menyimpan — mencegah token palsu/rusak
        // ditulis ke setting (CWE-345/CWE-347).
        app(TokenDecoder::class)->decode($token);

        return (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);
    }

    /**
     * Simpan token kredensial/pendaftaran setelah memverifikasi struktur JWT.
     *
     * Berbeda dengan simpan(), method ini tidak memvalidasi claim tanggal_berlangganan
     * karena token hasil pendaftaran kerjasama dari server layanan belum memiliki claim tersebut.
     *
     * @param  string  $token  Token JWT mentah dari server layanan.
     * @return bool True jika token berhasil disimpan, false jika gagal.
     *
     * @throws TokenTidakValidException Jika token tidak lolos verifikasi struktur.
     */
    public function simpanKredensial(string $token): bool
    {
        app(TokenDecoder::class)->decodeStruktur($token);

        return (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);
    }
}
