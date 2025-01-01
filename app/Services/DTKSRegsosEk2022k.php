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

namespace App\Services;

use App\Enums\Dtks\DtksEnum;
use App\Enums\Dtks\Regsosek2022kEnum;
use App\Enums\SasaranEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Dtks;
use App\Models\DtksAnggota;
use App\Models\DtksLampiran;
use App\Models\DtksPengaturanProgram;
use App\Models\KIA;
use App\Models\Pendidikan;
use App\Models\Penduduk;
use App\Models\PendudukHubungan;
use App\Models\SakitMenahun;
use App\Models\SettingAplikasi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Throwable;

defined('BASEPATH') || exit('No direct script access allowed');

class DTKSRegsosEk2022k
{
    /**
     *  @return [form_input_name => [target_table, target_field]]
     */
    protected static function relasiPengaturanProgram(): array
    {
        return [
            '501a'                => ['dtks', implode(',', ['kd_bss_bnpt', 'bulan_bss_bnpt', 'tahun_bss_bnpt'])],
            '501b'                => ['dtks', implode(',', ['kd_pkh', 'bulan_pkh', 'tahun_pkh'])],
            '501c'                => ['dtks', implode(',', ['kd_blt_dana_desa', 'bulan_blt_dana_desa', 'tahun_blt_dana_desa'])],
            '501d'                => ['dtks', implode(',', ['kd_subsidi_listrik', 'bulan_subsidi_listrik', 'tahun_subsidi_listrik'])],
            '501e'                => ['dtks', implode(',', ['kd_bantuan_pemda', 'bulan_bantuan_pemda', 'tahun_bantuan_pemda'])],
            '501f'                => ['dtks', implode(',', ['kd_subsidi_pupuk', 'bulan_subsidi_pupuk', 'tahun_subsidi_pupuk'])],
            '501g'                => ['dtks', implode(',', ['kd_subsidi_lpg', 'bulan_subsidi_lpg', 'tahun_subsidi_lpg'])],
            '431a1'               => ['dtks_anggota', 'kd_jamkes_setahun'],
            '431a2'               => ['dtks_anggota', 'kd_jamkes_setahun'],
            '431a3'               => ['dtks_anggota', 'kd_jamkes_setahun'],
            '431a4'               => ['dtks_anggota', 'kd_jamkes_setahun'],
            '431a1_431a4_default' => ['dtks_anggota', 'kd_jamkes_setahun'],
            '431b'                => ['dtks_anggota', 'kd_ikut_prakerja'],
            '431b_default'        => ['dtks_anggota', 'kd_ikut_prakerja'],
            '431c'                => ['dtks_anggota', 'kd_ikut_kur'],
            '431c_default'        => ['dtks_anggota', 'kd_ikut_kur'],
            '431d'                => ['dtks_anggota', 'kd_ikut_umi'],
            '431d_default'        => ['dtks_anggota', 'kd_ikut_umi'],
            '431e'                => ['dtks_anggota', 'kd_ikut_pip'],
            '431e_default'        => ['dtks_anggota', 'kd_ikut_pip'],
            '431f1'               => ['dtks_anggota', 'jumlah_jamket_kerja'],
            '431f2'               => ['dtks_anggota', 'jumlah_jamket_kerja'],
            '431f3'               => ['dtks_anggota', 'jumlah_jamket_kerja'],
            '431f4'               => ['dtks_anggota', 'jumlah_jamket_kerja'],
            '431f5'               => ['dtks_anggota', 'jumlah_jamket_kerja'],
            '431f1_431f5_default' => ['dtks_anggota', 'jumlah_jamket_kerja'],
        ];
    }

    /**
     * Cache temporary Model::get(), digunakan di generateDefaultDtks()
     * ketika ekspor anggota dilakukan, untuk mengurangi hit ke db
     *
     * @param mixed $model
     */
    protected function cacheTemporaryModelGet($model)
    {
        if ($model instanceof Model) {
            $model_class = get_class($model);
        } elseif ($model instanceof Builder) {
            $model_class = get_class($model->getModel());
        } else {
            $model_class = $model;
        }

        $class = str_replace('\\', '', $model_class);
        if (! isset($this->{$class})) {
            try {
                if ($model instanceof Model || $model instanceof Builder) {
                    $this->{$class} = $model->get();
                } else {
                    $str = "{$model_class}::get();";
                    eval("\$this->\$class = {$str};");
                }

                return $this->{$class};
            } catch (Throwable $th) {
                return collect();
            }
        } else {
            return $this->{$class};
        }
    }

    public function info()
    {
        $data                            = [];
        $daftar_bantuan                  = Bantuan::get();
        $data['daftar_bantuan_keluarga'] = $daftar_bantuan->whereIn('sasaran', [SasaranEnum::KELUARGA]);
        $data['daftar_bantuan_anggota']  = $daftar_bantuan->where('sasaran', SasaranEnum::PENDUDUK);
        $all_pengaturan_program          = DtksPengaturanProgram::where('versi_kuisioner', 2)->get();
        $relasi_program                  = static::relasiPengaturanProgram();

        foreach (array_keys($relasi_program) as $form_input_name) {
            $pengaturan_program = $all_pengaturan_program->where('kode', $form_input_name);
            if ($pengaturan_program && substr($form_input_name, -(strlen('default'))) !== 'default') {
                $data['name_' . $form_input_name] = $pengaturan_program->first()->id_bantuan;
            } elseif ($pengaturan_program && substr($form_input_name, -(strlen('default'))) === 'default') {
                $data['name_' . $form_input_name] = $pengaturan_program->first()->nilai_default;
            }
        }

        return view('admin.dtks.' . DtksEnum::VERSION_CODE . '.info', $data);
    }

    public function impor()
    {
        return view('admin.dtks.2.impor', [
            'formatImpor' => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-impor-dtks-regsosek2022k.xlsx')),
        ]);
    }

    /**
     * Set id_keluarga if null, split dtks for each keluarga in rtm
     *
     * @param mixed $dtks
     */
    protected function splitDTKSForEachKeluarga($dtks)
    {
        $semua_dtks = Dtks::where('id_rtm', $dtks->id_rtm)->whereNotNull('id_keluarga')->get();

        if ($semua_dtks->count() != $dtks->jumlah_keluarga) {
            // lepas semua anggota
            DtksAnggota::where('id_dtks', $dtks->id)->update(['id_dtks' => null]);

            // sesuaikan jumlah dtks dengan jumlah keluarga dalam rtm
            foreach ($dtks->keluarga_in_rtm as $keluarga) {
                $dtks_keluarga = $semua_dtks->where('id_keluarga', $keluarga->id)->first();
                $dtks_resync   = null;
                // dtks ini belum punya acuan keluarga
                if (! $dtks->id_keluarga) {
                    $dtks->id_keluarga = $keluarga->id;
                    $this->saveRelatedAttribute($dtks);
                    $dtks_resync = $dtks;
                }
                // clone dtks dan set id_keluarga
                elseif (! $dtks_keluarga) {
                    $new_dtks = Dtks::where('id_rtm', $dtks->id_rtm)->whereNull('id_keluarga')->first();
                    if ($new_dtks) {
                        $new_dtks->update(['id_keluarga' => $keluarga->id]);
                    } else {
                        $new_dtks = $dtks->replicate()->fill([
                            'id_keluarga' => $keluarga->id,
                        ]);
                        $this->saveRelatedAttribute($new_dtks);
                    }
                    $semua_dtks->push($new_dtks);
                    $dtks_resync = $new_dtks;
                } else {
                    $dtks_resync = $dtks;
                }
                if ($dtks_resync) {
                    foreach ($dtks_resync->anggota_keluarga_in_rtm[$dtks_resync->id_keluarga] as $agt) {
                        // cek data dtks anggota yang lepas
                        $dtks_anggota = DtksAnggota::where('id_penduduk', $agt->id)->first();
                        if (! $dtks_anggota) {
                            $dtks_anggota = new DtksAnggota();
                        }
                        $dtks_anggota->id_penduduk = $agt->id;
                        $dtks_anggota->id_keluarga = $dtks_resync->id_keluarga;
                        $dtks_anggota->id_dtks     = $dtks_resync->id;
                        $this->saveRelatedAttribute($dtks_anggota);
                    }
                }
            }

            // lepaskan keluarga yang tidak termasuk dalam rtm
            Dtks::where('id_rtm', $dtks->id_rtm)
                ->whereNotIn('id_keluarga', $dtks->keluarga_in_rtm->pluck('id'))
                ->update(['id_keluarga' => null]);
        }
    }

    /**
     * lepas anggota DTKS yg tidak ditemukan di tweb_penduduk status hidup,
     * masukkan data anggotaDtks yg terlepas / buat sync baru jika belum ada,
     * gabungkan identitas anggota dengan existing data di openSID
     *
     * @param mixed $dtks
     */
    public function generateDefaultDtks($dtks): Dtks
    {
        $dtks->setAppends([
            'kepala_keluarga',
            'jumlah_keluarga',
            'jumlah_anggota_dtks',
            'no_kk_art',
        ]);
        $dtks->loadMissing([
            'rtm',
            'rtm.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withOnly('Wilayah', 'keluarga');
            },
            'rtm.anggota' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withOnly(['keluarga', 'pekerjaan', 'pendidikanKK']);
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);
        $this->splitDTKSForEachKeluarga($dtks);

        if ($dtks->anggota_keluarga_in_rtm[$dtks->id_keluarga] == null) {
            $ids_anggota = collect([]);
        } else {
            $ids_anggota = $dtks->anggota_keluarga_in_rtm[$dtks->id_keluarga]->pluck('id');
        }

        // lepas anggota DTKS yg tidak ditemukan di tweb_penduduk status hidup
        DtksAnggota::whereNotIn('id_penduduk', $ids_anggota)
            ->where('id_dtks', $dtks->id)
            ->update(['id_dtks' => null]);

        $ref_eloquent_collection['hubungan_dengan_kk'] = $this->cacheTemporaryModelGet(PendudukHubungan::class);
        $ref_eloquent_collection['kia']                = KIA::whereIn('ibu_id', $ids_anggota)
            ->orWhereIn('anak_id', $ids_anggota)->get();
        // masukkan data anggotaDtks yg terlepas / buat sync baru jika belum ada
        if ($ids_anggota->count() > $dtks->dtksAnggota->count()) {
            $existing_dtks_anggotas = DtksAnggota::whereIn('id_penduduk', $ids_anggota);
            $existing_dtks_anggotas->update(['id_dtks' => $dtks->id]);
            $ids_existing_dtks_anggotas = $existing_dtks_anggotas->pluck('id_penduduk');
            $new_anggota                = $ids_anggota->diff($ids_existing_dtks_anggotas);
            // buat sync baru
            if ($new_anggota->count() > 0) {
                $daftar_sakit_menahun = $this->cacheTemporaryModelGet(SakitMenahun::class);
                $daftar_pendidikan    = $this->cacheTemporaryModelGet(Pendidikan::class);

                foreach ($dtks->anggota_keluarga_in_rtm[$dtks->id_keluarga]->whereIn('id', $new_anggota) as $agt) {
                    $usia_dinamis              = $agt->umur; // attribute
                    $dtks_anggota              = new DtksAnggota();
                    $dtks_anggota->id_dtks     = $dtks->id;
                    $dtks_anggota->id_penduduk = $agt->id;
                    $dtks_anggota->id_keluarga = $agt->keluarga->id;

                    $kepala_keluarga = $dtks->keluarga->kepalaKeluarga;
                    $dtks_anggota    = $this->syncKetDemografi($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);

                    if ($usia_dinamis >= 5) {
                        $dtks_anggota = $this->syncPendidikan($dtks_anggota, $agt, $daftar_pendidikan);
                        $dtks_anggota = $this->syncKetenagakerjaan($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);
                        $dtks_anggota = $this->syncKepemilikanUsaha($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);
                    }

                    $dtks_anggota = $this->syncKesehatan($dtks_anggota, $agt, $daftar_sakit_menahun);
                    $dtks_anggota = $this->syncProgramPerlindunganSosial($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);
                    $this->saveRelatedAttribute($dtks_anggota);
                }
            }
        }

        // hanya ambil field yg digunakan
        $dtks->load([
            'dtksAnggota' => static function ($builder): void {
                $builder->select(Regsosek2022kEnum::getUsedFields()['dtks_anggota']);
            },
        ]);
        // gabungkan identitas anggota dengan existing data di openSID
        $dtks->dtksAnggota = $dtks->dtksAnggota->transform(function ($item) use ($dtks, $ref_eloquent_collection) {
            $tmp_anggota     = $dtks->anggota_keluarga_in_rtm[$dtks->id_keluarga]->where('id', $item->id_penduduk)->first();
            $kepala_keluarga = $dtks->keluarga->kepalaKeluarga;
            $item            = $this->syncKetDemografi($item, $tmp_anggota, $kepala_keluarga, $ref_eloquent_collection);
            $item            = $this->syncProgramPerlindunganSosial($item, $tmp_anggota, $kepala_keluarga, []);

            $this->saveRelatedAttribute($item);

            $item->no_kk               = $tmp_anggota->keluarga->no_kk; // 402
            $item->nama                = $tmp_anggota->nama; // 402
            $item->nik                 = $tmp_anggota->nik; // 403
            $item->kd_jenis_kelamin    = $tmp_anggota->sex; // 405
            $item->tgl_lahir           = $tmp_anggota->tanggallahir; // 406
            $item->umur                = $tmp_anggota->umur; // getAttribute // 407
            $item->kd_stat_perkawinan  = $tmp_anggota->status_kawin; // 408
            $item->kd_status_kehamilan = $tmp_anggota->hamil ?? '2'; // 410 // 2. Tidak Hamil
            // digunakan untuk membantu memilih pekerjaan dan pendidikan
            $item->pekerjaan_saat_ini     = $tmp_anggota->pekerjaan->nama;
            $item->pendidikan_saat_ini    = $tmp_anggota->pendidikan;
            $item->pendidikan_kk_saat_ini = $tmp_anggota->pendidikanKK->nama;

            if ($tmp_anggota->usia >= 5) {
                // jika sedang sekolah, resync
                if (($item->kd_partisipasi_sekolah = 2) !== 0) {
                    // load sekali
                    $daftar_pendidikan = $this->cacheTemporaryModelGet(new Pendidikan());
                    $this->syncPendidikan($item, $tmp_anggota, $daftar_pendidikan);
                }
                $daftar_sakit_menahun = $this->cacheTemporaryModelGet(SakitMenahun::class);
                $this->syncKesehatan($item, $tmp_anggota, $daftar_sakit_menahun);
            }

            return $item;
        });

        if ($dtks->jumlah_keluarga > 1) {
            $dtks->all_dtks_id = Dtks::select('id', 'id_rtm', 'id_keluarga', 'versi_kuisioner')
                ->withOnly([
                    'rtm' => static function ($builder): void {
                        $builder->select('id', 'nik_kepala');
                    },
                    'rtm.kepalaKeluarga' => static function ($builder): void {
                        $builder->select('id', 'nama');
                        // override all items within the $with property in Penduduk
                        $builder->without([
                            'jenisKelamin',
                            'agama',
                            'pendidikanKK',
                            'pekerjaan',
                            'wargaNegara',
                            'golonganDarah',
                            'cacat',
                            'statusKawin',
                            'pendudukStatus',
                            'wilayah',
                        ]);
                    },
                    'keluarga' => static function ($builder): void {
                        $builder->select('id', 'nik_kepala', 'no_kk');
                    },
                    'keluarga.kepalaKeluarga' => static function ($builder): void {
                        $builder->select('id', 'nama');
                        // override all items within the $with property in Penduduk
                        $builder->without([
                            'jenisKelamin',
                            'agama',
                            'pendidikanKK',
                            'pekerjaan',
                            'wargaNegara',
                            'golonganDarah',
                            'cacat',
                            'statusKawin',
                            'pendudukStatus',
                            'wilayah',
                        ]);
                    },
                ])
                ->withCount('dtksAnggota')
                ->where('id_rtm', $dtks->id_rtm)
                ->whereNotNull('id_keluarga')
                ->get();
        }

        return $this->syncKepesertaanProgramKeluarga($dtks);
    }

