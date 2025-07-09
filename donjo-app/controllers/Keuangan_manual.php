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

use App\Models\Keuangan;
use App\Models\KeuanganTemplate;
use App\Traits\Upload;
use F9Web\ApiResponseHelpers;

defined('BASEPATH') || exit('No direct script access allowed');

class Keuangan_manual extends Admin_Controller
{
    use ApiResponseHelpers;
    use Upload;

    public $modul_ini     = 'keuangan';
    public $sub_modul_ini = 'input-data';
    private $tahun;
    private $nama_file;
    private array $data_siskeudes = [
        // realisasi belanja (Debit) dan pendapatan (Kredit)
        'keuangan_ta_jurnal_umum_rinci' => 'Ta_JurnalUmumRinci.csv',
        // realisasi bunga
        // 'keuangan_ta_mutasi'            => 'Ta_Mutasi.csv',
        // anggaran
        'keuangan_ta_rab_rinci' => 'Ta_RABRinci.csv',
        // belanja
        // 'keuangan_ta_spj_rinci'           => 'Ta_SPJRinci.csv',
        // belanja
        // 'keuangan_ta_spp_rinci'           => 'Ta_SPPRinci.csv',
        // pendapatan
        // 'keuangan_ta_tbp_rinci'           => 'Ta_TBPRinci.csv'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'jenis_anggaran' => KeuanganTemplate::jenisAnggaran()->get(),
            'tahun_anggaran' => Keuangan::tahunAnggaran()->get(),
            'filter'         => [
                'jenis' => $this->input->get('jenis_anggaran'),
                'tahun' => $this->input->get('tahun_anggaran') ?? date('Y'),
            ],
        ];

        return view('admin.keuangan.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $query = Keuangan::with('template')
                ->whereRaw('length(template_uuid) <= 5')
                ->when($this->input->get('jenis_anggaran'), static function ($query, $jenis) {
                    $query->where('template_uuid', 'like', "{$jenis}%");
                })
                ->when($this->input->get('tahun_anggaran'), static function ($query, $tahun) {
                    $query->where('tahun', $tahun);
                }, static function ($query) {
                    $query->where('tahun', date('Y'));
                });

                // Contoh query untuk mengambil hanya 2 nested kode rekening
                // ->whereRaw('length(template_uuid) in (1,3,5)')

                // Contoh query untuk mengambil hanya pendapatan (kode rekening 4)
                // ->where('template_uuid', 'like', '4%');

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($item): string {
                    if (can('u')) {
                        $aksi = match (strlen($item->template_uuid)) {
                            5 => '<a href="' . ci_route('keuangan_manual.form', $item->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ',
                            default => '',
                        };
                    }

                    return $aksi;
                })
                ->addColumn('kode_menjorok', static function ($item) {
                    return match (strlen($item->template_uuid)) {
                        1, 3 => $item->template->uuid,
                        5 => "&nbsp&nbsp&nbsp&nbsp{$item->template_uuid}",
                        default => $item->template_uuid,
                    };
                })
                ->addColumn('uraian_menjorok', static function ($item) {
                    return match (strlen($item->template_uuid)) {
                        1 => '<strong>' . strtoupper($item->template->uraian) . '</strong>',
                        3 => "<strong>{$item->template->uraian}</strong>",
                        5 => "&nbsp&nbsp&nbsp&nbsp{$item->template->uraian}",
                        default => $item->template->uraian,
                    };
                })
                ->editColumn('anggaran', static fn ($item) => Rupiah2($item->anggaran))
                ->editColumn('realisasi', static fn ($item) => Rupiah2($item->realisasi))
                ->rawColumns(['aksi', 'kode_menjorok', 'uraian_menjorok'])
                ->skipPaging()
                ->make();
        }

