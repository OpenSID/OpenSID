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

class ReplaceAlias
{
    private $suratMatser;
    private $inputForm;
    private $kodeIsian = [
        'nik',
        'nama',
        'tempatlahir',
        'tanggallahir',
        'ttl',
        'usia',
        'jenis_kelamin',
        'agama',
        'pendidikan_kk',
        'pekerjaan',
        'warga_negara',
        'alamat_jalan',
        'alamat',
        'nama_rt',
        'nama_rw',
        'nama_desa',
        'nama_kecamatan',
        'nama_kabupaten',
        'nama_provinsi',

        // kode isian lama
        'form_nama_non_warga',
        'form_nik_non_warga',
    ];

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

        if (! $input['opsi_penduduk'] || $input['opsi_penduduk'] == 1) {
            return false;
        }

        $prefix = '_' . $kategori;

        if ($kategori == 'individu') {
            $prefix = '';
            if ($this->inputForm['nik'] == $input['nik']) {
                unset($input['nik']);
            }
        }

        return collect($this->kodeIsian)->mapWithKeys(static function ($item) use ($prefix, $input) {
            $value = $input[$item];
            if (in_array($item, ['form_nama_non_warga', 'form_nik_non_warga'])) {
                return ['[' . ucfirst(uclast($item)) . ']' => $value];
            }

            if ($item == 'ttl') {
                $value = $input['tempatlahir'] . '/' . formatTanggal($input['tanggallahir']);
            }

            if ($item == 'usia') {
                $value = usia($input['tanggallahir'], null, '%y tahun');
            }

            if ($item == 'alamat') {
                $value = $input['alamat_jalan'] . ' RT ' . $input['nama_rt'] . ' RW ' . $input['nama_rw'] . ' ' . ucwords(setting('sebutan_desa') . ' ' . $input['nama_desa'] . ', ' . setting('sebutan_kecamatan') . ' ' . $input['nama_kecamatan'] . ', ' . setting('sebutan_kabupaten') . ' ' . $input['nama_kabupaten'] . ', Provinsi ' . $input['nama_provinsi']);
            }

            return ['[' . ucfirst(uclast($item . $prefix)) . ']' => $value];
        });
    }

    public function getKategori()
    {
        return collect($this->suratMatser->form_isian)->keys()->mapWithKeys(function ($item) {
            return $this->alias($item);
        })->toArray();
    }
}
