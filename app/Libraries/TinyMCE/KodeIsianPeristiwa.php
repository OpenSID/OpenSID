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

namespace App\Libraries\TinyMCE;

use App\Models\LogPenduduk;

class KodeIsianPeristiwa
{
    private $logPeristiwa;
    private $statusDasar;

    public function __construct($idPenduduk, $statusDasar)
    {
        $this->statusDasar  = $statusDasar;
        $this->logPeristiwa = LogPenduduk::where('id_pend', $idPenduduk)->latest()->first();
    }

    public static function get($idPenduduk, $statusDasar)
    {
        return (new self($idPenduduk, $statusDasar))->kodeIsian();
    }

    public function kodeIsian()
    {
        switch ($this->statusDasar) {
            case LogPenduduk::BARU_LAHIR:
                $data = $this->getLahir($this->logPeristiwa);
                break;

            case LogPenduduk::MATI:
                $data = $this->getKematian($this->logPeristiwa);
                break;

            case LogPenduduk::PINDAH_KELUAR:
                $data = $this->getPindah($this->logPeristiwa);
                break;

            case LogPenduduk::HILANG:
                $data = $this->getHilang($this->logPeristiwa);
                break;

            default:
                $data = [];
        }

        $lainnya = $this->getLainnya($this->logPeristiwa);

        return array_merge($data, $lainnya);
    }

    private function getLahir($peristiwa)
    {
        return [
            [
                'judul' => 'Hari Kelahiran',
                'isian' => 'Hari_kelahiranN',
                'data'  => hari($peristiwa->penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tanggal Kelahiran',
                'isian' => 'Tanggal_kelahiranN',
                'data'  => formatTanggal($peristiwa->penduduk->tanggallahir),
            ],
            [
                'judul' => 'Jam Kelahiran',
                'isian' => 'Jam_kelahiranN',
                'data'  => $peristiwa->penduduk->waktu_lahir,
            ],
            [
                'judul' => 'Tempat Dilahirkan',
                'isian' => 'Tempat_dilahirkanN',
                'data'  => $peristiwa->penduduk->tempatlahir,
            ],
            [
                'judul' => 'Tempat Kelahiran',
                'isian' => 'Tempat_kelahiranN',
                'data'  => $peristiwa->penduduk->tempatlahir,
            ],
            [
                'judul' => 'Jenis Kelahiran',
                'isian' => 'Jenis_kelahiranN',
                'data'  => $peristiwa->penduduk->jenisLahir,
            ],
            [
                'judul' => 'Kelahiran Anak Ke',
                'isian' => 'Kelahiran_anaK',
                'data'  => $peristiwa->penduduk->kelahiran_anak_ke,
            ],
            [
                'judul' => 'Penolong Kelahiran',
                'isian' => 'Penolong_kelahiranN',
                'data'  => $peristiwa->penduduk->penolongLahir,
            ],
            [
                'judul' => 'Berat Bayi',
                'isian' => 'Berat_bayI',
                'data'  => $peristiwa->penduduk->berat_lahir,
            ],
            [
                'judul' => 'Panjang Bayi',
                'isian' => 'Panjang_bayI',
                'data'  => $peristiwa->penduduk->panjang_lahir,
            ],
        ];
    }

    private function getKematian($peristiwa)
    {
        return [
            [
                'judul' => 'Hari Kematian',
                'isian' => 'Hari_kematiaN',
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Kematian',
                'isian' => 'Tanggal_kematiaN',
                'data'  => formatTanggal($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Jam Kematian',
                'isian' => 'Jam_kematiaN',
                'data'  => $peristiwa->jam_mati,
            ],
            [
                'judul' => 'Tempat Kematian',
                'isian' => 'Tempat_kematiaN',
                'data'  => $peristiwa->meninggal_di,
            ],
            [
                'judul' => 'Penyebab Kematian',
                'isian' => 'Penyebab_kematiaN',
                'data'  => $peristiwa->penyebab_kematian,
            ],
            [
                'judul' => 'Penolong Kematian',
                'isian' => 'Penolong_kematiaN',
                'data'  => $peristiwa->yang_menerangkan,
            ],
        ];
    }

    private function getPindah($peristiwa)
    {
        return [
            [
                'judul' => 'Hari Pindah',
                'isian' => 'Hari_pindaH',
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Pindah',
                'isian' => 'Tanggal_pindaH',
                'data'  => formatTanggal($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Alamat Tujuan',
                'isian' => 'Alamat_tujuaN',
                'data'  => $peristiwa->alamat_tujuan,
            ],
        ];
    }

    private function getHilang($peristiwa)
    {
        return [
            [
                'judul' => 'Hari Hilang',
                'isian' => 'Hari_hilanG',
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Hilang',
                'isian' => 'Tanggal_hilanG',
                'data'  => formatTanggal($peristiwa->tgl_peristiwa),
            ],
        ];
    }

    private function getLainnya($peristiwa)
    {
        return [
            [
                'judul' => 'Tanggal Lapor',
                'isian' => 'Tanggal_lapoR',
                'data'  => formatTanggal($peristiwa->tgl_lapor),
            ],
            [
                'judul' => 'Catatan',
                'isian' => 'CatataN',
                'data'  => $peristiwa->catatan,
            ],
        ];
    }
}
