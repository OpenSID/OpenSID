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

namespace App\Libraries\TinyMCE;

use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Models\Penduduk;
use Illuminate\Support\Str;

class KodeIsianPenduduk
{
    public function __construct(private $idPenduduk = null, private $prefix = '', private $prefixJudul = false)
    {
    }

    public static function get($idPenduduk = null, $prefix = '', $prefixJudul = false): array
    {
        return (new self($idPenduduk, $prefix, $prefixJudul))->kodeIsian();
    }

    public function kodeIsian(): array
    {
        $config   = identitas();
        $ortu     = null;
        $penduduk = null;

        // Data Umum
        if (! empty($this->prefix)) {
            $ortu   = ' ' . ucwords((string) $this->prefix);
            $prefix = '_' . $this->prefix;
        }

        if (! $this->prefixJudul) {
            $ortu = '';
        }

        if ($this->idPenduduk) {
            $penduduk = Penduduk::with(['keluarga', 'rtm', 'sakitMenahun', 'kb', 'bahasa'])->find($this->idPenduduk);
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
                'isian' => 'nama' . $prefix,
                'data'  => $penduduk->nama,
            ],
            [
                'judul' => 'Tanggal Lahir' . $ortu,
                'isian' => 'tanggallahir' . $prefix,
                'data'  => formatTanggal($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Lahir' . $ortu,
                'isian' => 'tempatlahir' . $prefix,
                'data'  => $penduduk->tempatlahir,
            ],
            [
                'judul' => 'Tempat Tanggal Lahir' . $ortu,
                'isian' => 'tempat_tgl_lahir' . $prefix,
                'data'  => $penduduk->tempatlahir . '/' . formatTanggal($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Tanggal Lahir (TTL)' . $ortu,
                'isian' => 'ttl' . $prefix,
                'data'  => $penduduk->tempatlahir . '/' . formatTanggal($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Usia' . $ortu,
                'isian' => 'usia' . $prefix,
                'data'  => $penduduk->usia,
            ],
            [
                'judul' => 'Jenis Kelamin' . $ortu,
                'isian' => 'jenis_kelamin' . $prefix,
                'data'  => $penduduk->jenisKelamin->nama,
            ],
            [
                'judul' => 'Jenis Kelamin ' . $ortu . '(Inisial)',
                'isian' => 'jenis_kelamin_inisial' . $prefix,
                'data'  => Str::substr($penduduk->jenisKelamin->nama, 0, 1),
            ],
            [
                'judul' => 'Agama' . $ortu,
                'isian' => 'agama' . $prefix,
                'data'  => $penduduk->agama->nama,
            ],
            [
                'judul' => 'Pekerjaan' . $ortu,
                'isian' => 'pekerjaan' . $prefix,
                'data'  => $penduduk->pekerjaan->nama,
            ],
            [
                'judul' => 'Warga Negara' . $ortu,
                'isian' => 'warga_negara' . $prefix,
                'data'  => $penduduk->wargaNegara->nama,
            ],
            [
                'judul' => 'Alamat' . $ortu,
                'isian' => 'alamat' . $prefix,
                'data'  => $penduduk->alamat_wilayah,
            ],
            [
                'judul' => 'Alamat Lengkap' . $ortu,
                'isian' => 'alamat_lengkap' . $prefix,
                'data'  => $penduduk->alamat_wilayah . ', ' . ucwords(setting('sebutan_desa') . ' ' . $config->nama_desa . ', ' . setting('sebutan_kecamatan') . ' ' . $config->nama_kecamatan . ', ' . setting('sebutan_kabupaten') . ' ' . $config->nama_kabupaten . ', Provinsi ' . $config->nama_propinsi),
            ],
            [
                'judul' => 'Golongan Darah' . $ortu,
                'isian' => 'Gol_daraH' . $prefix,
                'data'  => $penduduk->golonganDarah->nama,
            ],

            // melengkapi kode isian penduduk
            [
                'judul' => 'Suku' . $ortu,
                'isian' => 'suku' . $prefix,
                'data'  => $penduduk->suku,
            ],
            [
                'judul' => 'No Telepon' . $ortu,
                'isian' => 'telepon' . $prefix,
                'data'  => $penduduk->telepon,
            ],
            [
                'judul' => 'Nomor KITAS/KITAP' . $ortu,
                'isian' => 'dokumen_kitas' . $prefix,
                'data'  => $penduduk->dokumen_kitas,
            ],
            [
                'judul' => 'Email' . $ortu,
                'isian' => 'email' . $prefix,
                'data'  => $penduduk->email,
            ],
            [
                'judul' => 'Sakit Menahun' . $ortu,
                'isian' => 'sakit_menahun' . $prefix,
                'data'  => $penduduk->sakitMenahun->nama,
            ],
            [
                'judul' => 'Akseptor KB' . $ortu,
                'isian' => 'cara_kb' . $prefix,
                'data'  => $penduduk->kb->nama,
            ],
            [
                'judul' => 'Nama/Nomor Asuransi Kesehatan' . $ortu,
                'isian' => 'nama_asuransi' . $prefix,
                'data'  => $penduduk->nama_asuransi,
            ],
            [
                'judul' => 'Nomor BPJS Ketenagakerjaan' . $ortu,
                'isian' => 'bpjs_ketenagakerjaan' . $prefix,
                'data'  => $penduduk->bpjs_ketenagakerjaan,
            ],
            [
                'judul' => 'Bahasa' . $ortu,
                'isian' => 'Bahasa' . $prefix,
                'data'  => $penduduk->bahasa->nama,
            ],
            [
                'judul' => 'Pendidikan Sedang' . $ortu,
                'isian' => 'pendidikan_sedang' . $prefix,
                'data'  => $penduduk->pendidikan,
            ],
            [
                'judul' => 'Pendidikan Dalam KK' . $ortu,
                'isian' => 'pendidikan_kk' . $prefix,
                'data'  => $penduduk->pendidikanKK->nama,
            ],

            // kebutuhan penduduk luar desa
            [
                'judul' => 'Alamat Jalan' . $ortu,
                'isian' => 'alamat_jalan' . $prefix,
                'data'  => $penduduk->keluarga->alamat, // alamat kk jika ada
            ],
            [
                'judul' => 'Alamat Sebelumnya' . $ortu,
                'isian' => 'alamat_sebelumnya' . $prefix,
                'data'  => $penduduk->alamat_sebelumnya,
            ],
            [
                'judul' => 'Dusun' . $ortu,
                'isian' => 'nama_dusun' . $prefix,
                'data'  => $penduduk->wilayah->dusun,
            ],
            [
                'judul' => 'RW' . $ortu,
                'isian' => 'nama_rw' . $prefix,
                'data'  => $penduduk->wilayah->rw,
            ],
            [
                'judul' => 'RT' . $ortu,
                'isian' => 'nama_rt' . $prefix,
                'data'  => $penduduk->wilayah->rt,
            ],
            [
                'judul' => 'Desa' . $ortu,
                'isian' => 'pend_desa' . $prefix,
                'data'  => $config->nama_desa,
            ],
            [
                'judul' => 'Kecamatan' . $ortu,
                'isian' => 'pend_kecamatan' . $prefix,
                'data'  => $config->nama_kecamatan,
            ],
            [
                'judul' => 'Kabupaten' . $ortu,
                'isian' => 'pend_kabupaten' . $prefix,
                'data'  => $config->nama_kabupaten,
            ],
            [
                'judul' => 'Provinsi' . $ortu,
                'isian' => 'pend_provinsi' . $prefix,
                'data'  => $config->nama_propinsi,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Anak Ke' . $ortu,
                'isian'         => 'anakke' . $prefix,
                'data'          => $penduduk->kelahiran_anak_ke,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Jumlah Saudara' . $ortu,
                'isian'         => 'jumlah_saudara' . $prefix,
                'data'          => $penduduk->jml_anak,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Foto' . $ortu,
                'isian'         => 'foto_penduduk' . $prefix,
                'data'          => '[foto_penduduk]',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Foto Ukuran' . $ortu,
                'isian'         => '<img src="' . base_url('assets/images/pengguna/kuser.png') . '" width="124" height="148">',
                'data'          => empty($penduduk->foto) || ! file_exists(FCPATH . LOKASI_USER_PICT . $penduduk->foto) ? '' : base_url(LOKASI_USER_PICT . $penduduk->foto),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Foto Ukuran' . $ortu,
                'isian'         => '<img src="' . base_url('desa/upload/media/kuser.png') . '" width="124" height="148">',
                'data'          => empty($penduduk->foto) || ! file_exists(FCPATH . LOKASI_USER_PICT . $penduduk->foto) ? '' : base_url(LOKASI_USER_PICT . $penduduk->foto),
            ],
            [
                'judul' => 'Akta Kelahiran' . $ortu,
                'isian' => 'akta_lahir' . $prefix,
                'data'  => $penduduk->akta_lahir, // Cek ini
            ],
            [
                'judul' => 'Akta Perceraian' . $ortu,
                'isian' => 'akta_perceraian' . $prefix,
                'data'  => $penduduk->akta_perceraian, // Cek ini
            ],
            [
                'judul' => 'Status Perkawinan' . $ortu,
                'isian' => 'status_kawin' . $prefix,
                'data'  => $penduduk->status_perkawinan, // Cek ini
            ],
            [
                'judul' => 'Akta Perkawinan' . $ortu,
                'isian' => 'akta_perkawinan' . $prefix,
                'data'  => $penduduk->akta_perkawinan, // Cek ini
            ],
            [
                'judul' => 'Tanggal Perkawinan' . $ortu,
                'isian' => 'tanggalperkawinan' . $prefix,
                'data'  => formatTanggal($penduduk->tanggalperkawinan),
            ],
            [
                'judul' => 'Tanggal Perceraian' . $ortu,
                'isian' => 'tanggalperceraian' . $prefix,
                'data'  => formatTanggal($penduduk->tanggalperceraian),
            ],
            [
                'judul' => 'Cacat' . $ortu,
                'isian' => 'cacat' . $prefix,
                'data'  => $penduduk->cacat->nama,
            ],
            [
                'judul' => 'Dokumen Pasport' . $ortu,
                'isian' => 'dokumen_pasport' . $prefix,
                'data'  => $penduduk->dokumen_pasport,
            ],
            [
                'judul' => 'Tanggal Akhir Paspor' . $ortu,
                'isian' => 'tanggal_akhir_paspor' . $prefix,
                'data'  => formatTanggal($penduduk->tanggal_akhir_paspor),
            ],

            // Data KK
            [
                'judul' => 'Hubungan Dalam KK' . $ortu,
                'isian' => 'hubungan_kk' . $prefix,
                'data'  => $penduduk->pendudukHubungan->nama,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'No KK' . $ortu,
                'isian'         => 'no_kk' . $prefix,
                'data'          => get_nokk($penduduk->keluarga->no_kk),
            ],
            [
                'judul' => 'Kepala KK' . $ortu,
                'isian' => 'kepala_kk' . $prefix,
                'data'  => $penduduk->keluarga->kepalaKeluarga->nama,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'NIK KK' . $ortu,
                'isian'         => 'nik_kepala_kk' . $prefix,
                'data'          => get_nik($penduduk->keluarga->kepalaKeluarga->nik),
            ],

            // Data RTM
            [
                'case_sentence' => true,
                'judul'         => 'ID BDT' . $ortu,
                'isian'         => 'id_bdt' . $prefix,
                'data'          => $penduduk->rtm->bdt,
            ],
        ];

        if (empty($this->prefix)) {
            // Data Umum
            $data = $individu;

            // Data Orang Tua
            $id_ayah = Penduduk::where('nik', $penduduk->ayah_nik)->first()->id;
            $id_ibu  = Penduduk::where('nik', $penduduk->ibu_nik)->first()->id;

            if (! $id_ayah && $penduduk->kk_level == SHDKEnum::ANAK) {
                $id_ayah = Penduduk::where('id_kk', $penduduk->id_kk)
                    ->where(static function ($query): void {
                        $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->orWhere('kk_level', SHDKEnum::SUAMI);
                    })
                    ->where('sex', JenisKelaminEnum::LAKI_LAKI)
                    ->first()->id;
            }

            if (! $id_ibu && $penduduk->kk_level == SHDKEnum::ANAK) {
                $id_ibu = Penduduk::where('id_kk', $penduduk->id_kk)
                    ->where(static function ($query): void {
                        $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->orWhere('kk_level', SHDKEnum::ISTRI);
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
