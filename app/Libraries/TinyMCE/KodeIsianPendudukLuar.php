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

class KodeIsianPendudukLuar
{
    private $suratMatser;
    private $inputForm;
    public static array $kodeIsian = [
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
        'nama_dusun',
        'nama_rt',
        'nama_rw',
        'pend_desa',
        'pend_kecamatan',
        'pend_kabupaten',
        'pend_provinsi',

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

    public function alias(string $kategori = 'individu')
    {
        $input = $this->inputForm[$kategori];

        // filter hanya untuk nik dan nama yg tidak kosong
        if (empty($input['nik']) && empty($input['nama'])) {
            return [];
        }

        $prefix = '_' . $kategori;

        if ($kategori == 'individu') {
            if (isset($this->inputForm['nik'])) {
                return [];
            }

            $prefix = '';
            if ($this->inputForm['nik'] == $input['nik']) {
                unset($input['nik']);
            }
        } else {
            if ($this->inputForm["id_pend_{$kategori}"]) {
                return [];
            }
        }

        return collect(self::$kodeIsian)->mapWithKeys(static function (string $item) use ($prefix, $input): array {
            $value = $input[$item];

            if (in_array($item, ['form_nama_non_warga', 'form_nik_non_warga'])) {
                return ['[' . ucfirst(uclast($item)) . ']' => $value];
            }

            if (! empty($input['tanggallahir'])) {
                $tgl_lahir = $input['tanggallahir'];
            }

            if ($item == 'ttl') {
                $value = $input['tempatlahir'] . '/' . formatTanggal($tgl_lahir);
            }

            if ($item == 'usia') {
                $value = usia($tgl_lahir, null, '%y tahun');
            }

            if ($item == 'alamat') {
                $value = $input['alamat_jalan'] . ' RT ' . $input['nama_rt'] . ' RW ' . $input['nama_rw'] . ' ' . ucwords(setting('sebutan_desa') . ' ' . $input['pend_desa'] . ', ' . setting('sebutan_kecamatan') . ' ' . $input['pend_kecamatan'] . ', ' . setting('sebutan_kabupaten') . ' ' . $input['pend_kabupaten'] . ', Provinsi ' . $input['pend_provinsi']);
            }

            return ['[' . ucfirst(uclast($item . $prefix)) . ']' => $value];
        });
    }

    public function getKategori()
    {
        return collect($this->suratMatser->form_isian)->keys()->mapWithKeys(fn ($item) => $this->alias($item))->toArray();
    }
}
