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

use App\Enums\StatusEnum;
use App\Libraries\TinyMCE;
use App\Models\FormatSurat;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FakeDataIsian
{
    private readonly TinyMCE $tinymce;
    private $result;
    private array $data = [];

    public function __construct(private $request, private $jenis = null)
    {
        $this->tinymce = new TinyMCE();
    }

    public static function set($request, $jenis = null)
    {
        return (new self($request, $jenis))->replaceData();
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getData($key = null, $default = null)
    {
        return $key ? data_get($this->data, $key, $default) : $this->data;
    }

    private function replaceData()
    {
        $this->tempate();
        $this->formDinamis();
        $this->formStatis();
        $this->sumberData();
        $this->formPengikut();
        $this->prosesReplace();

        return $this;
    }

    private function tempate(): void
    {
        $setting_header = $this->request['header'] == StatusEnum::TIDAK ? '' : setting("header_surat{$this->jenis}");
        $setting_footer = $this->request['footer'] == StatusEnum::YA ? (setting('tte') == StatusEnum::YA ? setting("footer_surat{$this->jenis}_tte") : setting("footer_surat{$this->jenis}")) : '';
        $this->result   = preg_replace('/\\\\/', '', $setting_header) . '<!-- pagebreak -->' . ($this->request['template_desa']) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', $setting_footer);
    }

    private function sumberData(): void
    {
        $form_isian = json_decode((string) $this->request['form_isian'], true);

        if ($form_isian) {
            $pendudukLuar = json_decode(SettingAplikasi::where('key', 'form_penduduk_luar')->first()->value ?? [], true);

            foreach ($form_isian as $key => $value) {
                if ($value) {
                    if (in_array(1, ($value['data'] ?? []))) {
                        $this->data['input']['id_pend_' . $key] = Penduduk::filters([
                            'sex'          => $value['sex'],
                            'status_dasar' => $value['status_dasar'],
                            'kk_level'     => $value['kk_level'],
                        ])->orderBy(DB::raw('RAND()'))->first('id')->id;

                        if (! $this->data['input']['id_pend_' . $key]) {
                            redirect_with('error', 'Tidak ditemukan penduduk untuk dijadikan contoh');
                        }

                        // untuk individu ganti jadi $this->data['id_pend']
                        // TODO:: Sederhanakan cara ini
                        if ($key == 'individu') {
                            $this->data['id_pend'] = $this->data['input']['id_pend_' . $key];
                        }
                    } else {
                        // tidak ada pilihan penduduk desa
                        $pendudukLuarTerpilih = $pendudukLuar[array_rand($pendudukLuar)];
                        $formInputPenduduk    = explode(',', (string) $pendudukLuarTerpilih['input']);

                        foreach ($formInputPenduduk as $input) {
                            $input                             = $input === 'no_ktp' ? 'nik' : $input;
                            $this->data['input'][$key][$input] = 'Masukkan ' . $input . ' ' . $key;
                        }
                        $this->data['input'][$key]['opsi_penduduk'] = 2;
                    }
                } else {
                    // TODO: Perbarui ini mengikuti cara baru
                    $this->data['nik_non_warga']  = random_int(1_000_000_000_000_000, 9_999_999_999_999_999);
                    $this->data['nama_non_warga'] = 'Nama Non Warga';
                }
            }
        }
    }

    private function formDinamis(): void
    {
        $kode_isian = json_decode((string) $this->request['kode_isian'], true);

        foreach ($kode_isian as $value) {
            $tanggal = date('d-m-Y');

            switch($value['tipe']) {
                case 'select-manual':
                    $pilihan     = $value['pilihan'];
                    $nilai_isian = $pilihan[array_rand($pilihan)];
                    break;

                case 'select-otomatis':
                    $pilihan     = ref($value['refrensi']);
                    $nilai_isian = $pilihan[array_rand($pilihan)]->nama;
                    break;

                case 'date':
                case 'hari':
                case 'hari-tanggal':
                    $nilai_isian = $tanggal;
                    break;

                case 'number':
                    $min_value = (int) Str::between($value['atribut'], 'min="', '"');
                    $max_value = (int) Str::between($value['atribut'], 'max="', '"');

                    if ($min_value > $max_value) {
                        $temp      = $min_value;
                        $min_value = $max_value;
                        $max_value = $temp;
                    }

                    $nilai_isian = random_int($min_value, $max_value);
                    break;

                default:
                    if (preg_match('/hari/i', (string) $value['atribut'])) {
                        $nilai_isian = hari($tanggal);
                    } elseif (preg_match('/rupiah/i', (string) $value['atribut'])) {
                        $nilai_isian = 'Rp. ' . number_format(random_int(100, 9999) . '000', 0, ',', '.');
                    } else {
                        $nilai_isian = $value['deskripsi'] ?? $value['nama'];
                    }
            }

            $this->data['input'][str_replace(['[form_', ']'], '', $value['kode'])] = $nilai_isian;
        }
    }

    private function formStatis(): void
    {
        if ((int) $this->request['masa_berlaku'] > 0) {
            $tanggal_akhir = Carbon::now();

            switch ($this->request['satuan_masa_berlaku']) {
                case 'd':
                    $tanggal_akhir->addDays($this->request['masa_berlaku']);
                    break;

                case 'w':
                    $tanggal_akhir->addWeeks($this->request['masa_berlaku']);
                    break;

                case 'M':
                    $tanggal_akhir->addMonths($this->request['masa_berlaku']);
                    break;

                case 'y':
                    $tanggal_akhir->addYears($this->request['masa_berlaku']);
                    break;

                default:
                    // Do nothing for an unknown unit
                    break;
            }

            $this->data['input']['mulai_berlaku']  = formatTanggal(Carbon::now());
            $this->data['input']['berlaku_sampai'] = formatTanggal($tanggal_akhir);
        }
    }

    private function formPengikut(): void
    {
        // Pengikut Pindah
        if (preg_match('/pengikut_pindah/i', (string) $this->request['template_desa'])) {
            $pengikutPindah                = Penduduk::with('pendudukHubungan')->orderBy(DB::raw('RAND()'))->take(3)->get();
            $this->data['pengikut_pindah'] = generatePengikutPindah($pengikutPindah);
        }

        $pengikut_1    = Penduduk::where('id', $this->data['id_pend'])->get();
        $pengikut_kis  = generatePengikutSuratKIS($pengikut_1);
        $pengikut_2[0] = [
            'kartu'        => random_int(1_000_000_000_000_000, 9_999_999_999_999_999),
            'nama'         => $pengikut_1[0]->nama . ' A.',
            'nik'          => substr($pengikut_1[0]->nik, 0, 15) . '1',
            'alamat'       => 'INI ALAMAT YANG BENAR',
            'tanggallahir' => date('d-m-Y', strtotime($pengikut_1[0]->tanggallahir . ' + 1 month')),
            'faskes'       => 'RSUD',
        ];
        $pengikut_kartu_kis = generatePengikutKartuKIS($pengikut_2);

        $this->data['pengikut_kis']       = $pengikut_kis;
        $this->data['pengikut_kartu_kis'] = $pengikut_kartu_kis;
        $this->data['pengikut_surat']     = $pengikut_surat ?? null; // belum digunakan
    }

    private function terakhirReplace(): void
    {
        $data_gambar  = KodeIsianGambar::set($this->request, $this->result);
        $this->result = $data_gambar['result'];
    }

    private function prosesReplace(): void
    {
        // Pengingat : form_isian disamakan formatnya menggunakan object
        $this->data['surat']     = new FormatSurat($this->request);
        $this->data['isi_surat'] = $this->result;
        $this->result            = $this->tinymce->gantiKodeIsian($this->data, false, $this->jenis);

        $this->terakhirReplace();
    }
}
