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

namespace Modules\Analisis\Models;

use App\Models\BaseModel;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisIndikator extends BaseModel
{
    use ConfigId;

    /**
     * {@inheritDoc}
     */
    protected $table = 'analisis_indikator';

    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the kategori that owns the AnalisisIndikator
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(AnalisisKategori::class, 'id_kategori');
    }

    /**
     * Get all of the parameter for the AnalisisIndikator
     */
    public function parameter(): HasMany
    {
        return $this->hasMany(AnalisisParameter::class, 'id_indikator');
    }

    public static function hubungan($sasaran)
    {
        switch ($sasaran) {

            // Penduduk
            case 1:
                $data = [
                    'kk_level' => [
                        'judul' => 'Hubungan Dalam Keluarga',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_hubungan'),
                    ],
                    'rtm_level' => [
                        'judul' => 'Hubungan Dalam Rumah Tangga',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_rtm_hubungan'),
                    ],
                    'sex' => [
                        'judul' => 'Jenis Kelamin',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_sex'),
                    ],
                    'tempatlahir' => [
                        'judul' => 'Tempat Lahir',
                    ],
                    'tanggallahir' => [
                        'judul' => 'Tanggal Lahir',
                    ],
                    'agama_id' => [
                        'judul' => 'Agama',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_agama'),
                    ],
                    'pendidikan_kk_id' => [
                        'judul' => 'Pendidikan Dalam KK',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk'),
                    ],
                    'pendidikan_sedang_id' => [
                        'judul' => 'Pendidikan Sedang Ditempuh',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_pendidikan'),
                    ],
                    'pekerjaan_id' => [
                        'judul' => 'Pekerjaan',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_pekerjaan'),
                    ],
                    'status_kawin' => [
                        'judul' => 'Status_perkawinan',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_kawin'),
                    ],
                    'warganegara_id' => [
                        'judul' => 'Kewarganegaraan',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_warganegara'),
                    ],
                    'dokumen_pasport' => [
                        'judul' => 'Dokumen Passport',
                    ],
                    'dokumen_kitas' => [
                        'judul' => 'Dokumen KITAS',
                    ],
                    'ayah_nik' => [
                        'judul' => 'NIK Ayah',
                    ],
                    'nama_ayah' => [
                        'judul' => 'Nama Ayah',
                    ],
                    'ibu_nik' => [
                        'judul' => 'NIK Ibu',
                    ],
                    'nama_ibu' => [
                        'judul' => 'Nama Ibu',
                    ],
                    'golongan_darah_id' => [
                        'judul' => 'Golongan Darah',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_golongan_darah'),
                    ],
                    // id_cluster => wilayah, agar tdk duplikasi
                    'wilayah' => [
                        'judul' => 'Wilayah (Dusun/RW/RT)',
                    ],
                    'status' => [
                        'judul' => 'Status Penduduk',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_status'),
                    ],
                    'alamat_sebelumnya' => [
                        'judul' => 'Alamat Sebelumnya',
                    ],
                    'alamat_sekarang' => [
                        'judul' => 'Alamat Sekarang',
                    ],
                    'status_dasar' => [
                        'judul' => 'Status Dasar',
                        // 'referensi' => $this->referensi_model->list_data('tweb_status_dasar'),
                    ],
                    'hamil' => [
                        'judul' => 'Status Kehamilan',
                    ],
                    'cacat_id' => [
                        'judul' => 'Jenis Cacat',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_cacat'),
                    ],
                    'sakit_menahun_id' => [
                        'judul' => 'Sakit Menahun',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_sakit_menahun'),
                    ],
                    'akta_lahir' => [
                        'judul' => 'Akta Lahir',
                    ],
                    'akta_perkawinan' => [
                        'judul' => 'Akta Perkawinan',
                    ],
                    'tanggalperkawinan' => [
                        'judul' => 'Tanggal Perkawinan',
                    ],
                    'akta_perceraian' => [
                        'judul' => 'Akta Perceraian',
                    ],
                    'tanggalperceraian' => [
                        'judul' => 'Tanggal Perceraian',
                    ],
                    'cara_kb_id' => [
                        'judul' => 'Akseptor KB',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_cara_kb'),
                    ],
                    'telepon' => [
                        'judul' => 'Telepon',
                    ],
                    'tanggal_akhir_paspor' => [
                        'judul' => 'Tanggal Akhir Paspor',
                    ],
                    'no_kk_sebelumnya' => [
                        'judul' => 'No. KK Sebelumnya',
                    ],
                    'ktp_el' => [
                        'judul' => 'E-KTP',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_status_ktp'),
                    ],
                    'status_rekam' => [
                        'judul' => 'Status Rekam',
                        // 'referensi' => $this->referensi_model->list_status_rekam(),
                    ],
                    'waktu_lahir' => [
                        'judul' => 'Waktu Lahir',
                    ],
                    'tempat_dilahirkan' => [
                        'judul' => 'Tempat Dilahirkan',
                    ],
                    'jenis_kelahiran' => [
                        'judul' => 'Jenis Kelahiran',
                    ],
                    'kelahiran_anak_ke' => [
                        'judul' => 'Kelahiran Anak Ke - ',
                        'tipe'  => 3,
                    ],
                    'penolong_kelahiran' => [
                        'judul' => 'Penolong Kelahiran',
                    ],
                    'berat_lahir' => [
                        'judul' => 'Berat lahir',
                        'tipe'  => 3,
                    ],
                    'panjang_lahir' => [
                        'judul' => 'Panjang Lahir',
                        'tipe'  => 3,
                    ],
                    'tag_id_card' => [
                        'judul' => 'Tag ID Card',
                    ],
                    'id_asuransi' => [
                        'judul' => 'ID Asuransi',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_asuransi'),
                    ],
                    'no_asuransi' => [
                        'judul' => 'No. Asusransi',
                    ],
                    'email' => [
                        'judul' => 'Email',
                    ],
                    'bahasa_id' => [
                        'judul' => 'Dapat Membaca Huruf',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('ref_penduduk_bahasa'),
                    ],
                    'negara_asal' => [
                        'judul' => 'Negara Asal',
                    ],
                    'tempat_cetak_ktp' => [
                        'judul' => 'Tempat Cetak KTP',
                    ],
                    'tanggal_cetak_ktp' => [
                        'judul' => 'Tanggal Cetak KTP',
                    ],
                    'suku' => [
                        'judul' => 'Suku/Etnis',
                    ],
                    'bpjs_ketenagakerjaan' => [
                        'judul' => 'BPJS Ketenagakerjaan',
                    ],
                ];
                break;

                // Keluarga
            case 2:
                $data = [
                    'nik_kepala' => [
                        'judul' => 'NIK Kepala KK',
                    ],
                    'kelas_sosial' => [
                        'judul' => 'Kelas Sosial',
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_keluarga_sejahtera'),
                    ],
                    'alamat' => [
                        'judul' => 'Alamat',
                    ],
                    // id_cluster => wilayah, agar tdk duplikasi
                    'wilayah' => [
                        'judul' => 'Wilayah (Dusun/RW/RT)',
                    ],
                ];
                break;

                // Desa
            default:

                $desa   = setting('sebutan_desa');
                $kepala = setting('sebutan_kepala_desa');

                $data = [

                    // IDENTITAS DESA
                    'nama_desa' => [
                        'judul' => 'Nama ' . $desa,
                    ],
                    'kode_desa' => [
                        'judul' => 'Kode ' . $desa,
                    ],
                    'kode_pos' => [
                        'judul' => 'Kode POS',
                    ],
                    'nama_kepala_desa' => [
                        'judul' => 'Nama ' . $kepala,
                    ],
                    'nip_kepala_desa' => [
                        'judul' => 'NIP ' . $kepala,
                    ],
                    'jk_kepala_desa' => [
                        'judul' => 'Jenis Kelamin ' . $kepala,
                        'tipe'  => 1,
                        // 'referensi' => $this->referensi_model->list_data('tweb_penduduk_sex'),
                    ],
                    'titik_koordinat_desa' => [
                        'judul' => 'Titik Koordinat ' . $desa . ' (Lintang / Bujur)',
                    ],
                    'alamat_kantor' => [
                        'judul' => 'Alamat Kantor',
                    ],
                    'no_telepon_kepala_desa' => [
                        'judul' => 'Nomor Telepon Rumah / HP ' . $kepala,
                    ],
                    'no_telepon_kantor_desa' => [
                        'judul' => 'Nomor Telepon Kantor ' . $desa,
                    ],
                    'email_desa' => [
                        'judul' => 'Email ' . $desa,
                    ],
                    'pendidikan_kepala_desa' => [
                        'judul' => 'Pendidikan Terakhir ' . $kepala,
                    ],
                    'nama_kecamatan' => [
                        'judul' => 'Nama Kecamatan',
                    ],
                    'kode_kecamatan' => [
                        'judul' => 'Kode Kecamatan',
                    ],
                    'nama_kepala_camat' => [
                        'judul' => 'Nama Kepala Camat',
                    ],
                    'nip_kepala_camat' => [
                        'judul' => 'NIP Kepala Camat',
                    ],
                    'kode_kabupaten' => [
                        'judul' => 'Kode Kabupaten',
                    ],
                    'nama_propinsi' => [
                        'judul' => 'Nama Provinsi',
                    ],
                    'kode_propinsi' => [
                        'judul' => 'Kode Provinsi',
                    ],

                    // DEMOGRAFI
                    // # Penduduk
                    'jumlah_total_penduduk' => [
                        'judul' => 'Jumlah Total Penduduk',
                    ],
                    'jumlah_penduduk_laki_laki' => [
                        'judul' => 'Jumlah Penduduk Laki-laki',
                    ],
                    'jumlah_penduduk_perempuan' => [
                        'judul' => 'Jumlah Penduduk Perempuan',
                    ],
                    'jumlah_penduduk_pedatang' => [
                        'judul' => 'Jumlah Penduduk Pendatang',
                    ],
                    'jumlah_penduduk_yang_pergi' => [
                        'judul' => 'Jumlah Penduduk Yang Pergi',
                    ],

                    // # Kepala Keluarga
                    'jumlah_total_kepala_keluarga' => [
                        'judul' => 'Jumlah Total Kepala Keluarga',
                    ],
                    'jumlah_kepala_keluarga_laki_laki' => [
                        'judul' => 'Jumlah Kepala Keluarga Laki-laki',
                    ],
                    'jumlah_kepala_keluarga_perempuan' => [
                        'judul' => 'Jumlah Kepala Keluarga Perempuan',
                    ],

                    'jumlah_peserta_bpjs' => [
                        'judul' => 'Jumlah Penduduk Terdaftar BPJS Kesehatan / JKN',
                    ],
                ];
                break;
        }

        return $data;
    }

    public static function indikatorUnduh($idMaster, $parameter = 1)
    {
        $data    = self::where('id_master', $idMaster)->orderByRaw('LPAD(nomor, 10, " ")')->get()->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']  = $i + 1;
            $data[$i]['par'] = null;

            if ($parameter == 2) {
                $par             = AnalisisParameter::where('id_indikator', $data[$i]['id'])->where('asign', 1)->get()->toArray();
                $data[$i]['par'] = $par;
            }
        }

        return $data;
    }
}
