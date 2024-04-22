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

namespace App\Libraries;

use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusHubunganEnum;
use App\Libraries\TinyMCE\KodeIsianPeristiwa;
use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\Keluarga;
use App\Models\LogPenduduk;
use App\Models\Pamong;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Carbon\Carbon;
use CI_Controller;
use Karriere\PdfMerge\PdfMerge;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

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
            'Surat' => $this->getIsianSurat($data),

            // Data Identitas Desa
            'Identitas Desa' => $this->getIsianIdentitas($idPenduduk ?? $data['nik_non_warga']),

            // Data Dusun
            'Wilayah' => $this->getIsianWilayah(),

            // Data Penduduk Umum
            'Penduduk' => $this->getIsianPenduduk($idPenduduk),

            // Data Anggota keluarga
            'Anggota Keluarga' => $this->getIsianAnggotaKeluarga($idPenduduk),

            // Data Pasangan
            'Pasangan' => $this->getIsianPasangan($idPenduduk),
        ];

        $peristiwa = $data['surat']->form_isian->individu->status_dasar;
        if (in_array($peristiwa, LogPenduduk::PERISTIWA)) {
            $daftar_kode_isian['Peristiwa'] = KodeIsianPeristiwa::get($idPenduduk, $peristiwa);
        }

        // Penduduk Kategori
        foreach ($data['kategori'] as $key => $value) {
            $daftar_kode_isian[$key] = $this->getIsianPenduduk($value, $key);
        }

        // Data Dari Form Isian dsd
        $isian_post = $this->getIsianPost($data);
        if (isset($isian_post['kategori'])) {
            foreach ($isian_post['kategori'] as $key => $value) {
                $nama_ktg = $value['nama_kategori'];
                unset($value['nama_kategori']);
                $daftar_kode_isian['Input ' . $nama_ktg][] = $value;
                // unset($daftar_kode_isian['Input ' . $value['nama_kategori']]['nama_kategori']);
            }
            // $daftar_kode_isian['Input Kategori'] = $isian_post['kategori'];
            unset($isian_post['kategori']);
        }
        $daftar_kode_isian['Input'] = $isian_post;

        // Penandatangan
        $daftar_kode_isian['Penandatangan'] = $this->getPenandatangan($data['input']);

        // Jika penduduk luar, hilangkan isian penduduk
        if ($data['surat']['form_isian']->data == 2) {
            unset($daftar_kode_isian['Penduduk'], $daftar_kode_isian['Anggota Keluarga']);
        }

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

    private function getIsianSurat($data = [])
    {
        $DateConv = new DateConv();

        return [
            [
                'judul' => 'Format Nomor Surat',
                'isian' => getFormatIsian('Format_nomor_suraT'),
                'data'  => strtoupper($this->substitusiNomorSurat($data['no_surat'], ($data['surat']['format_nomor'] == '') ? setting('format_nomor_surat') : $data['surat']['format_nomor'])),
            ],
            [
                'judul' => 'Kode',
                'isian' => getFormatIsian('Kode_suraT'),
                'data'  => $data['surat']['kode_surat'],
            ],
            [
                'judul' => 'Nomer',
                'isian' => getFormatIsian('Nomer_suraT'),
                'data'  => $data['no_surat'],
            ],
            [
                'judul' => 'Judul',
                'isian' => getFormatIsian('Judul_suraT'),
                'data'  => $data['surat']['judul_surat'],
            ],
            [
                'judul' => 'Tanggal',
                'isian' => getFormatIsian('Tgl_suraT'),
                'data'  => tgl_indo(date('Y m d')),
            ],
            [
                'judul' => 'Tanggal Hijri',
                'isian' => getFormatIsian('Tgl_surat_hijrI'),
                'data'  => $DateConv->HijriDateId('j F Y'),
            ],
            [
                'judul' => 'Tahun',
                'isian' => getFormatIsian('TahuN'),
                'data'  => $data['log_surat']['bulan'] ?? date('Y'),
            ],
            [
                'judul' => 'Bulan Romawi',
                'isian' => getFormatIsian('Bulan_romawI'),
                'data'  => bulan_romawi((int) ($data['log_surat']['bulan'] ?? date('m'))),
            ],
            [
                'judul' => 'Logo Surat',
                'isian' => getFormatIsian('logo'),
                'data'  => '[logo]',
            ],
            [
                'judul' => 'QRCode',
                'isian' => getFormatIsian('qr_code'),
                'data'  => '[qr_code]',
            ],
            [
                'judul' => 'QRCode BSrE',
                'isian' => getFormatIsian('qr_bsre'),
                'data'  => '[qr_bsre]',
            ],
            [
                'judul' => 'Logo BSrE',
                'isian' => getFormatIsian('logo_bsre'),
                'data'  => '[logo_bsre]',
            ],
        ];
    }

    private function getIsianIdentitas($id_penduduk = null)
    {
        $config              = null;
        $sebutan_dusun       = null;
        $sebutan_desa        = null;
        $sebutan_kecamatan   = null;
        $sebutan_kec         = null;
        $sebutan_kabupaten   = null;
        $sebutan_kab         = null;
        $sebutan_camat       = null;
        $sebutan_kepala_desa = null;
        $sebutan_nip_desa    = null;

        if ($id_penduduk) {
            $config              = identitas();
            $sebutan_dusun       = setting('sebutan_dusun');
            $sebutan_desa        = setting('sebutan_desa');
            $sebutan_kecamatan   = setting('sebutan_kecamatan');
            $sebutan_kec         = setting('sebutan_kecamatan_singkat');
            $sebutan_kabupaten   = setting('sebutan_kabupaten');
            $sebutan_kab         = setting('sebutan_kabupaten_singkat');
            $sebutan_kepala_desa = setting('sebutan_kepala_desa');
            $sebutan_camat       = setting('sebutan_camat');

            if (! empty($config->email_desa)) {
                $alamat_desa  = "{$config->alamat_kantor} Email: {$config->email_desa} Kode Pos: {$config->kode_pos}";
                $alamat_surat = "{$config->alamat_kantor} Telp. {$config->telepon} Kode Pos: {$config->kode_pos} <br> Website: {$config->website} Email: {$config->email_desa}";
            } else {
                $alamat_desa  = "{$config->alamat_kantor} Kode Pos: {$config->kode_pos}";
                $alamat_surat = "{$config->alamat_kantor} Telp. {$config->telepon} Kode Pos: {$config->kode_pos}";
            }

            if (null === $config->pamong()->pamong_nip && (! empty($config->pamong()->pamong_niap))) {
                $sebutan_nip_desa = setting('sebutan_nip_desa');
            } else {
                $sebutan_nip_desa = 'NIP';
            }
        }

        return [
            [
                'judul' => 'Nama Desa',
                'isian' => getFormatIsian('Nama_desA'),
                'data'  => $config->nama_desa,
            ],
            [
                'judul' => 'Kode Desa',
                'isian' => getFormatIsian('Kode_desA'),
                'data'  => $config->kode_desa,
            ],
            [
                'judul' => 'Kode POS',
                'isian' => getFormatIsian('Kode_poS'),
                'data'  => $config->kode_pos,
            ],
            [
                'judul' => 'Sebutan Desa',
                'isian' => getFormatIsian('Sebutan_desA'),
                'data'  => $sebutan_desa,
            ],
            [
                'judul' => 'Sebutan Kepala Desa',
                'isian' => getFormatIsian('Sebutan_kepala_desA'),
                'data'  => $sebutan_kepala_desa,
            ],
            [
                'judul' => 'Nama Kepala Desa',
                'isian' => getFormatIsian('Nama_kepala_desA'),
                'data'  => $config->pamong_nama,
            ],
            [
                'judul' => 'Sebutan NIP Desa',
                'isian' => getFormatIsian('Sebutan_nip_desA'),
                'data'  => $sebutan_nip_desa,
            ],
            [
                'judul' => 'NIP Kepala Desa',
                'isian' => getFormatIsian('Nip_kepala_desA'),
                'data'  => $config->pamong_nip,
            ],
            [
                'judul' => 'Nama Kecamatan',
                'isian' => getFormatIsian('Nama_kecamataN'),
                'data'  => $config->nama_kecamatan,
            ],
            [
                'judul' => 'Kode Kecamatan',
                'isian' => getFormatIsian('Kode_kecamataN'),
                'data'  => $config->kode_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan',
                'isian' => getFormatIsian('Sebutan_kecamataN'),
                'data'  => $sebutan_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan (Singkat)',
                'isian' => getFormatIsian('Sebutan_keC'),
                'data'  => $sebutan_kec,
            ],
            [
                'judul' => 'Sebutan Camat',
                'isian' => getFormatIsian('Sebutan_camaT'),
                'data'  => $sebutan_camat,
            ],
            [
                'judul' => 'Nama Kepala Camat',
                'isian' => getFormatIsian('Nama_kepala_camaT'),
                'data'  => $config->nama_kepala_camat,
            ],
            [
                'judul' => 'NIP Kepala Camat',
                'isian' => getFormatIsian('Nip_kepala_camaT'),
                'data'  => $config->nip_kepala_camat,
            ],
            [
                'judul' => 'Nama Kabupaten',
                'isian' => getFormatIsian('Nama_kabupateN'),
                'data'  => $config->nama_kabupaten,
            ],
            [
                'judul' => 'Kode Kabupaten',
                'isian' => getFormatIsian('Kode_kabupateN'),
                'data'  => $config->kode_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten',
                'isian' => getFormatIsian('Sebutan_kabupateN'),
                'data'  => $sebutan_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten (Singkat)',
                'isian' => getFormatIsian('Sebutan_kaB'),
                'data'  => $sebutan_kab,
            ],
            [
                'judul' => 'Nama Provinsi',
                'isian' => getFormatIsian('Nama_provinsI'),
                'data'  => $config->nama_propinsi,
            ],
            [
                'judul' => 'Kode Provinsi',
                'isian' => getFormatIsian('Kode_provinsI'),
                'data'  => $config->kode_propinsi,
            ],
            [
                'judul' => 'Alamat Desa',
                'isian' => getFormatIsian('Alamat_desA'),
                'data'  => $alamat_desa,
            ],
            [
                'judul' => 'Alamat Surat Desa',
                'isian' => getFormatIsian('Alamat_suraT'),
                'data'  => $alamat_surat,
            ],
            [
                'judul' => 'Alamat Kantor Desa',
                'isian' => getFormatIsian('Alamat_kantor'),
                'data'  => $config->alamat_kantor,
            ],
            [
                'judul' => 'Email Desa',
                'isian' => getFormatIsian('Email_desA'),
                'data'  => $config->email_desa,
            ],
            [
                'judul' => 'Telepon Desa',
                'isian' => getFormatIsian('Telepon_desA'),
                'data'  => $config->telepon,
            ],
            [
                'judul' => 'Website Desa',
                'isian' => getFormatIsian('Website_desA'),
                'data'  => $config->website,
            ],
            [
                'judul' => 'Sebutan Dusun',
                'isian' => getFormatIsian('Sebutan_dusuN'),
                'data'  => $sebutan_dusun,
            ],
        ];
    }

    private function getIsianPenduduk($id_penduduk = null, $prefix = '')
    {
        $ortu     = null;
        $penduduk = null;
        // Data Umum
        if (! empty($prefix)) {
            $ortu   = ' ' . ucwords($prefix);
            $prefix = '_' . uclast($prefix);
        }

        if ($id_penduduk) {
            $penduduk = Penduduk::with(['keluarga', 'rtm'])->find($id_penduduk);
        }

        $individu = [
            [
                'judul' => 'NIK' . $ortu,
                'isian' => getFormatIsian('nik' . $prefix),
                'data'  => $penduduk->nik,
            ],
            [
                'judul' => 'Nama' . $ortu,
                'isian' => getFormatIsian('Nama' . $prefix),
                'data'  => $penduduk->nama,
            ],
            [
                'judul' => 'Tanggal Lahir' . $ortu,
                'isian' => getFormatIsian('Tanggallahir' . $prefix),
                'data'  => tgl_indo($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Lahir' . $ortu,
                'isian' => getFormatIsian('Tempatlahir' . $prefix),
                'data'  => $penduduk->tempatlahir,
            ],
            [
                'judul' => 'Tempat Tanggal Lahir' . $ortu,
                'isian' => getFormatIsian('Tempat_tgl_lahir' . $prefix),
                'data'  => ($penduduk->tempatlahir . '/' . tgl_indo($penduduk->tanggallahir)),
            ],
            [
                'judul' => 'Tempat Tanggal Lahir (TTL)' . $ortu,
                'isian' => getFormatIsian('Ttl' . $prefix),
                'data'  => ($penduduk->tempatlahir . '/' . tgl_indo($penduduk->tanggallahir)),
            ],
            [
                'judul' => 'Usia' . $ortu,
                'isian' => getFormatIsian('Usia' . $prefix),
                'data'  => $penduduk->usia,
            ],
            [
                'judul' => 'Jenis Kelamin' . $ortu,
                'isian' => getFormatIsian('Jenis_kelamin' . $prefix),
                'data'  => $penduduk->jenisKelamin->nama,
            ],
            [
                'judul' => 'Agama' . $ortu,
                'isian' => getFormatIsian('Agama' . $prefix),
                'data'  => $penduduk->agama->nama,
            ],
            [
                'judul' => 'Pekerjaan' . $ortu,
                'isian' => getFormatIsian('Pekerjaan' . $prefix),
                'data'  => $penduduk->pekerjaan->nama,
            ],
            [
                'judul' => 'Warga Negara' . $ortu,
                'isian' => getFormatIsian('Warga_negara' . $prefix),
                'data'  => $penduduk->wargaNegara->nama,
            ],
            [
                'judul' => 'Alamat' . $ortu,
                'isian' => getFormatIsian('Alamat' . $prefix),
                'data'  => $penduduk->alamat_wilayah,
            ],
            [
                'judul' => 'No KK' . $ortu,
                'isian' => getFormatIsian('No_kK' . $prefix),
                'data'  => $penduduk->keluarga->no_kk,
            ],
            [
                'judul' => 'Golongan Darah' . $ortu,
                'isian' => getFormatIsian('Gol_daraH' . $prefix),
                'data'  => $penduduk->golonganDarah->nama,
            ],
        ];

        if (empty($prefix)) {
            $lainnya = [
                [
                    'judul' => 'Foto',
                    'isian' => getFormatIsian('foto_penduduK'),
                    'data'  => '[foto_penduduk]',
                ],
                [
                    'judul' => 'Alamat Jalan',
                    'isian' => getFormatIsian('Alamat_jalan'),
                    'data'  => $penduduk->keluarga->alamat, // alamat kk jika ada
                ],
                [
                    'judul' => 'Alamat Sebelumnya',
                    'isian' => getFormatIsian('Alamat_sebelumnya'),
                    'data'  => $penduduk->alamat_sebelumnya,
                ],
                [
                    'judul' => 'Dusun',
                    'isian' => getFormatIsian('Nama_dusuN'),
                    'data'  => $penduduk->wilayah->dusun,
                ],
                [
                    'judul' => 'RW',
                    'isian' => getFormatIsian('Nama_rW'),
                    'data'  => $penduduk->wilayah->rw,
                ],
                [
                    'judul' => 'RT',
                    'isian' => getFormatIsian('Nama_rT'),
                    'data'  => $penduduk->wilayah->rt,
                ],
                [
                    'judul' => 'Akta Kelahiran',
                    'isian' => getFormatIsian('Akta_lahiR'),
                    'data'  => $penduduk->akta_lahir, // Cek ini
                ],
                [
                    'judul' => 'Akta Perceraian',
                    'isian' => getFormatIsian('Akta_perceraiaN'),
                    'data'  => $penduduk->akta_perceraian, // Cek ini
                ],
                [
                    'judul' => 'Status Perkawinan',
                    'isian' => getFormatIsian('Status_kawiN'),
                    'data'  => $penduduk->statusKawin->nama, // Cek ini
                ],
                [
                    'judul' => 'Akta Perkawinan',
                    'isian' => getFormatIsian('Akta_perkawinaN'),
                    'data'  => $penduduk->akta_perkawinan, // Cek ini
                ],
                [
                    'judul' => 'Tanggal Perkawinan',
                    'isian' => getFormatIsian('TanggalperkawinaN'),
                    'data'  => tgl_indo($penduduk->tanggalperkawinan),
                ],
                [
                    'judul' => 'Tanggal Perceraian',
                    'isian' => getFormatIsian('TanggalperceraiaN'),
                    'data'  => tgl_indo($penduduk->tanggalperceraian),
                ],
                [
                    'judul' => 'Cacat',
                    'isian' => getFormatIsian('CacaT'),
                    'data'  => $penduduk->cacat->nama,
                ],
                [
                    'judul' => 'Pendidikan Sedang',
                    'isian' => getFormatIsian('Pendidikan_sedanG'),
                    'data'  => $penduduk->pendidikan->nama,
                ],
                [
                    'judul' => 'Pendidikan Dalam KK',
                    'isian' => getFormatIsian('Pendidikan_kK'),
                    'data'  => $penduduk->pendidikanKK->nama,
                ],
                [
                    'judul' => 'Dokumen Pasport',
                    'isian' => getFormatIsian('Dokumen_pasporT'),
                    'data'  => $penduduk->dokumen_pasport,
                ],
                [
                    'judul' => 'Tanggal Akhir Paspor',
                    'isian' => getFormatIsian('Tanggal_akhir_paspoR'),
                    'data'  => tgl_indo($penduduk->tanggal_akhir_paspor),
                ],

                // Data KK
                [
                    'judul' => 'Hubungan Dalam KK',
                    'isian' => getFormatIsian('Hubungan_kK'),
                    'data'  => $penduduk->pendudukHubungan->nama,
                ],
                [
                    'judul' => 'No KK',
                    'isian' => getFormatIsian('No_kK'),
                    'data'  => $penduduk->keluarga->no_kk,
                ],
                [
                    'judul' => 'Kepala KK',
                    'isian' => getFormatIsian('Kepala_kK'),
                    'data'  => $penduduk->keluarga->kepalaKeluarga->nama,
                ],
                [
                    'judul' => 'NIK KK',
                    'isian' => getFormatIsian('Nik_kepala_kK'),
                    'data'  => $penduduk->keluarga->kepalaKeluarga->nik,
                ],

                // Data RTM
                [
                    'judul' => 'ID BDT',
                    'isian' => getFormatIsian('Id_bdT'),
                    'data'  => $penduduk->rtm->bdt,
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

            if ($id_ayah || empty($penduduk)) {
                // Data Ayah
                $data = array_merge($data, $this->getIsianPenduduk($id_ayah, 'ayah'));
            } else {
                $data_ortu = [
                    [
                        'judul' => 'Nama Ayah',
                        'isian' => getFormatIsian('Nama_ayaH'),
                        'data'  => $penduduk->nama_ayah,
                    ],
                    [
                        'judul' => 'NIK Ayah',
                        'isian' => getFormatIsian('nik_ayah'),
                        'data'  => $penduduk->ayah_nik,
                    ],
                ];
                $data = array_merge($data, $data_ortu);
            }

            if ($id_ibu || empty($penduduk)) {
                // Data Ibu
                $data = array_merge($data, $this->getIsianPenduduk($id_ibu, 'ibu'));
            } else {
                $data_ortu = [
                    [
                        'judul' => 'Nama Ibu',
                        'isian' => getFormatIsian('Nama_ibU'),
                        'data'  => $penduduk->nama_ibu,
                    ],
                    [
                        'judul' => 'NIK Ibu',
                        'isian' => getFormatIsian('nik_ibu'),
                        'data'  => $penduduk->ibu_nik,
                    ],
                ];
                $data = array_merge($data, $data_ortu);
            }

            return $data;
        }

        return $individu;
    }

    private function getIsianWilayah()
    {
        $wilayah = Wilayah::with('kepala')->dusun()->get();

        $data = [];

        foreach ($wilayah as $wil) {
            $data[] = [
                'judul' => ucwords(strtolower(setting('sebutan_dusun') . ' ' . $wil->dusun)),
                'isian' => getFormatIsian(ucwords(strtolower(setting('sebutan_dusun') . '_' . $wil->dusun))),
                'data'  => $wil->dusun,
            ];
            $data[] = [
                'judul' => ucwords(strtolower('Kepala ' . setting('sebutan_dusun') . ' ' . $wil->dusun)),
                'isian' => getFormatIsian(ucwords(strtolower('Kepala_' . setting('sebutan_dusun') . '_' . $wil->dusun))),
                'data'  => $wil->kepala->nama,
            ];
        }

        return $data;
    }

    private function getIsianAnggotaKeluarga($id_penduduk = null)
    {
        $id_kk   = Penduduk::where('kk_level', SHDKEnum::KEPALA_KELUARGA)->find($id_penduduk)->id_kk;
        $anggota = Keluarga::find($id_kk)->anggota;

        return [
            [
                'judul' => 'Urutan',
                'isian' => getFormatIsian('Klgx_nO'),
                'data'  => $anggota ? $anggota->pluck('id')
                    ->map(static fn ($item, $key) => $key + 1)
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'NIK',
                'isian' => getFormatIsian('Klgx_niK'),
                'data'  => $anggota ? $anggota->pluck('nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama',
                'isian' => getFormatIsian('Klgx_namA'),
                'data'  => $anggota ? $anggota->pluck('nama')->toArray() : '',
            ],
            [
                'judul' => 'Jenis Kelamin',
                'isian' => getFormatIsian('Klgx_jenis_kelamiN'),
                'data'  => $anggota ? $anggota->pluck('jenisKelamin.nama')->toArray() : '',
            ],
            [
                'judul' => 'Tempat Lahir',
                'isian' => getFormatIsian('Klgx_tempatlahiR'),
                'data'  => $anggota ? $anggota->pluck('tempatlahir')->toArray() : '',
            ],
            [
                'judul' => 'Tgl Lahir',
                'isian' => getFormatIsian('Klgx_tanggallahiR'),
                'data'  => $anggota ? $anggota->pluck('tanggallahir')
                    ->map(static fn ($item) => tgl_indo($item))
                    ->toArray() : '',
            ],
            [
                'judul' => 'Tempat Tgl Lahir',
                'isian' => getFormatIsian('Klgx_tempat_tgl_lahiR'),
                'data'  => $anggota ? $anggota->pluck('tempatlahir', 'tanggallahir')
                    ->map(static fn ($item, $key) => $item . ', ' . tgl_indo($key))
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'Tempat Tgl Lahir (TTL)',
                'isian' => getFormatIsian('Klgx_ttL'),
                'data'  => $anggota ? $anggota->pluck('tempatlahir', 'tanggallahir')
                    ->map(static fn ($item, $key) => $item . ', ' . tgl_indo($key))
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'Usia',
                'isian' => getFormatIsian('Klgx_usiA'),
                'data'  => $anggota ? $anggota->pluck('usia')->toArray() : '',
            ],
            [
                'judul' => 'Agama',
                'isian' => getFormatIsian('Klgx_agamA'),
                'data'  => $anggota ? $anggota->pluck('agama.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pendidikan Sedang',
                'isian' => getFormatIsian('Klgx_pendidikan_sedanG'),
                'data'  => $anggota ? $anggota->pluck('pendidikan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pendidikan Dalam KK',
                'isian' => getFormatIsian('Klgx_pendidikan_kK'),
                'data'  => $anggota ? $anggota->pluck('pendidikanKk.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pekerjaan',
                'isian' => getFormatIsian('Klgx_pekerjaaN'),
                'data'  => $anggota ? $anggota->pluck('pekerjaan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Status Perkawinan',
                'isian' => getFormatIsian('Klgx_status_kawiN'),
                'data'  => $anggota ? $anggota->pluck('statusKawin.nama')->toArray() : '',
            ],
            [
                'judul' => 'Hubungan Dalam KK',
                'isian' => getFormatIsian('Klgx_hubungan_kK'),
                'data'  => $anggota ? $anggota->pluck('pendudukHubungan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Warga Negara',
                'isian' => getFormatIsian('Klgx_warga_negarA'),
                'data'  => $anggota ? $anggota->pluck('warganegara.nama')->toArray() : '',
            ],
            [
                'judul' => 'Alamat',
                'isian' => getFormatIsian('Klgx_alamat'),
                'data'  => $anggota ? $anggota->pluck('alamat_wilayah')->toArray() : '',
            ],
            [
                'judul' => 'Golongan Darah',
                'isian' => getFormatIsian('Klgx_golongan_darah'),
                'data'  => $anggota ? $anggota->pluck('golonganDarah.nama')->toArray() : '',
            ],
            [
                'judul' => 'Dokumen Pasport',
                'isian' => getFormatIsian('Klgx_dokumen_pasporT'),
                'data'  => $anggota ? $anggota->pluck('dokumen_pasport')->toArray() : '',
            ],
            [
                'judul' => 'Tgl Akhir Paspor',
                'isian' => getFormatIsian('Klgx_tanggal_akhir_paspoR'),
                'data'  => $anggota ? $anggota->pluck('tanggal_akhir_paspor')
                    ->map(static fn ($item) => tgl_indo($item))
                    ->toArray() : '',
            ],
            [
                'judul' => 'NIK Ayah',
                'isian' => getFormatIsian('Klgx_nik_ayaH'),
                'data'  => $anggota ? $anggota->pluck('ayah_nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama Ayah',
                'isian' => getFormatIsian('Klgx_nama_ayaH'),
                'data'  => $anggota ? $anggota->pluck('nama_ayah')->toArray() : '',
            ],
            [
                'judul' => 'NIK Ibu',
                'isian' => getFormatIsian('Klgx_nik_ibU'),
                'data'  => $anggota ? $anggota->pluck('ibu_nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama Ibu',
                'isian' => getFormatIsian('Klgx_nama_ibU'),
                'data'  => $anggota ? $anggota->pluck('nama_ibu')->toArray() : '',
            ],
        ];
    }

    private function getIsianPasangan($id_penduduk = null)
    {
        $penduduk = Penduduk::find($id_penduduk);

        return [
            [
                'judul' => 'Jenis Kelamin Pasangan',
                'isian' => getFormatIsian('Jenis_kelamin_pasangaN'),
                'data'  => $penduduk->sex == JenisKelaminEnum::LAKI_LAKI ? 'Wanita' : 'Pria',
            ],
            [
                'judul' => 'Bin Pasangan',
                'isian' => getFormatIsian('Bin_pasangaN'),
                'data'  => $penduduk->sex == JenisKelaminEnum::LAKI_LAKI ? 'Binti' : 'Bin',
            ],
        ];
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
                        'isian' => getFormatIsian(str_replace(['[', ']'], '', $item['kode'])),
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
                    'isian' => getFormatIsian(str_replace(['[', ']'], '', $item->kode)),
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
                    'nama_kategori' => $item->kategori,
                    'judul'         => $item->nama,
                    'isian'         => getFormatIsian(str_replace(['[', ']'], '', $item->kode)),
                    'data'          => ($item->tipe == 'date') ? tgl_indo(Carbon::parse($data)->format('Y-m-d')) : $data,
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

    public function getPenandatangan($input = [])
    {
        $nama_desa = Config::select(['nama_desa'])->first()->nama_desa;

        //Data penandatangan
        $kades = Pamong::kepalaDesa()->first();

        $ttd         = $input['pilih_atas_nama'];
        $atas_nama   = $kades->pamong_jabatan . ' ' . $nama_desa;
        $jabatan     = $kades->pamong_jabatan;
        $nama_pamong = $kades->pamong_nama;
        $nip_pamong  = $kades->pamong_nip;
        $niap_pamong = $kades->pamong_niap;

        $sekdes = Pamong::ttd('a.n')->first();
        if (preg_match('/a.n/i', $ttd)) {
            $atas_nama   = 'a.n ' . $atas_nama . ' <br> ' . $sekdes->pamong_jabatan;
            $jabatan     = $sekdes->pamong_jabatan;
            $nama_pamong = $sekdes->pamong_nama;
            $nip_pamong  = $sekdes->pamong_nip;
            $niap_pamong = $sekdes->pamong_niap;
        }

        if (preg_match('/u.b/i', $ttd)) {
            $pamong      = Pamong::ttd('u.b')->find($input['pamong_id']);
            $atas_nama   = 'a.n ' . $atas_nama . ' <br> ' . $sekdes->pamong_jabatan . '<br> u.b <br>' . $pamong->jabatan->nama;
            $jabatan     = $pamong->pamong_jabatan;
            $nama_pamong = $pamong->pamong_nama;
            $nip_pamong  = $pamong->pamong_nip;
            $niap_pamong = $pamong->pamong_niap;
        }

        if (strlen($nip_pamong) > 10) {
            $sebutan_nip_desa = 'NIP';
            $nip              = $nip_pamong;
            $pamong_nip       = $sebutan_nip_desa . ' : ' . $nip;
        } else {
            $sebutan_nip_desa = setting('sebutan_nip_desa');
            if (! empty($niap_pamong)) {
                $nip        = $niap_pamong;
                $pamong_nip = $sebutan_nip_desa . ' : ' . $niap_pamong;
            } else {
                $pamong_nip = '';
            }
        }

        return [
            [
                'judul' => 'Atas Nama',
                'isian' => getFormatIsian('Atas_namA'),
                'data'  => $atas_nama,
            ],
            [
                'judul' => 'Nama Pamong',
                'isian' => getFormatIsian('Nama_pamonG'),
                'data'  => $nama_pamong,
            ],
            [
                'judul' => 'Jabatan Pamong',
                'isian' => getFormatIsian('JabataN'),
                'data'  => $jabatan,
            ],
            [
                'judul' => 'Sebutan NIP ' . ucwords(setting('sebutan desa')),
                'isian' => getFormatIsian('Sebutan_nip_desA'),
                'data'  => $sebutan_nip_desa,
            ],
            [
                'judul' => 'NIP Pamong',
                'isian' => getFormatIsian('Nip_pamonG'),
                'data'  => $nip,
            ],
            [
                'judul' => 'Sebutan NIP ' . ucwords(setting('sebutan desa')) . ' & NIP Pamong',
                'isian' => getFormatIsian('Form_nip_pamonG'),
                'data'  => $pamong_nip,
            ],
        ];
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
     * Kode isian nomor_surat bisa ditentukan panjangnya, diisi dengan '0' di sebelah kiri
     * Misalnya [nomor_surat, 3] akan menghasilkan seperti '012'
     *
     * @param mixed|null $nomor
     * @param mixed      $format
     */
    public function substitusiNomorSurat($nomor = null, $format = '')
    {
        // TODO : Cek jika null, cari no surat terakhir berdasarkan kelompok
        $format = str_replace('[nomor_surat]', "{$nomor}", $format);
        if (preg_match_all('/\[nomor_surat,\s*\d+\]/', $format, $matches)) {
            foreach ($matches[0] as $match) {
                $parts         = explode(',', $match);
                $panjang       = (int) trim(rtrim($parts[1], ']'));
                $nomor_panjang = str_pad("{$nomor}", $panjang, '0', STR_PAD_LEFT);
                $format        = str_replace($match, $nomor_panjang, $format);
            }
        }

        return $format;
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
        $format_surat  = $this->substitusiNomorSurat($input['nomor'], setting('format_nomor_surat'));
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
