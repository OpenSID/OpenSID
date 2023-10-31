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

class ReplaceAlias
{
    private $suratMatser;
    private $inputForm;

    public function __construct($suratMatser, $inputForm)
    {
        $this->suratMatser = $suratMatser;
        $this->inputForm   = $inputForm;
    }

    public static function get($suratMatser, $inputForm)
    {
        return (new self($suratMatser, $inputForm))->getKategori();
    }

    public function alias($kategori = 'individu')
    {
        $input = $this->inputForm[$kategori];

        $prefix = '_' . $kategori;

        if ($kategori == 'individu') {
            $prefix = '';
            if ($this->inputForm['nik'] == $input['nik']) {
                unset($input['nik']);
            }
        }

        return [
            "[nik{$prefix}]"           => $input['nik'] ?: "[nik{$prefix}]",
            "[nama{$prefix}]"          => $input['nama'] ?: "[nama{$prefix}]",
            "[tempatlahir{$prefix}]"   => $input['tempatlahir'] ?: "[tempatlahir{$prefix}]",
            "[tanggallahir{$prefix}]"  => $input['tanggallahir'] ? formatTanggal($input['tanggallahir']) : "[tanggallahir{$prefix}]",
            "[ttl{$prefix}]"           => $input['tanggallahir'] || $input['tanggallahir'] ? $input['tempatlahir'] . '/' . formatTanggal($input['tanggallahir']) : "[ttl{$prefix}]",
            "[jenis_kelamin{$prefix}]" => $input['jenis_kelamin'] ?: "[jenis_kelamin{$prefix}]",
            "[agama{$prefix}]"         => $input['agama'] ?: "[agama{$prefix}]",
            "[pendidikan_kk{$prefix}]" => $input['pendidikan'] ?: "[pendidikan_kk{$prefix}]",
            "[pekerjaan{$prefix}]"     => $input['pekerjaan'] ?: "[pekerjaan{$prefix}]",
            "[warga_negara{$prefix}]"  => $input['warga_negara'] ?: "[warga_negara{$prefix}]",
            "[alamat{$prefix}]"        => $input['alamat'] ?: "[alamat{$prefix}]",

            // agar bisa gunakan isian non warga versi sebelumnnya
            '[form_nama_non_warga]' => $input['nama'] ?: '[form_nama_non_warga]',
            '[form_nik_non_warga]'  => $input['nik'] ?: '[form_nik_non_warga]',
        ];
    }

    public function getKategori()
    {
        return collect($this->suratMatser->form_isian)->keys()->mapWithKeys(function ($item) {
            return $this->alias($item);
        })->toArray();
    }
}
