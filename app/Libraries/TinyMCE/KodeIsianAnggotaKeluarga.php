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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\TinyMCE;

use App\Enums\SHDKEnum;
use App\Models\Keluarga;
use App\Models\Penduduk;

class KodeIsianAnggotaKeluarga
{
    private $idPenduduk;

    public function __construct($idPenduduk)
    {
        $this->idPenduduk = $idPenduduk;
    }

    public static function get($idPenduduk)
    {
        return (new self($idPenduduk))->kodeIsian();
    }

    public function kodeIsian()
    {
        $id_kk   = Penduduk::where('kk_level', SHDKEnum::KEPALA_KELUARGA)->find($this->idPenduduk)->id_kk;
        $anggota = Keluarga::find($id_kk)->anggota;

        return [
            [
                'judul' => 'Urutan',
                'isian' => 'Klgx_nO',
                'data'  => $anggota ? $anggota->pluck('id')
                    ->map(static fn ($item, $key) => $key + 1)
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'NIK',
                'isian' => 'Klgx_niK',
                'data'  => $anggota ? $anggota->pluck('nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama',
                'isian' => 'Klgx_namA',
                'data'  => $anggota ? $anggota->pluck('nama')->toArray() : '',
            ],
            [
                'judul' => 'Jenis Kelamin',
                'isian' => 'Klgx_jenis_kelamiN',
                'data'  => $anggota ? $anggota->pluck('jenisKelamin.nama')->toArray() : '',
            ],
            [
                'judul' => 'Tempat Lahir',
                'isian' => 'Klgx_tempatlahiR',
                'data'  => $anggota ? $anggota->pluck('tempatlahir')->toArray() : '',
            ],
            [
                'judul' => 'Tgl Lahir',
                'isian' => 'Klgx_tanggallahiR',
                'data'  => $anggota ? $anggota->pluck('tanggallahir')
                    ->map(static fn ($item) => formatTanggal($item))
                    ->toArray() : '',
            ],
            [
                'judul' => 'Tempat Tgl Lahir',
                'isian' => 'Klgx_tempat_tgl_lahiR',
                'data'  => $anggota ? $anggota->pluck('tempatlahir', 'tanggallahir')
                    ->map(static fn ($item, $key) => $item . ', ' . formatTanggal($key))
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'Tempat Tgl Lahir (TTL)',
                'isian' => 'Klgx_ttL',
                'data'  => $anggota ? $anggota->pluck('tempatlahir', 'tanggallahir')
                    ->map(static fn ($item, $key) => $item . ', ' . formatTanggal($key))
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'Usia',
                'isian' => 'Klgx_usiA',
                'data'  => $anggota ? $anggota->pluck('usia')->toArray() : '',
            ],
            [
                'judul' => 'Agama',
                'isian' => 'Klgx_agamA',
                'data'  => $anggota ? $anggota->pluck('agama.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pendidikan Sedang',
                'isian' => 'Klgx_pendidikan_sedanG',
                'data'  => $anggota ? $anggota->pluck('pendidikan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pendidikan Dalam KK',
                'isian' => 'Klgx_pendidikan_kK',
                'data'  => $anggota ? $anggota->pluck('pendidikanKk.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pekerjaan',
                'isian' => 'Klgx_pekerjaaN',
                'data'  => $anggota ? $anggota->pluck('pekerjaan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Status Perkawinan',
                'isian' => 'Klgx_status_kawiN',
                'data'  => $anggota ? $anggota->pluck('statusKawin.nama')->toArray() : '',
            ],
            [
                'judul' => 'Hubungan Dalam KK',
                'isian' => 'Klgx_hubungan_kK',
                'data'  => $anggota ? $anggota->pluck('pendudukHubungan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Warga Negara',
                'isian' => 'Klgx_warga_negarA',
                'data'  => $anggota ? $anggota->pluck('warganegara.nama')->toArray() : '',
            ],
            [
                'judul' => 'Alamat',
                'isian' => 'Klgx_alamat',
                'data'  => $anggota ? $anggota->pluck('alamat_wilayah')->toArray() : '',
            ],
            [
                'judul' => 'Golongan Darah',
                'isian' => 'Klgx_golongan_darah',
                'data'  => $anggota ? $anggota->pluck('golonganDarah.nama')->toArray() : '',
            ],
            [
                'judul' => 'Dokumen Pasport',
                'isian' => 'Klgx_dokumen_pasporT',
                'data'  => $anggota ? $anggota->pluck('dokumen_pasport')->toArray() : '',
            ],
            [
                'judul' => 'Tgl Akhir Paspor',
                'isian' => 'Klgx_tanggal_akhir_paspoR',
                'data'  => $anggota ? $anggota->pluck('tanggal_akhir_paspor')
                    ->map(static fn ($item) => formatTanggal($item))
                    ->toArray() : '',
            ],
            [
                'judul' => 'NIK Ayah',
                'isian' => 'Klgx_nik_ayaH',
                'data'  => $anggota ? $anggota->pluck('ayah_nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama Ayah',
                'isian' => 'Klgx_nama_ayaH',
                'data'  => $anggota ? $anggota->pluck('nama_ayah')->toArray() : '',
            ],
            [
                'judul' => 'NIK Ibu',
                'isian' => 'Klgx_nik_ibU',
                'data'  => $anggota ? $anggota->pluck('ibu_nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama Ibu',
                'isian' => 'Klgx_nama_ibU',
                'data'  => $anggota ? $anggota->pluck('nama_ibu')->toArray() : '',
            ],
        ];
    }
}
