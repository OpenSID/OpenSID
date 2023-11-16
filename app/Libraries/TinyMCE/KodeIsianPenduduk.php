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

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusHubunganEnum;
use App\Models\Penduduk;

class KodeIsianPenduduk
{
    private $idPenduduk;

    public function __construct($idPenduduk = null, $prefix = '', $prefixJudul = false)
    {
        $this->idPenduduk  = $idPenduduk;
        $this->prefix      = $prefix;
        $this->prefixJudul = $prefixJudul;
    }

    public static function get($idPenduduk = null, $prefix = '', $prefixJudul = false)
    {
        return (new self($idPenduduk, $prefix, $prefixJudul))->kodeIsian();
    }

    public function kodeIsian()
    {
        $ortu     = null;
        $penduduk = null;

        // Data Umum
        if (! empty($this->prefix)) {
            $ortu   = ' ' . ucwords($this->prefix);
            $prefix = '_' . uclast($this->prefix);
        }

        if (! $this->prefixJudul) {
            $ortu = '';
        }

        if ($this->idPenduduk) {
            $penduduk = Penduduk::with(['keluarga', 'rtm'])->find($this->idPenduduk);
        }

        $individu = [
            [
                'case_sentence' => true,
                'judul'         => 'NIK' . $ortu,
                'isian'         => 'nik' . $prefix,
                'data'          => get_nik($penduduk->nik),
            ],
            [
                'judul' => 'Nama' . $ortu,
                'isian' => 'Nama' . $prefix,
                'data'  => $penduduk->nama,
            ],
            [
                'judul' => 'Tanggal Lahir' . $ortu,
                'isian' => 'Tanggallahir' . $prefix,
                'data'  => formatTanggal($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Lahir' . $ortu,
                'isian' => 'Tempatlahir' . $prefix,
                'data'  => $penduduk->tempatlahir,
            ],
            [
                'judul' => 'Tempat Tanggal Lahir' . $ortu,
                'isian' => 'Tempat_tgl_lahir' . $prefix,
                'data'  => $penduduk->tempatlahir . '/' . formatTanggal($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Tanggal Lahir (TTL)' . $ortu,
                'isian' => 'Ttl' . $prefix,
                'data'  => $penduduk->tempatlahir . '/' . formatTanggal($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Usia' . $ortu,
                'isian' => 'Usia' . $prefix,
                'data'  => $penduduk->usia,
            ],
            [
                'judul' => 'Jenis Kelamin' . $ortu,
                'isian' => 'Jenis_kelamin' . $prefix,
                'data'  => $penduduk->jenisKelamin->nama,
            ],
            [
                'judul' => 'Agama' . $ortu,
                'isian' => 'Agama' . $prefix,
                'data'  => $penduduk->agama->nama,
            ],
            [
                'judul' => 'Pekerjaan' . $ortu,
                'isian' => 'Pekerjaan' . $prefix,
                'data'  => $penduduk->pekerjaan->nama,
            ],
            [
                'judul' => 'Warga Negara' . $ortu,
                'isian' => 'Warga_negara' . $prefix,
                'data'  => $penduduk->wargaNegara->nama,
            ],
            [
                'judul' => 'Alamat' . $ortu,
                'isian' => 'Alamat' . $prefix,
                'data'  => $penduduk->alamat_wilayah,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'No KK' . $ortu,
                'isian'         => 'No_kK' . $prefix,
                'data'          => get_nokk($penduduk->keluarga->no_kk),
            ],
            [
                'judul' => 'Golongan Darah' . $ortu,
                'isian' => 'Gol_daraH' . $prefix,
                'data'  => $penduduk->golonganDarah->nama,
            ],
            [
                'judul' => 'Pendidikan Sedang' . $ortu,
                'isian' => 'Pendidikan_sedanG' . $prefix,
                'data'  => $penduduk->pendidikan->nama,
            ],
            [
                'judul' => 'Pendidikan Dalam KK' . $ortu,
                'isian' => 'Pendidikan_kK' . $prefix,
                'data'  => $penduduk->pendidikanKK->nama,
            ],
        ];

        if (empty($this->prefix)) {
            $lainnya = [
                [
                    'case_sentence' => true,
                    'judul'         => 'Foto',
                    'isian'         => 'foto_penduduK',
                    'data'          => '[foto_penduduk]',
                ],
                [
                    'judul' => 'Alamat Jalan',
                    'isian' => 'Alamat_jalan',
                    'data'  => $penduduk->keluarga->alamat, // alamat kk jika ada
                ],
                [
                    'judul' => 'Alamat Sebelumnya',
                    'isian' => 'Alamat_sebelumnya',
                    'data'  => $penduduk->alamat_sebelumnya,
                ],
                [
                    'judul' => 'Dusun',
                    'isian' => 'Nama_dusuN',
                    'data'  => $penduduk->wilayah->dusun,
                ],
                [
                    'judul' => 'RW',
                    'isian' => 'Nama_rW',
                    'data'  => $penduduk->wilayah->rw,
                ],
                [
                    'judul' => 'RT',
                    'isian' => 'Nama_rT',
                    'data'  => $penduduk->wilayah->rt,
                ],
                [
                    'judul' => 'Akta Kelahiran',
                    'isian' => 'Akta_lahiR',
                    'data'  => $penduduk->akta_lahir, // Cek ini
                ],
                [
                    'judul' => 'Akta Perceraian',
                    'isian' => 'Akta_perceraiaN',
                    'data'  => $penduduk->akta_perceraian, // Cek ini
                ],
                [
                    'judul' => 'Status Perkawinan',
                    'isian' => 'Status_kawiN',
                    'data'  => $penduduk->statusKawin->nama, // Cek ini
                ],
                [
                    'judul' => 'Akta Perkawinan',
                    'isian' => 'Akta_perkawinaN',
                    'data'  => $penduduk->akta_perkawinan, // Cek ini
                ],
                [
                    'judul' => 'Tanggal Perkawinan',
                    'isian' => 'TanggalperkawinaN',
                    'data'  => formatTanggal($penduduk->tanggalperkawinan),
                ],
                [
                    'judul' => 'Tanggal Perceraian',
                    'isian' => 'TanggalperceraiaN',
                    'data'  => formatTanggal($penduduk->tanggalperceraian),
                ],
                [
                    'judul' => 'Cacat',
                    'isian' => 'CacaT',
                    'data'  => $penduduk->cacat->nama,
                ],
                [
                    'judul' => 'Dokumen Pasport',
                    'isian' => 'Dokumen_pasporT',
                    'data'  => $penduduk->dokumen_pasport,
                ],
                [
                    'judul' => 'Tanggal Akhir Paspor',
                    'isian' => 'Tanggal_akhir_paspoR',
                    'data'  => formatTanggal($penduduk->tanggal_akhir_paspor),
                ],

                // Data KK
                [
                    'judul' => 'Hubungan Dalam KK',
                    'isian' => 'Hubungan_kK',
                    'data'  => $penduduk->pendudukHubungan->nama,
                ],
                [
                    'case_sentence' => true,
                    'judul'         => 'No KK',
                    'isian'         => 'No_kK',
                    'data'          => get_nokk($penduduk->keluarga->no_kk),
                ],
                [
                    'judul' => 'Kepala KK',
                    'isian' => 'Kepala_kK',
                    'data'  => $penduduk->keluarga->kepalaKeluarga->nama,
                ],
                [
                    'case_sentence' => true,
                    'judul'         => 'NIK KK',
                    'isian'         => 'Nik_kepala_kK',
                    'data'          => get_nik($penduduk->keluarga->kepalaKeluarga->nik),
                ],

                // Data RTM
                [
                    'case_sentence' => true,
                    'judul'         => 'ID BDT',
                    'isian'         => 'Id_bdT',
                    'data'          => $penduduk->rtm->bdt,
                ],
            ];

            // Data Umum
            $data = array_merge($individu, $lainnya);

            // Data Orang Tua
            $id_ayah = Penduduk::where('nik', $penduduk->ayah_nik)->first()->id;
            $id_ibu  = Penduduk::where('nik', $penduduk->ibu_nik)->first()->id;

            if (! $id_ayah && $penduduk->kk_level == StatusHubunganEnum::ANAK) {
                $id_ayah = Penduduk::where('id_kk', $penduduk->id_kk)
                    ->where(static function ($query) {
                        $query->where('kk_level', StatusHubunganEnum::KEPALA_KELUARGA)
                            ->orWhere('kk_level', StatusHubunganEnum::SUAMI);
                    })
                    ->where('sex', JenisKelaminEnum::LAKI_LAKI)
                    ->first()->id;
            }

            if (! $id_ibu && $penduduk->kk_level == StatusHubunganEnum::ANAK) {
                $id_ibu = Penduduk::where('id_kk', $penduduk->id_kk)
                    ->where(static function ($query) {
                        $query->where('kk_level', StatusHubunganEnum::KEPALA_KELUARGA)
                            ->orWhere('kk_level', StatusHubunganEnum::ISTRI);
                    })
                    ->where('sex', JenisKelaminEnum::PEREMPUAN)
                    ->first()->id;
            }

            // Data Ayah
            $data = array_merge($data, self::get($id_ayah, 'ayah', true));

            if (! $id_ayah && ! empty($penduduk)) {
                $data_ortu = [
                    [
                        'judul' => 'Nama Ayah',
                        'isian' => 'Nama_ayaH',
                        'data'  => $penduduk->nama_ayah,
                    ],
                    [
                        'case_sentence' => true,
                        'judul'         => 'NIK Ayah',
                        'isian'         => 'nik_ayah',
                        'data'          => get_nik($penduduk->ayah_nik),
                    ],
                ];
                $data = array_merge($data, $data_ortu);
            }

            // Data Ibu
            $data = array_merge($data, self::get($id_ibu, 'ibu', true));

            if (! $id_ibu && ! empty($penduduk)) {
                $data_ortu = [
                    [
                        'judul' => 'Nama Ibu',
                        'isian' => 'Nama_ibU',
                        'data'  => $penduduk->nama_ibu,
                    ],
                    [
                        'case_sentence' => true,
                        'judul'         => 'NIK Ibu',
                        'isian'         => 'nik_ibu',
                        'data'          => get_nik($penduduk->ibu_nik),
                    ],
                ];
                $data = array_merge($data, $data_ortu);
            }

            return $data;
        }

        return $individu;
    }
}
