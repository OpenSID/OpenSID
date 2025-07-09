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

use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Models\FormatSurat;
use App\Models\Keluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class DataSuratPenduduk extends CI_Controller
{
    private LogPenduduk $logpenduduk;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model']);
        $this->logpenduduk = new LogPenduduk();
    }

    public function index(): void
    {
        $id       = $this->input->get('id_penduduk');
        $surat    = FormatSurat::findOrFail($this->input->get('id_surat'));
        $kategori = $this->input->get('kategori');
        $this->dataPenduduk($id, $surat, $kategori);
    }

    private function dataPenduduk(int $id, FormatSurat $surat, string $kategori)
    {
        $data = ['individu' => Penduduk::findOrFail($id), 'anggota' => null, 'kategori' => $kategori];

        if ($kategori === 'individu') {
            $statusDasar = is_array($surat->form_isian->{$kategori}->status_dasar) ? $surat->form_isian->{$kategori}->status_dasar : [$surat->form_isian->{$kategori}->status_dasar];
            if (array_intersect($statusDasar, $this->logpenduduk::PERISTIWA)) {
                $data['logpenduduk'] = $this->logpenduduk;
                $data['peristiwa']   = $this->logpenduduk::with('penduduk')->where('id_pend', $id)->latest()->first();
            }

            if ($surat->form_isian->individu->data_orang_tua) {
                $data['ayah'] = Penduduk::where('nik', $data['individu']->ayah_nik)->first();
                $data['ibu']  = Penduduk::where('nik', $data['individu']->ibu_nik)->first();

                if (! $data['ayah'] && $data['individu']->kk_level == SHDKEnum::ANAK) {
                    $data['ayah'] = Penduduk::where('id_kk', $data['individu']->id_kk)
                        ->where(static function ($query): void {
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                                ->orWhere('kk_level', SHDKEnum::SUAMI);
                        })
                        ->where('sex', JenisKelaminEnum::LAKI_LAKI)
                        ->first();
                }

                if (! $data['ibu'] && $data['individu']->kk_level == SHDKEnum::ANAK) {
                    $data['ibu'] = Penduduk::where('id_kk', $data['individu']->id_kk)
                        ->where(static function ($query): void {
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                                ->orWhere('kk_level', SHDKEnum::ISTRI);
                        })
                        ->where('sex', JenisKelaminEnum::PEREMPUAN)
                        ->first();
                }

                $data['list_dokumen_ayah'] = empty($data['ayah']) ? null : $this->penduduk_model->list_dokumen($data['ayah']->id);
                $data['list_dokumen_ibu']  = empty($data['ibu']) ? null : $this->penduduk_model->list_dokumen($data['ibu']->id);
            }

            if ($surat->form_isian->individu->data_pasangan && in_array($data['individu']->kk_level, [1, 2, 3])) {
                $data['pasangan'] = Penduduk::where('id_kk', $data['individu']->id_kk)
                    ->where(static function ($query): void {
                        $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->orWhere('kk_level', SHDKEnum::ISTRI);
                    })
                    ->where('sex', JenisKelaminEnum::PEREMPUAN)
                    ->first();

                if ($data['individu']->sex == JenisKelaminEnum::PEREMPUAN) {
                    $data['pasangan'] = Penduduk::where('id_kk', $data['individu']->id_kk)
                        ->where(static function ($query): void {
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                                ->orWhere('kk_level', SHDKEnum::SUAMI);
                        })
                        ->where('sex', JenisKelaminEnum::LAKI_LAKI)
                        ->first();
                }
            }

            $data['list_dokumen_pasangan'] = empty($data['pasangan']) ? null : $this->penduduk_model->list_dokumen($data['pasangan']->id);

            $template = $surat->template_desa ?: $surat->template;
            if (preg_match('/\[pengikut_surat\]/i', $template)) {
                $pengikut = $this->pengikutDibawah18Tahun($data);
                if ($pengikut) {
                    $data['pengikut'] = $pengikut;
                }
            }

            if (preg_match('/\[pengikut_kis\]/i', $template)) {
                $pengikut = $this->pengikutSuratKIS($data);
                if ($pengikut) {
                    $data['pengikut_kis'] = $pengikut;
                }
            }

            if (preg_match('/\[pengikut_pindah\]/i', $template)) {
                $pengikut = $this->pengikutPindah($data);
                if ($pengikut) {
                    $data['pengikut_pindah'] = $pengikut;
                }
            }
        }

        $filters = collect($surat->form_isian->{$kategori})->toArray();
        unset($filters['data']);
        $kk_level    = $data['individu']['kk_level'];
        $ada_anggota = in_array(SHDKEnum::KEPALA_KELUARGA, $filters['kk_level'] ?? []) || $kk_level == SHDKEnum::KEPALA_KELUARGA;

        $data['anggota'] = $ada_anggota ? Keluarga::find($data['individu']['id_kk'])->anggota : null;

        $html = view('admin.surat.data_penduduk', $data, [], true);

        $getFormHubung = collect($surat->form_isian)
            ->where('hubungan', $kategori)
            ->toArray();

        $sumber = [
            'status'   => 1,
            'hubungan' => array_keys($getFormHubung),
            'html'     => (string) $html,
            'kategori' => $kategori,
        ];

        $kaitkan = [];

        foreach ($getFormHubung as $key => $value) {
            if ($data['individu']['kk_level'] !== SHDKEnum::ANAK) {
                continue;
            }

            $dataPenduduk = Penduduk::where('id_kk', $data['individu']['id_kk'])
                ->when(! empty($value->sex), static fn ($query) => $query->where('sex', $value->sex))
                ->when(! empty($value->kk_level), static fn ($query) => $query->whereIn('kk_level', $value->kk_level))
                ->when(! empty($value->status_dasar), static fn ($query) => $query->whereIn('status_dasar', $value->status_dasar))
                ->get()
                ->toArray();

            if (count($dataPenduduk) == 1) {
                $pendudukKait = $this->kategoriYangDikaitkan($dataPenduduk[0]['id'], $key);
                $sumber       = array_merge($sumber, $pendudukKait);
            } else {
                $sumber = array_merge($sumber, [
                    "option{$key}"   => '',
                    "html{$key}"     => '',
                    "kategori{$key}" => $key,
                ]);
            }
        }

        // Set the content type to JSON
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array_merge($sumber, $kaitkan), JSON_THROW_ON_ERROR));
    }

    private function pengikutDibawah18Tahun(array $data)
    {
        $pengikut = null;
        $minUmur  = 18;
        $kk_level = $data['individu']['kk_level'];
        if ($kk_level == SHDKEnum::KEPALA_KELUARGA) {
            if (! empty($data['anggota'])) {
                $pengikut = $data['anggota']->filter(static fn ($item): bool => $item->umur < $minUmur);
            }
        } else {
            // cek apakah ada penduduk yang nik_ayah atau nik_ibu = nik pemohon
            $filterColumn = 'ibu_nik';
            if ($data['individu']['jenis_kelamin'] == JenisKelaminEnum::LAKI_LAKI) {
                $filterColumn = 'ayah_nik';
            }
            $anak = Penduduk::where($filterColumn, $data['individu']['nik'])->orderKeluarga()->withoutGlobalScope(App\Scopes\ConfigIdScope::class)->get();
            if ($anak) {
                $pengikut = $anak->filter(static fn ($item): bool => $item->umur < $minUmur);
            }
        }

        return $pengikut;
    }

    private function pengikutSuratKIS(array $data)
    {
        return Penduduk::where(['id_kk' => $data['individu']['id_kk']])->orderKeluarga()->get();
    }

    private function pengikutPindah(array $data)
    {
        return Penduduk::status()->where(['id_kk' => $data['individu']['id_kk']])->orderKeluarga()->get();
    }

    private function kategoriYangDikaitkan($id, $hubunganForm): array
    {
        $data = ['individu' => Penduduk::findOrFail($id), 'anggota' => null, 'kategori' => $hubunganForm];

        $html = view('admin.surat.data_penduduk', $data, [], true);

        $option = '<option value="' . $data['individu']['id'] . '">' . $data['individu']['nama'] . '</option>';

        return [
            "option{$hubunganForm}"   => $option,
            "html{$hubunganForm}"     => (string) $html,
            "kategori{$hubunganForm}" => $hubunganForm,
        ];
    }
}
