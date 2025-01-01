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

namespace App\Libraries;

use App\Libraries\TinyMCE\FakeDataIsian;
use App\Libraries\TinyMCE\KodeIsianAnggotaKeluarga;
use App\Libraries\TinyMCE\KodeIsianAritmatika;
use App\Libraries\TinyMCE\KodeIsianForm;
use App\Libraries\TinyMCE\KodeIsianGambar;
use App\Libraries\TinyMCE\KodeIsianIdentitas;
use App\Libraries\TinyMCE\KodeIsianPasangan;
use App\Libraries\TinyMCE\KodeIsianPenandaTangan;
use App\Libraries\TinyMCE\KodeIsianPenduduk;
use App\Libraries\TinyMCE\KodeIsianPendudukLuar;
use App\Libraries\TinyMCE\KodeIsianPeristiwa;
use App\Libraries\TinyMCE\KodeIsianSurat;
use App\Libraries\TinyMCE\KodeIsianWilayah;
use App\Models\AliasKodeIsian;
use App\Models\FormatSurat;
use App\Models\LampiranSurat;
use App\Models\LogPenduduk;
use App\Models\LogSurat;
use App\Models\LogSuratDinas;
use App\Models\Pamong;
use App\Models\SettingAplikasi;
use App\Models\SuratDinas;
use CI_Controller;
use DOMDocument;
use Karriere\PdfMerge\PdfMerge;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