    public function form(Dtks $dtks)
    {
        $desa = SettingAplikasi::whereIn('key', [
            'sebutan_desa', 'sebutan_kecamatan', 'sebutan_kabupaten',
        ])->get();

        // echo json_encode($data['dtks']);
        // die();
        foreach ($desa as $item) {
            $data[$item->key] = ucwords($item->value);
        }

        if (! $dtks->id_rtm) {
            return json(['message' => 'Formulir Tidak terhubung ke Rumah Tangga'], 404);
        }

        $data['dtks'] = $this->generateDefaultDtks($dtks);

        try {
            $kode_desa_bps = getKodeDesaFromTrackSID()['bps_kemendagri_desa']['kode_desa_bps'];

            if (! $dtks->kode_provinsi || ! $dtks->kode_kabupaten || ! $dtks->kode_kecamatan || ! $dtks->kode_desa) {
                //  I. Keterangan Tempat
                $dtks->kode_provinsi  = $kode_desa_bps ? substr($kode_desa_bps, 0, 2) : ''; // 101
                $dtks->kode_kabupaten = $kode_desa_bps ? substr($kode_desa_bps, 2, 2) : ''; // 102
                $dtks->kode_kecamatan = $kode_desa_bps ? substr($kode_desa_bps, 2 + 2, 3) : ''; // 103
                $dtks->kode_desa      = $kode_desa_bps ? substr($kode_desa_bps, 2 + 2 + 3, 3) : ''; // 104
                $this->saveRelatedAttribute($dtks);
            }
            $data['dtks_prov'] = getKodeDesaFromTrackSID()['nama_prov'];
            $data['dtks_kab']  = getKodeDesaFromTrackSID()['nama_kab'];
            $data['dtks_kec']  = getKodeDesaFromTrackSID()['nama_kec'];
            $data['dtks_desa'] = $kode_desa_bps . ' | ' . getKodeDesaFromTrackSID()['nama_desa'];
        } catch (Throwable $th) {
            $data['dtks_prov'] = '';
            $data['dtks_kab']  = '';
            $data['dtks_kec']  = '';
            $data['dtks_desa'] = '';
            log_message('error', $th);
        }

        $data['bulan']          = bulan();
        $data['tahun_awal']     = 2005; //dipakai: form Periode Terakhir Mendapatkan Program, dll
        $data['pilihan1']       = Regsosek2022kEnum::pilihanBagian1();
        $data['pilihan2']       = Regsosek2022kEnum::pilihanBagian2();
        $data['pilihan3']       = Regsosek2022kEnum::pilihanBagian3();
        $data['pilihan4']       = Regsosek2022kEnum::pilihanBagian4();
        $data['pilihan5']       = Regsosek2022kEnum::pilihanBagian5();
        $data['judul_lampiran'] = DtksLampiran::select(DB::raw('DISTINCT(judul)'))->get()->pluck('judul');

        return view('admin.dtks.2.form', $data);
    }

    protected function generateCetakPdf(Dtks $dtks, $preview = false)
    {
        // digunakan di file template
        try {
            $prov = getKodeDesaFromTrackSID()['nama_prov'];
            $kab  = getKodeDesaFromTrackSID()['nama_kab'];
            $kec  = getKodeDesaFromTrackSID()['nama_kec'];
            $desa = getKodeDesaFromTrackSID()['nama_desa'];
        } catch (Throwable $th) {
            $prov = '';
            $kab  = '';
            $kec  = '';
            $desa = '';
            log_message('error', $th);
        }
        $dtks      = $this->generateDefaultDtks($dtks);
        $nama_file = 'cetak_regsosek2022k_' . $dtks->kepalaKeluarga->nik
            . '_' . $dtks->id_rtm . '_' . str_replace([':', '-', ' '], '', $dtks->updated_at) . '.pdf';
        $path = FCPATH . LOKASI_FOTO_DTKS . $nama_file;
        if (! is_file($path) || $preview) {
            // OK, berkas ada. Ambil konten berkasnya
            if (is_file($path) && $preview) {
                $data = file_get_contents($path);
                // Generate the server headers
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $nama_file . '"');
                header('Expires: 0');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . strlen($data));
                header('Cache-Control: private, no-transform, no-store, must-revalidate');

                return readfile($path);
            }

            // cari berkas dtks lama untuk dihapus
            foreach (glob(FCPATH . LOKASI_FOTO_DTKS . 'cetak_regsosek2022k_' . $dtks->kepalaKeluarga->nik
                . '_' . $dtks->id_rtm . '_*.pdf') as $file) {
                if (file_exists($file)) {
                    unlink($file);
                    break;
                }
            }

