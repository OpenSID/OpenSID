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

namespace App\Libraries;

use App\Libraries\TinyMCE\KodeIsianAnggotaKeluarga;
use App\Libraries\TinyMCE\KodeIsianAritmatika;
use App\Libraries\TinyMCE\KodeIsianIdentitas;
use App\Libraries\TinyMCE\KodeIsianPasangan;
use App\Libraries\TinyMCE\KodeIsianPenandaTangan;
use App\Libraries\TinyMCE\KodeIsianPenduduk;
use App\Libraries\TinyMCE\KodeIsianPeristiwa;
use App\Libraries\TinyMCE\KodeIsianSurat;
use App\Libraries\TinyMCE\KodeIsianWilayah;
use App\Models\FormatSurat;
use App\Models\Keluarga;
use App\Models\LogPenduduk;
use App\Models\Pamong;
use App\Models\Penduduk;
use Carbon\Carbon;
use CI_Controller;
use Karriere\PdfMerge\PdfMerge;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

if (! in_array(setting('font_surat'), FONT_SYSTEM_TINYMCE)) {
    define('K_PATH_MAIN', '');
    define('K_PATH_FONTS', LOKASI_FONT_DESA);
}

class TinyMCE
{
    public const HEADER = '
        <table style="border-collapse: collapse; width: 100%;">
        <tbody>
        <tr>
        <td style="width: 10%;">[logo]</td>
        <td style="text-align: center; width: 90%;">
        <p style="margin: 0; text-align: center;"><span style="font-size: 14pt;">PEMERINTAH [SEbutan_kabupaten] [NAma_kabupaten] <br />KECAMATAN [NAma_kecamatan]<strong><br />[SEbutan_desa] [NAma_desa] </strong></span></p>
        <p style="margin: 0; text-align: center;"><em><span style="font-size: 10pt;">[Alamat_desA]</span></em></p>
        </td>
        </tr>
        </tbody>
        </table>
        <hr style="border: 3px solid;" />
    ';
    public const FOOTER = '
        <table style="border-collapse: collapse; width: 100%; height: 10px;" border="0">
        <tbody>
        <tr>
        <td style="width: 11.2886%; height: 10px;">[kode_desa]</td>
        <td style="width: 78.3174%; height: 10px;">
        <p style="text-align: center;">&nbsp;</p>
        </td>
        <td style="width: 10.3939%; height: 10px; text-align: right;">[KOde_surat]</td>
        </tr>
        </tbody>
        </table>
    ';
    public const FOOTER_TTE = '
        <table style="border-collapse: collapse; width: 100%; height: 10px;" border="0">
        <tbody>
        <tr>
        <td style="width: 11.2886%; height: 10px;">[kode_desa]</td>
        <td style="width: 78.3174%; height: 10px;">
        <p style="text-align: center;">&nbsp;</p>
        </td>
        <td style="width: 10.3939%; height: 10px; text-align: right;">[KOde_surat]</td>
        </tr>
        </tbody>
        </table>
        <table style="border-collapse: collapse; width: 100%; height: 10px;" border="0">
        <tbody>
        <tr>
        <td style="width: 15%;"><div style="max-height: 73px;">[logo_bsre]</div></td>
        <td style="width: 60%; text-align: left; vertical-align: top;">
        <ul style="font-size: 6pt;">
        <li style="font-size: 6pt;"><span style="font-size: 6pt;">UU ITE No. 11 Tahun 2008 Pasal 5 ayat 1 "Informasi Elektronik dan/atau hasil cetaknya merupakan alat bukti hukum yang sah".</span></li>
        <li style="font-size: 6pt;"><span style="font-size: 6pt;">Dokumen ini tertanda ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan BSrE.</span></li>
        <li style="font-size: 6pt;"><span style="font-size: 6pt;">Surat ini dapat dibuktikan keasliannya dengan menggunakan qr code yang telah tersedia.</span></li>
        </ul>
        </td>
        <td style="width: 25%; text-align: center;">[qr_bsre]</td>
        </tr>
        </tbody>
        </table>
    ';
    public const TOP    = 3.5; // cm
    public const BOTTOM = 2; // cm

    /**
     * @var CI_Controller
     */
    protected $ci;

    /**
     * @var PdfMerge
     */
    public $pdfMerge;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model('surat_model');

