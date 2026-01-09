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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
use App\Enums\PeristiwaKeluargaEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKTPEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\StatusRekamEnum;
use App\Enums\SukuEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\BantuanPeserta;
use App\Models\Keluarga as KeluargaModel;
use App\Models\Penduduk;
use App\Models\PendudukHidup;
use App\Models\Wilayah;
use Illuminate\Support\Arr;
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

        $kk = KeluargaModel::with([
            'anggota'        => static fn ($q) => $q->with('wilayah')->without(['keluarga', 'rtm']),
            'kepalaKeluarga' => static fn ($q) => $q->with([
                'wilayah',
                'keluarga' => static fn ($r) => $r->with('wilayah'),  // ← Load nested wilayah dari keluarga
            ])->without(['rtm']),
        ])->find($id) ?? show_404();

        $data['no_kk'] = $kk->no_kk;
        $data['main']  = $kk->anggota->map(static function ($item) use ($kk) {
            $item->bisaPecahKK  = false;
            $item->bisaGabungKK = true;
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
        $data['hubungan']    = SHDKEnum::filterByKawin($kk->status_kawin_id, $kk->sex_id);
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
        $data['hubungan'] = Arr::except(SHDKEnum::all(), [SHDKEnum::KEPALA_KELUARGA]);

        $data['main'] = $keluarga->anggota->where('id', $id)->first();

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

        redirect_with('success', 'Berhasil menambahkan anggota keluarga', ci_route("keluarga.anggota.{$id}"));
    }

    public function update_anggota($id_kk = 0, $id = 0): void
    {
        isCan('u');
        $keluarga = KeluargaModel::with(['kepalaKeluarga'])->findOrFail($id_kk);
        if ($keluarga->kepalaKeluarga && $keluarga->kepalaKeluarga->status_dasar != 1) {
            show_404();
        }

        $data = $this->input->post();
        Penduduk::where(['id' => $id])->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)->update(['kk_level' => $data['kk_level']]);

        redirect_with('success', 'Berhasil ubah SDHK anggota keluarga', ci_route("keluarga.anggota.{$id_kk}"));
    }

    /**
     * Tampilkan form pecah KK dalam modal (AJAX).
     *
     * @param int|string $kk ID atau nomor KK lama
     * @param int|string $id ID penduduk yang akan menjadi kepala keluarga baru
     *
     * @return Illuminate\View\View
     *
     * Alur:
     * 1. Ambil calon kepala keluarga baru dari penduduk.
     * 2. Ambil daftar anggota KK lama selain kepala keluarga.
     * 3. Susun urutan: kepala keluarga baru di atas, lalu anggota lain.
     * 4. Siapkan daftar hubungan keluarga (SHDK) kecuali kepala keluarga.
     * 5. Kirim ke view untuk ditampilkan dalam modal.
     */
    public function ajax_gabung_kk($kk, $id)
    {
        $data['kk'] = $kk;
        $data['id'] = $id;

        // Ambil kepala keluarga baru (berdasarkan $id)
        $kepalaBaru                     = Penduduk::find($id);
        $data['isGabungKepalaKeluarga'] = ($kepalaBaru->kk_level == SHDKEnum::KEPALA_KELUARGA);

        // Ambil anggota selain kepala keluarga lama
        $anggotaLain = Penduduk::status(StatusDasarEnum::HIDUP)
            ->where('id_kk', $kk)
            ->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)
            ->where('id', '!=', $id) // pastikan calon kepala baru tidak ikut di daftar anggota
            ->orderBy('kk_level')
            ->orderBy('tanggallahir')
            ->get();

        // Gabungkan: kepala keluarga baru di atas, sisanya di bawah
        $data['main'] = collect([$kepalaBaru])->merge($anggotaLain);

        // Daftar hubungan (SHDK) kecuali Kepala Keluarga
        $data['hubungan'] = Arr::except(
            SHDKEnum::all(),
            [SHDKEnum::KEPALA_KELUARGA]
        );

        $data['statusKawin'] = StatusKawinEnum::all();

        // Data tambahan
        $data['no_kk']          = '';
        $data['nokk_sementara'] = KeluargaModel::formatNomerKKSementara();
        $data['form_action']    = ci_route('keluarga.gabung_kk', [$kk, $id]);

        return view('admin.penduduk.keluarga.modal.ajax_gabung_kk_form', $data);
    }

    /**
     * Pecah KK lama menjadi KK baru dengan kepala keluarga baru dan anggota terpilih.
     *
     * @param int|string $kk ID atau nomor KK lama
     * @param int|string $id ID penduduk yang menjadi kepala keluarga baru
     *
     * @return void
     *
     * Alur:
     * 1. Ambil data KK lama.
     * 2. Buat record KK baru dengan no_kk baru (atau sementara) dan set kepala keluarga baru.
     * 3. Pindahkan anggota yang dicentang ke KK baru, termasuk memperbarui hubungan keluarga (kk_level) jika diisi.
     * 4. Pastikan kepala baru otomatis menjadi KEPALA KELUARGA.
     * 5. Simpan log peristiwa (keluarga baru).
     * 6. Redirect ke halaman anggota KK baru.
     */
    public function gabung_kk($kk, $id)
    {
        $post           = $this->input->post();
        $kkLama         = KeluargaModel::find($kk);
        $noKkSebelumnya = $kkLama->no_kk;

        if (! $kkLama) {
            set_session('error', 'KK lama tidak ditemukan.');
            redirect('keluarga');
        }

        if (empty($post['nokk_sementara'])) {
            $cekKK = KeluargaModel::where('no_kk', $post['no_kk'])->first();
            if ($cekKK) {
                set_session('error', 'Nomor KK telah terdaftar.');
                redirect("keluarga/anggota/{$kkLama->id}");
            }
        }

        // Buat KK baru
        $kkBaru               = $kkLama->replicate();
        $kkBaru->no_kk        = $post['no_kk'] ?: KeluargaModel::formatNomerKKSementara();
        $kkBaru->nik_kepala   = $id;
        $kkBaru->tgl_cetak_kk = null;
        $kkBaru->tgl_daftar   = date('Y-m-d H:i:s');
        $kkBaru->updated_at   = date('Y-m-d H:i:s');
        $kkBaru->save();

        $anggota  = $post['anggota'] ?? [];
        $hubungan = $post['kk_level'] ?? [];

        foreach ($anggota as $idPenduduk) {
            $penduduk = Penduduk::find($idPenduduk);
            if ($penduduk) {
                $penduduk->id_kk = $kkBaru->id;
                // Ubah hubungan jika ada input baru
                if (isset($hubungan[$idPenduduk]) && $hubungan[$idPenduduk]) {
                    $penduduk->kk_level = $hubungan[$idPenduduk];
                }
                $penduduk->save();
            }
        }

        $statusKawin = $post['status_kawin'] ?? [];

        foreach ($statusKawin as $idPenduduk => $value) {
            $penduduk = Penduduk::find($idPenduduk);
            if ($penduduk) {
                $penduduk->no_kk_sebelumnya = $noKkSebelumnya;
                $penduduk->status_kawin     = $value;
                $penduduk->save();
            }
        }

        // Kepala baru
        $kepalaBaru = Penduduk::find($id);
        if ($kepalaBaru) {
            $kepalaBaru->id_kk    = $kkBaru->id;
            $kepalaBaru->kk_level = SHDKEnum::KEPALA_KELUARGA;
            $kepalaBaru->save();
        }

        App\Models\LogKeluarga::create([
            'id_kk'           => $kkBaru->id,
            'id_peristiwa'    => PeristiwaKeluargaEnum::KELUARGA_BARU->value,
            'tgl_peristiwa'   => date('Y-m-d H:i:s'),
            'id_pend'         => $id, // kepala keluarga baru
            'id_log_penduduk' => null,
            'updated_by'      => ci_auth()->id,
        ]);

        set_session('success', 'KK baru berhasil dibuat.');
        redirect("keluarga/anggota/{$kkBaru->id}");
    }

    // Pecah keluarga
    public function delete_anggota($kk = 0, $id = 0): void
    {
        isCan('u');

        try {
            $keluarga = KeluargaModel::findOrFail($kk);
            $penduduk = Penduduk::findOrFail($id);

            // Cek apakah dia kepala keluarga
            $isKepala = $penduduk->kk_level == SHDKEnum::KEPALA_KELUARGA;

            if ($isKepala) {
                // Ambil semua anggota (selain kepala)
                $anggota = Penduduk::where('id_kk', $kk)
                    ->where('id', '!=', $id)
                    ->get();

                // Pecahkan dulu semua anggota
                foreach ($anggota as $agt) {
                    $keluarga->hapusAnggota($agt->id, $keluarga->no_kk);
                }

                // Terakhir: pecahkan kepala keluarganya sendiri
                $keluarga->hapusAnggota($id, $keluarga->no_kk);
            } else {
                // Jika bukan kepala keluarga: hapus langsung
                $keluarga->hapusAnggota($id, $keluarga->no_kk);
            }

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
        $data['ktp_el']             = StatusRekamEnum::all();
        $data['status_rekam']       = StatusKTPEnum::all();
        $data['tempat_dilahirkan']  = array_flip(unserialize(TEMPAT_DILAHIRKAN));
        $data['jenis_kelahiran']    = array_flip(unserialize(JENIS_KELAHIRAN));
        $data['penolong_kelahiran'] = array_flip(unserialize(PENOLONG_KELAHIRAN));
        $data['pilihan_asuransi']   = AsuransiEnum::all();
        $data['kehamilan']          = HamilEnum::all();
        $data['suku']               = SukuEnum::all();
        $data['nik_sementara']      = Penduduk::nikSementara();
        $data['status_penduduk']    = [StatusPendudukEnum::TETAP => StatusPendudukEnum::valueOf(StatusPendudukEnum::TETAP)];
        $data['controller']         = 'keluarga';
        $data['jenis_peristiwa']    = $peristiwa;
        $data['marga_penduduk']     = Penduduk::distinct()->select('marga')->whereNotNull('marga')->whereRaw('LENGTH(marga) > 0')->pluck('marga', 'marga');
        $data['suku_penduduk']      = Penduduk::distinct()->select('suku')->whereNotNull('suku')->whereRaw('LENGTH(suku) > 0')->pluck('suku', 'suku');
        $data['adat_penduduk']      = Penduduk::distinct()->select('adat')->whereNotNull('adat')->whereRaw('LENGTH(adat) > 0')->pluck('adat', 'adat');

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
