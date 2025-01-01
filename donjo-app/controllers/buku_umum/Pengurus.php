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

use App\Enums\AgamaEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\StatusEnum;
use App\Models\Agama;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\PendidikanKK;
use App\Models\Penduduk;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use Modules\Kehadiran\Models\Kehadiran;
use Modules\Kehadiran\Models\KehadiranPengaduan;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengurus extends Admin_Controller
{
    public $modul_ini           = 'buku-administrasi-desa';
    public $sub_modul_ini       = 'administrasi-umum';
    public $akses_modul         = 'pemerintah-desa';
    public $kategori_pengaturan = 'Pemerintah Desa';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['main_content']       = 'admin.pengurus.index';
        $data['subtitle']           = 'Buku ' . ucwords((string) setting('sebutan_pemerintah_desa'));
        $data['selected_nav']       = 'pengurus';
        $data['jabatanSekdes']      = sekdes()->id;
        $data['jabatanKadesSekdes'] = RefJabatan::getKadesSekdes();
        $data['status']             = [Pamong::LOCK => 'Aktif', Pamong::UNLOCK => 'Tidak Aktif'];
        $data['default_status']     = request('status', Pamong::LOCK);

        view('admin.bumindes.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status    = $this->input->get('status') ?? null;
            $kehadiran = $this->input->get('kehadiran') ?? null;

            return datatables()->of(Pamong::urut())
                ->filter(static function ($query) use ($status, $kehadiran): void {
                    $query->when($status, static fn ($q) => $q->where('pamong_status', $status));
                    $query->when($kehadiran, static fn ($q) => $q->where('kehadiran', $kehadiran));
                })
                ->addColumn('drag-handle', static fn (): string => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static fn ($row): string => '<input type="checkbox" name="id_cb[]" value="' . $row->pamong_id . '"/>')
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('pengurus.form', $row->pamong_id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        if ($row->pamong_status == 1) {
                            $aksi .= '<a href="' . ci_route('pengurus.lock', "{$row->pamong_id}/2") . '" class="btn bg-navy btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('pengurus.lock', "{$row->pamong_id}/1") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a> ';
                        }
                        if ($row->kehadiran == 1) {
                            $aksi .= '<a href="' . ci_route('pengurus.kehadiran', "{$row->pamong_id}/0") . '" class="btn bg-aqua btn-sm" title="Non Aktifkan Kehadiran Perangkat"><i class="fa fa-check"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('pengurus.kehadiran', "{$row->pamong_id}/1") . '" class="btn bg-aqua btn-sm" title="Aktifkan Kehadiran Perangkat"><i class="fa fa-ban"></i></a> ';
                        }
                        if ($row->jabatan_id == sekdes()->id) {
                            if ($row->pamong_ttd == 1) {
                                $aksi .= '<a href="' . ci_route('pengurus.ttd', "a.n/{$row->pamong_id}/2") . '" class="btn bg-navy btn-sm" title="Bukan TTD a.n">a.n</a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('pengurus.ttd', "a.n/{$row->pamong_id}/1") . '" class="btn bg-purple btn-sm" title="Jadikan TTD a.n">a.n</a> ';
                            }
                        }
                        if (! in_array($row->jabatan_id, RefJabatan::getKadesSekdes())) {
                            if ($row->pamong_ub == 1) {
                                $aksi .= '<a href="' . ci_route('pengurus.ttd', "u.b/{$row->pamong_id}/2") . '" class="btn bg-navy btn-sm" title="Bukan TTD u.b">u.b</a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('pengurus.ttd', "u.b/{$row->pamong_id}/1") . '" class="btn bg-purple btn-sm" title="Jadikan TTD u.b">u.b</a> ';
                            }
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('pengurus.delete', $row->pamong_id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                // foto ambil dari staff_photo
                ->editColumn('foto', static fn ($row): string => '<img class="penduduk_kecil" src="' . AmbilFoto($row->foto_staff, '', ($row->pamong_sex ?? $row->penduduk->sex)) . '" class="img-circle" alt="Foto Penduduk"/>')
                ->editColumn('identitas', static fn ($row): string => $row->pamong_nama . '<p class="text-blue">NIP: ' . $row->pamong_nip . '<br> NIK: ' . ($row->pamong_nik ?? $row->penduduk->nik) . '<br> Tag ID Card: ' . ($row->pamong_tag_id_card ?? $row->penduduk->tag_id_card) . '</p>')
                ->editColumn('ttl', static fn ($row): string => ($row->pamong_tempatlahir ?? $row->penduduk->tempatlahir) . ', ' . tgl_indo($row->pamong_tanggallahir ?? $row->penduduk->tanggallahir))
                ->editColumn('sex', static fn ($row) => JenisKelaminEnum::valueOf($row->pamong_sex ?? $row->penduduk->sex))
                ->editColumn('agama', static fn ($row) => AgamaEnum::valueOf($row->pamong_agama ?? $row->penduduk->agama_id))
                ->editColumn('pendidikan_kk', static fn ($row) => PendidikanKKEnum::valueOf($row->pamong_pendidikan ?? $row->penduduk->pendidikan_kk_id))
                ->editColumn('pamong_tglsk', static fn ($row) => tgl_indo($row->pamong_tglsk))
                ->editColumn('pamong_tglhenti', static fn ($row) => tgl_indo($row->pamong_tglhenti))
                ->editColumn('jabatan.nama', static fn ($row) => $row->status_pejabat == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $row->jabatan->nama : $row->jabatan->nama)
                ->filterColumn('identitas', static function ($query, $keyword): void {
                    $query->whereRaw('pamong_nama like ?', ["%{$keyword}%"])
                        ->orwhereHas('penduduk', static fn ($q) => $q->whereRaw('nama like ?', ["%{$keyword}%"]));
                })
                ->rawColumns(['drag-handle', 'ceklist', 'aksi', 'foto', 'identitas'])
                ->make();
        }

        return show_404();
    }

    public function form($id = 0)
    {
        isCan('u');
        $id_pend = $this->input->post('id_pend');

        if ($id) {
            $data['aksi']           = 'Ubah';
            $data['pamong']         = Pamong::findOrFail($id);
            $data['pamong']['nama'] = $data['pamong']->getRawOriginal('pamong_nama');
            $data['pamong']         = $data['pamong']->toArray();
            if (! isset($id_pend)) {
                $id_pend = $data['pamong']['id_pend'];
            }
            $imageInfo         = getimagesize(AmbilFoto($data['pamong']['foto_staff'], '', $data['pamong']['sex']));
            $data['imageInfo'] = [
                'width'  => $imageInfo[0],
                'height' => $imageInfo[1],
            ];
            $data['form_action'] = site_url("pengurus/update/{$id}");
        } else {
            $data['aksi']        = 'Tambah';
            $data['pamong']      = null;
            $data['form_action'] = site_url('pengurus/insert');
        }

        $semua_jabatan = RefJabatan::urut()->latest()->pluck('nama', 'id');

        // Cek apakah kades
        $jabatan_kades = kades()->id;
        if (Pamong::where('jabatan_id', $jabatan_kades)->where('pamong_status', 1)->exists() && $data['pamong']['jabatan_id'] != $jabatan_kades) {
            $semua_jabatan = $semua_jabatan->except($jabatan_kades);
        }
        // Cek apakah sekdes
        $jabatan_sekdes = sekdes()->id;
        if (Pamong::where('jabatan_id', $jabatan_sekdes)->where('pamong_status', 1)->exists() && $data['pamong']['jabatan_id'] != $jabatan_sekdes) {
            $semua_jabatan = $semua_jabatan->except($jabatan_sekdes);
        }

        $data['jabatan']       = $semua_jabatan;
        $data['kades_id']      = kades()->id;
        $data['atasan']        = Pamong::listAtasan($id)->get();
        $data['pendidikan_kk'] = PendidikanKK::pluck('nama', 'id');
        $data['agama']         = Agama::pluck('nama', 'id');
        $data['individu']      = empty($id_pend) ? null : Penduduk::findOrFail($id_pend)->toArray();
        $settings              = SettingAplikasi::where('key', 'media_sosial_pemerintah_desa')->first();
        $data['media_sosial']  = collect($settings->option)
            ->filter(static fn ($item): bool => in_array($item['id'], json_decode($settings->value)))
            ->toArray();

        return view('admin.pengurus.form', $data);
    }

    public function insert(): void
    {
        isCan('u');
        $this->set_validasi();
        $this->form_validation->set_rules('pamong_tag_id_card', 'Tag ID Card', 'is_unique[tweb_desa_pamong.pamong_tag_id_card]');

        if ($this->form_validation->run() !== true) {
            session_error(trim(validation_errors()));
            redirect('pengurus/form');
        } else {
            $post = $this->input->post();
            $data = $this->validate($post);

            $data['pamong_tgl_terdaftar'] = date('Y-m-d');

            $pamong     = Pamong::create($data);
            $post['id'] = $pamong->pamong_id;

            $this->foto($post);

            if ($data['jabatan_id'] == kades()->id) {
                $this->ttd('pamong_ttd', $post['id'], 1);
            } else {
                $this->ttd('pamong_ub', $post['id'], 1);
            }

            redirect_with('success', 'Pamong berhasil disimpan');
        }
    }

    public function update($id = 0): void
    {
        isCan('u');
        $this->set_validasi();
        $this->form_validation->set_rules('pamong_tag_id_card', 'Tag ID Card', "is_unique[tweb_desa_pamong.pamong_tag_id_card,pamong_id,{$id}]");

        if ($this->form_validation->run() !== true) {
            session_error(trim(validation_errors()));
            redirect("pengurus/form/{$id}");
        } else {
            $post = $this->input->post();
            $data = $this->validate($post, $id);
            RefJabatan::getKades()->id;
            RefJabatan::getSekdes()->id;

            if (in_array($data['jabatan_id'], RefJabatan::getKadesSekdes())) {
                $data['pamong_ub'] = 0;
            }

            if ($data['jabatan_id'] != RefJabatan::getSekdes()->id) {
                $data['pamong_ttd'] = 0;
            }

            Pamong::findOrFail($id)->update($data);
            $post['id'] = $id;

            $this->foto($post);

            if ($data['jabatan_id'] == kades()->id) {
                $this->ttd('pamong_ttd', $post['id'], 1);
            } else {
                $this->ttd('pamong_ub', $post['id'], 1);
            }

            redirect_with('success', 'Pamong berhasil disimpan');
        }
    }

    private function set_validasi(): void
    {
        $this->form_validation->set_error_delimiters('', '');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (! $id) {
            foreach ($this->request['id_cb'] as $id_cb) {
                if ($this->boleh_hapus($id_cb)) {
                    redirect_with('error', "ID : {$id_cb} tidak dapat dihapus, data sudah tersedia di kehadiran perangkatl, pengaduan kehadiran dan layanan Surat.");
                }
            }
        } elseif ($this->boleh_hapus($id)) {
            redirect_with('error', "ID : {$id} tidak dapat dihapus, data sudah tersedia di kehadiran perangkatl, pengaduan kehadiran dan layanan Surat.");
        }

        if (Pamong::destroy($id ?? $this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    protected function boleh_hapus($id = null)
    {
        $kehadiranPerangkat = Kehadiran::where('pamong_id', $id)->exists();
        $kehadiranPengaduan = KehadiranPengaduan::where('id_pamong', $id)->exists();
        $kehadiranPengaduan = LogSurat::where('id_pamong', $id)->exists();

        return $kehadiranPerangkat || $kehadiranPengaduan || $kehadiranPengaduan;
    }

    protected function validate($post, $id = null)
    {
        $data                       = [];
        $data['id_pend']            = $post['id_pend'];
        $data['pamong_nama']        = null;
        $data['pamong_nip']         = strip_tags((string) $post['pamong_nip']);
        $data['pamong_niap']        = strip_tags((string) $post['pamong_niap']);
        $data['pamong_tag_id_card'] = strip_tags((string) $post['pamong_tag_id_card']) ?: null;
        $data['pamong_pin']         = strip_tags((string) $post['pamong_pin']);
        $data['jabatan_id']         = bilangan($post['jabatan_id']);
        $data['pamong_pangkat']     = strip_tags((string) $post['pamong_pangkat']);
        $data['pamong_status']      = $post['pamong_status'];
        $data['pamong_nosk']        = empty($post['pamong_nosk']) ? '' : strip_tags((string) $post['pamong_nosk']);
        $data['pamong_tglsk']       = empty($post['pamong_tglsk']) ? null : tgl_indo_in($post['pamong_tglsk']);
        $data['pamong_nohenti']     = empty($post['pamong_nohenti']) ? null : strip_tags((string) $post['pamong_nohenti']);
        $data['pamong_tglhenti']    = empty($post['pamong_tglhenti']) ? null : tgl_indo_in($post['pamong_tglhenti']);
        $data['pamong_masajab']     = strip_tags((string) $post['pamong_masajab']) ?: null;
        $data['atasan']             = bilangan($post['atasan']) ?: null;
        $data['bagan_tingkat']      = bilangan($post['bagan_tingkat']) ?: null;
        $data['bagan_offset']       = (int) $post['bagan_offset'] ?: null;
        $data['bagan_layout']       = htmlentities((string) $post['bagan_layout']);
        $data['bagan_warna']        = warna($post['bagan_warna']);
        $data['gelar_depan']        = strip_tags((string) $post['gelar_depan']) ?: null;
        $data['gelar_belakang']     = strip_tags((string) $post['gelar_belakang']) ?: null;
        $data['media_sosial']       = $post['media_sosial'];
        $data['status_pejabat']     = 0;

        if ($data['jabatan_id'] == kades()->id) {
            $data['urut']           = 1;
            $data['status_pejabat'] = $post['status_pejabat'];
        } elseif ($data['jabatan_id'] == sekdes()->id) {
            $data['urut'] = 2;
        } elseif ($id == 0 || $id == null) {
            $data['urut'] = Pamong::select('urut')->max('urut') + 1;
        }

        if (empty($data['id_pend'])) {
            $data['id_pend']             = null;
            $data['pamong_nama']         = strip_tags((string) $post['pamong_nama']);
            $data['pamong_nik']          = strip_tags((string) $post['pamong_nik']) ?: null;
            $data['pamong_tempatlahir']  = strip_tags((string) $post['pamong_tempatlahir']) ?: null;
            $data['pamong_tanggallahir'] = empty($post['pamong_tanggallahir']) ? null : tgl_indo_in($post['pamong_tanggallahir']);
            $data['pamong_sex']          = $post['pamong_sex'] ?: null;
            $data['pamong_pendidikan']   = $post['pamong_pendidikan'] ?: null;
            $data['pamong_agama']        = $post['pamong_agama'] ?: null;
        }

        return $data;
    }

    protected function foto($post)
    {
        $dimensi = $post['lebar'] . 'x' . $post['tinggi'];
        // Penduduk Luar Desa
        $foto = 'pamong_' . time() . '-' . $post['id'] . '-' . random_int(10000, 999999);
        if ($foto = upload_foto_penduduk($foto, $dimensi)) {
            Pamong::where('pamong_id', $post['id'])->update(['foto' => $foto]);
        }
    }

    public function ttd($jenis, $id, $val)
    {
        $pamong = Pamong::find($id);
        RefJabatan::getSekdes()->id;

        if ($jenis == 'a.n') {
            if ($pamong->jabatan_id == sekdes()->id) {
                $output = Pamong::where('jabatan_id', sekdes()->id)->find($id)->update(['pamong_ttd' => $val]);

                // Hanya 1 yang bisa jadi a.n dan harus sekretaris
                if ($output) {
                    Pamong::where('pamong_ttd', 1)->where('pamong_id', '!=', $id)->update(['pamong_ttd' => 0]);
                    // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
                    (new Pamong())->flushQueryCache();
                    redirect_with('success', 'Penandatangan a.n berhasil disimpan');
                }
            } else {
                $pesan = ', Penandatangan a.n harus ' . RefJabatan::whereJenis(RefJabatan::SEKDES)->first(['nama'])->nama;
                redirect_with('error', $pesan);
            }
        }

        if ($jenis == 'u.b') {
            if (! in_array($pamong->jabatan_id, RefJabatan::getKadesSekdes())) {
                $output = Pamong::whereNotIn('jabatan_id', RefJabatan::getKadesSekdes())->find($id)->update(['pamong_ub' => $val]);
                // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
                (new Pamong())->flushQueryCache();
                redirect_with('success', 'Penandatangan u.b berhasil disimpan');
            } else {
                $pesan = ', Penandatangan u.b harus pamong selain ' . RefJabatan::whereJenis(RefJabatan::KADES)->first(['nama'])->nama . ' dan ' . RefJabatan::whereJenis(RefJabatan::SEKDES)->first(['nama'])->nama;
                redirect_with('error', $pesan);
            }
        }

        return $output;
    }

    public function tukar()
    {
        isCan('u');

        $pamong = $this->input->post('data');
        Pamong::setNewOrder($pamong);
        // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        (new Pamong())->flushQueryCache();

        return json(['status' => 1]);
    }

    public function lock($id = 0, $val = 1): void
    {
        isCan('u');

        $pamong        = Pamong::find($id) ?? show_404();
        $jabatan_aktif = Pamong::whereJabatanId($pamong->jabatan_id)->wherePamongStatus(1)->exists();

        // Cek untuk kades atau sekdes apakah sudah ada yang aktif saat mengaktifkan
        if ($val == 1 && $jabatan_aktif && in_array($pamong->jabatan_id, RefJabatan::getKadesSekdes())) {
            redirect_with('error', 'Pamong ' . $pamong->jabatan->nama . ' sudah tersedia, silahakan non-aktifkan terlebih dahulu jika ingin menggantinya.');
        }

        $pamong->update(['pamong_status' => $val]);
        redirect_with('success', 'Status Pamong berhasil disimpan');
    }

    public function kehadiran($id = 0, $val = 1): void
    {
        isCan('u');

        $pamong = Pamong::find($id) ?? show_404();
        $pamong->update(['kehadiran' => $val]);

        redirect_with('success', 'Status Kehadiran Pamong berhasil disimpan');
    }

    public function dialog($aksi = 'cetak')
    {
        $data               = $this->modal_penandatangan();
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('pengurus.daftar', $aksi);

        return view('admin.pengurus.dialog_cetak', $data);
    }

    public function daftar($aksi = 'cetak'): void
    {
        $status    = $this->input->post('status') ?? null;
        $kehadiran = $this->input->post('kehadiran') ?? null;
        $ttd       = $this->modal_penandatangan();

        $query = Pamong::urut()->when($status, static fn ($q) => $q->where('pamong_status', $status))->when($kehadiran, static fn ($q) => $q->where('kehadiran', $kehadiran));

        $paramDatatable = json_decode($this->input->post('params'), 1);
        $ids            = $this->input->post('id_cb') ?? null;

        if ($ids) {
            $query->whereIn('pamong_id', $ids);
        }
        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }
        $data = [
            'main'  => $query->take($paramDatatable['length'])->get(),
            'start' => $paramDatatable['start'],
        ];
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $ttd['pamong_ketahui']->pamong_id])->first()->toArray();

        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=wilayah_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        view('admin.pengurus.cetak', $data);
    }

    public function bagan($ada_bpd = ''): void
    {
        $data['ada_bpd'] = ! empty($ada_bpd);

        $atasan = Pamong::select('atasan', 'pamong_id')
            ->where('atasan', '!=', null)->status()
            ->get()->toArray();

        $data['bagan']['struktur'] = [];

        foreach ($atasan as $pamong) {
            $data['bagan']['struktur'][] = [$pamong['atasan'] => $pamong['pamong_id']];
        }
        $data['bagan']['nodes'] = Pamong::status()->get()->map(static function ($item) {
            $item->jabatan->nama = ($item->status_pejabat == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') : '') . $item->jabatan->nama;

            return $item;
        })->toArray();

        view('admin.pengurus.bagan', $data);
    }

    public function atur_bagan(): void
    {
        isCan('u');
        $data['atasan']      = Pamong::listAtasan()->get()->toArray();
        $data['form_action'] = ci_route('pengurus/update_bagan');

        view('admin.pengurus.ajax_atur_bagan', $data);
    }

    public function update_bagan(): void
    {
        isCan('u');
        $post    = $this->input->post();
        $list_id = $post['list_id'];
        if ($post['atasan']) {
            $data['atasan'] = ($post['atasan'] <= 0) ? null : $post['atasan'];
        }
        if ($post['bagan_tingkat']) {
            $data['bagan_tingkat'] = ($post['bagan_tingkat'] <= 0) ? null : $post['bagan_tingkat'];
        }
        if ($post['bagan_warna']) {
            $data['bagan_warna'] = (warna($post['bagan_warna'] == '#000000')) ? null : warna($post['bagan_warna']);
        }

        Pamong::whereRaw("pamong_id in ({$list_id})")->update($data);
        // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        (new Pamong())->flushQueryCache();
        redirect_with('success', 'Data Berhasil Simpan');
    }

    // Jabatan
    public function jabatan()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(RefJabatan::query()->urut()->latest())
                ->addColumn('ceklist', static function ($row) {
                    if (! can('h')) {
                        return;
                    }
                    if (in_array($row->id, RefJabatan::getKadesSekdes())) {
                        return;
                    }

                    return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('pengurus.jabatanform', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h') && ! in_array($row->id, RefJabatan::getKadesSekdes())) {
                        $aksi .= '<a href="#" data-href="' . ci_route('pengurus.jabatandelete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('admin.jabatan.index', [
            'selected_nav' => 'pengurus',
        ]);
    }

    public function jabatanform($id = '')
    {
        isCan('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = route('buku-umum.pengurus.jabatanUpdate', $id);
            $jabatan     = RefJabatan::find($id) ?? show_404();
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('pengurus.jabataninsert');
            $jabatan     = null;
        }

        $selected_nav = 'pengurus';

        return view('admin.jabatan.form', ['selected_nav' => $selected_nav, 'action' => $action, 'form_action' => $form_action, 'jabatan' => $jabatan]);
    }

    public function jabataninsert(): void
    {
        isCan('u');

        if (RefJabatan::create(static::jabatanValidate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'pengurus/jabatan');
        }
        redirect_with('error', 'Gagal Tambah Data', 'pengurus/jabatan');
    }

    public function jabatanUpdate($id = ''): void
    {
        isCan('u');

        $data = RefJabatan::find($id) ?? show_404();

        if ($data->update(static::jabatanValidate($this->request, $data->id))) {
            redirect_with('success', 'Berhasil Ubah Data', 'pengurus/jabatan');
        }
        redirect_with('error', 'Gagal Ubah Data', 'pengurus/jabatan');
    }

    public function jabatandelete($id = ''): void
    {
        isCan('h');

        $data = RefJabatan::find($id) ?? show_404();
        if (in_array($data->id, RefJabatan::getKadesSekdes())) {
            redirect_with('error', 'Gagal Hapus Data, ' . $data->nama . ' Tidak Boleh Dihapus.', 'pengurus/jabatan');
        }

        if ($data->destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'pengurus/jabatan');
        }

        redirect_with('error', 'Gagal Hapus Data', 'pengurus/jabatan');
    }

    // Hanya filter inputan
    protected static function jabatanValidate($request = [], $id = null)
    {
        return [
            'nama'    => nama_terbatas($request['nama']),
            'tupoksi' => $request['tupoksi'],
        ];
    }

    public function apidaftarpenduduk()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->whereNotIn('id', Pamong::whereNotNull('id_pend')->pluck('id_pend')->toArray())
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => "NIK : {$item->nik} - {$item->nama} - {$item->wilayah->dusun}",
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }
}
