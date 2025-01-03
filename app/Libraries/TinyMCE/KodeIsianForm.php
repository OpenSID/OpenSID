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

class KodeIsianForm
{
    private array $statisForm = [
        [
            'nama' => 'Mulai Berlaku',
            'kode' => 'mulai_berlaku',
        ],
        [
            'nama' => 'Berlaku Sampai',
            'kode' => 'berlaku_sampai',
        ],
        [
            'nama' => 'Pengikut Surat',
            'kode' => 'pengikut_surat',
        ],
        [
            'nama' => 'Pengikut KIS',
            'kode' => 'pengikut_kis',
        ],
        [
            'nama' => 'Pengikut Kartu KIS',
            'kode' => 'pengikut_kartu_kis',
        ],
        [
            'nama' => 'Pengikut Pindah',
            'kode' => 'pengikut_pindah',
        ],
    ];

    /**
     * KodeIsianForm constructor.
     *
     * @param array      $inputForm
     * @param array|null $kodeIsian
     * @param bool       $masaBerlaku
     *
     * @return void
     */
    public function __construct(private $inputForm, private $kodeIsian, private $masaBerlaku = false)
    {
    }

    /**
     * @param array      $inputForm
     * @param array|null $kodeIsian
     * @param bool       $masaBerlaku
     *
     * @return \Illuminate\Support\Collection
     */
    public static function get($inputForm, $kodeIsian, $masaBerlaku = false)
    {
        return (new self($inputForm, $kodeIsian, $masaBerlaku))->getKodeIsian();
    }

    /**
     * Menerjemahkan kode isian menjadi data yang akan ditampilkan
     *
     * @return \Illuminate\Support\Collection
     */
    public function getKodeIsian()
    {
        $input     = is_array($this->inputForm) ? $this->inputForm : json_decode($this->inputForm, true);
        $kodeIsian = $this->kodeIsian;

        if (! is_array($kodeIsian)) {
            $kodeIsian = $this->statisForm;

            if (! $this->masaBerlaku) {
                unset($kodeIsian[0], $kodeIsian[1]);
            }
        }

        return collect($kodeIsian)
            ->map(static function (array $item, $key) use ($input): array {
                $input_data = $input[str_replace(['[form_', ']'], '', $item['kode'])];
                if ($item['tipe'] == 'date') {
                    $data = formatTanggal($input_data);
                } elseif ($item['tipe'] == 'hari-tanggal') {
                    if ($input_data != '') {
                        $day  = get_hari($input_data);
                        $data = $day . ', ' . formatTanggal($input_data);
                    }
                } elseif ($item['tipe'] == 'hari') {
                    if ($input_data != '') {
                        $data = get_hari($input_data);
                    }
                } else {
                    $data = $input_data;
                }

                return [
                    'case_sentence' => in_array($item['tipe'], ['number', 'time']),
                    'judul'         => $item['nama'],
                    'isian'         => $item['kode'],
                    'data'          => $data,
                ];
            });
    }
}
