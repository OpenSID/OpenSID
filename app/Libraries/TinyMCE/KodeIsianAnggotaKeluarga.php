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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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

    public static function get($idPenduduk): array
    {
        return (new self($idPenduduk))->kodeIsian();
    }

    public function kodeIsian(): array
    {
        $id_kk   = Penduduk::where('kk_level', SHDKEnum::KEPALA_KELUARGA)->find($this->idPenduduk)->id_kk;
        $anggota = Keluarga::find($id_kk)->anggota;

        return [
            [
                'case_sentence' => true,
                'judul'         => 'Urutan',
                'isian'         => 'klgx_no',
                'data'          => $anggota ? $anggota->pluck('id')
                    ->map(static fn ($item, $key) => $key + 1)
                    ->values()->toArray() : '',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'NIK',
                'isian'         => 'klgx_nik',
                'data'          => $anggota ? $anggota->pluck('nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama',
                'isian' => 'klgx_namA',
                'data'  => $anggota ? $anggota->pluck('nama')->toArray() : '',
            ],
            [
                'judul' => 'Jenis Kelamin',
                'isian' => 'klgx_jenis_kelamin',
                'data'  => $anggota ? $anggota->pluck('jenisKelamin.nama')->toArray() : '',
            ],
            [
                'judul' => 'Tempat Lahir',
                'isian' => 'klgx_tempatlahir',
                'data'  => $anggota ? $anggota->pluck('tempatlahir')->toArray() : '',
            ],
            [
                'judul' => 'Tgl Lahir',
                'isian' => 'klgx_tanggallahir',
                'data'  => $anggota ? $anggota->pluck('tanggallahir')
                    ->map(static fn ($item) => formatTanggal($item))
                    ->toArray() : '',
            ],
            [
                'judul' => 'Tempat Tgl Lahir',
                'isian' => 'klgx_tempat_tgl_lahir',
                'data'  => $anggota ? $anggota->pluck('tempatlahir', 'tanggallahir')
                    ->map(static fn ($item, $key): string => $item . ', ' . formatTanggal($key))
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'Tempat Tgl Lahir (TTL)',
                'isian' => 'klgx_ttl',
                'data'  => $anggota ? $anggota->pluck('tempatlahir', 'tanggallahir')
                    ->map(static fn ($item, $key): string => $item . ', ' . formatTanggal($key))
                    ->values()->toArray() : '',
            ],
            [
                'judul' => 'Usia',
                'isian' => 'klgx_usia',
                'data'  => $anggota ? $anggota->pluck('usia')->toArray() : '',
            ],
            [
                'judul' => 'Agama',
                'isian' => 'klgx_agama',
                'data'  => $anggota ? $anggota->pluck('agama.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pendidikan Sedang',
                'isian' => 'klgx_pendidikan_sedang',
                'data'  => $anggota ? $anggota->pluck('pendidikan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pendidikan Dalam KK',
                'isian' => 'klgx_pendidikan_kk',
                'data'  => $anggota ? $anggota->pluck('pendidikanKk.nama')->toArray() : '',
            ],
            [
                'judul' => 'Pekerjaan',
                'isian' => 'klgx_pekerjaan',
                'data'  => $anggota ? $anggota->pluck('pekerjaan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Status Perkawinan',
                'isian' => 'klgx_status_kawin',
                'data'  => $anggota ? $anggota->pluck('statusKawin.nama')->toArray() : '',
            ],
            [
                'judul' => 'Hubungan Dalam KK',
                'isian' => 'klgx_hubungan_kk',
                'data'  => $anggota ? $anggota->pluck('pendudukHubungan.nama')->toArray() : '',
            ],
            [
                'judul' => 'Warga Negara',
                'isian' => 'klgx_warga_negara',
                'data'  => $anggota ? $anggota->pluck('warganegara.nama')->toArray() : '',
            ],
            [
                'judul' => 'Alamat',
                'isian' => 'klgx_alamat',
                'data'  => $anggota ? $anggota->pluck('alamat_wilayah')->toArray() : '',
            ],
            [
                'judul' => 'Golongan Darah',
                'isian' => 'klgx_golongan_darah',
                'data'  => $anggota ? $anggota->pluck('golonganDarah.nama')->toArray() : '',
            ],
            [
                'judul' => 'Dokumen Pasport',
                'isian' => 'klgx_dokumen_pasport',
                'data'  => $anggota ? $anggota->pluck('dokumen_pasport')->toArray() : '',
            ],
            [
                'judul' => 'Tgl Akhir Paspor',
                'isian' => 'klgx_tanggal_akhir_paspor',
                'data'  => $anggota ? $anggota->pluck('tanggal_akhir_paspor')
                    ->map(static fn ($item) => formatTanggal($item))
                    ->toArray() : '',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'NIK Ayah',
                'isian'         => 'klgx_nik_ayah',
                'data'          => $anggota ? $anggota->pluck('ayah_nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama Ayah',
                'isian' => 'klgx_nama_ayah',
                'data'  => $anggota ? $anggota->pluck('nama_ayah')->toArray() : '',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'NIK Ibu',
                'isian'         => 'klgx_nik_ibu',
                'data'          => $anggota ? $anggota->pluck('ibu_nik')->toArray() : '',
            ],
            [
                'judul' => 'Nama Ibu',
                'isian' => 'klgx_nama_ibu',
                'data'  => $anggota ? $anggota->pluck('nama_ibu')->toArray() : '',
            ],
        ];
    }
}