            // convert in PDF
            try {
                // get the HTML using output buffer
                ob_start();

                include resource_path('views/admin/dtks/2/cetak.php');

                $content = ob_get_clean();

                $html2pdf = new Html2Pdf();
                // $html2pdf->pdf->SetDisplayMode('fullpage');
                $html2pdf->writeHTML($content);

                $html2pdf->output($path, $preview ? 'FI' : 'F');
            } catch (Html2PdfException $e) {
                $html2pdf->clean();
                log_message('error', $formatter->getHtmlMessage());
            }
        }

        return ['file' => $path, 'nama' => $nama_file, 'id' => $dtks->id, 'status_file' => 1];
    }

    public function cetakPreviewSingle(Dtks $dtks): void
    {
        $this->generateCetakPdf($dtks, true);
    }

    /**
     * @return array ['file'=>'', 'nama' => '', 'id'=>'', 'status_file'=>boolean]
     */
    public function cetakZip(Collection $many_dtks): array
    {
        $list_path        = [];
        $buat_file_sekali = null;

        foreach ($many_dtks as $dtks) {
            $nama_file = 'cetak_regsosek2022k_' . $dtks->kepala_keluarga->nik
                . '_' . $dtks->id_rtm . '_' . str_replace([':', '-', ' '], '', $dtks->updated_at) . '.pdf';
            $path = FCPATH . LOKASI_FOTO_DTKS . $nama_file;

            if (! is_file($path)) {
                if ($buat_file_sekali == null) {
                    $buat_file_sekali = $dtks;
                } else {
                    $list_path[] = ['file' => $path, 'nama' => $nama_file, 'id' => $dtks->id, 'status_file' => 0];
                }
            } else {
                $list_path[] = ['file' => $path, 'nama' => $nama_file,  'id' => $dtks->id, 'status_file' => 1];
            }
        }

        if ($buat_file_sekali) {
            $list_path[] = $this->generateCetakPdf($buat_file_sekali);
        }

        return $list_path;
    }

    public function ekspor(): void
    {
        $file = namafile('Dtks Regsosek2022k') . '.xlsx';

        $writer = new Writer();
        $writer->openToBrowser($file);

        $dtks_v2 = Dtks::whereNotNull('id_rtm')->where('versi_kuisioner', DtksEnum::REGSOS_EK2022_K)->get();

        $this->eksporKeluarga($writer, $dtks_v2);
        $this->eksporAnggota($writer, $dtks_v2);

        $writer->close();
    }

    protected function eksporKeluarga(&$writer, $dtks_v2)
    {
        $judul = [
            ['Terakhir diubah', ''], // 0,1
            ['I. KETERANGAN TEMPAT', '101'],  // 1,1 : 15,1 // 02
            ['', '102'],  // 03
            ['', '103'],  // 04
            ['', '104'],  // 05
            ['', '105'],  // 06
            ['', '105a Kode Sub SLS'],  // 07
            ['', '106'],  // 08
            ['', '107'],  // 09
            ['', '108'],  // 10
            ['', '109'],  // 11
            ['', '110 No Urut Keluarga'], // 12
            ['', '111'], // 13
            ['', '112'], // 14
            ['', '113'], // 15
            ['', '114'], // 16
            ['', '115'], // 17

            ['II. KETERANGAN PETUGAS', '201', 'tanggal_pendataan'],
            ['', '202', 'nama_ppl'],
            ['', '202a Kode PPL', 'kode_ppl'],
            ['', '203', 'tanggal_pemeriksaan'],
            ['', '204', 'nama_pml'],
            ['', '204a Kode Pemeriksa', 'kode_pml'],
            ['', 'Responden', 'nama_responden'],
            ['', 'No Hp responden', 'no_hp_responden'],
            ['', '205', 'kd_hasil_pendataan_keluarga'],

            ['III. KETERANGAN PERUMAHAN', '301a', 'kd_stat_bangunan_tinggal'],
            ['', '301b', 'kd_sertiv_lahan_milik'],
            ['', '302', 'luas_lantai'],
            ['', '303', 'kd_jenis_lantai_terluas'],
            ['', '304', 'kd_jenis_dinding'],
            ['', '305', 'kd_jenis_atap'],
            ['', '306a', 'kd_sumber_air_minum'],
            ['', '306b', 'kd_jarak_sumber_air_ke_tpl'],
            ['', '307a', 'kd_sumber_penerangan_utama'],
            ['', '307b1', 'kd_daya_terpasang'],
            ['', '307b2', 'kd_daya_terpasang2'],
            ['', '307b3', 'kd_daya_terpasang3'],
            ['', '308', 'kd_bahan_bakar_memasak'],
            ['', '309a', 'kd_fasilitas_tempat_bab'],
            ['', '309b', 'kd_jenis_kloset'],
            ['', '310', 'kd_pembuangan_akhir_tinja'],

            ['V. KEIKUTSERTAAN PROGRAM, KEPEMILIKAN ASET, DAN LAYANAN', '501a', 'kd_bss_bnpt'],
            ['', '501a Bulan', 'bulan_bss_bnpt'],
            ['', '501a Tahun', 'tahun_bss_bnpt'],
            ['', '501b', 'kd_pkh'],
            ['', '501b Bulan', 'bulan_pkh'],
            ['', '501b Tahun', 'tahun_pkh'],
            ['', '501c', 'kd_blt_dana_desa'],
            ['', '501c Bulan', 'bulan_blt_dana_desa'],
            ['', '501c Tahun', 'tahun_blt_dana_desa'],
            ['', '501d', 'kd_subsidi_listrik'],
            ['', '501d Bulan', 'bulan_subsidi_listrik'],
            ['', '501d Tahun', 'tahun_subsidi_listrik'],
            ['', '501e', 'kd_bantuan_pemda'],
            ['', '501e Bulan', 'bulan_bantuan_pemda'],
            ['', '501e Tahun', 'tahun_bantuan_pemda'],
            ['', '501f', 'kd_subsidi_pupuk'],
            ['', '501f Bulan', 'bulan_subsidi_pupuk'],
            ['', '501f Tahun', 'tahun_subsidi_pupuk'],
            ['', '501g', 'kd_subsidi_lpg'],
            ['', '501g Bulan', 'bulan_subsidi_lpg'],
            ['', '501g Tahun', 'tahun_subsidi_lpg'],
            ['', '502a', 'kd_tabung_gas_5_5_kg'],
            ['', '502b', 'kd_lemari_es'],
            ['', '502c', 'kd_ac'],
            ['', '502d', 'kd_pemanas_air'],
            ['', '502e', 'kd_telepon_rumah'],
            ['', '502f', 'kd_televisi'],
            ['', '502g', 'kd_perhiasan_10_gr_emas'],
            ['', '502h', 'kd_komputer_laptop'],
            ['', '502i', 'kd_sepeda_motor'],
            ['', '502j', 'kd_sepeda'],
            ['', '502k', 'kd_mobil'],
            ['', '502l', 'kd_perahu'],
            ['', '502m', 'kd_kapal_perahu_motor'],
            ['', '502n', 'kd_smartphone'],
            ['', '503a', 'kd_lahan'],
            ['', '503b', 'kd_rumah_ditempat_lain'],
            ['', '504a', 'jumlah_sapi'],
            ['', '504b', 'jumlah_kerbau'],
            ['', '504c', 'jumlah_kuda'],
            ['', '504d', 'jumlah_babi'],
            ['', '504e', 'jumlah_kambing_domba'],
            ['', '505', 'kd_internet_sebulan'],
            ['', '506', 'kd_rek_aktif'],
            ['VI. CATATAN', 'Catatan', 'catatan'],
        ];
        $writer->getCurrentSheet()->setName('Keluarga');
        $writer->addRow(Row::fromValues(array_column($judul, 0)));
        $writer->addRow(Row::fromValues(array_column($judul, 1)));

        // $writer->mergeCells([0,1] , [0,2]);     // updated_at
        // $writer->mergeCells([1,1] , [16,1]);    // bag 1
        // $writer->mergeCells([17,1] , [25,1]);   // bag 2
        // $writer->mergeCells([26,1] , [41,1]);   // bag 3
        // $writer->mergeCells([42,1] , [85,1]);   // bag 5
        // $writer->mergeCells([86,1] , [86,2]);   // catatan

        foreach ($dtks_v2 as $dtks) {
            $dtks = $this->generateDefaultDtks($dtks);
            $data = [
                '' . $dtks->updated_at, // agar tidak di konversi ke angka
                $dtks->kode_provinsi,
                $dtks->kode_kabupaten,
                $dtks->kode_kecamatan,
                $dtks->kode_desa,
                $dtks->kode_sls_non_sls,
                $dtks->kode_sub_sls,
                $dtks->nama_sls_non_sls,
                $dtks->keluarga->kepalaKeluarga->alamat_wilayah,
                $dtks->keluarga->kepalaKeluarga->nama,
                $dtks->no_urut_bangunan_tinggal,
                $dtks->no_urut_keluarga_verif,
                $dtks->status_keluarga,
                $dtks->jumlah_anggota_dtks,
                $dtks->kode_landmark_wilkerstat,
                $dtks->kepala_keluarga->keluarga->no_kk,
                $dtks->kd_kk,
            ];

            // dapatkan kode field di judul kolom 'index 2', kemudian gabung ke data
            foreach (array_column(array_slice($judul, 16, count($judul)), 2) as $field) {
                $data[] = in_array($field, ['tanggal_pendataan', 'tanggal_pemeriksaan']) ? '' . $dtks->{$field} : $dtks->{$field};
            }

            $writer->addRow(Row::fromValues($data));
        }
    }

    protected function eksporAnggota(&$writer2, $dtks_v2)
    {
        $judul = [
            ['I. KETERANGAN TEMPAT', '', '101'],  // 01
            ['', '', '102'],  // 02
            ['', '', '103'],  // 03
            ['', '', '104'],  // 04
            ['', '', '105'],  // 05
            ['', '', '105a Kode Sub SLS'],  // 06
            ['', '', '109'],  // 07
            ['', '', '110 No Urut Keluarga'], // 08
            ['IV. KETERANGAN SOSIAL EKONOMI ANGGOTA KELUARGA', 'A. KETERANGAN DEMOGRAFI', 'No KK'], // 09
            ['', '', '401'], // 09
            ['', '', '402 Nama'], // 10
            ['', '', '403 NIK'], // 11
            ['', '', '404', 'kd_ket_keberadaan_art'], // 12
            ['', '', '405'], // 13
            ['', '', '406'], // 14
            ['', '', '407'], // 15
            ['', '', '408'], // 16
            ['', '', '409', 'kd_hubungan_dg_kk'], // 17
            ['', '', '410'], // 18
            ['', '', '411', 'kd_punya_kartuid'], // 19
            ['', 'B. Pendidikan', '412', 'kd_partisipasi_sekolah'], // 20
            ['', '', '413', 'kd_pendidikan_tertinggi'],
            ['', '', '414', 'kd_kelas_tertinggi'],
            ['', '', '415', 'kd_ijazah_tertinggi'],
            ['', 'C. Ketenagakerjaan', '416a', 'kd_bekerja_seminggu_lalu'], // 24
            ['', '', '416b', 'jumlah_jam_kerja_seminggu_lalu'],
            ['', '', '417', 'kd_lapangan_usaha_pekerjaan'],
            ['', '', '417 Tulis', 'tulis_lapangan_usaha_pekerjaan'],
            ['', '', '418', 'kd_kedudukan_di_pekerjaan'],
            ['', '', '419', 'kd_punya_npwp'],
            ['', 'D. Kepemilikan Usaha', '420a', 'kd_punya_usaha_sendiri_bersama'], // 30
            ['', '', '420b', 'jumlah_usaha_sendiri_bersama'],
            ['', '', '421', 'kd_lapangan_usaha_dr_usaha'],
            ['', '', '421 Tulis', 'tulis_lapangan_usaha_dr_usaha'],
            ['', '', '422', 'jumlah_pekerja_dibayar'],
            ['', '', '423', 'jumlah_pekerja_tidak_dibayar'],
            ['', '', '424', 'kd_kepemilikan_ijin_usaha'],
            ['', '', '425', 'kd_omset_usaha_perbulan'],
            ['', '', '426', 'kd_guna_internet_usaha'],
            ['', 'E. Kesehatan', '427', 'kd_gizi_seimbang'], // 39
            ['', '', '428a', 'kd_sulit_penglihatan'],
            ['', '', '428b', 'kd_sulit_pendengaran'],
            ['', '', '428c', 'kd_sulit_jalan_naiktangga'],
            ['', '', '428d', 'kd_sulit_gerak_tangan_jari'],
            ['', '', '428e', 'kd_sulit_belajar_intelektual'],
            ['', '', '428f', 'kd_sulit_perilaku_emosi'],
            ['', '', '428g', 'kd_sulit_paham_bicara_kom'],
            ['', '', '428h', 'kd_sulit_mandiri'],
            ['', '', '428i', 'kd_sulit_ingat_konsentrasi'],
            ['', '', '428j', 'kd_sering_sedih_depresi'],
            ['', '', '429', 'kd_memiliki_perawat'],
            ['', '', '430', 'kd_penyakit_kronis_menahun'],
            ['', 'F. Program Perlindungan Sosial', '431a', 'kd_jamkes_setahun'], //52
            ['', '', '431b', 'kd_ikut_prakerja'],
            ['', '', '431c', 'kd_ikut_kur'],
            ['', '', '431d', 'kd_ikut_umi'],
            ['', '', '431e', 'kd_ikut_pip'],
            ['', '', '431f', 'jumlah_jamket_kerja'],
        ];

        $writer2->addNewSheetAndMakeItCurrent()->setName('Anggota Keluarga');
        $writer2->addRow(Row::fromValues(array_column($judul, 0)));
        $writer2->addRow(Row::fromValues(array_column($judul, 1)));
        $writer2->addRow(Row::fromValues(array_column($judul, 2)));

        // $writer2->mergeCells([0,1] ,  [7, 2]);     // Bag 1
        // $writer2->mergeCells([8, 1] ,  [56, 1]);     // Bag 4
        // $writer2->mergeCells([9, 2] ,  [18, 2]);     // demogra
        // $writer2->mergeCells([20, 2] , [22, 2]);     // pen
        // $writer2->mergeCells([24, 2] , [28, 2]);     // ketkerja
        // $writer2->mergeCells([30, 2] , [37, 2]);     // kep usaha
        // $writer2->mergeCells([38, 2] , [50, 2]);     // kesehat
        // $writer2->mergeCells([51, 2] , [56, 2]);     // prog sos

        foreach ($dtks_v2 as $dtks) {
            $dtks = $this->generateDefaultDtks($dtks);

            foreach ($dtks->dtksAnggota as $key => $agt) {
                $data = [
                    $dtks->kode_provinsi,
                    $dtks->kode_kabupaten,
                    $dtks->kode_kecamatan,
                    $dtks->kode_desa,
                    $dtks->kode_sls_non_sls,
                    $dtks->kode_sub_sls,
                    $dtks->no_urut_bangunan_tinggal,
                    $dtks->no_urut_keluarga_verif,

                    $agt->no_kk,
                    $key + 1,
                    $agt->nama,
                    $agt->nik,
                    $agt->kd_ket_keberadaan_art,
                    $agt->kd_jenis_kelamin,
                    $agt->tgl_lahir->format('Y-m-d'),
                    $agt->umur,
                    $agt->kd_stat_perkawinan,
                    $agt->kd_hubungan_dg_kk,
                    $agt->kd_status_kehamilan,
                    $agt->kd_punya_kartuid,
                ];

                // dapatkan kode field di judul kolom 'index 2', kemudian gabung ke data
                foreach (array_column(array_slice($judul, 19, count($judul)), 3) as $field) {
                    $data[] = $agt->{$field};
                }

                $writer2->addRow(Row::fromValues($data));
            }
        }
    }

    /**
     * Syncronize Data OpenSid to Form RegsosEk2022K
     *
     * @param \App\Models\Config $config
     */
    public function syncronizeWithOpenSid(Dtks $dtks): Dtks
    {
        $dtks->load([
            'rtm',
            'rtm.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withOnly('Wilayah', 'keluarga');
            },
            'rtm.anggota' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withOnly('keluarga');
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);

        try {
            $kode_desa_bps = getKodeDesaFromTrackSID()['bps_kemendagri_desa']['kode_desa_bps'];

            //  I. Keterangan Tempat
            $dtks->kode_provinsi  = $kode_desa_bps ? substr($kode_desa_bps, 0, 2) : ''; // 101
            $dtks->kode_kabupaten = $kode_desa_bps ? substr($kode_desa_bps, 2, 2) : ''; // 102
            $dtks->kode_kecamatan = $kode_desa_bps ? substr($kode_desa_bps, 2 + 2, 3) : ''; // 103
            $dtks->kode_desa      = $kode_desa_bps ? substr($kode_desa_bps, 2 + 2 + 3, 3) : ''; // 104
        } catch (Throwable $th) {
            log_message('error', $th);
        }
        // $dtks->kode_sls_non_sls = null; // 105
        // $dtks->kode_sub_sls     = null; // 105_sub
        $dtks->nama_sls_non_sls = $dtks->keluarga->kepalaKeluarga->alamat_wilayah; // 106
        // $dtks->alamat           = $dtks->keluarga->kepalaKeluarga->alamat_wilayah; // attribute  // 107
        // $dtks->nik_krt          = $dtks->keluarga->kepalaKeluarga->nik;
        // $dtks->no_urut_bangunan_tinggal  = null; // 109
        // $dtks->no_urut_keluarga_verif    = null; // 110
        // $dtks->status_keluarga           = null; // 111
        // $dtks->kode_landmark_wilkerstat  = null; // 113
        // $dtks->no_kk            = $dtks->keluarga->kepalaKeluarga->keluarga->no_kk; // 114
        // $dtks->kd_kk            = null; // 115

        $this->saveRelatedAttribute($dtks);

        $ref_eloquent_collection['hubungan_dengan_kk'] = $this->cacheTemporaryModelGet(PendudukHubungan::class);
        $daftar_sakit_menahun                          = $this->cacheTemporaryModelGet(SakitMenahun::class);
        $daftar_pendidikan                             = $this->cacheTemporaryModelGet(Pendidikan::class);
        $ref_eloquent_collection['kia']                = KIA::whereIn('ibu_id', $dtks->rtm->anggota->pluck('id'))
            ->orWhereIn('anak_id', $dtks->rtm->anggota->pluck('id'))->get();

        $kepala_keluarga = $dtks->rtm->kepalaKeluarga;
        $dtks_anggotas   = [];

        foreach ($dtks->rtm->anggota as $agt) {
            // cek data dtks anggota yang lepas
            $dtks_anggota = DtksAnggota::where('id_penduduk', $agt->id)->first();
            if (! $dtks_anggota) {
                $dtks_anggota = new DtksAnggota();
            }
            $usia_dinamis              = $agt->umur; // attribute
            $dtks_anggota->id_penduduk = $agt->id;
            $dtks_anggota->id_keluarga = $agt->keluarga->id;

            $dtks_anggota = $this->syncKetDemografi($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);

            if ($usia_dinamis >= 5) {
                $dtks_anggota = $this->syncPendidikan($dtks_anggota, $agt, $daftar_pendidikan);
                $dtks_anggota = $this->syncKetenagakerjaan($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);
                $dtks_anggota = $this->syncKepemilikanUsaha($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);
            }

            $dtks_anggota = $this->syncKesehatan($dtks_anggota, $agt, $daftar_sakit_menahun);
            $dtks_anggota = $this->syncProgramPerlindunganSosial($dtks_anggota, $agt, $kepala_keluarga, $ref_eloquent_collection);

            $dtks_anggotas[] = $dtks_anggota;
        }

        // save and sync dtks with dtks anggota
        $dtks->dtksAnggota()->saveMany($dtks_anggotas);

        return $this->syncKepesertaanProgramKeluarga($dtks);
    }

    /**
     * Save Data in Form RegsosEk2022k
     *
     * @return array['content' => '', 'header_code' => '']
     */
    public function save(array $request, ?Dtks $dtks = null): array
    {
        $tipe = [
            'bagian1',
            'bagian2',
            'bagian3',
            'bagian5',
            'bagian6',
            'bagian7_upload',
            'bagian4_demografi',
            'bagian4_pendidikan',
            'bagian4_ketenagakerjaan',
            'bagian4_kepemilikan_usaha',
            'bagian4_kesehatan',
            'bagian4_program_perlindungan_sosial',
            'pengaturan_program',
        ];
        if (! in_array($request['tipe_save'], $tipe)) {
            return ['content' => ['message' => 'Tipe tidak ditemukan'], 'header_code' => 406];
        }

        // contoh = saveBagian2
        $method = Str::camel('save_' . $request['tipe_save']);
        if (! method_exists($this, $method)) {
            return ['content' => ['message' => 'Proses simpan pada bagian ini tidak ditemukan, silahkan hubungi developer'], 'header_code' => 404];
        }

        try {
            if ($dtks == null) {
                return $this->{$method}($request);
            }

            return $this->{$method}($dtks, $request);
        } catch (Throwable $th) {
            log_message('error', $th);

            return ['content' => ['message' => 'Terjadi Error, silahkan hubungi developer'], 'header_code' => 500];
        }
    }

    /**
     * Remove Some Data
     *
     * @return array['content' => '', 'header_code' => '']
     */
    public function remove(Dtks $dtks, array $request): array
    {
        $tipe = [
            'lampiran',
        ];
        if (! in_array($request['tipe_remove'], $tipe)) {
            return ['content' => ['message' => 'Tipe tidak ditemukan'], 'header_code' => 406];
        }

        $method = Str::camel('remove_' . $request['tipe_remove']);
        if (! method_exists($this, $method)) {
            return ['content' => ['message' => 'Proses remove pada bagian ini tidak ditemukan, silahkan hubungi developper'], 'header_code' => 404];
        }

        return $this->{$method}($dtks, $request);
    }

    protected function removeLampiran(Dtks $dtks, array $request): array
    {
        $lampiran_id = bilangan($request['lampiran_id']);

        if ($lampiran_id == null) {
            return ['content' => ['message' => 'ID Lampiran salah'], 'header_code' => 404];
        }

        $lampiran = DtksLampiran::withCount('dtks')->where('id', $lampiran_id)->first();

        if (! $lampiran) {
            return ['content' => ['message' => 'Lampiran tidak ditemukan'], 'header_code' => 404];
        }
        // kalau lampiran hanya terkait di dtks ini hapus file dan lampiran
        if ($lampiran->dtks_count == 1) {
            DtksLampiran::findOrFail($lampiran_id)->delete();
        }

        return ['content' => ['message' => 'Berhasil dihapus', 'data' => $lampiran], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian1(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['input']['1'] as $key => $input) {
            if (in_array($key, ['105', '105sub'])) {
                $request['input']['1'][$key] = alfanumerik($input);
            }
            if ($key == '105' && strlen($request['input']['1']['105']) > 4) {
                $message[] = "No.{$key}: Kode SLS/Non SLS maksimal 4 huruf/angka";
            }
            if ($key == '105sub' && strlen($request['input']['1']['105sub']) > 2) {
                $message[] = "No.{$key}: Kode Sub SLS maksimal 2 huruf/angka";
            }
            if (in_array($key, ['106', '107'])) {
                $request['input']['1'][$key] = alamat($input);
            }
            if ($key == '106' && strlen($request['input']['1']['106']) > 100) {
                $message[] = "No.{$key}: Nama SLS/Non SLS maksimal 100 huruf/angka/spasi/titik/koma/tanda petik/strip/garis miring";
            }
            if (in_array($key, ['109', '110']) && $input != '' && ! is_numeric($input) && strlen($request['input']['1'][$key]) < 0 && strlen($request['input']['1'][$key]) > 999) {
                $message[] = "No.{$key}: Harus berisi angka, minimal 1 angka dan maksimal 3 angka";
            }
            if ($key == '111' && strlen($request['input']['1']['111']) > 1) {
                $message[] = "No.{$key}: Maksimal 1 huruf/angka";
            }
            if ($key != '113') {
                continue;
            }
            if (strlen($request['input']['1']['113']) <= 6) {
                continue;
            }
            $message[] = "No.{$key}: Maksimal 6 huruf/angka";
        }

        if ($request['pilihan']['1']['115'] != '' && ! array_key_exists($request['pilihan']['1']['115'], Regsosek2022kEnum::pilihanBagian1()['115'])) {
            $message[] = 'Kode Kartu Keluarga: Pilihan tidak ditemukan';
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        // validasi ada di perulangan diatas
        $dtks->kode_sls_non_sls = $this->null_or_value($request['input']['1']['105']);
        $dtks->kode_sub_sls     = $this->null_or_value($request['input']['1']['105sub']);
        $dtks->nama_sls_non_sls = $this->null_or_value($request['input']['1']['106']);
        // $dtks->alamat                   = $this->null_or_value($request['input']['1']['107']);
        $dtks->no_urut_bangunan_tinggal = $this->null_or_value($request['input']['1']['109']);
        $dtks->no_urut_keluarga_verif   = $this->null_or_value($request['input']['1']['110']);
        $dtks->status_keluarga          = $this->null_or_value($request['input']['1']['111']);
        $dtks->kode_landmark_wilkerstat = $this->null_or_value($request['input']['1']['113']);
        $dtks->kd_kk                    = $this->null_or_value($request['pilihan']['1']['115']);

        $this->saveRelatedAttribute($dtks);

        return ['content' => ['message' => 'Berhasil disimpan'], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian2(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['input']['2'] as $key => $input) {
            if (in_array($key, ['201', '203']) && $input != '' && validate_date($input)) {
                $message[] = "No.{$key}: Tanggal tidak sesuai ";
            }
            if (in_array($key, ['202', '204', 'responden']) && $input != '' && cekNama($input)) {
                $message[] = ($key == 'responden' ? 'Responden' : 'No.' . $key) .
                    ': Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip ';
            }
            if (in_array($key, ['202', '204responden'])) {
                $request['input']['2'][$key] = nama($input);
            }
            if (in_array($key, ['202a', '402a'])) {
                $request['input']['2'][$key] = alfanumerik($input);
            }
            if ($key == '202a' && strlen($request['input']['2']['202a']) > 4) {
                $message[] = "No.{$key}: Kode pencacah maksimal 4 huruf/angka";
            }
            if ($key == '204a' && strlen($request['input']['2']['204a']) > 3) {
                $message[] = "No.{$key}: Kode pemeriksa maksimal 3 huruf/angka";
            }
            if ($key != 'responden_hp') {
                continue;
            }
            if (strlen($request['input']['2']['responden_hp']) <= 16) {
                continue;
            }
            $message[] = "No.{$key}: Nomor Hp maksimal 16 angka";
        }

        if ($request['pilihan']['2']['205'] != '' && ! array_key_exists($request['pilihan']['2']['205'], Regsosek2022kEnum::pilihanBagian2()['205'])) {
            $message[] = 'Hasil pendataan keluarga: Pilihan tidak ditemukan';
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        // validasi ada di perulangan diatas
        $dtks->tanggal_pendataan           = $this->null_or_value($request['input']['2']['201']);
        $dtks->nama_ppl                    = $this->null_or_value($request['input']['2']['202']);
        $dtks->kode_ppl                    = $this->null_or_value($request['input']['2']['202a']);
        $dtks->tanggal_pemeriksaan         = $this->null_or_value($request['input']['2']['203']);
        $dtks->nama_pml                    = $this->null_or_value($request['input']['2']['204']);
        $dtks->kode_pml                    = $this->null_or_value($request['input']['2']['204a']);
        $dtks->nama_responden              = $this->null_or_value($request['input']['2']['responden']);
        $dtks->no_hp_responden             = $this->null_or_value($request['input']['2']['responden_hp']);
        $dtks->kd_hasil_pendataan_keluarga = $this->null_or_value($request['pilihan']['2']['205']);

        $this->saveRelatedAttribute($dtks);

        return ['content' => ['message' => 'Berhasil disimpan'], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian3(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['input']['3'] as $key => $input) {
            if (in_array($key, ['302']) && $input != '' && ! is_numeric($input)) {
                $message[] = "No.{$key}: Tidak sesuai ";
            }
            if ($key != '302') {
                continue;
            }
            if (strlen($request['input']['3']['302']) <= 3) {
                continue;
            }
            $message[] = "No.{$key}: Luas lantai maksimal 3 angka";
        }

        foreach ($request['pilihan']['3'] as $key => $input) {
            if ($input != '' && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian3()["{$key}"])) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
            if (array_key_exists($input, Regsosek2022kEnum::pilihanBagian3()["{$key}"])) {
                continue;
            }
            if ($input == '') {
                continue;
            }
            $message[] = "No {$key}: {$input} Pilihan tidak ditemukan";
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $dtks->kd_stat_bangunan_tinggal = $this->null_or_value($request['pilihan']['3']['301a']);
        $dtks->kd_sertiv_lahan_milik    = $dtks->kd_stat_bangunan_tinggal == '1'
            ? $this->null_or_value($request['pilihan']['3']['301b'])
            : null;
        $dtks->luas_lantai                = $this->null_or_value(bilangan($request['input']['3']['302']));
        $dtks->kd_jenis_lantai_terluas    = $this->null_or_value($request['pilihan']['3']['303']);
        $dtks->kd_jenis_dinding           = $this->null_or_value($request['pilihan']['3']['304']);
        $dtks->kd_jenis_atap              = $this->null_or_value($request['pilihan']['3']['305']);
        $dtks->kd_sumber_air_minum        = $this->null_or_value($request['pilihan']['3']['306a']);
        $dtks->kd_jarak_sumber_air_ke_tpl = in_array($dtks->kd_sumber_air_minum, ['4', '5', '6', '7', '8'])
            ? $this->null_or_value($request['pilihan']['3']['306b'])
            : null;
        $dtks->kd_sumber_penerangan_utama = $this->null_or_value($request['pilihan']['3']['307a']);
        $dtks->kd_daya_terpasang          = $dtks->kd_sumber_penerangan_utama == '1'
            ? $this->null_or_value($request['pilihan']['3']['307b1'])
            : null;
        $dtks->kd_daya_terpasang2 = $dtks->kd_sumber_penerangan_utama == '1'
            ? $this->null_or_value($request['pilihan']['3']['307b2'])
            : null;
        $dtks->kd_daya_terpasang3 = $dtks->kd_sumber_penerangan_utama == '1'
            ? $this->null_or_value($request['pilihan']['3']['307b3'])
            : null;
        $dtks->kd_bahan_bakar_memasak  = $this->null_or_value($request['pilihan']['3']['308']);
        $dtks->kd_fasilitas_tempat_bab = $this->null_or_value($request['pilihan']['3']['309a']);
        $dtks->kd_jenis_kloset         = in_array($dtks->kd_fasilitas_tempat_bab, ['1', '2', '3'])
            ? $this->null_or_value($request['pilihan']['3']['309b'])
            : null;
        $dtks->kd_pembuangan_akhir_tinja = $this->null_or_value($request['pilihan']['3']['310']);

        $this->saveRelatedAttribute($dtks);

        return ['content' => ['message' => 'Berhasil disimpan'], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian5(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['input']['5'] as $key => $input) {
            if (in_array($key, ['504a', '504b', '504c', '504d', '504e']) && $input == '') {
                $request['input']['5'][$key] = 0;
            }
            if (in_array($key, ['504a', '504b', '504c', '504d', '504e']) && $input != '' && ! is_numeric($input) && $input < 0 && $input > 999) {
                $message[] = "No.{$key}: {$input} Tidak sesuai, Minimal 0 dan Maksimal 999";
            }
        }

        foreach ($request['pilihan']['5'] as $key => $input) {
            if ($input != '' && in_array($key, [
                '501a_dapat', '501b_dapat', '501c_dapat', '501d_dapat', '501e_dapat', '501f_dapat', '501g_dapat',
                '502a', '502b', '502c', '502d', '502e', '502f', '502g', '502h', '502i', '502j', '502k', '502l', '502m', '502n',
                '503a', '503b',
            ])) {
                if (! array_key_exists($input, Regsosek2022kEnum::YA_TIDAK)) {
                    $message[] = "No {$key}: Pilihan yg tersedia hanya ya atau tidak";
                }
            } elseif ($input != '' && similar_text($key, '_bulan') == strlen('_bulan')) {
                if (! array_key_exists($input, bulan())) {
                    $message[] = "No {$key}: Bulan salah";
                }
            } elseif ($input != '' && similar_text($key, '_tahun') == strlen('_tahun')) {
                if (! validate_date($input, 'Y')) {
                    $message[] = "No {$key}: Tahun salah";
                }
            } elseif ($input != '' && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian5()["{$key}"])) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $dtks->kd_bss_bnpt        = $this->null_or_value($request['pilihan']['5']['501a_dapat']);
        $dtks->kd_pkh             = $this->null_or_value($request['pilihan']['5']['501b_dapat']);
        $dtks->kd_blt_dana_desa   = $this->null_or_value($request['pilihan']['5']['501c_dapat']);
        $dtks->kd_subsidi_listrik = $this->null_or_value($request['pilihan']['5']['501d_dapat']);
        $dtks->kd_bantuan_pemda   = $this->null_or_value($request['pilihan']['5']['501e_dapat']);
        $dtks->kd_subsidi_pupuk   = $this->null_or_value($request['pilihan']['5']['501f_dapat']);
        $dtks->kd_subsidi_lpg     = $this->null_or_value($request['pilihan']['5']['501g_dapat']);

        $dtks->bulan_bss_bnpt        = $this->null_or_value($request['pilihan']['5']['501a_bulan']);
        $dtks->bulan_pkh             = $this->null_or_value($request['pilihan']['5']['501b_bulan']);
        $dtks->bulan_blt_dana_desa   = $this->null_or_value($request['pilihan']['5']['501c_bulan']);
        $dtks->bulan_subsidi_listrik = $this->null_or_value($request['pilihan']['5']['501d_bulan']);
        $dtks->bulan_bantuan_pemda   = $this->null_or_value($request['pilihan']['5']['501e_bulan']);
        $dtks->bulan_subsidi_pupuk   = $this->null_or_value($request['pilihan']['5']['501f_bulan']);
        $dtks->bulan_subsidi_lpg     = $this->null_or_value($request['pilihan']['5']['501g_bulan']);

        $dtks->tahun_bss_bnpt        = $this->null_or_value($request['pilihan']['5']['501a_tahun']);
        $dtks->tahun_pkh             = $this->null_or_value($request['pilihan']['5']['501b_tahun']);
        $dtks->tahun_blt_dana_desa   = $this->null_or_value($request['pilihan']['5']['501c_tahun']);
        $dtks->tahun_subsidi_listrik = $this->null_or_value($request['pilihan']['5']['501d_tahun']);
        $dtks->tahun_bantuan_pemda   = $this->null_or_value($request['pilihan']['5']['501e_tahun']);
        $dtks->tahun_subsidi_pupuk   = $this->null_or_value($request['pilihan']['5']['501f_tahun']);
        $dtks->tahun_subsidi_lpg     = $this->null_or_value($request['pilihan']['5']['501g_tahun']);

        $dtks->kd_tabung_gas_5_5_kg    = $this->null_or_value($request['pilihan']['5']['502a']);
        $dtks->kd_lemari_es            = $this->null_or_value($request['pilihan']['5']['502b']);
        $dtks->kd_ac                   = $this->null_or_value($request['pilihan']['5']['502c']);
        $dtks->kd_pemanas_air          = $this->null_or_value($request['pilihan']['5']['502d']);
        $dtks->kd_telepon_rumah        = $this->null_or_value($request['pilihan']['5']['502d']);
        $dtks->kd_televisi             = $this->null_or_value($request['pilihan']['5']['502e']);
        $dtks->kd_perhiasan_10_gr_emas = $this->null_or_value($request['pilihan']['5']['502f']);
        $dtks->kd_komputer_laptop      = $this->null_or_value($request['pilihan']['5']['502g']);
        $dtks->kd_sepeda_motor         = $this->null_or_value($request['pilihan']['5']['502h']);
        $dtks->kd_sepeda               = $this->null_or_value($request['pilihan']['5']['502i']);
        $dtks->kd_mobil                = $this->null_or_value($request['pilihan']['5']['502k']);
        $dtks->kd_perahu               = $this->null_or_value($request['pilihan']['5']['502l']);
        $dtks->kd_kapal_perahu_motor   = $this->null_or_value($request['pilihan']['5']['502m']);
        $dtks->kd_smartphone           = $this->null_or_value($request['pilihan']['5']['502n']);

        $dtks->jumlah_sapi          = $this->null_or_value(bilangan($request['input']['5']['504a']));
        $dtks->jumlah_kerbau        = $this->null_or_value(bilangan($request['input']['5']['504b']));
        $dtks->jumlah_kuda          = $this->null_or_value(bilangan($request['input']['5']['504c']));
        $dtks->jumlah_babi          = $this->null_or_value(bilangan($request['input']['5']['504d']));
        $dtks->jumlah_kambing_domba = $this->null_or_value(bilangan($request['input']['5']['504e']));

        $dtks->kd_lahan               = $this->null_or_value($request['pilihan']['5']['503a']);
        $dtks->kd_rumah_ditempat_lain = $this->null_or_value($request['pilihan']['5']['503b']);
        $dtks->kd_internet_sebulan    = $this->null_or_value($request['pilihan']['5']['505']);
        $dtks->kd_rek_aktif           = $this->null_or_value($request['pilihan']['5']['506']);

        $this->saveRelatedAttribute($dtks);

        return ['content' => ['message' => 'Berhasil disimpan'], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian6(Dtks $dtks, array $request): array
    {
        $message = [];

        if ($request['catatan'] == '') {
            $message[] = 'Catatan tidak boleh kosong';
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $dtks->catatan = $this->null_or_value(alamat($request['catatan']));

        $this->saveRelatedAttribute($dtks);

        return ['content' => ['message' => 'Berhasil disimpan'], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian7Upload(Dtks $dtks, array $request): array
    {
        $message = [];

        $kamera      = $request['file_path'];
        $unggah_foto = $_FILES['foto'];
        $old_foto    = $request['old_foto'];
        $nama_file   = time() . mt_rand(10000, 999999);
        $judul       = nama($request['judul_foto']);
        $keterangan  = alamat($request['keterangan_foto']);
        $tempat_file = LOKASI_FOTO_DTKS;

        if ($keterangan == '') {
            return ['content' => ['message' => 'Keterangan harus diisi'], 'header_code' => 406];
        }

        // Buat folder desa/upload/dtks apabila belum ada
        if (! file_exists(LOKASI_FOTO_DTKS)) {
            mkdir(LOKASI_FOTO_DTKS, 0755);
        }
        // Buat folder desa/upload/dtks/{id_dtks} apabila belum ada
        if (! file_exists($tempat_file)) {
            mkdir($tempat_file, 0755);
        }

        if ($unggah_foto['error'] == 0) {
            $nama_file .= get_extension($unggah_foto['name']);

            $tipe_file   = TipeFile($unggah_foto);
            $dimensi     = ['width' => 200, 'height' => 200];
            $nama_simpan = 'kecil_' . $nama_file;

            if (! UploadResizeImage($tempat_file, $dimensi, 'foto', $nama_file, $nama_simpan, null, $tipe_file)) {
                $message[] = $_SESSION['error_msg'];
                unset($_SESSION['error_msg'], $_SESSION['success']);
            }
        } else {
            $nama_file .= '.png';
            $foto = str_replace('data:image/png;base64,', '', $kamera);
            $foto = base64_decode($foto, true);

            if ($foto == '') {
                $message[] = 'Foto belum dipilih/direkam';
            }

            file_put_contents($tempat_file . $nama_file, $foto);
            file_put_contents($tempat_file . 'kecil_' . $nama_file, $foto);
        }

        if ($message !== []) {
            unlink($tempat_file . $nama_file, $foto);
            unlink($tempat_file . 'kecil_' . $nama_file, $foto);

            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $lampiran = DtksLampiran::create([
            'judul'      => $judul,
            'keterangan' => $keterangan,
            'foto'       => $nama_file,
            'id_rtm'     => $dtks->rtm->id,
        ]);

        $lampiran['foto_kecil'] = site_url() . LOKASI_FOTO_DTKS . 'kecil_' . $nama_file;

        // simpan
        $dtks->lampiran()->attach($lampiran->id);

        return ['content' => ['message' => 'Berhasil disimpan', 'data' => $lampiran], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian4Demografi(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['pilihan']['4'] as $key => $input) {
            if (
                $input != '' && in_array($key, ['404', '408', '409', '410'])
                && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])
            ) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
            if ($input != '' && $key == '411') {
                $keys = explode(',', $input);

                foreach ($keys as $item) {
                    if (! array_key_exists($item, Regsosek2022kEnum::pilihanBagian4()['411'])) {
                        $message[] = "No {$key}: Pilihan tidak ditemukan";
                    }
                }
            }
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $selected_anggota = $dtks->dtksAnggota->where('id', $request['id_art'])->first();

        if (! $selected_anggota) {
            return ['content' => ['message' => 'Anggota keluarga tidak ditemukan'], 'header_code' => 406];
        }

        $selected_anggota->kd_ket_keberadaan_art = $this->null_or_value($request['pilihan']['4']['404']);
        // $selected_anggota->kd_stat_perkawinan       = $this->null_or_value($request['pilihan']['4']['408']);
        $selected_anggota->kd_hubungan_dg_kk = $this->null_or_value($request['pilihan']['4']['409']);
        // $selected_anggota->kd_status_kehamilan      = ($umur >= 10 && $umur <= 54 && in_array($selected_anggota->kd_stat_perkawinan, ['2', '3', '4']) && $selected_anggota->kd_jenis_kelamin == 2)
        //     ? $this->null_or_value($request['pilihan']['4']['410'])
        //     : null;
        $selected_anggota->kd_punya_kartuid = ($request['pilihan']['4']['411'] != '')
            ? $this->null_or_value(array_sum(explode(',', $request['pilihan']['4']['411'])))
            : null;

        $this->saveRelatedAttribute($selected_anggota);

        $new_data = [
            'id'                    => $selected_anggota->id,
            'kd_ket_keberadaan_art' => $selected_anggota->kd_ket_keberadaan_art,
            // 'kd_stat_perkawinan'       => $selected_anggota->kd_stat_perkawinan,
            'kd_hubungan_dg_kk' => $selected_anggota->kd_hubungan_dg_kk,
            // 'kd_status_kehamilan'      => $selected_anggota->kd_status_kehamilan,
            'kd_punya_kartuid' => $selected_anggota->kd_punya_kartuid,
        ];

        return ['content' => ['message' => 'Berhasil disimpan', 'new_data' => $new_data], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian4Pendidikan(Dtks $dtks, array $request): array
    {
        $message        = [];
        $pilihanBagian4 = Regsosek2022kEnum::pilihanBagian4(); // Avoid repeated function calls

        foreach ($request['pilihan']['4'] as $key => $input) {
            if ($input === '' || array_key_exists($input, $pilihanBagian4["{$key}"])) {
                continue;
            }
            $message[] = "No {$key}: Pilihan tidak ditemukan";
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $selected_anggota = $dtks->dtksAnggota->where('id', $request['id_art'])->first();
        $umur             = $selected_anggota->umur;

        if (! $selected_anggota) {
            return ['content' => ['message' => 'Anggota keluarga tidak ditemukan'], 'header_code' => 406];
        }

        $selected_anggota->kd_partisipasi_sekolah = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['412'])
            : null;
        $selected_anggota->kd_pendidikan_tertinggi = $umur >= 5 && in_array($selected_anggota->kd_partisipasi_sekolah, ['2', '3'])
            ? $this->null_or_value($request['pilihan']['4']['413'])
            : null;
        $selected_anggota->kd_kelas_tertinggi = $umur >= 5 && in_array($selected_anggota->kd_partisipasi_sekolah, ['2', '3'])
            ? $this->null_or_value($request['pilihan']['4']['414'])
            : null;
        $selected_anggota->kd_ijazah_tertinggi = $umur >= 5 && in_array($selected_anggota->kd_partisipasi_sekolah, ['2', '3'])
            ? $this->null_or_value($request['pilihan']['4']['415'])
            : null;

        $this->saveRelatedAttribute($selected_anggota);

        $new_data = [
            'id'                      => $selected_anggota->id,
            'kd_partisipasi_sekolah'  => $selected_anggota->kd_partisipasi_sekolah,
            'kd_pendidikan_tertinggi' => $selected_anggota->kd_pendidikan_tertinggi,
            'kd_kelas_tertinggi'      => $selected_anggota->kd_kelas_tertinggi,
            'kd_ijazah_tertinggi'     => $selected_anggota->kd_ijazah_tertinggi,
        ];

        return ['content' => ['message' => 'Berhasil disimpan', 'new_data' => $new_data], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian4Ketenagakerjaan(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['pilihan']['4'] as $key => $input) {
            if ($input != '' && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
            if (array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                continue;
            }
            $message[] = "No {$key}: Pilihan tidak ditemukan";
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $selected_anggota = $dtks->dtksAnggota->where('id', $request['id_art'])->first();
        $umur             = $selected_anggota->umur;

        if (! $selected_anggota) {
            return ['content' => ['message' => 'Anggota keluarga tidak ditemukan'], 'header_code' => 406];
        }

        $selected_anggota->kd_bekerja_seminggu_lalu = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['416a'])
            : null;
        $selected_anggota->jumlah_jam_kerja_seminggu_lalu = $umur >= 5 && $selected_anggota->kd_bekerja_seminggu_lalu == '1'
            ? $this->null_or_value(bilangan($request['input']['4']['416b']))
            : null;
        $selected_anggota->kd_lapangan_usaha_pekerjaan = $umur >= 5 && $selected_anggota->kd_bekerja_seminggu_lalu == '1'
            ? $this->null_or_value($request['pilihan']['4']['417'])
            : null;
        $selected_anggota->tulis_lapangan_usaha_pekerjaan = $umur >= 5 && $selected_anggota->kd_bekerja_seminggu_lalu == '1'
            ? alamat($request['input']['4']['lapangan_usaha_pekerjaan'])
            : '';
        $selected_anggota->kd_kedudukan_di_pekerjaan = $umur >= 5 && $selected_anggota->kd_bekerja_seminggu_lalu == '1'
            ? $this->null_or_value($request['pilihan']['4']['418'])
            : null;
        $selected_anggota->kd_punya_npwp = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['419'])
            : null;

        $this->saveRelatedAttribute($selected_anggota);

        $new_data = [
            'id'                             => $selected_anggota->id,
            'kd_bekerja_seminggu_lalu'       => $selected_anggota->kd_bekerja_seminggu_lalu,
            'jumlah_jam_kerja_seminggu_lalu' => $selected_anggota->jumlah_jam_kerja_seminggu_lalu,
            'kd_lapangan_usaha_pekerjaan'    => $selected_anggota->kd_lapangan_usaha_pekerjaan,
            'tulis_lapangan_usaha_pekerjaan' => $selected_anggota->tulis_lapangan_usaha_pekerjaan,
            'kd_kedudukan_di_pekerjaan'      => $selected_anggota->kd_kedudukan_di_pekerjaan,
            'kd_punya_npwp'                  => $selected_anggota->kd_punya_npwp,
        ];

        return ['content' => ['message' => 'Berhasil disimpan', 'new_data' => $new_data], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian4KepemilikanUsaha(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['input']['4'] as $key => $input) {
            if (in_array($key, ['420b', '423']) && $input != '' && ! is_numeric($input) && strlen($request['input']['4'][$key]) > 2) {
                $message[] = "No.{$key}: {$input} Tidak sesuai, maksimal 99";
            }
            if (in_array($key, ['422']) && $input != '' && ! is_numeric($input) && strlen($request['input']['4'][$key]) > 3) {
                $message[] = "No.{$key}: {$input} Tidak sesuai, maksimal 999";
            }
        }

        foreach ($request['pilihan']['4'] as $key => $input) {
            if ($input != '' && $key == '426') {
                $keys = explode(',', $input);

                foreach ($keys as $item) {
                    if (! array_key_exists($item, Regsosek2022kEnum::pilihanBagian4()['426'])) {
                        $message[] = "No {$key}: Pilihan tidak ditemukan";
                    }
                }
            }
            if ($input != '' && ! in_array($key, ['426']) && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $selected_anggota = $dtks->dtksAnggota->where('id', $request['id_art'])->first();
        $umur             = $selected_anggota->umur;

        if (! $selected_anggota) {
            return ['content' => ['message' => 'Anggota keluarga tidak ditemukan'], 'header_code' => 406];
        }

        $selected_anggota->kd_punya_usaha_sendiri_bersama = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['420a'])
            : null;
        $selected_anggota->jumlah_usaha_sendiri_bersama = $umur >= 5 && bilangan($request['input']['4']['420b']) == null && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? 0 : $this->null_or_value(bilangan($request['input']['4']['420b']));
        $selected_anggota->kd_lapangan_usaha_dr_usaha = $umur >= 5 && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? $this->null_or_value($request['pilihan']['4']['421'])
            : null;
        $selected_anggota->tulis_lapangan_usaha_dr_usaha = $umur >= 5 && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? alamat($request['input']['4']['lapangan_usaha_dr_usaha'])
            : '';
        $selected_anggota->jumlah_pekerja_dibayar = $umur >= 5 && bilangan($request['input']['4']['422']) == null && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? 0 : bilangan(bilangan($request['input']['4']['422']));
        $selected_anggota->jumlah_pekerja_tidak_dibayar = $umur >= 5 && bilangan($request['input']['4']['423']) == null && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? 0 : bilangan(bilangan($request['input']['4']['423']));
        $selected_anggota->kd_kepemilikan_ijin_usaha = $umur >= 5 && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? $this->null_or_value($request['pilihan']['4']['424'])
            : null;
        $selected_anggota->kd_omset_usaha_perbulan = $umur >= 5 && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1'
            ? $this->null_or_value($request['pilihan']['4']['425'])
            : null;
        $selected_anggota->kd_guna_internet_usaha = $umur >= 5 && $selected_anggota->kd_punya_usaha_sendiri_bersama == '1' && ($request['pilihan']['4']['426'] != '')
            ? $this->null_or_value(array_sum(explode(',', $request['pilihan']['4']['426'])))
            : null;

        $this->saveRelatedAttribute($selected_anggota);

        $new_data = [
            'id'                             => $selected_anggota->id,
            'kd_punya_usaha_sendiri_bersama' => $selected_anggota->kd_punya_usaha_sendiri_bersama,
            'jumlah_usaha_sendiri_bersama'   => $selected_anggota->jumlah_usaha_sendiri_bersama,
            'kd_lapangan_usaha_dr_usaha'     => $selected_anggota->kd_lapangan_usaha_dr_usaha,
            'tulis_lapangan_usaha_dr_usaha'  => $selected_anggota->tulis_lapangan_usaha_dr_usaha,
            'jumlah_pekerja_dibayar'         => $selected_anggota->jumlah_pekerja_dibayar,
            'jumlah_pekerja_tidak_dibayar'   => $selected_anggota->jumlah_pekerja_tidak_dibayar,
            'kd_kepemilikan_ijin_usaha'      => $selected_anggota->kd_kepemilikan_ijin_usaha,
            'kd_omset_usaha_perbulan'        => $selected_anggota->kd_omset_usaha_perbulan,
            'kd_guna_internet_usaha'         => $selected_anggota->kd_guna_internet_usaha,
        ];

        return ['content' => ['message' => 'Berhasil disimpan', 'new_data' => $new_data], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian4Kesehatan(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['pilihan']['4'] as $key => $input) {
            if ($input != '' && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
            if (array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                continue;
            }
            if ($input == '') {
                continue;
            }
            $message[] = "No {$key}: Pilihan tidak ditemukan";
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $selected_anggota = $dtks->dtksAnggota->where('id', $request['id_art'])->first();
        $umur             = $selected_anggota->umur;

        if (! $selected_anggota) {
            return ['content' => ['message' => 'Anggota keluarga tidak ditemukan'], 'header_code' => 406];
        }

        $selected_anggota->kd_gizi_seimbang = ($umur <= 4)
            ? $this->null_or_value($request['pilihan']['4']['427'])
            : null;
        $selected_anggota->kd_sulit_penglihatan = ($umur >= 2)
            ? $this->null_or_value($request['pilihan']['4']['428a'])
            : null;
        $selected_anggota->kd_sulit_pendengaran = ($umur >= 2)
            ? $this->null_or_value($request['pilihan']['4']['428b'])
            : null;
        $selected_anggota->kd_sulit_jalan_naiktangga = ($umur >= 2)
            ? $this->null_or_value($request['pilihan']['4']['428c'])
            : null;
        $selected_anggota->kd_sulit_gerak_tangan_jari = ($umur >= 2)
            ? $this->null_or_value($request['pilihan']['4']['428d'])
            : null;
        $selected_anggota->kd_sulit_belajar_intelektual = ($umur >= 2)
            ? $this->null_or_value($request['pilihan']['4']['428e'])
            : null;
        $selected_anggota->kd_sulit_perilaku_emosi = ($umur >= 2)
            ? $this->null_or_value($request['pilihan']['4']['428f'])
            : null;
        $selected_anggota->kd_sulit_paham_bicara_kom = ($umur >= 5)
            ? $this->null_or_value($request['pilihan']['4']['428g'])
            : null;
        $selected_anggota->kd_sulit_mandiri = ($umur >= 5)
            ? $this->null_or_value($request['pilihan']['4']['428h'])
            : null;
        $selected_anggota->kd_sulit_ingat_konsentrasi = ($umur >= 5)
            ? $this->null_or_value($request['pilihan']['4']['428i'])
            : null;
        $selected_anggota->kd_sering_sedih_depresi = ($umur >= 5)
            ? $this->null_or_value($request['pilihan']['4']['428j'])
            : null;
        $selected_anggota->kd_memiliki_perawat = (
            $umur >= 60
            || in_array($selected_anggota->kd_sulit_penglihatan, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_pendengaran, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_jalan_naiktangga, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_gerak_tangan_jari, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_belajar_intelektual, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_perilaku_emosi, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_paham_bicara_kom, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_mandiri, ['1', '2'])
            || in_array($selected_anggota->kd_sulit_ingat_konsentrasi, ['1', '2'])
            || in_array($selected_anggota->kd_sering_sedih_depresi, ['1', '2'])
        )
            ? $this->null_or_value($request['pilihan']['4']['429'])
            : null;
        $selected_anggota->kd_penyakit_kronis_menahun = $this->null_or_value($request['pilihan']['4']['430']);

        $this->saveRelatedAttribute($selected_anggota);

        $new_data = [
            'id'                           => $selected_anggota->id,
            'kd_gizi_seimbang'             => $selected_anggota->kd_gizi_seimbang,
            'kd_sulit_penglihatan'         => $selected_anggota->kd_sulit_penglihatan,
            'kd_sulit_pendengaran'         => $selected_anggota->kd_sulit_pendengaran,
            'kd_sulit_jalan_naiktangga'    => $selected_anggota->kd_sulit_jalan_naiktangga,
            'kd_sulit_gerak_tangan_jari'   => $selected_anggota->kd_sulit_gerak_tangan_jari,
            'kd_sulit_belajar_intelektual' => $selected_anggota->kd_sulit_belajar_intelektual,
            'kd_sulit_perilaku_emosi'      => $selected_anggota->kd_sulit_perilaku_emosi,
            'kd_sulit_paham_bicara_kom'    => $selected_anggota->kd_sulit_paham_bicara_kom,
            'kd_sulit_mandiri'             => $selected_anggota->kd_sulit_mandiri,
            'kd_sulit_ingat_konsentrasi'   => $selected_anggota->kd_sulit_ingat_konsentrasi,
            'kd_sering_sedih_depresi'      => $selected_anggota->kd_sering_sedih_depresi,
            'kd_memiliki_perawat'          => $selected_anggota->kd_memiliki_perawat,
            'kd_penyakit_kronis_menahun'   => $selected_anggota->kd_penyakit_kronis_menahun,
        ];

        return ['content' => ['message' => 'Berhasil disimpan', 'new_data' => $new_data], 'header_code' => 200];
    }

    /**
     * @return array['content' => '', 'header_code' => '']
     */
    protected function saveBagian4ProgramPerlindunganSosial(Dtks $dtks, array $request): array
    {
        $message = [];

        foreach ($request['pilihan']['4'] as $key => $input) {
            if ($input != '' && in_array($key, ['431a', '431f'])) {
                $keys = explode(',', $input);

                foreach ($keys as $item) {
                    if (! array_key_exists($item, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                        $message[] = "No {$key}: Pilihan tidak ditemukan";
                    }
                }
            }
            if ($input != '' && ! in_array($key, ['431a', '431f']) && ! array_key_exists($input, Regsosek2022kEnum::pilihanBagian4()["{$key}"])) {
                $message[] = "No {$key}: Pilihan tidak ditemukan";
            }
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        $selected_anggota = $dtks->dtksAnggota->where('id', $request['id_art'])->first();
        $umur             = $selected_anggota->umur;

        if (! $selected_anggota) {
            return ['content' => ['message' => 'Anggota keluarga tidak ditemukan'], 'header_code' => 406];
        }

        $selected_anggota->kd_jamkes_setahun = ($request['pilihan']['4']['431a'] != '')
            ? $this->null_or_value(array_sum(explode(',', $request['pilihan']['4']['431a'])))
            : null;
        $selected_anggota->kd_ikut_prakerja = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['431b'])
            : null;
        $selected_anggota->kd_ikut_kur = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['431c'])
            : null;
        $selected_anggota->kd_ikut_umi = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['431d'])
            : null;
        $selected_anggota->kd_ikut_pip = $umur >= 5
            ? $this->null_or_value($request['pilihan']['4']['431e'])
            : null;
        $selected_anggota->jumlah_jamket_kerja = ($request['pilihan']['4']['431f'] != '')
            ? $this->null_or_value(array_sum(explode(',', $request['pilihan']['4']['431f'])))
            : null;

        $this->saveRelatedAttribute($selected_anggota);

        $new_data = [
            'id'                  => $selected_anggota->id,
            'kd_jamkes_setahun'   => $selected_anggota->kd_jamkes_setahun,
            'kd_ikut_prakerja'    => $selected_anggota->kd_ikut_prakerja,
            'kd_ikut_kur'         => $selected_anggota->kd_ikut_kur,
            'kd_ikut_umi'         => $selected_anggota->kd_ikut_umi,
            'kd_ikut_pip'         => $selected_anggota->kd_ikut_pip,
            'jumlah_jamket_kerja' => $selected_anggota->jumlah_jamket_kerja,
        ];

        return ['content' => ['message' => 'Berhasil disimpan', 'new_data' => $new_data], 'header_code' => 200];
    }

    /**
     * @return mixed[][]
     */
    protected function savePengaturanProgram(array $request): array
    {
        $relasi  = static::relasiPengaturanProgram();
        $message = [];

        $bantuan_keluarga_rtm = Bantuan::whereIn('sasaran', [SasaranEnum::KELUARGA, SasaranEnum::PENDUDUK])->get();
        $is_for_anggota       = false;

        foreach ($request as $key => $item) {
            if ($item != '' && in_array($key, array_keys($relasi)) && (substr($key, -(strlen('default'))) !== 'default') && $bantuan_keluarga_rtm->where('id', $item)->count() == 0) {
                $message[] = "{$key}: Bantuan tidak ditemukan";
            } elseif ($item != '' && in_array($key, array_keys($relasi)) && in_array($key, ['431a1_431a4_default', '431f1_431f5_default']) && ! in_array($item, ['0', '99'])) {
                $message[] = "{$key}: Nilai bawaan tidak ditemukan";
            } elseif ($item != '' && in_array($key, array_keys($relasi)) && in_array($key, ['431b_default', '431c_default', '431d_default', '431e_default']) && ! in_array($item, ['2', '8'])) {
                $message[] = "{$key}: Nilai bawaan tidak ditemukan";
            }
            $is_for_anggota = $is_for_anggota || $relasi[$key][0] == 'dtks_anggota';
        }

        if ($message !== []) {
            return ['content' => ['message' => $message], 'header_code' => 406];
        }

        if ($is_for_anggota) {
            unset($relasi['501a'], $relasi['501b'], $relasi['501c'], $relasi['501d'], $relasi['501e'], $relasi['501f'], $relasi['501g']);
        } else {
            unset($relasi['431a1'], $relasi['431a2'], $relasi['431a3'], $relasi['431a4'], $relasi['431b'], $relasi['431c'], $relasi['431d'], $relasi['431e'], $relasi['431f1'], $relasi['431f2'], $relasi['431f3'], $relasi['431f4'], $relasi['431f5'], $relasi['431a1_431a4_default'], $relasi['431b_default'], $relasi['431c_default'], $relasi['431d_default'], $relasi['431e_default'], $relasi['431f1_431f5_default']);
        }

        // Ambil pengaturan program dtks untuk versi ini
        $target_table        = array_column($relasi, 0)[0];
        $pengaturan_programs = DtksPengaturanProgram::where('versi_kuisioner', '2')
            ->where('target_table', $target_table)
            ->whereIn('target_field', array_column($relasi, 1))
            ->get();

        $to_be_deleted  = [];
        $to_be_inserted = [];

        foreach ($relasi as $form_input_name => $item) {
            $pengaturan_program = $pengaturan_programs->where('kode', $form_input_name)->first();

            if ($request[$form_input_name] == '' && $pengaturan_program) {
                $to_be_deleted[] = $pengaturan_program->id;
            }
            // khusus pengaturan selain program anggota default
            elseif ($request[$form_input_name] != '' && $pengaturan_program && (substr($form_input_name, -(strlen('default'))) !== 'default') && $request[$form_input_name] != $pengaturan_program->id_bantuan) {
                $pengaturan_program->update(['id_bantuan' => $request[$form_input_name]]);
            }
            // khusus pengaturan program anggota default
            elseif ($request[$form_input_name] != '' && $pengaturan_program && (substr($form_input_name, -(strlen('default'))) === 'default') && $request[$form_input_name] != $pengaturan_program->nilai_default) {
                $pengaturan_program->update(['nilai_default' => $request[$form_input_name]]);
            } elseif ($request[$form_input_name] != '' && ! $pengaturan_program && (substr($form_input_name, -(strlen('default'))) !== 'default')) {
                $to_be_inserted[] = [
                    'config_id'       => identitas('id'),
                    'versi_kuisioner' => '2',
                    'kode'            => $form_input_name,
                    'target_table'    => $item[0],
                    'target_field'    => $item[1],
                    'id_bantuan'      => $request[$form_input_name],
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ];
            } elseif ($request[$form_input_name] != '' && ! $pengaturan_program && (substr($key, -(strlen('default'))) === 'default')) {
                $to_be_inserted[] = [
                    'config_id'       => identitas('id'),
                    'versi_kuisioner' => '2',
                    'kode'            => $form_input_name,
                    'target_table'    => $item[0],
                    'target_field'    => $item[1],
                    'nilai_default'   => $request[$form_input_name],
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ];
            }
        }
        if ($to_be_deleted !== []) {
            DtksPengaturanProgram::whereIn('id', $to_be_deleted)->delete();
        }
        if ($to_be_inserted !== []) {
            DtksPengaturanProgram::insert($to_be_inserted);
        }

        return ['content' => ['message' => 'Berhasil disimpan'], 'header_code' => 200];
    }

    public function syncKetDemografi(DtksAnggota $dtks_anggota, $agt, ?Penduduk $kepala_keluarga, array $ref_eloquent_collection): DtksAnggota
    {
        // $dtks_anggota->nama  = $agt->nama; // 402
        // $dtks_anggota->nik   = $agt->nik; // 403
        // $dtks_anggota->no_kk = $agt->keluarga->no_kk;
        // 404 karena data anggota yg diambil hanya anggota yang masih hidup,
        // set ke pilihan 1. Tinggal bersama keluarga
        $dtks_anggota->kd_ket_keberadaan_art = 1; // 404
        // $dtks_anggota->kd_jenis_kelamin      = $agt->sex;  // 405
        // $dtks_anggota->tgl_lahir             = $agt->tanggallahir; // 406
        // $dtks_anggota->umur                  = $agt->umur; // getAttribute // 407
        // $dtks_anggota->kd_stat_perkawinan    = $agt->status_kawin; // 408
        // jika anggota satu kk dengan kepala rumah tangga, hubungan dengan krt = hubungan dengan kk
        // jika bukan satu kk, maka hubungannya jadi lainnya, biar diatur sendiri oleh user
        if ($agt->id_kk == ($kepala_keluarga ? $kepala_keluarga->id_kk : null)) {
            $hubungan_dengan_kk              = $ref_eloquent_collection['hubungan_dengan_kk']->where('id', $agt->kk_level)->pluck('nama')->first();
            $dtks_anggota->kd_hubungan_dg_kk = $this->getIndexPilihanWithDefault(Regsosek2022kEnum::pilihanBagian4()['409'], $hubungan_dengan_kk);
        } else {
            $kd_hubungan_dg_kk = $this->getIndexPilihan(Regsosek2022kEnum::pilihanBagian4()['409'], 'Lainnya');
            // jika sinkron dengan data dtks, selainnya dapat disesuaikan manual
            if ($kd_hubungan_dg_kk != 8) {
                $dtks_anggota->kd_hubungan_dg_kk = $kd_hubungan_dg_kk;
            }
        }
        // if($dtks_anggota->umur >= 10 && $dtks_anggota->umur <= 54 && $dtks_anggota->kd_jenis_kelamin == 2 && in_array($dtks_anggota->kd_stat_perkawinan, ['2', '3', '4'])){
        //     $dtks_anggota->kd_status_kehamilan   = $agt->hamil; // 410
        // }else{
        // $dtks_anggota->kd_status_kehamilan   = null; // 410
        // }
        // 0:tidak punya, 1:akta lahir, 2:kia, 4:ktp
        $total = 0;
        if ($agt->akta_lahir) {
            $total++;
        }
        $is_ibu_anak_punya_data_kia = $ref_eloquent_collection['kia']->filter(static fn ($item): bool => $item->ibu_id == $agt->id || $item->anak_id == $agt->id);
        $ref_ktp_el                 = unserialize(KTP_EL);
        if ($is_ibu_anak_punya_data_kia->count() > 0 || $agt->ktp_el == $ref_ktp_el['kia']) {
            $total += 2;
        }
        if ($agt->ktp_el == $ref_ktp_el['ktp-el']) {
            $total += 4;
        }
        $dtks_anggota->kd_punya_kartuid = $total; // 411

        return $dtks_anggota;
    }

    public function syncPendidikan(DtksAnggota $dtks_anggota, $agt, Collection $daftar_pendidikan): DtksAnggota
    {
        // Setelah Tamat SD
        if (in_array($agt->pendidikan_kk_id, [3, 4, 5]) || in_array($agt->pendidikan_sedang_id, [6, 7])) {
            $dtks_anggota->kd_kelas_tertinggi = 8; // (tamat & lulus) // 414
        }

        $nama_pendidikan = $agt->pendidikan;
        // tidak/belum pernah sekolah
        if ($agt->pendidikan_sedang_id == 3) {
            $dtks_anggota->kd_partisipasi_sekolah = 1; // 413

            return $dtks_anggota;
        }
        // tidak sekolah lagi
        if ($agt->pendidikan_sedang_id == 18) {
            $dtks_anggota->kd_partisipasi_sekolah = 3; // 413
        }
        // sedang sekolah
        elseif (strpos($nama_pendidikan, 'SEDANG ') == 0) {
            $dtks_anggota->kd_partisipasi_sekolah = 2; // 413
        }

        // untuk D1 s.d S3
        if (in_array($agt->pendidikan_sedang_id, [8, 9, 10, 11, 12, 13]) || in_array($agt->pendidikan_kk_id, [6, 7, 8, 9, 10])) {
            // sedang => konversi nama ke => D1 s.d S3
            $nama_pendidikan = str_replace(['SEDANG', ' ', '-', '/SEDERAJAT'], '', $nama_pendidikan);
            // keterangan kk
            if (in_array($agt->pendidikan_kk_id, [6, 7])) {
                $pendidikan_kk = 'D1/D2/D3';
            } elseif ($agt->pendidikan_kk_id == 8) {
                $pendidikan_kk = 'S1';
            } elseif ($agt->pendidikan_kk_id == 9) {
                $pendidikan_kk = 'S2';
            } elseif ($agt->pendidikan_kk_id == 10) {
                $pendidikan_kk = 'S3';
            }

            $nama_pendidikan = in_array($agt->pendidikan_sedang_id, [8, 9, 10, 11, 12, 13])
                ? $nama_pendidikan
                : $pendidikan_kk ?? '';

            $dtks_anggota->kd_pendidikan_tertinggi = $this->getIndexPilihan(Regsosek2022kEnum::pilihanBagian4()['413'], $nama_pendidikan);
            $dtks_anggota->kd_kelas_tertinggi      = 8; // (tamat & lulus) // 414
            $dtks_anggota->kd_ijazah_tertinggi     = $this->getIndexPilihan(Regsosek2022kEnum::pilihanBagian4()['415'], $nama_pendidikan); // 415
        }

        // biarkan diisi manual jika tidak ada yg sesuai

        return $dtks_anggota;
    }

    public function syncKetenagakerjaan(DtksAnggota $dtks_anggota, $agt, Penduduk $kepala_keluarga, $ref_eloquent_collection): DtksAnggota
    {
        // $dtks_anggota->kd_bekerja_seminggu_lalu       = ; // 416a
        // $dtks_anggota->jumlah_jam_kerja_seminggu_lalu = ; // 416b
        // $dtks_anggota->kd_lapangan_usaha_pekerjaan    = ; // 417
        // $dtks_anggota->tulis_lapangan_usaha_pekerjaan =; // 417_tulis
        // $dtks_anggota->kd_kedudukan_di_pekerjaan      = ; // 418
        // $dtks_anggota->kd_punya_npwp                  = ; // 419

        return $dtks_anggota;
    }

    public function syncKepemilikanUsaha(DtksAnggota $dtks_anggota, $agt, Penduduk $kepala_keluarga, $ref_eloquent_collection): DtksAnggota
    {
        // $dtks_anggota->kd_punya_usaha_sendiri_bersama       =; // 420a
        // $dtks_anggota->jumlah_usaha_sendiri_bersama   =; // 420b
        // $dtks_anggota->kd_lapangan_usaha_dr_usaha     =; // 421
        // $dtks_anggota->tulis_lapangan_usaha_dr_usaha  =; // 421_tulis
        // $dtks_anggota->jumlah_pekerja_dibayar         =; // 422
        // $dtks_anggota->jumlah_pekerja_tidak_dibayar   =; // 423
        // $dtks_anggota->kd_kepemilikan_ijin_usaha      =; // 424
        // $dtks_anggota->kd_omset_usaha_perbulan        =; // 425
        // $dtks_anggota->kd_guna_internet_usaha         =; // 426

        return $dtks_anggota;
    }

    public function syncKesehatan(DtksAnggota $dtks_anggota, $agt, Collection $daftar_sakit_menahun): DtksAnggota
    {
        // $dtks_anggota->kd_gizi_seimbang     = ; // 427
        $usia_dinamis = $agt->umur; // attribute
        if ($usia_dinamis >= 2) {
            // $dtks_anggota->kd_sulit_penglihatan          =; // 428a
            // $dtks_anggota->kd_sulit_pendengaran          =; // 428b
            // $dtks_anggota->kd_sulit_jalan_naiktangga     =; // 438c
            // $dtks_anggota->kd_sulit_gerak_tangan_jari    =; // 438d
            // $dtks_anggota->kd_sulit_belajar_intelektual  =; // 438e
            // $dtks_anggota->kd_sulit_perilaku_emosi       =; // 438f
        }
        if ($usia_dinamis >= 5) {
            // $dtks_anggota->kd_sulit_paham_bicara_kom     =; // 438g
            // $dtks_anggota->kd_sulit_mandiri              =; // 438h
            // $dtks_anggota->kd_sulit_ingat_konsentrasi    =; // 438i
            // $dtks_anggota->kd_sering_sedih_depresi       =; // 438j
        }
        if ($usia_dinamis >= 60 && in_array($dtks_anggota->kd_sering_sedih_depresi, [1, 2])) {
            // $dtks_anggota->kd_memiliki_perawat       =; // 429
        }

        // tweb_sakit_menahun | 1;JANTUNG 2;LEVER 3;PARU-PARU 4;KANKER 5;STROKE 6;DIABETES MELITUS 7;GINJAL
        // 8;MALARIA 9;LEPRA/KUSTA 10;HIV/AIDS 11;GILA/STRESS 12;TBC 13;ASTHMA 14;TIDAK ADA/TIDAK SAKIT

        // untuk penulisan yg tidak mirip
        if ($agt->sakit_menahun_id == 6) {
            $dtks_anggota->kd_penyakit_kronis_menahun = 6; // 430 | 06. Diabeles (kencing manis)
        } elseif ($agt->sakit_menahun_id == 13) {
            $dtks_anggota->kd_penyakit_kronis_menahun = 4; // 430 | 04. Asma
        } else {
            // bandingkan kemudian set ke lainnya jika tidak ditemukan
            $sakit_menahun                            = $daftar_sakit_menahun->where('id', $agt->sakit_menahun_id)->pluck('nama')->first();
            $dtks_anggota->kd_penyakit_kronis_menahun = $this->getIndexPilihanWithDefault(Regsosek2022kEnum::pilihanBagian4()['430'], $sakit_menahun); // 430
        }

        return $dtks_anggota;
    }

    public function syncProgramPerlindunganSosial(DtksAnggota $dtks_anggota, $agt, ?Penduduk $kepala_keluarga, $ref_eloquent_collection): DtksAnggota
    {
        $pengaturan_programs = DtksPengaturanProgram::where('versi_kuisioner', '2')
            ->where('target_table', 'dtks_anggota');

        $pengaturan_programs = $this->cacheTemporaryModelGet($pengaturan_programs);

        if ($pengaturan_programs->count() > 0) {
            // ambil semua bantuan anggota ini
            $semua_kepesertaan_anggota_ini = BantuanPeserta::where('peserta', $agt->nik)
                ->whereIn('program_id', $pengaturan_programs->pluck('id_bantuan'))
                ->get();

            //1. PBI/JKN, 2. JKN Mandiri, 4. JKN Pemberi Kerja, 8. Jamkes lainnya
            $nilai_jaminan_kesehatan = ['431a1' => '1', '431a2' => '2', '431a3' => '4', '431a4' => '8'];
            //1. BPJS Jaminan Kecelakaan Kerja, 2. BPJS Jaminan Kematian, 4. BPJS Jaminan Hari Tua, 8. BPJS Jaminan Pensiun, 16. Pensiunan/Jaminan hari tua lainnya (Taspen/Program Pensiun Swasta)
            $nilai_jaminan_ketenagakerjaan     = ['431f1' => '1', '431f2' => '2', '431f3' => '4', '431f4' => '8', '431f5' => 16];
            $pengaturan_program_selain_default = $pengaturan_programs->filter(static fn ($item) => substr($item->kode, -(strlen('default'))) !== 'default');
            $pengaturan_program_default        = $pengaturan_programs->filter(static fn ($item) => substr($item->kode, -(strlen('default'))) === 'default')->keyBy('target_field');

            $to_be_updated = [];

            foreach ($pengaturan_program_selain_default as $item) {
                $kepesertaan_anggota_ini = $semua_kepesertaan_anggota_ini->where('program_id', $item->id_bantuan)->first();
                $target_field            = static::relasiPengaturanProgram()[$item->kode][1];
                $fields                  = explode(',', $target_field);
                $tgl_sekarang            = Carbon::now();
                $akhir_program           = Carbon::parse($kepesertaan_anggota_ini->bantuan->edate);
                $kepesertaannya          = $akhir_program->floatDiffInYears($tgl_sekarang);

                // jika memiliki kepesertaan dan mendapatkan program kurang dari satu tahun lalu
                if ($kepesertaan_anggota_ini && $kepesertaannya <= 1) {
                    if (in_array($item->kode, array_keys($nilai_jaminan_kesehatan))) {
                        $to_be_updated[$fields[0]] += $to_be_updated[$fields[0]]
                            ? $nilai_jaminan_kesehatan[$item->kode]
                            : $nilai_jaminan_kesehatan[$item->kode];
                    } elseif (in_array($item->kode, array_keys($nilai_jaminan_ketenagakerjaan))) {
                        $to_be_updated[$fields[0]] += $to_be_updated[$fields[0]]
                            ? $nilai_jaminan_ketenagakerjaan[$item->kode]
                            : $nilai_jaminan_ketenagakerjaan[$item->kode];
                    } else {
                        $to_be_updated[$fields[0]] = 1;
                    }
                } else {
                    $default_program = $pengaturan_program_default[$fields[0]];
                    // jangan ubah, agar bisa di sesuaikan manual
                    if ($default_program->nilai_default !== null) {
                        $to_be_updated[$fields[0]] = $default_program->nilai_default;
                    }
                }
            }
            $is_dirty = false;

            foreach ($to_be_updated as $key => $item) {
                if ($dtks_anggota->{$key} != $item) {
                    $is_dirty = true;
                }
                $dtks_anggota->{$key} = $item;
            }
            // lakukan update
            if ($is_dirty) {
                DtksAnggota::where('id', $dtks_anggota->id)->update($to_be_updated);
            }
        }

        return $dtks_anggota;
    }

    public function syncKepesertaanProgramKeluarga(Dtks $dtks): Dtks
    {
        $pengaturan_programs = DtksPengaturanProgram::where('versi_kuisioner', '2')
            ->where('target_table', 'dtks')
            ->get();

        if ($pengaturan_programs->count() > 0) {
            // agak ragu menentukan kepesertaan apakah datanya
            // hanya 1 per keluarga atau bisa lebih

            // ambil semua kepesertaan keluarga ini
            $kepesertaan_keluarga_ini = BantuanPeserta::where('peserta', $dtks->kepala_keluarga->keluarga->no_kk)
                ->whereIn('program_id', $pengaturan_programs->pluck('id_bantuan'))
                ->with('bantuan')
                ->get();

            $to_be_updated = [];

            foreach ($kepesertaan_keluarga_ini as $item) {
                $bantuan        = $item->bantuan->where('id', $item->program_id)->first();
                $tgl_sekarang   = Carbon::now();
                $akhir_program  = Carbon::parse($bantuan->edate);
                $kepesertaannya = $akhir_program->floatDiffInYears($tgl_sekarang);

                // cek kode relasi
                $kode         = $pengaturan_programs->where('id_bantuan', $item->program_id)->first()->kode; //501a ... e
                $target_field = static::relasiPengaturanProgram()[$kode][1];
                $fields       = explode(',', $target_field);

                // jika memiliki kepesertaan kurang dari satu tahun terakhir
                // Kepesertaan 1. Ya, 2. Tidak
                $dtks->{$fields[0]} = $kepesertaannya <= 1 ? 1 : 2;

                // bulan
                $dtks->{$fields[1]} = $akhir_program->isoFormat('M');
                // tahun
                $dtks->{$fields[2]} = $akhir_program->isoFormat('YYYY');

                if ($dtks->isDirty($fields[0]) || $dtks->isDirty($fields[1]) || $dtks->isDirty($fields[2])) {
                    $to_be_updated[$fields[0]] = $dtks->{$fields[0]};
                    $to_be_updated[$fields[1]] = $dtks->{$fields[1]};
                    $to_be_updated[$fields[2]] = $dtks->{$fields[2]};
                }
            }

            // data kepesertaan yg tidak ditemukan
            $bukan_peserta_program = $pengaturan_programs->whereNotIn('id_bantuan', $kepesertaan_keluarga_ini->pluck('program_id'));

            foreach ($bukan_peserta_program as $item) {
                $target_field = static::relasiPengaturanProgram()[$item->kode][1];
                $fields       = explode(',', $target_field);

                // Kepesertaan 1. Ya, 2. Tidak
                if ($dtks->isDirty($fields[0])) {
                    $dtks->{$fields[0]}        = 2;
                    $to_be_updated[$fields[0]] = 2;
                }
            }
            // lakukan update
            if ($to_be_updated !== []) {
                Dtks::where('id', $dtks->id)->update($to_be_updated);
            }
        }

        return $dtks;
    }

    /**
     * jika ada perubahan, hanya ubah atribute field terkait,
     * karena menyebabkan error jika atribute tidak ada di db
     *
     * @param mixed $dtks_or_dtks_anggota
     */
    protected function saveRelatedAttribute($dtks_or_dtks_anggota)
    {
        if ($dtks_or_dtks_anggota instanceof Dtks) {
            $attribute_tersedia = Regsosek2022kEnum::getUsedFields()['dtks'];
        } elseif ($dtks_or_dtks_anggota instanceof DtksAnggota) {
            $attribute_tersedia = Regsosek2022kEnum::getUsedFields()['dtks_anggota'];
        } else {
            return;
        }

        if ($dtks_or_dtks_anggota->isDirty($attribute_tersedia)) {
            $tmp_attributes = [];

            foreach ($dtks_or_dtks_anggota->attributesToArray() as $atr => $val) {
                if (! in_array($atr, $attribute_tersedia)) {
                    $tmp_attributes[$atr] = $val;
                    unset($dtks_or_dtks_anggota->{$atr});
                }
            }
            $dtks_or_dtks_anggota->save();

            foreach ($tmp_attributes as $atr => $val) {
                $dtks_or_dtks_anggota->{$atr} = $val;
            }
        }
    }

    protected function null_or_value($value)
    {
        if ($value === '') {
            return null;
        }

        return $value;
    }

    /**
     * return index atau null
     *
     * @param mixed $value
     * @param mixed $default_value
     */
    protected function getIndexPilihanWithDefault(array $daftar_pilihan, $value, $default_value = 'Lainnya')
    {
        $related_data = $this->getIndexPilihan($daftar_pilihan, $value);
        // Jika tidak ada nama yang sama, cari nama 'lainnya'
        if (! ($related_data ?? false)) {
            $related_data = $this->getIndexPilihan($daftar_pilihan, $default_value);
        }

        // kembalikan id yang ditemukan atau null
        return $related_data ?: null;
    }

    /**
     * return index atau null
     *
     * @param mixed $search_value
     */
    protected function getIndexPilihan(array $daftar_pilihan, $search_value)
    {
        return collect($daftar_pilihan)->search(static function ($item, $key) use ($search_value): bool {
            $first   = strtolower($item);
            $second  = strtolower($search_value);
            $similar = similar_text($first, $second);

            return strlen($first) == $similar || strlen($second) == $similar;
        });
    }
}
