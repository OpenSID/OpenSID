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

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class PekerjaanEnum extends BaseEnum
{
    public const BELUM_TIDAK_BEKERJA            = 1;
    public const MENGURUS_RUMAH_TANGGA          = 2;
    public const PELAJAR_MAHASISWA              = 3;
    public const PENSIUNAN                      = 4;
    public const PEGAWAI_NEGERI_SIPIL_PNS       = 5;
    public const TENTARA_NASIONAL_INDONESIA_TNI = 6;
    public const KEPOLISIAN_RI_POLRI            = 7;
    public const PERDAGANGAN                    = 8;
    public const PETANI_PEKEBUN                 = 9;
    public const PETERNAK                       = 10;
    public const NELAYAN_PERIKANAN              = 11;
    public const INDUSTRI                       = 12;
    public const KONSTRUKSI                     = 13;
    public const TRANSPORTASI                   = 14;
    public const KARYAWAN_SWASTA                = 15;
    public const KARYAWAN_BUMN                  = 16;
    public const KARYAWAN_BUMD                  = 17;
    public const KARYAWAN_HONORER               = 18;
    public const BURUH_HARIAN_LEPAS             = 19;
    public const BURUH_TANI_PERKEBUNAN          = 20;
    public const BURUH_NELAYAN_PERIKANAN        = 21;
    public const BURUH_PETERNAKAN               = 22;
    public const PEMBANTU_RUMAH_TANGGA          = 23;
    public const TUKANG_CUKUR                   = 24;
    public const TUKANG_LISTRIK                 = 25;
    public const TUKANG_BATU                    = 26;
    public const TUKANG_KAYU                    = 27;
    public const TUKANG_SOL_SEPATU              = 28;
    public const TUKANG_LAS_PANDAI_BESI         = 29;
    public const TUKANG_JAHIT                   = 30;
    public const TUKANG_GIGI                    = 31;
    public const PENATA_RIAS                    = 32;
    public const PENATA_BUSANA                  = 33;
    public const PENATA_RAMBUT                  = 34;
    public const MEKANIK                        = 35;
    public const SENIMAN                        = 36;
    public const TABIB                          = 37;
    public const PARAJI                         = 38;
    public const PERANCANG_BUSANA               = 39;
    public const PENTERJEMAH                    = 40;
    public const IMAM_MASJID                    = 41;
    public const PENDETA                        = 42;
    public const PASTOR                         = 43;
    public const WARTAWAN                       = 44;
    public const USTADZ_MUBALIGH                = 45;
    public const JURU_MASAK                     = 46;
    public const PROMOTOR_ACARA                 = 47;
    public const ANGGOTA_DPR_RI                 = 48;
    public const ANGGOTA_DPD                    = 49;
    public const ANGGOTA_BPK                    = 50;
    public const PRESIDEN                       = 51;
    public const WAKIL_PRESIDEN                 = 52;
    public const ANGGOTA_MAHKAMAH_KONSTITUSI    = 53;
    public const ANGGOTA_KABINET_KEMENTERIAN    = 54;
    public const DUTA_BESAR                     = 55;
    public const GUBERNUR                       = 56;
    public const WAKIL_GUBERNUR                 = 57;
    public const BUPATI                         = 58;
    public const WAKIL_BUPATI                   = 59;
    public const WALIKOTA                       = 60;
    public const WAKIL_WALIKOTA                 = 61;
    public const ANGGOTA_DPRD_PROVINSI          = 62;
    public const ANGGOTA_DPRD_KABUPATEN_KOTA    = 63;
    public const DOSEN                          = 64;
    public const GURU                           = 65;
    public const PILOT                          = 66;
    public const PENGACARA                      = 67;
    public const NOTARIS                        = 68;
    public const ARSITEK                        = 69;
    public const AKUNTAN                        = 70;
    public const KONSULTAN                      = 71;
    public const DOKTER                         = 72;
    public const BIDAN                          = 73;
    public const PERAWAT                        = 74;
    public const APOTEKER                       = 75;
    public const PSIKIATER_PSIKOLOG             = 76;
    public const PENYIAR_TELEVISI               = 77;
    public const PENYIAR_RADIO                  = 78;
    public const PELAUT                         = 79;
    public const PENELITI                       = 80;
    public const SOPIR                          = 81;
    public const PIALANG                        = 82;
    public const PARANORMAL                     = 83;
    public const PEDAGANG                       = 84;
    public const PERANGKAT_DESA                 = 85;
    public const KEPALA_DESA                    = 86;
    public const BIARAWATI                      = 87;
    public const WIRASWASTA                     = 88;
    public const LAINNYA                        = 89;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::BELUM_TIDAK_BEKERJA            => 'BELUM/TIDAK BEKERJA',
            self::MENGURUS_RUMAH_TANGGA          => 'MENGURUS RUMAH TANGGA',
            self::PELAJAR_MAHASISWA              => 'PELAJAR/MAHASISWA',
            self::PENSIUNAN                      => 'PENSIUNAN',
            self::PEGAWAI_NEGERI_SIPIL_PNS       => 'PEGAWAI NEGERI SIPIL (PNS)',
            self::TENTARA_NASIONAL_INDONESIA_TNI => 'TENTARA NASIONAL INDONESIA (TNI)',
            self::KEPOLISIAN_RI_POLRI            => 'KEPOLISIAN RI (POLRI)',
            self::PERDAGANGAN                    => 'PERDAGANGAN',
            self::PETANI_PEKEBUN                 => 'PETANI/PEKEBUN',
            self::PETERNAK                       => 'PETERNAK',
            self::NELAYAN_PERIKANAN              => 'NELAYAN/PERIKANAN',
            self::INDUSTRI                       => 'INDUSTRI',
            self::KONSTRUKSI                     => 'KONSTRUKSI',
            self::TRANSPORTASI                   => 'TRANSPORTASI',
            self::KARYAWAN_SWASTA                => 'KARYAWAN SWASTA',
            self::KARYAWAN_BUMN                  => 'KARYAWAN BUMN',
            self::KARYAWAN_BUMD                  => 'KARYAWAN BUMD',
            self::KARYAWAN_HONORER               => 'KARYAWAN HONORER',
            self::BURUH_HARIAN_LEPAS             => 'BURUH HARIAN LEPAS',
            self::BURUH_TANI_PERKEBUNAN          => 'BURUH TANI/PERKEBUNAN',
            self::BURUH_NELAYAN_PERIKANAN        => 'BURUH NELAYAN/PERIKANAN',
            self::BURUH_PETERNAKAN               => 'BURUH PETERNAKAN',
            self::PEMBANTU_RUMAH_TANGGA          => 'PEMBANTU RUMAH TANGGA',
            self::TUKANG_CUKUR                   => 'TUKANG CUKUR',
            self::TUKANG_LISTRIK                 => 'TUKANG LISTRIK',
            self::TUKANG_BATU                    => 'TUKANG BATU',
            self::TUKANG_KAYU                    => 'TUKANG KAYU',
            self::TUKANG_SOL_SEPATU              => 'TUKANG SOL SEPATU',
            self::TUKANG_LAS_PANDAI_BESI         => 'TUKANG LAS/PANDAI BESI',
            self::TUKANG_JAHIT                   => 'TUKANG JAHIT',
            self::TUKANG_GIGI                    => 'TUKANG GIGI',
            self::PENATA_RIAS                    => 'PENATA RIAS',
            self::PENATA_BUSANA                  => 'PENATA BUSANA',
            self::PENATA_RAMBUT                  => 'PENATA RAMBUT',
            self::MEKANIK                        => 'MEKANIK',
            self::SENIMAN                        => 'SENIMAN',
            self::TABIB                          => 'TABIB',
            self::PARAJI                         => 'PARAJI',
            self::PERANCANG_BUSANA               => 'PERANCANG BUSANA',
            self::PENTERJEMAH                    => 'PENTERJEMAH',
            self::IMAM_MASJID                    => 'IMAM MASJID',
            self::PENDETA                        => 'PENDETA',
            self::PASTOR                         => 'PASTOR',
            self::WARTAWAN                       => 'WARTAWAN',
            self::USTADZ_MUBALIGH                => 'USTADZ/MUBALIGH',
            self::JURU_MASAK                     => 'JURU MASAK',
            self::PROMOTOR_ACARA                 => 'PROMOTOR ACARA',
            self::ANGGOTA_DPR_RI                 => 'ANGGOTA DPR-RI',
            self::ANGGOTA_DPD                    => 'ANGGOTA DPD',
            self::ANGGOTA_BPK                    => 'ANGGOTA BPK',
            self::PRESIDEN                       => 'PRESIDEN',
            self::WAKIL_PRESIDEN                 => 'WAKIL PRESIDEN',
            self::ANGGOTA_MAHKAMAH_KONSTITUSI    => 'ANGGOTA MAHKAMAH KONSTITUSI',
            self::ANGGOTA_KABINET_KEMENTERIAN    => 'ANGGOTA KABINET KEMENTERIAN',
            self::DUTA_BESAR                     => 'DUTA BESAR',
            self::GUBERNUR                       => 'GUBERNUR',
            self::WAKIL_GUBERNUR                 => 'WAKIL GUBERNUR',
            self::BUPATI                         => 'BUPATI',
            self::WAKIL_BUPATI                   => 'WAKIL BUPATI',
            self::WALIKOTA                       => 'WALIKOTA',
            self::WAKIL_WALIKOTA                 => 'WAKIL WALIKOTA',
            self::ANGGOTA_DPRD_PROVINSI          => 'ANGGOTA DPRD PROVINSI',
            self::ANGGOTA_DPRD_KABUPATEN_KOTA    => 'ANGGOTA DPRD KABUPATEN/KOTA',
            self::DOSEN                          => 'DOSEN',
            self::GURU                           => 'GURU',
            self::PILOT                          => 'PILOT',
            self::PENGACARA                      => 'PENGACARA',
            self::NOTARIS                        => 'NOTARIS',
            self::ARSITEK                        => 'ARSITEK',
            self::AKUNTAN                        => 'AKUNTAN',
            self::KONSULTAN                      => 'KONSULTAN',
            self::DOKTER                         => 'DOKTER',
            self::BIDAN                          => 'BIDAN',
            self::PERAWAT                        => 'PERAWAT',
            self::APOTEKER                       => 'APOTEKER',
            self::PSIKIATER_PSIKOLOG             => 'PSIKIATER/PSIKOLOG',
            self::PENYIAR_TELEVISI               => 'PENYIAR TELEVISI',
            self::PENYIAR_RADIO                  => 'PENYIAR RADIO',
            self::PELAUT                         => 'PELAUT',
            self::PENELITI                       => 'PENELITI',
            self::SOPIR                          => 'SOPIR',
            self::PIALANG                        => 'PIALANG',
            self::PARANORMAL                     => 'PARANORMAL',
            self::PEDAGANG                       => 'PEDAGANG',
            self::PERANGKAT_DESA                 => 'PERANGKAT DESA',
            self::KEPALA_DESA                    => 'KEPALA DESA',
            self::BIARAWATI                      => 'BIARAWATI',
            self::WIRASWASTA                     => 'WIRASWASTA',
            self::LAINNYA                        => 'LAINNYA',
        ];
    }
}