        return show_404();
    }

    public function template()
    {
        $data = $this->validated(request(), [
            'tahun' => 'required|unique:keuangan,tahun',
        ]);

        try {
            KeuanganTemplate::pluck('uuid')
                ->each(static function ($uuid) use ($data) {
                    Keuangan::create([
                        'template_uuid' => $uuid,
                        'tahun'         => $data['tahun'],
                    ]);
                });
        } catch (Exception $e) {
            log_message('error', $e);

            redirect_with('error', 'Tidal berhasil menyalin data');
        }

        redirect_with('success', 'Berhasil menyalin data', "keuangan_manual?tahun_anggaran={$data['tahun']}");
    }

    public function form($id)
    {
        $keuangan = Keuangan::with([
            'template' => [
                'parent.parent',
            ],
        ])->find($id) ?? show_404();

        return view('admin.keuangan.form_update', compact('keuangan'));
    }

    public function update($id)
    {
        $data = $this->validated(request(), [
            'tahun'           => 'required',
            '1_template_uuid' => 'required',
            '2_template_uuid' => 'required',
            '3_template_uuid' => 'required',
            'nilai_anggaran'  => 'required',
            'nilai_realisasi' => 'required',
        ]);
        
        $keuangan = Keuangan::with([
            'template' => function ($query) {
                $query->with(['children' => function ($query) {
                    $query->limit(1);
                }]);
            },
        ])
        ->findOrFail($id);
        
        $keuangan->anggaran  = $data['nilai_anggaran'];
        $keuangan->realisasi = $data['nilai_realisasi'];
        $keuangan->save();
        
        $child = $keuangan->template?->children?->first();
        
        if ($child) {
            // update keuangan child pertama dari template
            $keuangan->where(['tahun' => $keuangan->tahun, 'template_uuid' => $child->uuid])
                ->update([
                    'anggaran'  => $data['nilai_anggaran'],
                    'realisasi' => $data['nilai_anggaran'],
                ]);
        }

        redirect_with('success', 'Berhasil mengubah data', "keuangan_manual?tahun_anggaran={$data['tahun']}");
    }

    public function impor_data(): void
    {
        isCan('b');
        $this->sub_modul_ini = 'impor-data';
        $data['form_action'] = ci_route('keuangan_manual.proses_impor');
        view('admin.keuangan.impor_data', $data);
    }

    private function confirmationForm(): void
    {
        isCan('b');
        $this->sub_modul_ini  = 'impor-data';
        $data['form_action']  = ci_route('keuangan_manual.proses_impor');
        $data['confirmation'] = 1;
        $data['tahun']        = $this->tahun;
        $data['nama_file']    = $this->nama_file;
        view('admin.keuangan.confirmation', $data);
    }

    public function proses_impor(): void
    {
        isCan('u');
        $confirmation = $this->request['confirmation'] ?? null;
        if ($confirmation) {
            $namaFile = $this->request['nama_file'];
            $tahun    = $this->request['tahun'];
            $this->simpanData($namaFile, $tahun);
        } else {
            $this->checkFile();
            $this->confirmationForm();
        }

        redirect_with('success', 'Data berhasil diimpor', ci_route('keuangan_manual') . '?tahun_anggaran=' . $tahun);
    }

    // data tahun anggaran untuk keperluan dropdown pada plugin keuangan di text editor
    public function cek_tahun_manual(): void
    {
        $list_tahun = Keuangan::tahunAnggaran()->get()->map(static function ($item) {
            return [
                'text'  => (string) $item->tahun,
                'value' => (string) $item->tahun,
            ];
        })->toArray();
        echo json_encode($list_tahun, JSON_THROW_ON_ERROR);
    }

    private function simpanData($namaFile, $tahun)
    {
        $data          = $this->extract($namaFile);
        $templateTahun = Keuangan::whereRaw('length(template_uuid) >= 5')->where('tahun', $tahun)->get()->keyBy('template_uuid');
        if ($data) {
            foreach ($data as $key => $items) {
                foreach (collect($items)->groupBy('Kd_Rincian') as $rincian => $item) {
                    $kodeCoa = preg_replace('/\.$/', '', $rincian);
                    if (isset($templateTahun[$kodeCoa])) {
                        $obj = $templateTahun[$kodeCoa];

                        switch($key) {
                            case 'keuangan_ta_rab_rinci':
                                $obj->anggaran = $item->sum('AnggaranStlhPAK');
                                break;

                            case 'keuangan_ta_jurnal_umum_rinci':
                                $obj->realisasi = $item->sum('Debet') + $item->sum('Kredit');
                                break;
                        }
                        $obj->save();
                    }
                }
            }
        }
        $this->deleteAllFiles(LOKASI_KEUANGAN_ZIP);
    }

    private function extract($nama_file)
    {
        $result = [];

        foreach ($this->data_siskeudes as $tabel_opensid => $file_siskeudes) {
            $data_tabel_siskeudes = get_csv(LOKASI_KEUANGAN_ZIP . $nama_file, $file_siskeudes);
            if (! empty($data_tabel_siskeudes)) {
                $result[$tabel_opensid] = $data_tabel_siskeudes;
            }
        }

        return $result;
    }

    private function checkFile(): void
    {
        $nama       = $_FILES['keuangan'];
        $file_parts = pathinfo($nama['name']);
        if ($file_parts['extension'] === 'zip') {
            $config = [
                'upload_path'   => LOKASI_KEUANGAN_ZIP,
                'allowed_types' => 'zip',
                'max_size'      => max_upload() * 1024,
            ];
            if (! file_exists(LOKASI_KEUANGAN_ZIP)) {
                folder(LOKASI_KEUANGAN_ZIP);
            }
            $this->deleteAllFiles(LOKASI_KEUANGAN_ZIP);
            $this->nama_file = $this->upload('keuangan', $config, ci_route('keuangan_manual.impor_data'));
            $zipfile         = $_FILES['keuangan']['tmp_name'];
            $csv_anggaran    = get_csv($zipfile, 'Ta_RAB.csv');

            if ($csv_anggaran !== []) {
                $this->tahun = $csv_anggaran[0]['Tahun'];
                if (! Keuangan::where('tahun', $this->tahun)->exists()) {
                    redirect_with('error', 'Template keuangan tahun ' . $this->tahun . ' tidak ditemukan, tambahkan template terlebih dahulu.', ci_route('keuangan_manual'));
                }
            }

            if (! $this->tahun) {
                redirect_with('error', 'File tidak berisi data siskeudes', ci_route('keuangan_manual.impor_data'));
            }

        } else {
            redirect_with('error', 'File harus dalam format .zip', ci_route('keuangan_manual.impor_data'));
        }
    }

    private function deleteAllFiles($folderPath)
    {
        $files = scandir($folderPath);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $folderPath . '/' . $file;
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }
}
