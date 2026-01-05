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

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukKursusEnum extends BaseEnum
{
    public const KURSUS_KOMPUTER                 = 1;
    public const KURSUS_MENJAHIT                 = 2;
    public const PELATIHAN_KELISTRIKAN           = 3;
    public const KURSUS_MEKANIK_MOTOR            = 4;
    public const PELATIHAN_SECURITY              = 5;
    public const KURSUS_OTOMOTIF                 = 6;
    public const KURSUS_BAHASA_INGGRIS           = 7;
    public const KURSUS_TATA_KECANTIKAN_KULIT    = 8;
    public const KURSUS_MENGEMUDI                = 9;
    public const KURSUS_TATA_BOGA                = 10;
    public const KURSUS_MEUBELER                 = 11;
    public const KURSUS_LAS                      = 12;
    public const KURSUS_SABLON                   = 13;
    public const KURSUS_PENERBANGAN              = 14;
    public const KURSUS_DESAIN_INTERIOR          = 15;
    public const KURSUS_TEKNISI_HP               = 16;
    public const KURSUS_GARMENT                  = 17;
    public const KURSUS_AKUPUNTUR                = 18;
    public const KURSUS_SENAM                    = 19;
    public const KURSUS_PENDIDIK_PAUD            = 20;
    public const KURSUS_BABY_SITTER              = 21;
    public const KURSUS_DESAIN_GRAFIS            = 22;
    public const KURSUS_BAHASA_INDONESIA         = 23;
    public const KURSUS_PHOTOGRAFI               = 24;
    public const KURSUS_EXPOR_IMPOR              = 25;
    public const KURSUS_JURNALISTIK              = 26;
    public const KURSUS_BAHASA_ARAB              = 27;
    public const KURSUS_BAHASA_JEPANG            = 28;
    public const KURSUS_ANAK_BUAH_KAPAL          = 29;
    public const KURSUS_REFLEKSI                 = 30;
    public const KURSUS_PERHOTELAN               = 32;
    public const KURSUS_TATA_RIAS                = 33;
    public const KURSUS_ADMINISTRASI_PERKANTORAN = 34;
    public const KURSUS_BROADCASTING             = 35;
    public const KURSUS_KERAJINAN_TANGAN         = 36;
    public const KURSUS_SOSIAL_MEDIA_MARKETING   = 37;
    public const KURSUS_INTERNET_MARKETING       = 38;
    public const KURSUS_SEKRETARIS               = 39;
    public const KURSUS_PERPAJAKAN               = 40;
    public const KURSUS_PUBLIK_SPEAKING          = 41;
    public const KURSUS_PUBLIK_RELATION          = 42;
    public const KURSUS_BATIK                    = 43;
    public const KURSUS_PENGOBATAN_TRADISIONAL   = 44;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::KURSUS_KOMPUTER                 => 'Kursus Komputer',
            self::KURSUS_MENJAHIT                 => 'Kursus Menjahit',
            self::PELATIHAN_KELISTRIKAN           => 'Pelatihan Kelistrikan',
            self::KURSUS_MEKANIK_MOTOR            => 'Kursus Mekanik Motor',
            self::PELATIHAN_SECURITY              => 'Pelatihan Security',
            self::KURSUS_OTOMOTIF                 => 'Kursus Otomotif',
            self::KURSUS_BAHASA_INGGRIS           => 'Kursus Bahasa Inggris',
            self::KURSUS_TATA_KECANTIKAN_KULIT    => 'Kursus Tata Kecantikan Kulit',
            self::KURSUS_MENGEMUDI                => 'Kursus Mengemudi',
            self::KURSUS_TATA_BOGA                => 'Kursus Tata Boga',
            self::KURSUS_MEUBELER                 => 'Kursus Meubeler',
            self::KURSUS_LAS                      => 'Kursus Las',
            self::KURSUS_SABLON                   => 'Kursus Sablon',
            self::KURSUS_PENERBANGAN              => 'Kursus Penerbangan',
            self::KURSUS_DESAIN_INTERIOR          => 'Kursus Desain Interior',
            self::KURSUS_TEKNISI_HP               => 'Kursus Teknisi HP',
            self::KURSUS_GARMENT                  => 'Kursus Garment',
            self::KURSUS_AKUPUNTUR                => 'Kursus Akupuntur',
            self::KURSUS_SENAM                    => 'Kursus Senam',
            self::KURSUS_PENDIDIK_PAUD            => 'Kursus Pendidik PAUD',
            self::KURSUS_BABY_SITTER              => 'Kursus Baby Sitter',
            self::KURSUS_DESAIN_GRAFIS            => 'Kursus Desain Grafis',
            self::KURSUS_BAHASA_INDONESIA         => 'Kursus Bahasa Indonesia',
            self::KURSUS_PHOTOGRAFI               => 'Kursus Photografi',
            self::KURSUS_EXPOR_IMPOR              => 'Kursus Expor Impor',
            self::KURSUS_JURNALISTIK              => 'Kursus Jurnalistik',
            self::KURSUS_BAHASA_ARAB              => 'Kursus Bahasa Arab',
            self::KURSUS_BAHASA_JEPANG            => 'Kursus Bahasa Jepang',
            self::KURSUS_ANAK_BUAH_KAPAL          => 'Kursus Anak Buah Kapal',
            self::KURSUS_REFLEKSI                 => 'Kursus Refleksi',
            self::KURSUS_PERHOTELAN               => 'Kursus Perhotelan',
            self::KURSUS_TATA_RIAS                => 'Kursus Tata Rias',
            self::KURSUS_ADMINISTRASI_PERKANTORAN => 'Kursus Administrasi Perkantoran',
            self::KURSUS_BROADCASTING             => 'Kursus Broadcasting',
            self::KURSUS_KERAJINAN_TANGAN         => 'Kursus Kerajinan Tangan',
            self::KURSUS_SOSIAL_MEDIA_MARKETING   => 'Kursus Sosial Media Marketing',
            self::KURSUS_INTERNET_MARKETING       => 'Kursus Internet Marketing',
            self::KURSUS_SEKRETARIS               => 'Kursus Sekretaris',
            self::KURSUS_PERPAJAKAN               => 'Kursus Perpajakan',
            self::KURSUS_PUBLIK_SPEAKING          => 'Kursus Publik Speaking',
            self::KURSUS_PUBLIK_RELATION          => 'Kursus Publik Relation',
            self::KURSUS_BATIK                    => 'Kursus Batik',
            self::KURSUS_PENGOBATAN_TRADISIONAL   => 'Kursus Pengobatan Tradisional',
        ];
    }
}