        $this->pdfMerge = new PdfMerge();
    }

    public function getTemplate()
    {
        $template = [
            [
                'nama'     => 'Header',
                'template' => [
                    'sistem' => static::HEADER,
                    'desa'   => setting('header_surat'),
                ],
            ],

            [
                'nama'     => 'Footer',
                'template' => [
                    'sistem' => static::FOOTER,
                    'desa'   => setting('footer_surat'),
                ],
            ],

            [
                'nama'     => 'Footer TTE',
                'template' => [
                    'sistem' => static::FOOTER_TTE,
                    'desa'   => setting('footer_surat_tte'),
                ],
            ],
        ];

        return collect($template);
    }

    public function getTemplateSurat()
    {
        return collect(FormatSurat::whereNotNull('template')->jenis(FormatSurat::TINYMCE)->get(['nama', 'template', 'template_desa']))
            ->map(static fn ($item, $key) => [
                'nama'     => 'Surat ' . $item->nama,
                'template' => [
                    'sistem' => $item->template,
                    'desa'   => $item->template_desa,
                ],
            ]);
    }

    public function getFormatedKodeIsian($data = [], $withData = false)
    {
        $idPenduduk = $data['id_pend'];

        $daftar_kode_isian = [
            // Data Surat
            'Surat' => KodeIsianSurat::get($data),

            // Data Identitas Desa
            'Identitas Desa' => KodeIsianIdentitas::get($idPenduduk ?? $data['nik_non_warga']),

            // Data Dusun
            'Wilayah' => KodeIsianWilayah::get(),

            // Data Penduduk Umum
            'Penduduk' => KodeIsianPenduduk::get($idPenduduk),

            // Data Anggota keluarga
            'Anggota Keluarga' => KodeIsianAnggotaKeluarga::get($idPenduduk),

            // Data Pasangan
            'Pasangan' => KodeIsianPasangan::get($idPenduduk),

            // Aritmatika untuk penambahan, pengurangan, dan operasi lainnya serta terbilang
            'Aritmatika' => KodeIsianAritmatika::get(),
        ];

        $peristiwa = $data['surat']->form_isian->individu->status_dasar;
        if (in_array($peristiwa, LogPenduduk::PERISTIWA)) {
            $daftar_kode_isian['Peristiwa'] = KodeIsianPeristiwa::get($idPenduduk, $peristiwa);
        }

        $daftarKategori = collect($data['surat']->form_isian)->toArray();

        foreach ($daftarKategori as $key => $value) {
            if ($value->sumber == 1 && $key != 'individu') {
                $daftar_kode_isian[$value->judul] = KodeIsianPenduduk::get(null, $key);
            }
        }

        // Data Dari Form Isian
        $isian_post = $this->getIsianPost($data);

        if (isset($isian_post['kategori'])) {
            foreach ($isian_post['kategori'] as $key => $value) {
                $key_ktg  = $value['prefix_kategori'];
                $nama_ktg = $daftarKategori[$key_ktg]->judul;
                unset($value['prefix_kategori']);
                $daftar_kode_isian['Input ' . $nama_ktg][] = $value;
            }
            unset($isian_post['kategori']);
        }
        $daftar_kode_isian['Input'] = $isian_post;

        // Penandatangan
        $daftar_kode_isian['Penandatangan'] = KodeIsianPenandaTangan::get($data['input']);

        // Jika penduduk luar, hilangkan isian penduduk
        if ($data['surat']['form_isian']->individu->data == 2) {
            unset($daftar_kode_isian['Penduduk'], $daftar_kode_isian['Anggota Keluarga']);
        }

        $daftar_kode_isian = collect($daftar_kode_isian)->map(static function ($item) {
            return collect($item)->map(static function ($item) {
                $item['isian'] = getFormatIsian($item['isian']);

                return $item;
            });
        })->toArray();

        if ($withData) {
            return collect($daftar_kode_isian)
                ->flatten(1)
                ->pluck('data', 'isian.normal')
                ->toArray();
        }

        return $daftar_kode_isian;
    }

    public function formatPdf($header, $footer, $isi)
    {
        // Pisahkan isian surat
        $isi = str_replace('<p><!-- pagebreak --></p>', '', $isi);
        $isi = explode('<!-- pagebreak -->', $isi);

        // Pengaturan Header
        switch ($header) {
            case 0:
                $backtop    = '0mm';
                $isi_header = '<page_header>' . $isi[0] . '</page_header>';
                $isi_surat  = $isi[1];
                break;

            case 1:
                $backtop    = ((float) setting('tinggi_header')) * 10 . 'mm';
                $isi_header = '<page_header>' . $isi[0] . '</page_header>';
                $isi_surat  = $isi[1];
                break;

            default:
                $backtop    = '0mm';
                $isi_header = '';
                $isi_surat  = $isi[0] . $isi[1];
                break;
        }

        // Pengaturan Footer
        switch ($footer) {
            case 0:
                $backbottom = '0mm';
                $isi_footer = '';
                break;

            default:
                $backbottom = (((float) setting('tinggi_footer')) * 10) . 'mm';
                $isi_footer = '<page_footer>' . $isi[2] . '</page_footer>';
                break;
        }

        return '
            <page backtop="' . $backtop . '" backbottom="' . $backbottom . '">
            ' . $isi_header . '
            ' . $isi_footer . '
            ' . $isi_surat . '
            </page>
        ';
    }

    private function getIsianPost($data = [])
    {
        $input = $data['input'];

        // Statis Post
        $postStatis = [];

        if ((int) $data['surat']['masa_berlaku'] > 0) {
            $postStatis = [
                [
                    'nama' => 'Mulai Berlaku',
                    'kode' => '[mulai_berlaku]',
                ],
                [
                    'nama' => 'Berlaku Sampai',
                    'kode' => '[berlaku_sampai]',
                ],
                [
                    'nama' => 'Pengikut Surat',
                    'kode' => '[pengikut_surat]',
                ],
                [
                    'nama' => 'Pengikut KIS',
                    'kode' => '[pengikut_kis]',
                ],
                [
                    'nama' => 'Pengikut Kartu KIS',
                    'kode' => '[pengikut_kartu_kis]',
                ],
                [
                    'nama' => 'Pengikut Pindah',
                    'kode' => '[pengikut_pindah]',
                ],
            ];

            $postStatis = collect($postStatis)
                ->map(static function ($item, $key) use ($input) {
                    return [
                        'judul' => $item['nama'],
                        'isian' => $item['kode'],
                        'data'  => $input[underscore($item['nama'], true, true)],
                    ];
                })
                ->toArray();
        }
        // Dinamis
        $dinadata = collect($data['surat']['kode_isian'])->reject(static function ($item) {
            return isset($item->kategori);
        })->values();

        $postDinamis = collect($dinadata)
            ->map(static function ($item, $key) use ($input) {
                $input_data = $input[underscore($item->nama, true, true)];
                if ($item->tipe == 'date') {
                    $data = Carbon::parse($input_data)->format('Y-m-d');
                } elseif ($item->tipe == 'hari-tanggal') {
                    if ($input_data != '') {
                        $day  = self::get_hari($input_data);
                        $data = tgl_indo(Carbon::parse($input_data)->format('Y-m-d'), '', $day);
                    }
                } elseif ($item->tipe == 'hari') {
                    if ($input_data != '') {
                        $data = self::get_hari($input_data);
                    }
                } else {
                    $data = $input_data;
                }

                return [
                    'judul' => $item->nama,
                    'isian' => $item->kode,
                    'data'  => $data,
                ];
            })
            ->toArray();
        $kategori_isianP = [];
        $kategori_isian  = collect($data['surat']['kode_isian'])->filter(static function ($item) use (&$kategori_nama, &$kategori_isianP, &$input) {
            $kategori_isianP[$item->kategori][] = $item;

            return isset($item->kategori);
        })->values();
        $post2['kategori'] = $postDinamis2 = collect($kategori_isian)
            ->map(static function ($item, $key) use ($input) {
                $nama = $item->nama;
                $data = $input[underscore($nama, true, true) . '_' . $item->kategori];

                return [
                    'prefix_kategori' => $item->kategori,
                    'judul'           => $item->nama,
                    'isian'           => $item->kode,
                    'data'            => ($item->tipe == 'date') ? tgl_indo(Carbon::parse($data)->format('Y-m-d')) : $data,
                ];
            })
            ->toArray();

        return array_merge($postStatis, $postDinamis, $post2);
    }

    public function get_hari($tanggal)
    {
        $hari = Carbon::createFromFormat('d-m-Y', $tanggal)->locale('id');

        return $hari->dayName;
    }

    public function replceKodeIsian($data = [], $kecuali = [])
    {
        $result       = $data['isi_surat'];
        $gantiDengan  = setting('ganti_data_kosong');
        $newKodeIsian = collect($this->getFormatedKodeIsian($data, true))
            ->flatMap(static function ($value, $key) {
                if (preg_match('/klg/i', $key)) {
                    return collect(range(1, 10))->map(static function ($i) use ($key, $value) {
                        return [
                            'isian' => str_replace('x_', "{$i}_", $key),
                            'data'  => $value[$i - 1] ?? '',
                        ];
                    });
                } else {
                    return [
                        [
                            'isian' => $key,
                            'data'  => $value,
                        ],
                    ];
                }
            })
            ->mapWithKeys(static function ($item) {
                return [$item['isian'] => $item['data']];
            })
            ->map(static function ($item) use ($gantiDengan) {
                if (null === $item || $item == '/') {
                    return $gantiDengan;
                }

                return $item;
            })
            ->toArray();

        if ((int) $data['surat']['masa_berlaku'] == 0) {
            $result = str_replace('[mulai_berlaku] s/d [berlaku_sampai]', $gantiDengan, $result);
        }

        foreach ($newKodeIsian as $key => $value) {
            if (in_array($key, $kecuali)) {
                continue;
            }
            if (in_array($key, ['[atas_nama]', '[format_nomor_surat]'])) {
                $result = str_replace($key, $value, $result);
            }
            if (preg_match('/pengikut_surat/i', $key)) {
                $result = str_replace($key, $data['pengikut_surat'] ?? '', $result);
            }
            if (preg_match('/pengikut_kartu_kis/i', $key)) {
                $result = str_replace($key, $data['pengikut_kartu_kis'] ?? '', $result);
            }
            if (preg_match('/pengikut_kis/i', $key)) {
                $result = str_replace($key, $data['pengikut_kis'] ?? '', $result);
            }
            if (preg_match('/pengikut_pindah/i', $key)) {
                $result = str_replace($key, $data['pengikut_pindah'] ?? '', $result);
            } else {
                $result = case_replace($key, $value, $result);
            }
        }

        // if (isset($data['pengikut_surat'])) {
        //     log_message('error',"pengikut_surat ". $data['pengikut_surat']);
        //     $result = str_ireplace('[Pengikut_suraT]', $data['pengikut_surat'], $result);
        // }

        return $result;
    }

    /**
     * Daftar penandatangan dan pamongnya
     */
    public function formPenandatangan()
    {
        $atas_nama     = [];
        $config        = identitas();
        $penandatangan = Pamong::penandaTangan()->get();

        // Kepala Desa
        $kades = Pamong::kepalaDesa()->first();
        if ($kades) {
            $atas_nama[''] = $kades->pamong_jabatan . ' ' . $config->nama_desa;

            // Sekretaris Desa
            $sekdes = Pamong::ttd('a.n')->first();
            if ($sekdes) {
                $atas_nama['a.n'] = 'a.n ' . $kades->pamong_jabatan . ' ' . $config->nama_desa;

                // Pamogn selain Kepala Desa dan Sekretaris Desa
                $pamong = Pamong::ttd('u.b')->exists();
                if ($pamong) {
                    $atas_nama['u.b'] = 'u.b ' . $sekdes->pamong_jabatan . ' ' . $config->nama_desa;
                }
            }

            return [
                'penandatangan' => $penandatangan,
                'atas_nama'     => $atas_nama,
            ];
        }
        session_error(', ' . setting('sebutan_kepala_desa') . ' belum ditentukan.');
        redirect('pengurus');
    }

    public function getDaftarLampiran()
    {
        $lampiran               = [];
        $daftar_lampiran_sistem = glob(DEFAULT_LOKASI_LAMPIRAN_SURAT . '*', GLOB_ONLYDIR);
        $daftar_lampiran_desa   = glob(LOKASI_LAMPIRAN_SURAT_DESA . '*', GLOB_ONLYDIR);
        $daftar_lampiran        = array_merge($daftar_lampiran_desa, $daftar_lampiran_sistem);

        foreach ($daftar_lampiran as $value) {
            if (file_exists(FCPATH . $value . '/view.php')) {
                $lampiran[] = kode_format(basename($value));
            }
        }

        return collect($lampiran)->unique()->sort()->values();
    }

    public static function getKodeIsianNonWarga()
    {
        return json_encode([
            [
                'tipe'      => 'text',
                'kode'      => '[form_nama_non_warga]',
                'nama'      => 'Nama Non Warga',
                'deskripsi' => 'Masukkan Nama',
                'atribut'   => 'class="required nama"',
                'statis'    => true,
            ],
            [

                'tipe'      => 'text',
                'kode'      => '[form_nik_non_warga]',
                'nama'      => 'NIK Non Warga',
                'deskripsi' => 'Masukkan NIK',
                'atribut'   => 'class="required nik"',
                'statis'    => true,
            ],
        ]);
    }

    /**
     * Generate surat menggunakan html2pdf, kemudian gabungakan ke pdfMerge.
     *
     * @param string $surat
     * @param array  $margins
     *
     * @return PdfMerge
     */
    public function generateSurat($surat, array $data, $margins)
    {
        (new Html2Pdf($data['surat']['orientasi'], $data['surat']['ukuran'], 'en', true, 'UTF-8', $margins))
            ->setTestTdInOnePage(true)
            ->setDefaultFont(underscore(setting('font_surat'), true, true))
            ->writeHTML($surat) // buat surat
            ->output($out = tempnam(sys_get_temp_dir(), '') . '.pdf', 'F');

        return $this->pdfMerge->add($out);
    }

    /**
     * Generate lampiran menggunakan html2pdf, kemudian gabungakan ke pdfMerge.
     *
     * @param int|string|null $id
     *
     * @return PdfMerge|null
     */
    public function generateLampiran($id = null, array $data = [])
    {
        if (empty($data['surat']['lampiran'])) {
            return;
        }

        $surat         = $data['surat'];
        $input         = $data['input'];
        $config        = $this->ci->header['desa'];
        $individu      = $this->surat_model->get_data_surat($id);
        $penandatangan = $this->surat_model->atas_nama($data);
        $lampiran      = explode(',', strtolower($surat['lampiran']));
        $format_surat  = substitusiNomorSurat($input['nomor'], setting('format_nomor_surat'));
        $format_surat  = str_replace('[kode_surat]', $surat['kode_surat'], $format_surat);
        $format_surat  = str_replace('[kode_desa]', identitas()->kode_desa, $format_surat);
        $format_surat  = str_replace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
        $format_surat  = str_replace('[tahun]', date('Y'), $format_surat);

        if (isset($input['gunakan_format'])) {
            unset($lampiran);

            switch (strtolower($input['gunakan_format'])) {
                case 'f-1.08 (pindah pergi)':
                    $lampiran[] = 'f-1.08';
                    break;

                case 'f-1.23, f-1.25, f-1.29, f-1.34 (sesuai tujuan)':
                    $lampiran[] = 'f-1.25';
                    break;

                case 'f-1.03 (pindah datang)':
                    $lampiran[] = 'f-1.03';
                    break;

                case 'f-1.27, f-1.31, f-1.39 (sesuai tujuan)':
                    $lampiran[] = 'f-1.27';
                    break;

                default:
                    $lampiran[] = null;
                    break;
            }
        }

        for ($i = 0; $i < count($lampiran); $i++) {
            // Cek lampiran desa
            $view_lampiran[$i] = FCPATH . LOKASI_LAMPIRAN_SURAT_DESA . $lampiran[$i] . '/view.php';

            if (! file_exists($view_lampiran[$i])) {
                $view_lampiran[$i] = FCPATH . DEFAULT_LOKASI_LAMPIRAN_SURAT . $lampiran[$i] . '/view.php';
            }

            $data_lampiran[$i] = FCPATH . LOKASI_LAMPIRAN_SURAT_DESA . $lampiran[$i] . '/data.php';
            if (! file_exists($data_lampiran[$i])) {
                $data_lampiran[$i] = FCPATH . DEFAULT_LOKASI_LAMPIRAN_SURAT . $lampiran[$i] . '/data.php';
            }

            // Data lampiran
            include $data_lampiran[$i];
        }

        ob_start();

        for ($j = 0; $j < count($lampiran); $j++) {
            // View Lampiran
            include $view_lampiran[$j];
        }

        $lampiran = ob_get_clean();

        (new Html2Pdf($data['surat']['orientasi'], $data['surat']['ukuran'], 'en', true, 'UTF-8'))
            ->setTestTdInOnePage(true)
            ->setDefaultFont(underscore(setting('font_surat'), true, true))
            ->writeHTML($lampiran) // buat lampiran
            ->output($out = tempnam(sys_get_temp_dir(), '') . '.pdf', 'F');

        return $this->pdfMerge->add($out);
    }

    public function __get($name)
    {
        return $this->ci->{$name};
    }

    public function __call($method, $arguments)
    {
        return $this->ci->{$method}(...$arguments);
    }
}