define('K_PATH_FONTS', LOKASI_FONT_DESA);

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
    public const HEADER_DINAS = '
        <TABLE STYLE="BORDER-COLLAPSE: COLLAPSE; WIDTH: 100%;">
        <TBODY>
        <TR>
        <TD STYLE="WIDTH: 10%;">[LOGO]</TD>
        <TD STYLE="TEXT-ALIGN: CENTER; WIDTH: 90%;">
        <P STYLE="MARGIN: 0; TEXT-ALIGN: CENTER;"><SPAN STYLE="FONT-SIZE: 14PT;">PEMERINTAH [SEBUTAN_KABUPATEN] [NAMA_KABUPATEN] <BR />KECAMATAN [NAMA_KECAMATAN]<STRONG><BR />[SEBUTAN_DESA] [NAMA_DESA] </STRONG></SPAN></P>
        <P STYLE="MARGIN: 0; TEXT-ALIGN: CENTER;"><EM><SPAN STYLE="FONT-SIZE: 10PT;">[ALAMAT_DESA]</SPAN></EM></P>
        </TD>
        </TR>
        </TBODY>
        </TABLE>
        <HR STYLE="BORDER: 3PX SOLID;" />
    ';
    public const FOOTER_DINAS = '
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
    public const FOOTER_TTE_DINAS = '
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
    public const TOP          = 4; // cm
    public const BOTTOM       = 2; // cm
    public const DEFAULT_FONT = 'Times New Roman';

    /**
     * @var CI_Controller
     */
    protected $ci;

    /**
     * @var PdfMerge
     */
    public $pdfMerge;

    private $defaultFont;

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

    public function getTemplateDinas()
    {
        $template = [
            [
                'nama'     => 'Header',
                'template' => [
                    'sistem' => static::HEADER_DINAS,
                    'desa'   => setting('header_surat_dinas'),
                ],
            ],

            [
                'nama'     => 'Footer',
                'template' => [
                    'sistem' => static::FOOTER_DINAS,
                    'desa'   => setting('footer_surat_dinas'),
                ],
            ],

            [
                'nama'     => 'Footer TTE',
                'template' => [
                    'sistem' => static::FOOTER_TTE_DINAS,
                    'desa'   => setting('footer_surat_tte_dinas'),
                ],
            ],
        ];

        return collect($template);
    }

    public function getTemplateSurat()
    {
        return collect(FormatSurat::whereNotNull('template')->jenis(FormatSurat::TINYMCE)->get(['nama', 'template', 'template_desa']))
            ->map(static fn ($item, $key): array => [
                'nama'     => 'Surat ' . $item->nama,
                'template' => [
                    'sistem' => $item->template,
                    'desa'   => $item->template_desa,
                ],
            ]);
    }

    public function getTemplateSuratDinas()
    {
        return collect(SuratDinas::whereNotNull('template')->get(['nama', 'template', 'template_desa']))
            ->map(static fn ($item, $key): array => [
                'nama'     => 'Surat ' . $item->nama,
                'template' => [
                    'sistem' => $item->template,
                    'desa'   => $item->template_desa,
                ],
            ]);
    }

    public function getFormatedKodeIsian(array $data = [], $withData = false, $suratDinas = false, $jenis = null)
    {
        $daftar_kode_isian = [];
        $idPenduduk        = $data['id_pend'];
        $judulPenduduk     = $data['surat']->form_isian->individu->judul ?? 'Penduduk';
        $daftarKodeIsian   = grup_kode_isian($data['surat']->kode_isian);
        $daftarKategori    = collect($data['surat']->form_isian)->map(static fn ($item): array => collect($item)->toArray())->toArray();

        $alias = AliasKodeIsian::get();

        $daftar_kode_isian['Alias'] = $alias->map(static fn ($item): array => [
            'judul' => $item->judul,
            'isian' => $item->alias,
            'data'  => $item->content,
        ])->toArray();

        // Surat
        $daftar_kode_isian['Surat'] = KodeIsianSurat::get($data, $jenis);

        // Data Form Surat
        $daftar_kode_isian['Form Surat'] = KodeIsianForm::get($data['input'], null, $data['surat']['masa_berlaku'] > 0);

        // Data Identitas Desa
        $daftar_kode_isian['Identitas Desa'] = KodeIsianIdentitas::get();

        // Data Dusun
        $daftar_kode_isian['Wilayah'] = KodeIsianWilayah::get();

        // Data Penduduk
        if (! $suratDinas) {
            $daftar_kode_isian[$judulPenduduk] = KodeIsianPenduduk::get($idPenduduk);
        }

        // Data Form Penduduk
        $formPenduduk = KodeIsianForm::get($data['input'], $daftarKodeIsian['individu'] ?? []);
        if (count($formPenduduk) > 0) {
            $daftar_kode_isian["Form {$judulPenduduk}"] = $formPenduduk;
        }

        if (! $suratDinas) {
            // Data Anggota keluarga
            $daftar_kode_isian['Anggota Keluarga'] = KodeIsianAnggotaKeluarga::get($idPenduduk);

            // Data Pasangan
            $daftar_kode_isian['Pasangan'] = KodeIsianPasangan::get($idPenduduk);
        }

        // Data Aritmatika untuk penambahan, pengurangan, dan operasi lainnya serta terbilang
        $daftar_kode_isian['Aritmatika'] = KodeIsianAritmatika::get();

        if ($alias->count() <= 0) {
            unset($daftar_kode_isian['Alias']);
        }

        $peristiwa = $data['surat']->form_isian->individu->status_dasar ?? [];
        $peristiwa = is_array($peristiwa) ? $peristiwa : [$peristiwa];
        if (array_intersect($peristiwa, LogPenduduk::PERISTIWA)) {
            $daftar_kode_isian['Peristiwa'] = KodeIsianPeristiwa::get($idPenduduk, $peristiwa);
        }

        foreach ($daftarKategori as $key => $value) {
            if (! $value['sumber']) {
                $value['sumber'] = 1;
            }

            if (! $value['judul'] || ! $value['label']) {
                $judul          = str_replace('_', ' ', ucwords($key));
                $value['judul'] = $judul;
                $value['label'] = $judul;
            }

            $kodeIsianPendudukLuar = KodeIsianPendudukLuar::$kodeIsian;
            if ($key == 'individu') {
                if (! array_intersect(($value['data'] ?? []), [1])) {
                    $daftar_kode_isian[$judulPenduduk] = collect($daftar_kode_isian[$judulPenduduk])->filter(static fn ($item) => in_array($item['isian'], $kodeIsianPendudukLuar))->toArray();
                }

                if (! (is_array($daftarKodeIsian[$key]) && count($daftarKodeIsian[$key]) > 0)) {
                    unset($daftar_kode_isian["Form {$judulPenduduk}"]);
                }
            } else {
                $daftar_kode_isian[$value['judul']] = KodeIsianPenduduk::get($data['input']['id_pend_' . $key], $key);
                $kodeIsianPendudukLuar              = array_map(static fn ($item): string => $item . "_{$key}", $kodeIsianPendudukLuar);
                if (! array_intersect($value['data'] ?? [], [1])) {
                    $daftar_kode_isian[$value['judul']] = collect($daftar_kode_isian[$value['judul']])->filter(static fn ($item) => in_array($item['isian'], $kodeIsianPendudukLuar))->toArray();
                }

                if (is_array($daftarKodeIsian[$key]) && count($daftarKodeIsian[$key]) > 0) {
                    $daftar_kode_isian["Form {$value['judul']}"] = KodeIsianForm::get($data['input'], $daftarKodeIsian[$key] ?? []);
                }
            }
        }

        // Penandatangan
        $daftar_kode_isian['Penandatangan'] = KodeIsianPenandaTangan::get($data['input']);

        $daftar_kode_isian = collect($daftar_kode_isian)->map(static fn ($item) => collect($item)->map(static function (array $item): array {
            $item['isian'] = getFormatIsian($item['isian'], $item['case_sentence']);

            return $item;
        }))->toArray();

        if ($withData) {
            return collect($daftar_kode_isian)
                ->flatten(1)
                ->pluck('data', 'isian.normal')
                ->toArray();
        }

        if (isset($daftar_kode_isian['Alias'])) {
            // Tukar Posisi Alias agar tampil terakhir
            $daftar_kode_isian['Alias'] = array_shift($daftar_kode_isian);
        }

        return $daftar_kode_isian;
    }

    public function formatPdf(string $header, string $footer, string $isi, $preview = false): string
    {
        $isi = $this->escapeSymbols($isi);
        $isi = $this->generateMultiPage($isi);

        $isi          = implode("<div class=\"new-break\" style=\"page-break-after: always;\">\u{a0}</div>", $isi);
        $font_surat   = SettingAplikasi::where(['key' => 'font_surat', 'kategori' => 'format_surat'])->first()->option ?? [];
        $font_surat   = array_map('strtolower', $font_surat);
        $replace_font = array_map(static fn ($item) => underscore(strtolower($item)), $font_surat);
        $isi          = str_replace($font_surat, $replace_font, $isi);

        // Pisahkan isian surat
        $isi           = str_replace('<p><!-- pagebreak --></p>', '<!-- pagebreak -->', $isi);
        $isi           = explode('<!-- pagebreak -->', $isi);
        $tinggi_header = (float) ($this->ci->session->pengaturan_surat['tinggi_header'] ?: setting('tinggi_header')) * 10 . 'mm';
        $tinggi_footer = (float) ($this->ci->session->pengaturan_surat['tinggi_footer'] ?: setting('tinggi_footer')) * 10 . 'mm';

        // Pengaturan Header
        switch ($header) {
            case 0:
                $backtop    = '0mm';
                $isi_header = '<page_header>' . $isi[0] . '</page_header>';
                $isi_surat  = $isi[1];
                break;

            case 1:
                $backtop    = $tinggi_header;
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
                $backbottom = $tinggi_footer;
                $isi_footer = '<page_footer>' . $isi[2] . '</page_footer>';
                break;
        }

        $style = '
            <style>
            .special-symbol {
                font-family: "DejaVuSans", sans-serif;
            }
            </style>
        ';

        $backimg = '';

        if ($preview) {
            $backimg = base_url('assets/images/draft-watermark.png');
        }

        return '
            <page backimg="' . $backimg . '" backtop="' . $backtop . '" backbottom="' . $backbottom . '">
            ' . $style . '
            ' . $isi_header . '
            ' . $isi_surat . '
            ' . $isi_footer . '
            </page>
        ';
    }

    /**
     * Ganti kode isian dengan data yang sesuai.
     *
     * @param array      $data
     * @param bool       $imageReplace
     * @param mixed|null $jenis
     *
     * @return string
     */
    public function gantiKodeIsian($data = [], $imageReplace = true, $jenis = null)
    {
        $result = $data['isi_surat'];

        $gantiDengan  = setting('ganti_data_kosong');
        $newKodeIsian = collect($this->getFormatedKodeIsian($data, true, false, $jenis))
            ->flatMap(static function ($value, $key) {
                if (preg_match('/klg/i', $key)) {
                    return collect(range(1, 10))->map(static fn ($i): array => [
                        'isian' => str_replace('x_', "{$i}_", $key),
                        'data'  => $value[$i - 1] ?? '',
                    ]);
                }

                return [
                    [
                        'isian' => $key,
                        'data'  => $value,
                    ],
                ];
            })
            ->mapWithKeys(static fn ($item): array => [$item['isian'] => $item['data']])
            ->map(static function ($item) use ($gantiDengan) {
                if (null === $item || $item == '/') {
                    return $gantiDengan;
                }

                return $item;
            })
            ->toArray();
        if ((int) $data['surat']['masa_berlaku'] == 0) {
            $result = str_ireplace('[mulai_berlaku] s/d [berlaku_sampai]', $gantiDengan, $result);
        }

        // Kode isian yang berupa alias harus didahulukan
        $alias = KodeIsianPendudukLuar::get($data['surat'], $data['input']);
        if ($alias) {
            $newKodeIsian = array_replace($newKodeIsian, $alias);
        }

        $pisahkanFoto = [];

        foreach ($newKodeIsian as $key => $value) {
            if (in_array(strtolower($key), ['[terbilang]', '[hitung]'])) {
                continue;
            }
            if (preg_match('/(<img src=")(.*?)(">)/', $key)) {
                $pisahkanFoto[$key] = $value;

                continue;
            }
            // coba terapkan yang dari gpt, yaitu tambahkan class/style jika ada simbol yang dicari.
            // TODO:: Cek dari awal pembuatan, kodeisian [format_nomor_surat] tidak mengikuti aturan penulisan, selalu hasilnya huruf besar.
            if (in_array(strtolower($key), ['[format_nomor_surat]'])) {
                $result = str_ireplace($key, strtoupper($value), $result);
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
            }

            if (preg_match('/nip_pamong/i', $key)) {
                if (empty($value) || $value == '-') {
                    $result = str_replace(setting('sebutan_nip_desa') . ' : ', '', $result);
                    $value  = '';
                }
            }

            $result = case_replace($key, $value, $result);
        }

        // Kode isian berupa hitungan perlu didahulukan
        $result = caseHitung($result);
        $result = terjemahkanTerbilang($result);

        // $settingKotak = setting('lampiran_kotak');

        // $result = bungkusKotak($result, json_decode($settingKotak, 1) ?? LampiranSurat::KOTAK);
        if ($imageReplace) {
            foreach ($pisahkanFoto as $key => $value) {
                $result = caseReplaceFoto($result, $key, $value);
            }
        }

        return $result;
    }

    /**
     * Daftar penandatangan dan pamongnya
     *
     * @return array
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
                $atas_nama['a.n'] = 'a.n. ' . $kades->nama_jabatan . ' ' . $config->nama_desa;

                // Pamogn selain Kepala Desa dan Sekretaris Desa
                $pamong = Pamong::ttd('u.b')->exists();
                if ($pamong) {
                    $atas_nama['u.b'] = 'u.b. ' . $sekdes->nama_jabatan . ' ' . $config->nama_desa;
                }
            }

            return [
                'penandatangan' => $penandatangan,
                'atas_nama'     => $atas_nama,
            ];
        }
        set_session('error', setting('sebutan_kepala_desa') . ' belum ditentukan.');
        redirect('pengurus');
    }

    /**
     * Daftar penandatangan dan pamongnya
     *
     * @return array
     */
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

    public function getDaftarLampiranSuratDinas()
    {
        $lampiran               = [];
        $daftar_lampiran_sistem = glob(DEFAULT_LOKASI_LAMPIRAN_SURAT_DINAS . '*', GLOB_ONLYDIR);
        $daftar_lampiran_desa   = glob(LOKASI_LAMPIRAN_SURAT_DINAS_DESA . '*', GLOB_ONLYDIR);
        $daftar_lampiran        = array_merge($daftar_lampiran_desa, $daftar_lampiran_sistem);

        foreach ($daftar_lampiran as $value) {
            if (file_exists(FCPATH . $value . '/view.php')) {
                $lampiran[] = kode_format(basename($value));
            }
        }

        return collect($lampiran)->unique()->sort()->values();
    }

    /**
     * Generate surat menggunakan html2pdf, kemudian gabungakan ke pdfMerge.
     *
     * @param string $surat
     * @param array  $margins
     * @param mixed  $defaultFont
     *
     * @return PdfMerge
     */
    public function generateSurat($surat, array $data, $margins, $defaultFont)
    {
        $surat = str_replace(base_url(), FCPATH, $surat);
        $surat = $this->updateHeightTd($surat);
        $pdf   = (new Html2Pdf($data['surat']['orientasi'], $data['surat']['ukuran'], 'en', true, 'UTF-8', $margins))
            ->setTestTdInOnePage(true)
            ->setDefaultFont($defaultFont);

        $this->cekFontSurat($surat, $pdf->pdf->getFontList());

        $pdf->writeHTML($surat) // buat surat
            ->output($out = tempnam(sys_get_temp_dir(), '') . '.pdf', 'F');

        return $this->pdfMerge->add($out);
    }

    /**
     * Cek font yang digunakan pada surat. Jika font tidak ditemukan, maka tampilkan pesan error.
     *
     * @param string $surat
     * @param array  $listFont
     *
     * @return void
     */
    private function cekFontSurat($surat, $listFont)
    {
        preg_match_all("/font-family:\\s*'([^']+)'/", $surat, $matches);

        // Mengambil semua font-family yang ditemukan
        $fontSurat = [];
        if (! empty($matches[1])) {
            $fontFamilies = $matches[1];
            $fontSurat    = array_unique($fontFamilies);
        }

        // remove font default, misalnya 'arial' karna tidak ada didalam listFont (sudah ada di sistem), tambahkan jika ada penyesuaian
        $fontSurat = array_diff($fontSurat, ['arial']);

        $missingFonts = array_diff($fontSurat, $listFont);
        if (! empty($missingFonts)) {
            $missingFonts = implode(', ', $missingFonts);
            $missingFonts = ucwords(str_replace('_', ' ', $missingFonts));
            redirect_with('error', 'Font ' . $missingFonts . ' pada surat tidak ditemukan, silahkan hubungi administrator.');
        }
    }

    /**
     * Generate lampiran menggunakan html2pdf, kemudian gabungakan ke pdfMerge.
     *
     * @param int|string|null $id
     *
     * @return PdfMerge|null
     */
    public function generateLampiran($id = null, array $data = [], array $input = [])
    {
        if (empty($data['surat']['lampiran'])) {
            return;
        }

        $surat  = $data['surat'];
        $config = identitas();

        // TODO: Cek apakah ini masih digunakan
        $individu = $this->surat_model->get_data_surat($id);
        // Data penandatangan terpilih
        $penandatangan = $this->surat_model->atas_nama($data);

        $lampiran     = $input['lampiran'] ?? explode(',', $data['surat']['lampiran']);
        $format_surat = substitusiNomorSurat($input['nomor'], format_penomoran_surat($surat['format_nomor_global'], setting('format_nomor_surat'), $surat['format_nomor']));
        $format_surat = str_ireplace('[kode_surat]', $surat['kode_surat'], $format_surat);
        $format_surat = str_ireplace('[kode_desa]', $config['kode_desa'], $format_surat);
        $format_surat = str_ireplace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
        $format_surat = str_ireplace('[tahun]', date('Y'), $format_surat);

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

        // exclude lampiran jika lampiran tidak dikaitkan dengan nilai inputan tertentu
        $lampiran = $this->excludeLampiran($surat, $input ?? [], $lampiran ?? []);

        for ($i = 0; $i < count($lampiran); $i++) {
            $lampiran[$i] = strtolower($lampiran[$i]);
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

        if (isset($input) && ! empty($input)) {
            $data['input'] = $input;
        }
        $data['isi_surat'] = $lampiran;
        $lampiran          = $this->gantiKodeIsian($data, false);

        // Replace Gambar menggunakan KodeIsianGambar
        $data_gambar    = KodeIsianGambar::set($data['surat'], $lampiran, $surat, true);
        $lampiran       = $data_gambar['result'];
        $surat->urls_id = $data_gambar['urls_id'];

        (new Html2Pdf($data['surat']['orientasi'], $data['surat']['ukuran'], 'en', true, 'UTF-8'))
            ->setTestTdInOnePage(true)
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

    private function excludeLampiran($surat, array $input, array $lampiran): array
    {
        $kodeIsian       = $surat->kode_isian;
        $includeLampiran = []; // tambahkan lampiran jika memenuhi syarat
        $excludeLampiran = []; // semua lampiran dengan syarat

        foreach ($kodeIsian as $isian) {
            if (! $isian->kaitkan_kode) {
                continue;
            }
            if (empty($isian->kaitkan_kode)) {
                continue;
            }

            foreach ((array) $isian->kaitkan_kode as $kaitkanItem) {
                $kaitkanArr = json_decode($kaitkanItem, true);

                foreach ($kaitkanArr as $kaitkan) {
                    $namaElm = substr('[form_status_kawin_pria]', strlen('[form_'), -1);

                    if ($kaitkan['lampiran_terkait']) {
                        foreach ($kaitkan['lampiran_terkait'] as $value) {
                            $excludeLampiran[] = strtolower($value);
                        }
                    }

                    if (in_array($input[$namaElm], $kaitkan['nilai_isian'])) {
                        if ($kaitkan['lampiran_terkait']) {
                            foreach ($kaitkan['lampiran_terkait'] as $value) {
                                $includeLampiran[] = strtolower($value);
                            }
                        }
                    }
                }
            }
        }
        $lampiranTanpaSyarat = array_diff($lampiran, $excludeLampiran);

        return array_merge($lampiranTanpaSyarat, $includeLampiran);
    }

    public function getPreview($request, $jenis = null)
    {
        return FakeDataIsian::set($request, $jenis);
    }

    public function escapeSymbols($content)
    {
        // Daftar simbol yang ingin diganti
        $symbols = [
            // text
            '©' => '<span class="special-symbol">&copy;</span>',
            'µ' => '<span class="special-symbol">&micro;</span>',
            '·' => '<span class="special-symbol">&middot;</span>',
            '•' => '<span class="special-symbol">&bull;</span>',
            '…' => '<span class="special-symbol">&hellip;</span>',
            '′' => '<span class="special-symbol">&prime;</span>',
            '″' => '<span class="special-symbol">&Prime;</span>',
            // quotation
            '≤' => '<span class="special-symbol">&le;</span>',
            '≥' => '<span class="special-symbol">&ge;</span>',
            '–' => '<span class="special-symbol>&ndash;</span>',
            '—' => '<span class="special-symbol>&mdash;</span>',
            '¡' => '<span class="special-symbol">&iexcl;</span>',
            '¿' => '<span class="special-symbol">&iquest;</span>',
            'ˆ' => '<span class="special-symbol">&circ;</span>',
            '˜' => '<span class="special-symbol">&tilde;</span>',
            '°' => '<span class="special-symbol>&deg;</span>',
            '−' => '<span class="special-symbol">&minus;</span>',
            '±' => '<span class="special-symbol">&plusmn;</span>',
            '÷' => '<span class="special-symbol">&divide;</span>',
            '⁄' => '<span class="special-symbol">&frasl;</span>',
            // mathematic
            '∞' => '<span class="special-symbol">&infin;</span>',
            '√' => '<span class="special-symbol">&radic;</span>',
            '∼' => '<span class="special-symbol">&sim;</span>',
            '≠' => '<span class="special-symbol">&ne;</span>',
            // arrows
            '←' => '<span class="special-symbol">&larr;</span>',
            '↑' => '<span class="special-symbol">&uarr;</span>',
            '→' => '<span class="special-symbol">&rarr;</span>',
            '↓' => '<span class="special-symbol">&darr;</span>',
            '↔' => '<span class="special-symbol">&harr;</span>',
            '↵' => '<span class="special-symbol">&crarr;</span>',
            '⇐' => '<span class="special-symbol">&lArr;</span>',
            '⇑' => '<span class="special-symbol">&uArr;</span>',
            '⇒' => '<span class="special-symbol">&rArr;</span>',
            '⇓' => '<span class="special-symbol">&dArr;</span>',
            '⇔' => '<span class="special-symbol">&hArr;</span>',
            // Tambahkan simbol lain jika diperlukan
        ];

        // Ganti simbol dengan span dan kelas khusus
        return str_replace(array_keys($symbols), array_values($symbols), $content);
    }

    public function generateMultiPage(?string $templateString)
    {
        if (empty($templateString)) {
            return [];
        }
        $pattern = '/<div\s+class="new-break" style="page-break-after:\s*always;">.*<!-- pagebreak -->.*<\/div>/im';

        return preg_split($pattern, $templateString);
    }

    public function cetak_surat($id)
    {
        $this->defaultFont = underscore($this->session->pengaturan_surat['font_surat'] ?? setting('font_surat'));
        $surat             = LogSurat::find($id);
        $this->cetak_surat_tinymce($surat);
    }

    public function cetak_surat_dinas($id)
    {
        $this->defaultFont  = underscore(setting('font_surat_dinas'));
        $surat              = LogSuratDinas::find($id);
        $surat->formatSurat = $surat->suratDinas;
        $this->cetak_surat_tinymce($surat, '_dinas');
    }

    public function cetak_surat_tinymce($surat, $jenis = null)
    {
        // Cek ada file
        if (file_exists(FCPATH . LOKASI_ARSIP . $surat->nama_surat)) {
            return ambilBerkas($surat->nama_surat, $this->controller, null, LOKASI_ARSIP, true);
        }
        $input            = json_decode($surat->input, true) ?? [];
        $isi_cetak        = $surat->isi_surat;
        $nama_surat       = $surat->nama_surat;
        $cetak['surat']   = $surat->formatSurat;
        $cetak['id_pend'] = $surat->id_pend;

        $data_gambar    = KodeIsianGambar::set($cetak['surat'], $isi_cetak, $surat);
        $isi_cetak      = $data_gambar['result'];
        $surat->urls_id = $data_gambar['urls_id'];

        $margin_cm_to_mm = $this->session->has_userdata('pengaturan_surat')
            ? [
                json_decode($this->session->pengaturan_surat['surat_margin'])->kiri * 10,
                json_decode($this->session->pengaturan_surat['surat_margin'])->atas * 10,
                json_decode($this->session->pengaturan_surat['surat_margin'])->kanan * 10,
                json_decode($this->session->pengaturan_surat['surat_margin'])->bawah * 10,
            ]
            : $cetak['surat']['margin_cm_to_mm'];

        if ($cetak['surat']['margin_global'] == '1') {
            $margin_cm_to_mm = setting("surat{$jenis}_margin_cm_to_mm");
        }

        // convert in PDF
        try {
            $this->generateSurat($isi_cetak, $cetak, $margin_cm_to_mm, $this->defaultFont);
            $this->generateLampiran($surat->id_pend, $cetak, $input);

            $this->pdfMerge->merge(FCPATH . LOKASI_ARSIP . $nama_surat, 'FI');
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            log_message('error', $formatter->getHtmlMessage());
        }
    }

    private function updateHeightTd($html)
    {
        // Load the HTML into DOMDocument
        libxml_use_internal_errors(true); // Suppress warnings for malformed HTML
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Find all <td> elements
        $rows = $dom->getElementsByTagName('tr');

        // Loop through each <tr> element
        foreach ($rows as $row) {
            // Get the height from the <tr> style
            $rowStyle = $row->getAttribute('style');
            if (preg_match('/height:\s*(\d+px)/', $rowStyle, $matches)) {
                $heightValue = $matches[1]; // Extract the height value (e.g., "18px")

                // Get all <td> elements within this <tr>
                $cells = $row->getElementsByTagName('td');

                // Loop through each <td> element
                foreach ($cells as $cell) {
                    // Set the height in the style attribute of the <td>
                    $cellStyle = $cell->getAttribute('style');
                    // Update or add the height to the <td> style
                    if (! empty($cellStyle)) {
                        $cellStyle .= ' height: ' . $heightValue . ';';
                    } else {
                        $cellStyle = 'height: ' . $heightValue . ';';
                    }
                    $cell->setAttribute('style', $cellStyle);
                }
            }
        }

        return $dom->saveHTML();
    }
}
