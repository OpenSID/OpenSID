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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\Pamong;
use App\Models\Penduduk;
use CI_Controller;

class TinyMCE
{
    public const HEADER = '
        <table style="border-collapse: collapse; width: 100%;">
        <tbody>
        <tr>
        <td style="width: 10%;">[logo]</td>
        <td style="text-align: center; width: 90%;">
        <p style="margin: 0; text-align: center;"><span style="font-size: 18pt;">PEMERINTAH [SEbutan_kabupaten] [NAma_kabupaten] <br />KECAMATAN [NAma_kecamatan]<strong><br />[SEbutan_desa] [NAma_desa] </strong></span></p>
        <p style="margin: 0; text-align: center;"><em><span style="font-size: 10pt;">[Alamat_desa]</span></em></p>
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
        <td style="width: 10.3939%; height: 10px; text-align: right;">[kode_surat]</td>
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
        <td style="width: 10.3939%; height: 10px; text-align: right;">[kode_surat]</td>
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

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model('keluarga_model');
    }

    public function getTemplate()
    {
        $template = [
            [
                'nama'     => 'Header',
                'template' => static::HEADER,
            ],

            [
                'nama'     => 'Footer',
                'template' => static::FOOTER,
            ],

            [
                'nama'     => 'Footer TTE',
                'template' => static::FOOTER_TTE,
            ],
        ];

        return collect($template);
    }

    public function getTemplateSurat()
    {
        return collect(FormatSurat::whereNotNull('template')->jenis(FormatSurat::TINYMCE)->get(['nama', 'template', 'template_desa']))
            ->map(static function ($item, $key) {
                return [
                    'nama'     => 'Surat ' . $item->nama,
                    'template' => $item->template_desa ?? $item->template,
                ];
            });
    }

    public function getFormatedKodeIsian($data = [], $withData = false)
    {
        $daftar_kode_isian = [
            // Data Surat
            'Surat' => $this->getIsianSurat($data),

            // Data Identitas Desa
            'Identitas Desa' => $this->getIsianIdentitas($data['id_pend']),

            // Data Penduduk Umum
            'Penduduk' => $this->getIsianPenduduk($data['id_pend']),

            // Data Dari Form Isian
            'Input' => $this->getIsianPost($data),

            // Penandatangan
            'Penandatangan' => $this->getPenandatangan($data['input']),
        ];

        if ($withData) {
            $kodeIsian = collect($daftar_kode_isian)
                ->flatten(1)
                ->toArray();

            return array_combine(array_column($kodeIsian, 'isian'), array_column($kodeIsian, 'data'));
        }

        return $daftar_kode_isian;
    }

    private function getIsianSurat($data = [])
    {
        $DateConv = new DateConv();

        return [
            [
                'judul' => 'Format Nomor Surat',
                'isian' => '[format_nomor_surat]',
                'data'  => strtoupper($this->substitusiNomorSurat($data['no_surat'], setting('format_nomor_surat'))),
            ],
            [
                'judul' => 'Kode',
                'isian' => '[kode_surat]',
                'data'  => $data['surat']['kode_surat'],
            ],
            [
                'judul' => 'Nomer',
                'isian' => '[nomer_surat]',
                'data'  => $data['no_surat'],
            ],
            [
                'judul' => 'Judul',
                'isian' => '[judul_surat]',
                'data'  => $data['surat']['judul_surat'],
            ],
            [
                'judul' => 'Tgl',
                'isian' => '[tgl_surat]',
                'data'  => tgl_indo(date('Y m d')),
            ],
            [
                'judul' => 'Tgl Hijri',
                'isian' => '[tgl_surat_hijri]',
                'data'  => $DateConv->HijriDateId('j F Y'),
            ],
            [
                'judul' => 'Tahun',
                'isian' => '[tahun]',
                'data'  => $data['log_surat']['bulan'] ?? date('Y'),
            ],
            [
                'judul' => 'Bulan Romawi',
                'isian' => '[bulan_romawi]',
                'data'  => bulan_romawi((int) ($data['log_surat']['bulan'] ?? date('m'))),
            ],
            [
                'judul' => 'Logo Surat',
                'isian' => '[logo]',
                'data'  => '[logo]',
            ],
            [
                'judul' => 'QRCode',
                'isian' => '[qr_code]',
                'data'  => '[qr_code]',
            ],
            [
                'judul' => 'QRCode BSrE',
                'isian' => '[qr_bsre]',
                'data'  => '[qr_bsre]',
            ],
            [
                'judul' => 'Logo BSrE',
                'isian' => '[logo_bsre]',
                'data'  => '[logo_bsre]',
            ],
        ];
    }

    private function getIsianIdentitas($id_penduduk = null)
    {
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
            $config              = Config::first();
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
                'isian' => '[nama_desa]',
                'data'  => $config->nama_desa,
            ],
            [
                'judul' => 'Kode Desa',
                'isian' => '[kode_desa]',
                'data'  => $config->kode_desa,
            ],
            [
                'judul' => 'Kode POS',
                'isian' => '[kode_pos]',
                'data'  => $config->kode_pos,
            ],
            [
                'judul' => 'Sebutan Desa',
                'isian' => '[sebutan_desa]',
                'data'  => $sebutan_desa,
            ],
            [
                'judul' => 'Sebutan Kepala Desa',
                'isian' => '[sebutan_kepala_desa]',
                'data'  => $sebutan_kepala_desa,
            ],
            [
                'judul' => 'Nama Kepala Desa',
                'isian' => '[nama_kepala_desa]',
                'data'  => $config->pamong_nama,
            ],
            [
                'judul' => 'Sebutan NIP Desa',
                'isian' => '[sebutan_nip_desa]',
                'data'  => $sebutan_nip_desa,
            ],
            [
                'judul' => 'NIP Kepala Desa',
                'isian' => '[nip_kepala_desa]',
                'data'  => $config->pamong_nip,
            ],
            [
                'judul' => 'Nama Kecamatan',
                'isian' => '[nama_kecamatan]',
                'data'  => $config->nama_kecamatan,
            ],
            [
                'judul' => 'Kode Kecamatan',
                'isian' => '[kode_kecamatan]',
                'data'  => $config->kode_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan',
                'isian' => '[sebutan_kecamatan]',
                'data'  => $sebutan_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan (Singkat)',
                'isian' => '[sebutan_kec]',
                'data'  => $sebutan_kec,
            ],
            [
                'judul' => 'Sebutan Camat',
                'isian' => '[sebutan_camat]',
                'data'  => $sebutan_camat,
            ],
            [
                'judul' => 'Nama Kepala Camat',
                'isian' => '[nama_kepala_camat]',
                'data'  => $config->nama_kepala_camat,
            ],
            [
                'judul' => 'NIP Kepala Camat',
                'isian' => '[nip_kepala_camat]',
                'data'  => $config->nip_kepala_camat,
            ],
            [
                'judul' => 'Nama Kabupaten',
                'isian' => '[nama_kabupaten]',
                'data'  => $config->nama_kabupaten,
            ],
            [
                'judul' => 'Kode Kabupaten',
                'isian' => '[kode_kabupaten]',
                'data'  => $config->kode_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten',
                'isian' => '[sebutan_kabupaten]',
                'data'  => $sebutan_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten (Singkat)',
                'isian' => '[sebutan_kab]',
                'data'  => $sebutan_kab,
            ],
            [
                'judul' => 'Nama Provinsi',
                'isian' => '[nama_provinsi]',
                'data'  => $config->nama_propinsi,
            ],
            [
                'judul' => 'Kode Provinsi',
                'isian' => '[kode_provinsi]',
                'data'  => $config->kode_propinsi,
            ],
            [
                'judul' => 'Alamat Desa',
                'isian' => '[alamat_desa]',
                'data'  => $alamat_desa,
            ],
            [
                'judul' => 'Alamat Surat Desa',
                'isian' => '[alamat_surat]',
                'data'  => $alamat_surat,
            ],
            [
                'judul' => 'Alamat Kantor Desa',
                'isian' => '[alamat_kantor]',
                'data'  => $config->alamat_kantor,
            ],
            [
                'judul' => 'Email Desa',
                'isian' => '[email_desa]',
                'data'  => $config->email_desa,
            ],
            [
                'judul' => 'Telepon Desa',
                'isian' => '[telepon_desa]',
                'data'  => $config->telepon,
            ],
            [
                'judul' => 'Website Desa',
                'isian' => '[website_desa]',
                'data'  => $config->website,
            ],
            [
                'judul' => 'Sebutan Dusun',
                'isian' => '[sebutan_dusun]',
                'data'  => $sebutan_dusun,
            ],
        ];
    }

    private function getIsianPenduduk($id_penduduk = null, $prefix = '')
    {
        // Data Umum
        if (! empty($prefix)) {
            $ortu   = ' ' . ucwords($prefix);
            $prefix = '_' . $prefix;
        }

        if ($id_penduduk) {
            $penduduk = Penduduk::with(['keluarga', 'rtm'])->find($id_penduduk);
        }

        $individu = [
            [
                'judul' => 'NIK' . $ortu,
                'isian' => '[nik' . $prefix . ']',
                'data'  => $penduduk->nik,
            ],
            [
                'judul' => 'Nama' . $ortu,
                'isian' => '[nama' . $prefix . ']',
                'data'  => $penduduk->nama,
            ],
            [
                'judul' => 'Tgl Lahir' . $ortu,
                'isian' => '[tanggallahir' . $prefix . ']',
                'data'  => tgl_indo($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Lahir' . $ortu,
                'isian' => '[tempatlahir' . $prefix . ']',
                'data'  => $penduduk->tempatlahir,
            ],
            [
                'judul' => 'Tempat Tgl Lahir' . $ortu,
                'isian' => '[tempat_tgl_lahir' . $prefix . ']',
                'data'  => $penduduk->tempatlahir . '/' . tgl_indo($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Tempat Tgl Lahit (TTL)' . $ortu,
                'isian' => '[ttl' . $prefix . ']',
                'data'  => $penduduk->tempatlahir . '/' . tgl_indo($penduduk->tanggallahir),
            ],
            [
                'judul' => 'Usia' . $ortu,
                'isian' => '[usia' . $prefix . ']',
                'data'  => $penduduk->usia,
            ],
            [
                'judul' => 'Jenis Kelamin' . $ortu,
                'isian' => '[jenis_kelamin' . $prefix . ']',
                'data'  => $penduduk->jenisKelamin->nama,
            ],
            [
                'judul' => 'Agama' . $ortu,
                'isian' => '[agama' . $prefix . ']',
                'data'  => $penduduk->agama->nama,
            ],
            [
                'judul' => 'Pekerjaan' . $ortu,
                'isian' => '[pekerjaan' . $prefix . ']',
                'data'  => $penduduk->pekerjaan->nama,
            ],
            [
                'judul' => 'Warga Negara' . $ortu,
                'isian' => '[warga_negara' . $prefix . ']',
                'data'  => $penduduk->wargaNegara->nama,
            ],
            [
                'judul' => 'Alamat' . $ortu,
                'isian' => '[alamat' . $prefix . ']',
                'data'  => $penduduk->alamat_wilayah,
            ],
        ];

        if (empty($prefix)) {
            $lainnya = [
                [
                    'judul' => 'Alamat Jalan',
                    'isian' => '[alamat_jalan]',
                    'data'  => $penduduk->keluarga->alamat, // alamat kk jika ada
                ],
                [
                    'judul' => 'Alamat Sebelumnya',
                    'isian' => '[alamat_sebelumnya]',
                    'data'  => $penduduk->alamat_sebelumnya,
                ],
                [
                    'judul' => 'Dusun',
                    'isian' => '[dusun]',
                    'data'  => $penduduk->wilayah->dusun,
                ],
                [
                    'judul' => 'RW',
                    'isian' => '[rw]',
                    'data'  => $penduduk->wilayah->rw,
                ],
                [
                    'judul' => 'RT',
                    'isian' => '[rt]',
                    'data'  => $penduduk->wilayah->rt,
                ],
                [
                    'judul' => 'Akta Kelahiran',
                    'isian' => '[akta_lahir]',
                    'data'  => $penduduk->akta_lahir, // Cek ini
                ],
                [
                    'judul' => 'Akta Perceraian',
                    'isian' => '[akta_perceraian]',
                    'data'  => $penduduk->akta_perceraian, // Cek ini
                ],
                [
                    'judul' => 'Status Perkawinan',
                    'isian' => '[status_kawin]',
                    'data'  => $penduduk->statusKawin->nama, // Cek ini
                ],
                [
                    'judul' => 'Akta Perkawinan',
                    'isian' => '[akta_perkawinan]',
                    'data'  => $penduduk->akta_perkawinan, // Cek ini
                ],
                [
                    'judul' => 'Tgl Perkawinan',
                    'isian' => '[tanggalperkawinan]',
                    'data'  => tgl_indo($penduduk->tanggalperkawinan),
                ],
                [
                    'judul' => 'Tgl Perceraian',
                    'isian' => '[tanggalperceraian]',
                    'data'  => tgl_indo($penduduk->tanggalperceraian),
                ],
                [
                    'judul' => 'Cacat',
                    'isian' => '[cacat]',
                    'data'  => $penduduk->cacat->nama,
                ],
                [
                    'judul' => 'Golongan Darah',
                    'isian' => '[gol_darah]',
                    'data'  => $penduduk->golonganDarah->nama,
                ],
                [
                    'judul' => 'Pendidikan Sedang',
                    'isian' => '[pendidikan_sedang]',
                    'data'  => $penduduk->pendidikan->nama,
                ],
                [
                    'judul' => 'Pendidikan Dalam KK',
                    'isian' => '[pendidikan_kk]',
                    'data'  => $penduduk->pendidikanKK->nama,
                ],
                [
                    'judul' => 'Dokumen Pasport',
                    'isian' => '[dokumen_pasport]',
                    'data'  => $penduduk->dokumen_pasport,
                ],
                [
                    'judul' => 'Tgl Akhir Paspor',
                    'isian' => '[tanggal_akhir_paspor]',
                    'data'  => tgl_indo($penduduk->tanggal_akhir_paspor),
                ],

                // Data KK
                [
                    'judul' => 'Hubungan Dalam KK',
                    'isian' => '[hubungan_kk]',
                    'data'  => $penduduk->pendudukHubungan->nama,
                ],
                [
                    'judul' => 'No KK',
                    'isian' => '[no_kk]',
                    'data'  => $penduduk->keluarga->no_kk,
                ],
                [
                    'judul' => 'Kepala KK',
                    'isian' => '[kepala_kk]',
                    'data'  => $penduduk->keluarga->kepalaKeluarga->nama,
                ],
                [
                    'judul' => 'NIK KK',
                    'isian' => '[nik_kepala_kk]',
                    'data'  => $penduduk->keluarga->kepalaKeluarga->nik,
                ],

                // Data RTM
                [
                    'judul' => 'ID BDT',
                    'isian' => '[bdt]',
                    'data'  => $penduduk->rtm->bdt,
                ],

                // Anggota Keluarga
                [
                    'judul' => 'Anggota Keluarga',
                    'isian' => '[anggota_keluarga]',
                    'data'  => $this->generateAnggotaKeluarga(
                        $this->ci->keluarga_model->list_anggota($penduduk->id_kk, ['dengan_kk' => true], true)
                    ),
                ],
            ];

            // Data Umum
            $data = array_merge($individu, $lainnya);

            // Data Orang Tua
            if ($penduduk->id_kk && $penduduk->kk_level != 4) {
                $data_ortu = [
                    [
                        'judul' => 'NIK Ayah',
                        'isian' => '[nik_ayah]',
                        'data'  => $penduduk->ayah_nik,
                    ],
                    [
                        'judul' => 'Nama Ayah',
                        'isian' => '[nama_ayah]',
                        'data'  => $penduduk->nama_ayah,
                    ],
                    [
                        'judul' => 'NIK Ibu',
                        'isian' => '[nik_ibu]',
                        'data'  => $penduduk->ibu_nik,
                    ],
                    [
                        'judul' => 'Nama Ibu',
                        'isian' => '[nama_ibu]',
                        'data'  => $penduduk->nama_ibu,
                    ],
                ];

                return array_merge($data, $data_ortu);
            }
            // Data Ayah
            $data = array_merge($data, $this->getIsianPenduduk($penduduk->id, 'ayah'));

            // Data Ibu
            return array_merge($data, $this->getIsianPenduduk($penduduk->id, 'ibu'));
        }

        return $individu;
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
        $postDinamis = collect($data['surat']['kode_isian'])
            ->map(static function ($item, $key) use ($input) {
                return [
                    'judul' => $item->nama,
                    'isian' => $item->kode,
                    'data'  => $input[underscore($item->nama, true, true)],
                ];
            })
            ->toArray();

        return array_merge($postStatis, $postDinamis);
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
                'isian' => '[atas_nama]',
                'data'  => $atas_nama,
            ],
            [
                'judul' => 'Nama Pamong',
                'isian' => '[nama_pamong]',
                'data'  => $nama_pamong,
            ],
            [
                'judul' => 'Jabatan Pamong',
                'isian' => '[jabatan]',
                'data'  => $jabatan,
            ],
            [
                'judul' => 'Sebutan NIP ' . ucwords(setting('sebutan desa')),
                'isian' => '[sebutan_nip_desa]',
                'data'  => $sebutan_nip_desa,
            ],
            [
                'judul' => 'NIP Pamong',
                'isian' => '[nip_pamong]',
                'data'  => $nip,
            ],
            [
                'judul' => 'Sebutan NIP ' . ucwords(setting('sebutan desa')) . ' & NIP Pamong',
                'isian' => '[form_nip_pamong]',
                'data'  => $pamong_nip,
            ],
        ];
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
        $config        = Config::first();
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

    protected function generateAnggotaKeluarga($keluarga)
    {
        $html = '
            <table style="border-collapse: collapse; width: 100.012%;" border="1">
                <tbody>
                    <tr>
                        <td style="width: 3.1401%; text-transform: uppercase; text-align: center; font-size: 9pt"><strong>NO</strong></td>
                        <td style="width: 19.3854%; text-transform: uppercase; text-align: center; font-size: 9pt"><strong>NIK</strong></td>
                        <td style="width: 27.4745%; text-transform: uppercase; text-align: center; font-size: 9pt"><strong>NAMA</strong></td>
                        <td style="width: 16.6667%; text-transform: uppercase; text-align: center; font-size: 9pt"><strong>L/P</strong></td>
                        <td style="width: 16.6667%; text-transform: uppercase; text-align: center; font-size: 9pt"><strong>TEMPAT TANGGAL LAHIR</strong></td>
                        <td style="width: 16.6667%; text-transform: uppercase; text-align: center; font-size: 9pt"><strong>SHDK</strong></td>
                    </tr>
        ';

        $no = 1;

        foreach ($keluarga as $anggota) {
            $no++;

            $html .= "
                <tr>
                    <td style='width: 3.1401%; text-transform: uppercase; font-size: 9pt'>{$no}</td>
                    <td style='width: 19.3854%; text-transform: uppercase; font-size: 9pt'>{$anggota['nik']}</td>
                    <td style='width: 27.4745%; text-transform: uppercase; font-size: 9pt'>{$anggota['nama']}</td>
                    <td style='width: 16.6667%; text-transform: uppercase; font-size: 9pt'>{$anggota['sex']}</td>
                    <td style='width: 16.6667%; text-transform: uppercase; font-size: 9pt'>{$anggota['tempatlahir']}, {$anggota['tanggallahir']}</td>
                    <td style='width: 16.6667%; text-transform: uppercase; font-size: 9pt'>{$anggota['hubungan']}</td>
                </tr>
            ";
        }

        $html .= '
                </tbody>
            </table>
        ';

        return $html;
    }
}
