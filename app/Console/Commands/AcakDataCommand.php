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

namespace App\Console\Commands;

use App\Models\Config;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Rtm;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Lapak\Models\Pelapak;

class AcakDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensid:db-acak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Acak data untuk identitas desa, wilayah administratif, RTM, keluarga, penduduk, dan pelapak pada data yang sudah ada.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // INIT
        $kode_kecamatan = identitas('kode_kecamatan');
        $this->info('>_ Mulai');

        // IDENTITAS DESA
        $this->info('>_ Acak Identitas Desa');
        // exclude booted models
        Config::first()->update([
            'alamat_kantor' => 'Jalan ' . fake()->streetName . ' No. ' . fake()->buildingNumber,
            'email'         => fake()->email,
            'telepon'       => '08' . fake()->numerify('##########'),
            'ponsel'        => '08' . fake()->numerify('##########'),
            'website'       => 'https://serambi.opendesa.id',
            'kode_pos'      => fake()->numerify('#####'),
            'nama_camat'    => strtoupper(fake()->name),
            'nip_camat'     => fake()->numerify('###############'),
        ]);

        // WILAYAH ADMINISTRATIF
        $this->info('>_ Acak Wilayah Administratif');
        $dusunList = [
            'Sumber Rejo',
            'Tanjung Sari',
            'Suka Maju',
            'Sido Makmur',
            'Suka Damai',
            'Sejahtera',
            'Mekar Jaya',
            'Harapan Jaya',
            'Tirta Kencana',
            'Karya Bakti',
            'Sri Rejeki',
            'Mukti Jaya',
            'Sari Murni',
            'Kerta Bumi',
            'Mitra Sejati',
            'Sentosa',
            'Subur Makmur',
            'Wijaya Kusuma',
            'Amerta Jaya',
            'Bhakti Mulya',
            'Surya Mandiri',
            'Suka Sejahtera',
            'Lestari',
            'Suka Raharja',
            'Bhakti Jaya',
            'Sumber Harapan',
            'Suka Rukun',
            'Bakti Sejahtera',
            'Mukti Rejo',
            'Mekar Sejati',
            'Citra Jaya',
            'Wana Bakti',
            'Tirta Makmur',
            'Karya Utama',
            'Sri Mulya',
            'Mukti Sejati',
            'Sari Jaya',
            'Tirta Murni',
            'Mitra Jaya',
            'Karya Lestari',
            'Sejahtera Abadi',
            'Mukti Sari',
            'Harapan Baru',
            'Suka Mulya',
            'Bhakti Rejo',
            'Sumber Jaya',
            'Suka Makmur',
            'Sari Sejati',
            'Kerta Makmur',
        ];

        $dusuns = Wilayah::dusun()->get();

        foreach ($dusuns as $dusun) {
            Wilayah::where('dusun', $dusun->dusun)->update(['dusun' => fake()->unique()->randomElement($dusunList)]);
        }

        // RTM
        $this->info('>_ Acak RTM');
        $rtms = Rtm::orderBy('id')->get(['id', 'no_kk', 'bdt']);

        foreach ($rtms as $key => $rtm) {
            $update_tgl_daftar = Carbon::parse($rtm->tgl_daftar)->addDays(7);
            $tgl_daftar        = Carbon::parse($update_tgl_daftar)->format('dmy');
            $bdt               = empty($rtm->bdt) ? null : $kode_kecamatan . str_pad($key + 1, 10, '0', STR_PAD_LEFT);
            Rtm::find($rtm->id)->update([
                'no_kk'      => $kode_kecamatan . $tgl_daftar . str_pad($rtm->id, 4, '0', STR_PAD_LEFT),
                'tgl_daftar' => $update_tgl_daftar,
                'bdt'        => $bdt,
            ]);
        }

        // KELUARGA
        $this->info('>_ Acak Keluarga');
        $keluargas = Keluarga::get(['id', 'tgl_daftar']);

        foreach ($keluargas as $keluarga) {
            $update_tgl_daftar = Carbon::parse($keluarga->tgl_daftar)->addDays(7);
            $tgl_daftar        = Carbon::parse($update_tgl_daftar)->format('dmy');
            Keluarga::find($keluarga->id)->update([
                'no_kk'      => $kode_kecamatan . $tgl_daftar . str_pad($keluarga->id, 4, '0', STR_PAD_LEFT),
                'tgl_daftar' => $update_tgl_daftar,
            ]);
        }

        // PENDUDUK
        $this->info('>_ Acak Penduduk');
        $penduduks = Penduduk::get(['id', 'nik', 'nama', 'tempatlahir', 'tanggallahir', 'ayah_nik', 'ibu_nik', 'nama_ayah', 'nama_ibu', 'foto', 'akta_lahir', 'akta_perkawinan', 'tanggalperkawinan', 'telepon', 'no_kk_sebelumnya', 'tag_id_card', 'no_asuransi', 'tempat_cetak_ktp', 'tanggal_cetak_ktp']);

        foreach ($penduduks as $penduduk) {
            $update_tgl_lahir = Carbon::parse($penduduk->tanggallahir)->addDays(7);
            $tgl_lahir        = Carbon::parse($update_tgl_lahir)->format('dmy');
            $nik              = $kode_kecamatan . $tgl_lahir . str_pad($penduduk->id, 4, '0', STR_PAD_LEFT);
            $nama             = $penduduk->sex == '1' ? fake()->firstNameMale : fake()->firstNameFemale;

            Penduduk::find($penduduk->id)->update([
                'nik'               => $nik,
                'nama'              => strtoupper($nama),
                'tempatlahir'       => fake()->city,
                'tgl_lahir'         => $tgl_lahir,
                'ayah_nik'          => $penduduk->ayah_nik ? fake()->numerify('################') : null,
                'ibu_nik'           => $penduduk->ibu_nik ? fake()->numerify('################') : null,
                'nama_ayah'         => strtoupper(fake()->firstNameMale),
                'nama_ibu'          => strtoupper(fake()->firstNameFemale),
                'foto'              => null,
                'akta_lahir'        => fake()->numerify('#####-LT-######-####'),
                'akta_perkawinan'   => fake()->numerify('###/##/X/####'),
                'tanggalperkawinan' => Carbon::parse($penduduk->tanggalperkawinan)->addDays(7),
                'telepon'           => $penduduk->telepon ? '08' . fake()->numerify('##########') : null,
                'no_kk_sebelumnya'  => $penduduk->no_kk_sebelumnya ? fake()->numerify('############') : null,
                'tag_id_card'       => fake()->numerify('##########'), // 10 digit
                'no_asuransi'       => fake()->numerify('#######'), // 7 digit
                'tempat_cetak_ktp'  => identitas('nama_kabupaten'),
                'tanggal_cetak_ktp' => Carbon::parse($penduduk->tanggal_cetak_ktp)->addDays(7),
            ]);
        }

        // PELAPAK
        $this->info('>_ Acak Pelapak');
        $pelapaks = Pelapak::whereNotNull('telepon')->get(['id', 'telepon']);

        foreach ($pelapaks as $pelapak) {
            Pelapak::find($pelapak->id)->update([
                'telepon' => '08' . fake()->numerify('##########'),
            ]);
        }

        cache()->flush();
        $this->info('>_ Selesai');
    }
}
