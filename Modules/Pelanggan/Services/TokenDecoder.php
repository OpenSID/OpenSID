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

namespace Modules\Pelanggan\Services;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Modules\Pelanggan\Services\Exceptions\TokenTidakValidException;
use Throwable;

/**
 * Decoder tunggal & terverifikasi untuk token berlangganan OpenDesa.
 *
 * Menggantikan salinan decodeTokenPayload() yang tersebar di
 * Modules/Pelanggan/Http/Controllers/TokenController.php dan
 * Modules/Pelanggan/Services/CekService.php (yang juga dipakai oleh
 * PelangganService). Semua pemanggil wajib lewat service ini:
 * app(TokenDecoder::class)->decode($token).
 */
class TokenDecoder
{
    /**
     * Pola satu segmen JWT yang sah: base64 / base64url saja.
     * Menolak karakter konteks-JS ($ { } ` ( ) spasi, dsb).
     */
    private const POLA_SEGMEN = '/^[A-Za-z0-9+\/_-]+={0,2}$/';

    /**
     * Dekode + validasi token; kembalikan payload (stdClass) bila valid.
     *
     * @throws TokenTidakValidException
     */
    public function decode(string $token): object
    {
        $segmen = $this->pisahkanSegmen($token);

        // Verifikasi kriptografis bila kunci tersedia (target state);
        // jika belum, jatuh ke validasi struktural ketat (interim), karena
        // server layanan saat ini belum menandatangani token (alg=none).
        $payload = $this->kunciTersedia()
            ? $this->decodeTerverifikasi($token)
            : $this->decodeInterim($segmen);

        return $this->validasiPayload($payload);
    }

    /**
     * @return list<string>
     *
     * @throws TokenTidakValidException
     */
    private function pisahkanSegmen(string $token): array
    {
        $segmen = explode('.', $token);

        if (count($segmen) !== 3) {
            throw new TokenTidakValidException('Jumlah segmen token salah.');
        }

        foreach ($segmen as $bagian) {
            // Tolak segmen kosong & karakter non-base64 (payload XSS ${...} gugur di sini).
            if ($bagian === '' || preg_match(self::POLA_SEGMEN, $bagian) !== 1) {
                throw new TokenTidakValidException('Segmen token mengandung karakter tidak sah.');
            }
        }

        return $segmen;
    }

    private function kunciTersedia(): bool
    {
        return ! empty(config('layanan.jwt_public_key')) && ! empty(config('layanan.jwt_algo'));
    }

    /**
     * Verifikasi kriptografis penuh. alg=none ditolak secara inheren karena
     * algoritma dibatasi lewat objek Key (tidak pernah 'none').
     *
     * @throws TokenTidakValidException
     */
    private function decodeTerverifikasi(string $token): object
    {
        try {
            return JWT::decode(
                $token,
                new Key(config('layanan.jwt_public_key'), config('layanan.jwt_algo'))
            );
        } catch (Throwable $e) {
            throw new TokenTidakValidException('Signature token tidak valid.', 0, $e);
        }
    }

    /**
     * Mode interim (sebelum server layanan menandatangani token): tidak ada
     * jaminan kriptografis, hanya validasi struktur base64 ketat (sudah
     * dilakukan di pisahkanSegmen) + penolakan alg=none + dekode payload.
     *
     * @param list<string> $segmen
     *
     * @throws TokenTidakValidException
     */
    private function decodeInterim(array $segmen): object
    {
        if (config('layanan.tolak_alg_none', true)) {
            $header = json_decode((string) base64_decode($segmen[0], true));

            if (! is_object($header) || strtolower((string) ($header->alg ?? '')) === 'none') {
                throw new TokenTidakValidException('Token alg=none atau header tidak valid.');
            }
        }

        $payload = json_decode((string) base64_decode($segmen[1], true));

        if (! is_object($payload)) {
            throw new TokenTidakValidException('Payload token bukan JSON objek.');
        }

        return $payload;
    }

    /**
     * Validasi bentuk & tipe payload. Menolak field bertipe non-skalar
     * sebagai lapisan pertahanan tambahan bila payload dirender ke view.
     *
     * @throws TokenTidakValidException
     */
    private function validasiPayload(object $payload): object
    {
        $akhir = data_get($payload, 'tanggal_berlangganan.akhir');

        if (! is_string($akhir) || $this->parseTanggal($akhir) === null) {
            throw new TokenTidakValidException('Field tanggal_berlangganan.akhir tidak valid.');
        }

        foreach (['desa_id', 'domain', 'domain_alternatif'] as $bidang) {
            if (isset($payload->{$bidang}) && ! is_scalar($payload->{$bidang})) {
                throw new TokenTidakValidException("Field {$bidang} bertipe tidak valid.");
            }
        }

        return $payload;
    }

    private function parseTanggal(string $nilai): ?DateTimeImmutable
    {
        $tanggal = DateTimeImmutable::createFromFormat('Y-m-d', $nilai);

        return ($tanggal && $tanggal->format('Y-m-d') === $nilai) ? $tanggal : null;
    }
}
