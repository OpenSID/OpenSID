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
use App\Enums\AsuransiEnum;
use App\Enums\BahasaEnum;
use App\Enums\CacatEnum;
use App\Enums\CaraKBEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\HamilEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKTPEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\SukuEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\BantuanPeserta;
use App\Models\Keluarga as KeluargaModel;
use App\Models\Penduduk;
use App\Models\PendudukHidup;
use App\Models\PendudukHubungan;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class AnggotaKeluarga extends Admin_Controller
{
    public $modul_ini     = 'kependudukan';
    public $sub_modul_ini = 'keluarga';
    public $akses_modul   = 'keluarga';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($id): void
    {
        $data['kk'] = $id;

        $kk            = KeluargaModel::with(['anggota', 'kepalaKeluarga'])->find($id) ?? show_404();
        $data['no_kk'] = $kk->no_kk;
        $data['main']  = $kk->anggota->map(static function ($item) use ($kk) {
            $item->bisaPecahKK = false;
            if ($item->kk_level != SHDKEnum::KEPALA_KELUARGA) {
                $item->bisaPecahKK = true;
            } else {
                if ($kk->anggota->count() == 1) {
                    if ($item->sex == JenisKelaminEnum::PEREMPUAN) {
                        $item->bisaPecahKK = true;
                    }
                }
            }
            $item->hubungan = SHDKEnum::valueOf($item->kk_level);
            $item->sex      = JenisKelaminEnum::valueOf($item->sex);

            return $item;
        })->toArray();
        $data['kepala_kk'] = $kk->kepalaKeluarga;
        $data['program']   = ['programkerja' => BantuanPeserta::with(['bantuan'])->whereHas('bantuan', static fn ($q) => $q->whereSasaran(SasaranEnum::KELUARGA))->wherePeserta($kk->no_kk)->get()->toArray()];

        view('admin.penduduk.keluarga.anggota.index', $data);
    }

    public function ajax_add_anggota($id = 0): void
    {
        isCan('u');
        $keluarga            = KeluargaModel::with(['anggota'])->findOrFail($id);
        $kk                  = $keluarga->anggota->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->first();
        $data['kepala_kk']   = $kk ?: null;
        $data['hubungan']    = PendudukHubungan::kawin($kk->status_kawin_id, $kk->sex_id)->get();
        $data['main']        = $keluarga->anggota;
        $data['penduduk']    = PendudukHidup::lepas(true)->get();
        $data['form_action'] = ci_route("keluarga.add_anggota.{$id}");

        view('admin.penduduk.keluarga.modal.ajax_add_anggota_form', $data);

    }

    // $id adalah id tweb_penduduk
    public function edit_anggota($id_kk = 0, $id = 0): void
    {
        isCan('u');
        $keluarga         = KeluargaModel::with(['anggota'])->findOrFail($id_kk);
        $data['hubungan'] = SHDKEnum::all();
        $data['main']     = $keluarga->anggota->where('id', $id)->first();

        $kk                  = $keluarga->kepalaKeluarga;
        $data['kepala_kk']   = $kk ?: null;
        $data['form_action'] = ci_route("keluarga.update_anggota.{$id_kk}.{$id}");

        view('admin.penduduk.keluarga.modal.ajax_edit_anggota_form', $data);
    }

    // Tidak boleh tambah anggota bagi kasus kepala keluarga mati/hilang/pindah
    public function add_anggota($id = 0): void
    {
        isCan('u');
        $keluarga = KeluargaModel::with(['kepalaKeluarga'])->findOrFail($id);
        if ($keluarga->kepalaKeluarga && $keluarga->kepalaKeluarga->status_dasar != 1) {
            show_404();
        }

        $data = $this->input->post();
        if ($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            Penduduk::where(['id_kk' => $id, 'kk_level' => SHDKEnum::KEPALA_KELUARGA])->update(['kk_level' => SHDKEnum::LAINNYA]);
            $keluarga->update(['nik_kepala' => $data['nik'], 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => ci_auth()->id]);
        }
        Penduduk::where(['id' => $data['nik']])->update(['kk_level' => $data['kk_level'], 'id_kk' => $id]);

        redirect(ci_route("keluarga.anggota.{$id}"));
    }

    public function update_anggota($id_kk = 0, $id = 0): void
    {
        isCan('u');
        $keluarga = KeluargaModel::with(['kepalaKeluarga'])->findOrFail($id_kk);
        if ($keluarga->kepalaKeluarga && $keluarga->kepalaKeluarga->status_dasar != 1) {
            show_404();
        }

        $data = $this->input->post();
        if ($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            Penduduk::where(['id_kk' => $id_kk, 'kk_level' => SHDKEnum::KEPALA_KELUARGA])->update(['kk_level' => SHDKEnum::LAINNYA]);
            $keluarga->update(['nik_kepala' => $id, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => ci_auth()->id]);
        }
        Penduduk::where(['id' => $id])->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)->update(['kk_level' => $data['kk_level']]);

        redirect(ci_route("keluarga.anggota.{$id_kk}"));
    }

    // Pecah keluarga
    public function delete_anggota($kk = 0, $id = 0): void
    {
        isCan('u');

        try {
            $keluarga = KeluargaModel::findOrFail($kk);
            $keluarga->hapusAnggota($id, $keluarga->no_kk);
            redirect_with('success', 'Berhasil hapus anggota keluarga', ci_route("keluarga.anggota.{$kk}"));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal hapus anggota keluarga ' . $e->getMessage(), ci_route("keluarga.anggota.{$kk}"));
        }
    }

    // Keluarkan karena salah mengisi
    public function keluarkan_anggota($kk, $id = 0): void
    {
        isCan('u');

        try {
            $keluarga = KeluargaModel::findOrFail($kk);
            $keluarga->hapusAnggota($id);
            redirect_with('success', 'Berhasil keluarkan anggota keluarga', ci_route("keluarga.anggota.{$kk}"));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal keluarkan anggota keluarga ' . $e->getMessage(), ci_route("keluarga.anggota.{$kk}"));
        }

        redirect(ci_route("keluarga.anggota.{$kk}"));
    }

    // Tambah anggota keluarga dari penduduk baru
    // Tidak boleh tambah anggota bagi kasus kepala keluarga mati/hilang/pindah
    public function form($peristiwa, $id = 0): void
    {
        isCan('u');
        $keluarga = KeluargaModel::with(['kepalaKeluarga'])->findOrFail($id);
        if ($keluarga->kepalaKeluarga && $keluarga->kepalaKeluarga->status_dasar != 1) {
            show_404();
        }
        $excludeSHDK = [];
        if ($keluarga->kepalaKeluarga) {
            $excludeSHDK[] = SHDKEnum::KEPALA_KELUARGA;

            if ($keluarga->kepalaKeluarga->status_kawin == StatusKawinEnum::BELUMKAWIN) {
                $excludeSHDK[] = SHDKEnum::SUAMI;
                $excludeSHDK[] = SHDKEnum::ISTRI;
                $excludeSHDK[] = SHDKEnum::MENANTU;
                $excludeSHDK[] = SHDKEnum::CUCU;
                $excludeSHDK[] = SHDKEnum::MERTUA;
                if ($keluarga->kepalaKeluarga->sex != JenisKelaminEnum::PEREMPUAN) {
                    $excludeSHDK[] = SHDKEnum::ANAK;
                }
            }
        }

        $data['id_kk'] = $id;
        $data['kk']    = [
            'nama'       => $keluarga->kepalaKeluarga->nama,
            'no_kk'      => $keluarga->no_kk,
            'id_cluster' => $keluarga->kepalaKeluarga->id_cluster,
            'alamat'     => $keluarga->kepalaKeluarga->alamat_sekarang,
            'dusun'      => $keluarga->kepalaKeluarga->wilayah->dusun,
            'rw'         => $keluarga->kepalaKeluarga->wilayah->rw,
            'rt'         => $keluarga->kepalaKeluarga->wilayah->rt,
        ];
        $validSHDK = collect(SHDKEnum::all())->filter(static fn ($key, $item) => ! in_array($item, $excludeSHDK ))->all();

        $data['form_action']        = ci_route('keluarga.insert_anggota');
        $data['agama']              = AgamaEnum::all();
        $data['pendidikan_kk']      = PendidikanKKEnum::all();
        $data['pendidikan_sedang']  = PendidikanSedangEnum::all();
        $data['pekerjaan']          = PekerjaanEnum::all();
        $data['warganegara']        = WargaNegaraEnum::all();
        $data['hubungan']           = $validSHDK;
        $data['kawin']              = StatusKawinEnum::all();
        $data['golongan_darah']     = GolonganDarahEnum::all();
        $data['bahasa']             = BahasaEnum::all();
        $data['cacat']              = CacatEnum::all();
        $data['sakit_menahun']      = SakitMenahunEnum::all();
        $data['cara_kb']            = CaraKBEnum::all();
        $data['ktp_el']             = array_flip(unserialize(KTP_EL));
        $data['status_rekam']       = StatusKTPEnum::all();
        $data['tempat_dilahirkan']  = array_flip(unserialize(TEMPAT_DILAHIRKAN));
        $data['jenis_kelahiran']    = array_flip(unserialize(JENIS_KELAHIRAN));
        $data['penolong_kelahiran'] = array_flip(unserialize(PENOLONG_KELAHIRAN));
        $data['pilihan_asuransi']   = AsuransiEnum::all();
        $data['kehamilan']          = HamilEnum::all();
        $data['suku']               = SukuEnum::all();
        $data['suku_penduduk']      = Penduduk::distinct()->select('suku')->whereNotNull('suku')->whereRaw('LENGTH(suku) > 0')->pluck('suku', 'suku');
        $data['nik_sementara']      = Penduduk::nikSementara();
        $data['status_penduduk']    = [StatusPendudukEnum::TETAP => StatusPendudukEnum::valueOf(StatusPendudukEnum::TETAP)];
        $data['controller']         = 'keluarga';
        $data['jenis_peristiwa']    = $peristiwa;

        // data orang tua
        $orangTua          = Penduduk::orangTua($id);
        $data['data_ayah'] = $orangTua['ayah'];
        $data['data_ibu']  = $orangTua['ibu'];

        $originalInput = session('old_input');
        if ($originalInput) {
            $data['penduduk'] = $originalInput;
            if (isset($originalInput['id_cluster'])) {
                $wilayah                     = Wilayah::find((int) ($originalInput['id_cluster']));
                $data['penduduk']['wilayah'] = ['dusun' => $wilayah->dusun, 'rw' => $wilayah->rw, 'rt' => $wilayah->rt];
            }
            $data['penduduk']['id_sex'] = $originalInput['sex'];
            $data['no_kk']              = $originalInput['no_kk'];
        }

        view('admin.penduduk.keluarga.anggota.form', $data);
    }

    public function insert(): void
    {
        isCan('u');
        $data          = $this->input->post();
        $originalInput = $data;
        if ($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            $keluarga = KeluargaModel::find($data['id_kk']);
            if ($keluarga->nik_kepala) {
                set_session('old_input', $originalInput);
                redirect_with('error', 'Tidak bisa tambah kepala keluarga', ci_route('keluarga.form_peristiwa.' . $data['jenis_peristiwa'], $data['id_kk']));
            }
        }
        $valid = KeluargaModel::validasi_data_keluarga($data);
        if (! $valid['status']) {
            set_session('old_input', $originalInput);
            redirect_with('error', $valid['messages'], ci_route('keluarga.form_peristiwa.' . $data['jenis_peristiwa'], $data['id_kk']));
        }
        $data['tgl_lapor']     = rev_tgl($data['tgl_lapor']);
        $data['tgl_peristiwa'] = $data['tgl_peristiwa'] ? rev_tgl($data['tgl_peristiwa']) : rev_tgl($data['tanggallahir']);

        $validasiPenduduk = Penduduk::validasi($data);
        if (! $validasiPenduduk['status']) {
            set_session('old_input', $originalInput);
            redirect_with('error', $validasiPenduduk['messages'], ci_route('keluarga.form_peristiwa.' . $data['jenis_peristiwa'], $data['id_kk']));
        }

        $lokasi_file = $_FILES['foto']['tmp_name'];
        $tipe_file   = $_FILES['foto']['type'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/pjpeg' && $tipe_file != 'image/png') {
                unset($data['foto']);
            } else {
                UploadFoto($nama_file, '');
                $data['foto'] = $nama_file;
            }
        } else {
            unset($data['foto']);
        }

        unset($data['file_foto'], $data['old_foto'], $data['nik_lama'], $data['dusun'], $data['rw']);

        DB::beginTransaction();

        try {
            KeluargaModel::tambahAnggota($data);
            DB::commit();
            redirect_with('success', 'Anggota keluarga baru berhasil ditambahkan', ci_route('keluarga.anggota', $data['id_kk']));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            DB::rollBack();
            set_session('old_input', $originalInput);
            redirect_with('error', 'Anggota keluarga baru gagal ditambahkan', ci_route('keluarga.form_peristiwa.' . $data['jenis_peristiwa'], $data['id_kk']));
        }
    }
}
