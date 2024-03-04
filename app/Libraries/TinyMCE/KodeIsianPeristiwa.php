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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\TinyMCE;

use App\Models\LogPenduduk;

class KodeIsianPeristiwa
{
    private $logPeristiwa;
    private array $statusDasar;

    public function __construct($idPenduduk, array $statusDasar = [])
    {
        $this->statusDasar  = $statusDasar;
        $this->logPeristiwa = LogPenduduk::where('id_pend', $idPenduduk)->latest()->first();
    }

    public static function get($idPenduduk, $statusDasar): array
    {
        return (new self($idPenduduk, $statusDasar))->kodeIsian();
    }

    public function kodeIsian(): array
    {
        switch ($this->statusDasar) {
            case [LogPenduduk::BARU_LAHIR]:
                $data = $this->getLahir($this->logPeristiwa);
                break;

            case [LogPenduduk::MATI]:
                $data = $this->getKematian($this->logPeristiwa);
                break;

            case [LogPenduduk::PINDAH_KELUAR]:
                $data = $this->getPindah($this->logPeristiwa);
                break;

            case [LogPenduduk::HILANG]:
                $data = $this->getHilang($this->logPeristiwa);
                break;

            default:
                $data = [];
        }

        $lainnya = $this->getLainnya($this->logPeristiwa);

        return array_merge($data, $lainnya);
    }

    private function getLahir($peristiwa): array
    {
        return [
            [
                'judul' => 'Hari Kelahiran',
                'isian' => 'hari_kelahiran',
                'data'  => hari($peristiwa->penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tanggal Kelahiran',
                'isian' => 'tanggal_kelahiran',
                'data'  => formatTanggal($peristiwa->penduduk->tanggallahir),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Jam Kelahiran',
                'isian'         => 'jam_kelahiran',
                'data'          => $peristiwa->penduduk->waktu_lahir,
            ],
            [
                'judul' => 'Tempat Dilahirkan',
                'isian' => 'tempat_dilahirkanN',
                'data'  => $peristiwa->penduduk->tempatlahir,
            ],
            [
                'judul' => 'Tempat Kelahiran',
                'isian' => 'tempat_kelahiran',
                'data'  => $peristiwa->penduduk->tempatlahir,
            ],
            [
                'judul' => 'Jenis Kelahiran',
                'isian' => 'jenis_kelahiran',
                'data'  => $peristiwa->penduduk->jenisLahir,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kelahiran Anak Ke',
                'isian'         => 'kelahiran_anaK',
                'data'          => $peristiwa->penduduk->kelahiran_anak_ke,
            ],
            [
                'judul' => 'Penolong Kelahiran',
                'isian' => 'penolong_kelahiran',
                'data'  => $peristiwa->penduduk->penolongLahir,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Berat Bayi',
                'isian'         => 'berat_bayI',
                'data'          => $peristiwa->penduduk->berat_lahir,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Panjang Bayi',
                'isian'         => 'panjang_bayI',
                'data'          => $peristiwa->penduduk->panjang_lahir,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Jumlah Saudara',
                'isian'         => 'jumlah_saudara_kelahiran',
                'data'          => $peristiwa->penduduk->jml_anak,
            ],
        ];
    }

    private function getKematian($peristiwa): array
    {
        return [
            [
                'judul' => 'Hari Kematian',
                'isian' => 'hari_kematian',
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Kematian',
                'isian' => 'tanggal_kematian',
                'data'  => formatTanggal($peristiwa->tgl_peristiwa),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Jam Kematian',
                'isian'         => 'jam_kematian',
                'data'          => $peristiwa->jam_mati,
            ],
            [
                'judul' => 'Tempat Kematian',
                'isian' => 'tempat_kematian',
                'data'  => $peristiwa->meninggal_di,
            ],
            [
                'judul' => 'Penyebab Kematian',
                'isian' => 'penyebab_kematian',
                'data'  => $peristiwa->penyebab_kematian,
            ],
            [
                'judul' => 'Penolong Kematian',
                'isian' => 'penolong_kematian',
                'data'  => $peristiwa->yang_menerangkan,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Anak Ke',
                'isian'         => 'anakke_kematian',
                'data'          => $peristiwa->penduduk->kelahiran_anak_ke,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Jumlah Saudara',
                'isian'         => 'jumlah_saudara_kematian',
                'data'          => $peristiwa->penduduk->jml_anak,
            ],
            [
                'judul' => 'Bukti Kematian',
                'isian' => 'bukti_kematian',
                'data'  => $peristiwa->akta_mati,
            ],
        ];
    }

    private function getPindah($peristiwa): array
    {
        return [
            [
                'judul' => 'Hari Pindah',
                'isian' => 'hari_pindah',
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Pindah',
                'isian' => 'tanggal_pindah',
                'data'  => formatTanggal($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Alamat Tujuan',
                'isian' => 'alamat_tujuaN',
                'data'  => $peristiwa->alamat_tujuan,
            ],
        ];
    }

    private function getHilang($peristiwa): array
    {
        return [
            [
                'judul' => 'Hari Hilang',
                'isian' => 'hari_hilang',
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Hilang',
                'isian' => 'tanggal_hilang',
                'data'  => formatTanggal($peristiwa->tgl_peristiwa),
            ],
        ];
    }

    private function getLainnya($peristiwa): array
    {
        return [
            [
                'judul' => 'Tanggal Lapor',
                'isian' => 'tanggal_lapor',
                'data'  => formatTanggal($peristiwa->tgl_lapor),
            ],
            [
                'judul' => 'Catatan',
                'isian' => 'catatan',
                'data'  => $peristiwa->catatan,
            ],
        ];
    }
}
