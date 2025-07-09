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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\SasaranEnum;
use App\Imports\BantuanImports;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Kelompok;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

class Program_bantuan extends Admin_Controller
{
    public $modul_ini        = 'bantuan';
    public $akses_modul      = 'program-bantuan';
    private array $_set_page = ['20', '50', '100'];

    public function __construct()
    {
        parent::__construct();
        isCan('b', 'program-bantuan');
        $this->load->model(['program_bantuan_model']);
    }

    public function clear(): void
    {
        $this->session->per_page = $this->_set_page[0];
        $this->session->unset_userdata('sasaran');
        redirect('program_bantuan');
    }

    public function index(): void
    {
        $data['list_sasaran'] = SasaranEnum::all();
        $data['func']         = 'index';
        $data['formatImpor']  = ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-impor-program-bantuan.xlsx'));

        view('admin.program_bantuan.program', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $sasaran    = $this->input->get('sasaran') ?? null;
            $program_id = $this->input->get('program_id') ?? null;

            return datatables()->of(Bantuan::getProgram($program_id)->when($sasaran, static fn ($q) => $q->where('sasaran', $sasaran)))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $openKab = null === $row->config_id ? 'disabled' : '';

                    $aksi = '<a href="' . site_url("peserta_bantuan/detail_clear/{$row->id}") . '" class="btn bg-purple btn-sm" title="Rincian"><i class="fa fa-list"></i></a>';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("program_bantuan/edit/{$row->id}") . '" class="btn bg-orange btn-sm ' . $openKab . '" title="Ubah"><i class="fa fa-edit"></i></a>';
                    }

                    if ($row->peserta_count != 0) {
                        $aksi .= '<a href="' . site_url("program_bantuan/expor/{$row->id}") . '" class="btn bg-navy btn-sm ' . $openKab . '" title="Expor"><i class="fa fa-download"></i></a>';
                    }

                    if (can('h')) {
                        if ($row->peserta_count != 0 || null === $row->config_id) {
                            $aksi .= '<a class="btn bg-maroon btn-sm disabled" title="Hapus"><i class="fa fa-trash-o"></i></a>';
                        } else {
                            $aksi .= '<a href="#" data-href="' . site_url("program_bantuan/hapus/{$row->id}") . '" class="btn bg-maroon btn-sm ' . $openKab . '" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('tampil_tanggal', static fn ($row): string => fTampilTgl($row->sdate, $row->edate))
                ->editColumn('sasaran', static fn ($row): string => SasaranEnum::valueOf($row->sasaran))
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function apipendudukbantuan()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $bantuan  = $this->input->get('bantuan');
            $sasaran  = $this->input->get('sasaran');
            $peserta  = BantuanPeserta::where('program_id', '=', $bantuan)->pluck('peserta');
            $kk_level = Bantuan::where('id', '=', $bantuan)->first()->kk_level;

            switch ($sasaran) {
                case 1:
                    $this->get_pilihan_penduduk($cari, $peserta);
                    break;

                case 2:
                    $this->get_pilihan_kk($cari, $peserta, $kk_level);
                    break;

                case 3:
                    $this->get_pilihan_rtm($cari, $peserta);
                    break;

                case 4:
                    $this->get_pilihan_kelompok($cari, $peserta);
                    break;

                default:
            }
        }

        return show_404();
    }

    private function get_pilihan_penduduk($cari, $peserta)
    {
        $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                });
            })
            ->whereNotIn('nik', $peserta)
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper((string) setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kk($cari, $peserta, $kk_level)
    {
        $kk_level = json_decode((string) $kk_level, true);
        if ($kk_level === null || count($kk_level) == 0) {
            $kk_level = ['1', '2', '3', '4'];
        }

        $penduduk = Penduduk::with('pendudukHubungan')
            ->select(['tweb_penduduk.id', 'tweb_penduduk.nik', 'keluarga_aktif.no_kk', 'tweb_penduduk.kk_level', 'tweb_penduduk.nama', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk_hubungan', static function ($join): void {
                $join->on('tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id');
            })
            ->leftJoin('keluarga_aktif', static function ($join): void {
                $join->on('tweb_penduduk.id_kk', '=', 'keluarga_aktif.id');
            })
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('tweb_penduduk.nik', 'like', "%{$cari}%")
                        ->orWhere('keluarga_aktif.no_kk', 'like', "%{$cari}%")
                        ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
                });
            })
            ->whereIn('tweb_penduduk.kk_level', $kk_level)
            ->whereNotIn('keluarga_aktif.no_kk', $peserta)
            ->orderBy('tweb_penduduk.id_kk')
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'No KK : ' . $item->no_kk . ' - ' . $item->pendudukHubungan->nama . '- NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper((string) setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_rtm($cari, $peserta)
    {
        $penduduk = Penduduk::select(['id', 'id_rtm', 'nama', 'id_cluster'])
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%")
                        ->orWhere('id_rtm', 'like', "%{$cari}%");
                });
            })
            ->whereHas('rtm', static function ($query) use ($peserta): void {
                $query->whereNotIn('no_kk', $peserta);
            })
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->rtm->no_kk,
                    'text' => 'No. RT : ' . $item->rtm->no_kk . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper((string) setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kelompok($cari, $peserta)
    {
        $penduduk = Kelompok::select(['kelompok.id', 'tweb_penduduk.nik', 'tweb_penduduk.nama as nama_penduduk', 'kelompok.nama as nama_kelompok', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk', static function ($join): void {
                $join->on('kelompok.id_ketua', '=', 'tweb_penduduk.id');
            })
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('kelompok.nama', 'like', "%{$cari}%")
                        ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
                });
            })
            ->whereNotIn('kelompok.id', $peserta)
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => $item->nama_penduduk . ' [' . $item->nama_kelompok . ']' . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper((string) setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    public function panduan(): void
    {
        view('admin.program_bantuan.panduan');
    }

    private function validasi_form(): void
    {
        $this->form_validation->set_rules('cid', 'Sasaran', 'required');
        $this->form_validation->set_rules('nama', 'Nama Program', 'required');
        $this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
        $this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
        $this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
    }

    private function validasi_bantuan(array $post): array
    {
        $kk_level = json_encode($post['kk_level']);
        if ($post['cid'] != 2) {
            $kk_level = null;
        }

        return [
            // Ambil dan bersihkan data input
            'sasaran'  => $post['cid'],
            'nama'     => nomor_surat_keputusan($post['nama']),
            'ndesc'    => htmlentities((string) $post['ndesc']),
            'asaldana' => $post['asaldana'],
            'sdate'    => date('Y-m-d', strtotime((string) $post['sdate'])),
            'edate'    => date('Y-m-d', strtotime((string) $post['edate'])),
            'kk_level' => $kk_level,
        ];
    }

    public function create(): void
    {
        isCan('u', 'program-bantuan');

        $this->validasi_form();

        $data['asaldana'] = unserialize(ASALDANA);
        $data['kk_level'] = DB::table('tweb_penduduk_hubungan')->pluck('nama', 'id')->toArray();

        if ($this->form_validation->run() === false) {
            $data['sasaran'] = SasaranEnum::all();
            view('admin.program_bantuan.create', $data);
        } else {
            $post = $this->input->post();
            $this->insert($post);
        }
    }

    public function insert($post): void
    {
        if (Bantuan::create($this->validasi_bantuan($post))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function edit($id = 0): void
    {
        isCan('u', 'program-bantuan');

        $this->validasi_form();

        $bantuan              = Bantuan::getProgram($id)->first();
        $data['program']      = $bantuan ? $bantuan->toArray() : show_404();
        $data['asaldana']     = unserialize(ASALDANA);
        $data['jml']          = $this->program_bantuan_model->jml_peserta_program($id);
        $data['nama_excerpt'] = Str::limit($data['program']['nama'], 25);
        $data['kk_level']     = DB::table('tweb_penduduk_hubungan')->pluck('nama', 'id')->toArray();
        $data['sasaran']      = SasaranEnum::all();
        if ($this->form_validation->run() === false) {
            view('admin.program_bantuan.edit', $data);
        } else {
            $post = $this->input->post();
            $this->update($post, $id);
        }
    }

    public function update($post, $id): void
    {
        isCan('u', 'program-bantuan');
        if ($id !== 0 && Bantuan::findOrFail($id)->update($this->validasi_bantuan($post))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function hapus($id): void
    {
        isCan('h', 'program-bantuan');
        $bantuan = Bantuan::findOrFail($id);
        if ($bantuan->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function impor(): void
    {
        isCan('u', 'program-bantuan');

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
            'file_name'     => namafile('Impor Peserta Program Bantuan'),
        ]);

        if ($this->upload->do_upload('userfile')) {
            $upload = $this->upload->data();

            $ganti_program      = $this->input->post('ganti_program');
            $kosongkan_peserta  = $this->input->post('kosongkan_peserta');
            $ganti_peserta      = $this->input->post('ganti_peserta');
            $rand_kartu_peserta = $this->input->post('rand_kartu_peserta');

            $result = (new BantuanImports($upload['full_path'], $ganti_program, $kosongkan_peserta, $ganti_peserta, $rand_kartu_peserta))->import();
            if (! $result) {
                redirect_with('error', 'Program Bantuan gagal diimport');
            }
        }

        session_error($this->upload->display_errors());
        redirect($this->controller);
    }

    // TODO: function ini terlalu panjang dan sebaiknya dipecah menjadi beberapa method
    public function expor($program_id = ''): void
    {
        if ($this->program_bantuan_model->jml_peserta_program($program_id) == 0) {
            $this->session->success = -1;
            redirect($this->controller);
        }

        // Data Program Bantuan
        $temp                    = $this->session->per_page;
        $this->session->per_page = 1_000_000_000;
        $data                    = $this->program_bantuan_model->get_program(1, $program_id);
        $tbl_program             = $data[0];
        $tbl_peserta             = $data[1];

        //Nama File
        $fileName = namafile('program_bantuan_' . $tbl_program['nama']) . '.xlsx';
        $writer   = new Writer();
        $writer->openToBrowser($fileName);

        // Sheet Program
        $writer->getCurrentSheet()->setName('Program');
        $data_program = [
            ['id', $tbl_program['id']],
            ['config_id', identitas('id')],
            ['Nama Program', $tbl_program['nama']],
            ['Sasaran Program', $tbl_program['sasaran']],
            ['Keterangan', $tbl_program['ndesc']],
            ['Asal Dana', $tbl_program['asaldana']],
            ['Rentang Waktu (Awal)', $tbl_program['sdate']],
            ['Rentang Waktu (Akhir)', $tbl_program['edate']],
        ];

        foreach ($data_program as $row) {
            $expor_program = [$row[0], $row[1]];
            $rowFromValues = Row::fromValues($expor_program);
            $writer->addRow($rowFromValues);
        }

        // Sheet Peserta
        $writer->addNewSheetAndMakeItCurrent()->setName('Peserta');
        $judul_peserta = ['Peserta', 'No. Peserta', 'NIK', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat'];
        $style         = (new Style())
            ->setFontBold()
            ->setFontSize(12)
            ->setBackgroundColor(Color::YELLOW);

        $header = Row::fromValues($judul_peserta, $style);
        $writer->addRow($header);

        //Isi Tabel
        foreach ($tbl_peserta as $row) {
            $peserta = $row['peserta'];
            // Ubah id menjadi kode untuk data kelompok
            // Berkaitan dgn issue #3417
            // Cari data kelompok berdasarkan id
            if ($tbl_program['sasaran'] == 4) {
                $this->load->model('kelompok_model');
                $kelompok = $this->kelompok_model->get_kelompok($peserta);
                $peserta  = $kelompok['kode'];
            }

            $data_peserta = [
                $peserta,
                $row['no_id_kartu'],
                $row['kartu_nik'],
                $row['kartu_nama'],
                $row['kartu_tempat_lahir'],
                $row['kartu_tanggal_lahir'],
                $row['kartu_alamat'],
            ];
            $rowFromValues = Row::fromValues($data_peserta);
            $writer->addRow($rowFromValues);
        }
        $writer->close();

        $this->session->per_page = $temp;
    }

    /**
     * Unduh kartu peserta berdasarkan kolom program_peserta.kartu_peserta
     *
     * @param int $id_peserta Id peserta program bantuan
     */
    public function unduh_kartu_peserta($id_peserta = 0): void
    {
        // Ambil nama berkas dari database
        $kartu_peserta = $this->db
            ->select('kartu_peserta')
            ->where('id', $id_peserta)
            ->where('config_id', identitas('id'))
            ->get('program_peserta')
            ->row()
            ->kartu_peserta;
        ambilBerkas($kartu_peserta, $this->controller . '/detail/' . $id_peserta, null, LOKASI_DOKUMEN);
    }

    // Hapus peserta bantuan yg sudah dihapus
    // TODO: ubah peserta menggunakan id untuk semua sasaran dan gunakan relasi database delete cascade
    public function bersihkan_data(): void
    {
        isCan('h', 'program-bantuan');

        $invalid      = [];
        $list_sasaran = array_keys($this->referensi_model->list_ref(SASARAN));

        foreach ($list_sasaran as $sasaran) {
            $invalid = Bantuan::peserta_tidak_valid($sasaran);
        }

        $duplikat     = [];
        $list_program = Bantuan::listProgram();

        foreach ($list_program as $program) {
            $duplikat = array_merge($duplikat, Bantuan::peserta_duplikat($program));
        }

        $data['ref_sasaran'] = $this->referensi_model->list_ref(SASARAN);
        $data['invalid']     = $invalid;
        $data['duplikat']    = $duplikat;

        view('admin.program_bantuan.hasil_pembersihan', $data);
    }

    public function bersihkan_data_peserta(): void
    {
        isCan('h', 'program-bantuan');

        BantuanPeserta::whereIn('id', $this->input->post('id_cb'))->delete();

        $this->session->success = 1;

        redirect('program_bantuan/bersihkan_data');
    }

    protected function cek_is_date($cells)
    {
        return $cells->isDate() ? $cells->getValue()->format('Y-m-d') : (string) $cells;
    }
}
