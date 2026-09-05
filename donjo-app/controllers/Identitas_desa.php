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

use App\Models\Config;
use App\Models\Pamong;
use App\Models\ProfilDesa;
use App\Models\Wilayah;
use App\Traits\Upload;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

defined('BASEPATH') || exit('No direct script access allowed');

class Identitas_desa extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'info-desa';
    public $sub_modul_ini = 'identitas-desa';
    protected $identitas_desa;
    private $cek_kades;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->cek_kades = Pamong::kepalaDesa()->exists();
        // TODO: Cek bagian ini selalu bermasalah jika model penduduk atau pamong aktifkan global observer config_id
        $config               = Config::appKey()->first()->makeVisible(['nama_kontak', 'hp_kontak', 'jabatan_kontak']);
        $this->identitas_desa = $config ? $config->toArray() : null;
    }

    /**
     * View index identitas desa
     */
    public function index(): void
    {
        $profil_desa     = ProfilDesa::get()->groupBy('kategori');
        $cek_profil_desa = $profil_desa->isNotEmpty();

        view('admin.identitas_desa.index', [
            'main'            => $this->identitas_desa,
            'cek_kades'       => $this->cek_kades,
            'profil_desa'     => $profil_desa,
            'cek_profil_desa' => $cek_profil_desa,
        ]);
    }

    /**
     * View form ubah identitas desa
     */
    public function form(): void
    {
        isCan('u');
        $data['main']            = $this->identitas_desa;
        $data['cek_kades']       = $this->cek_kades;
        $data['form_action']     = ci_route('identitas_desa.update');
        $data['status_pantau']   = checkWebsiteAccessibility(config_item('server_pantau')) ? 1 : 0;
        $data['profil_desa']     = ProfilDesa::pluck('value', 'key')->toArray();
        $data['cek_profil_desa'] = true;

        view('admin.identitas_desa.form', $data);
    }

    /**
     * Proses tambah identitas desa
     *
     * @return void
     */
    public function insert()
    {
        isCan('u');

        if (Config::create($this->validate($this->request))) {
            return json([
                'status' => true,
            ]);
        }

        return json([
            'status' => false,
        ]);
    }

    /**
     * Proses ubah identitas desa
     *
     * @return void
     */
    public function update()
    {
        isCan('u');

        if (! empty($this->request['email_desa']) && ! filter_var($this->request['email_desa'], FILTER_VALIDATE_EMAIL)) {
            return json(['status' => false, 'message' => 'Alamat email tidak valid.']);
        }

        $id       = $this->identitas_desa['id'];
        $config   = Config::find($id);
        $validate = $this->validate($this->request, $config);
        $cek      = $this->cek_kode_wilayah($validate);

        if ($cek['status'] && $config->update($validate)) {
            $dataProfil = array_intersect_key($this->request, array_flip([
                'jenis_tanah',
                'topografi',
                'sumber_daya_alam',
                'flora_fauna',
                'rawan_bencana',
                'kearifan_lokal',
                'jenis_jaringan',
                'provider_internet',
                'cakupan_wilayah',
                'kecepatan_internet',
                'akses_publik',
                'status_desa',
                'lembaga_adat',
                'struktur_adat',
                'wilayah_adat',
                'peraturan_adat',
                'regulasi_penetapan_kampung_adat',
                'dokumen_regulasi_penetapan_kampung_adat',
            ]));

            $oldProfil = ProfilDesa::whereIn('key', ['dokumen_regulasi_penetapan_kampung_adat', 'struktur_adat'])
                ->pluck('value', 'key')
                ->toArray();

            $dataProfil['dokumen_regulasi_penetapan_kampung_adat'] = $this->upload_dokumen(
                'dokumen_regulasi_penetapan_kampung_adat',
                $oldProfil['dokumen_regulasi_penetapan_kampung_adat']
            );

            $dataProfil['struktur_adat'] = $this->upload_dokumen(
                'struktur_adat',
                $oldProfil['struktur_adat']
            );

            ProfilDesa::simpanData($dataProfil, $config->id);

            return json(['status' => true]);
        }

        return json(['status' => false, 'message' => $cek['message']]);
    }

    /**
     * View Form Ubah Peta
     *
     * @param string $tipe
     */
    public function maps($tipe = 'kantor'): void
    {
        $data_desa            = $this->identitas_desa;
        $data['desa']         = $data_desa;
        $data['poly']         = ($tipe == 'wilayah') ? 'multi' : 'poly';
        $data['wil_ini']      = $data_desa;
        $data['wil_atas']     = $data_desa;
        $data['dusun_gis']    = Wilayah::dusun()->get();
        $data['rw_gis']       = Wilayah::rw()->get();
        $data['rt_gis']       = Wilayah::rt()->get();
        $data['nama_wilayah'] = ucwords(setting('sebutan_desa') . ' ' . $data_desa['nama_desa']);
        $data['breadcrumb']   = [
            ['link' => ci_route('identitas_desa'), 'judul' => 'Identitas ' . ucwords((string) setting('sebutan_desa'))],
        ];

        $data['form_action']     = ci_route('identitas_desa.update_maps', $tipe);
        $data['route_kosongkan'] = ci_route('identitas_desa.kosongkan');
        view('admin.wilayah.maps_' . $tipe, $data);
    }

    /**
     * Proses ubah peta
     *
     * @param string $tipe
     */
    public function update_maps($tipe = 'kantor'): void
    {
        isCan('u');

        $data['zoom'] = bilangan($this->request['zoom']);

        if ($tipe == 'kantor') {
            $data['lat'] = koordinat($this->request['lat']);
            $data['lng'] = koordinat($this->request['lng']);
        } else {
            $data['path']   = htmlentities((string) $this->request['path']);
            $data['warna']  = warna($this->request['warna']);
            $data['border'] = warna($this->request['border']);
        }

        if (Config::find($this->identitas_desa['id'])->update($data)) {
            redirect_with('success', 'Berhasil Ubah Peta ' . ucwords($tipe));
        }

        redirect_with('error', 'Gagal Ubah Peta ' . ucwords($tipe));
    }

    /**
     * Proses kosongkan path peta
     *
     * @param string $id
     */
    public function kosongkan(): void
    {
        isCan('u');

        if (Config::find($this->identitas_desa['id'])->update(['path' => null])) {
            redirect_with('success', 'Berhasil Kosongkan Peta');
        }
        redirect_with('error', 'Gagal Kosongkan Peta');
    }

    public function validate($request = [], $old = null)
    {
        if (empty($request['ukuran'])) {
            $request['ukuran'] = 100;
        }

        $validate = [
            'logo' => (! empty($_FILES['logo']['name']))
                ? $this->uploadGambar('logo', LOKASI_LOGO_DESA, $request['ukuran'], false, true)
                : $old->logo,
            'kantor_desa' => (! empty($_FILES['kantor_desa']['name']))
                ? $this->uploadGambar('kantor_desa', LOKASI_LOGO_DESA)
                : $old->kantor_desa,
            'nama_desa'         => nama_desa($request['nama_desa']),
            'kode_desa'         => substr((string) bilangan($request['kode_desa']), 0, 10),
            'kode_pos'          => bilangan($request['kode_pos']),
            'alamat_kantor'     => alamat($request['alamat_kantor']),
            'email_desa'        => email($request['email_desa']),
            'telepon'           => bilangan($request['telepon']),
            'website'           => alamat_web($request['website']),
            'nama_kecamatan'    => nama_desa($request['nama_kecamatan']),
            'kode_kecamatan'    => substr((string) bilangan($request['kode_kecamatan']), 0, 6),
            'nama_kepala_camat' => nama($request['nama_kepala_camat']),
            'nip_kepala_camat'  => nomor_surat_keputusan($request['nip_kepala_camat']),
            'nama_kabupaten'    => nama($request['nama_kabupaten']),
            'kode_kabupaten'    => substr((string) bilangan($request['kode_kabupaten']), 0, 4),
            'nama_propinsi'     => nama_terbatas($request['nama_propinsi']),
            'kode_propinsi'     => substr((string) bilangan($request['kode_propinsi']), 0, 2),
            'nomor_operator'    => bilangan($request['nomor_operator']),
            'nama_kontak'       => nama($request['nama_kontak']),
            'hp_kontak'         => bilangan($request['hp_kontak']),
            'jabatan_kontak'    => nama($request['jabatan_kontak']),
            'kode_desa_bps'     => substr((string) bilangan($request['kode_desa_bps']), 0, 10),
        ];

        return $validate;
    }

    public function reset(): void
    {
        isCan('u');

        if (null === $this->identitas_desa) {
            unlink(DESAPATH . 'app_key');
            cache()->forget('identitas_desa');

            set_session('error', 'Berhasil Reset AppKey, Silakan Tentukan Identitas Desa');
        }

        redirect('identitas_desa');
    }

    private function upload_dokumen(string $field, ?string $oldFile = null): ?string
    {
        $file = request()->file($field);

        if (! $file || ! $file->isValid()) {
            return $oldFile;
        }

        $isImage = $field === 'struktur_adat';

        return $this->upload(
            file: $field,
            config: [
                'upload_path'   => LOKASI_DOKUMEN,
                'allowed_types' => $isImage ? 'jpg|jpeg|png|webp' : 'pdf',
                'max_size'      => 2048, // 2 MB
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) use ($isImage, $oldFile) {
                $newFilename = '';

                if ($isImage) {
                    // Konversi ke .webp
                    $newFilename = "{$uploadData['raw_name']}.webp";
                    Image::load($uploadData['full_path'])
                        ->format(Manipulations::FORMAT_WEBP)
                        ->save("{$uploadData['file_path']}{$newFilename}");

                    // Hapus file asli (non-webp)
                    @unlink($uploadData['full_path']);
                } else {
                    $newFilename = $uploadData['file_name'];
                }

                // Hapus file lama (jika ada dan berbeda dari file baru)
                if (! empty($oldFile)) {
                    $oldPath = LOKASI_DOKUMEN . $oldFile;
                    if (file_exists($oldPath) && basename($oldPath) !== $newFilename) {
                        @unlink($oldPath);
                    }
                }

                return $newFilename;
            }
        );
    }

    private function cek_kode_wilayah(array $request = []): array
    {
        $status    = false;
        $config    = new Config();
        $db_level  = config_item('db_level');
        $firstItem = $config->where('id', '!=', $this->identitas_desa['id'])->first();

        switch (true) {
            case $config->count() <= 1:
                $message = 'Tentukan Identitas Desa Terlebih Dahulu';
                $status  = true;
                break;

            case in_array($request['kode_desa'], $config->where('kode_desa', '!=', $this->identitas_desa['kode_desa'])->pluck('kode_desa')->toArray()):
                $message = 'Kode Desa Sudah Digunakan';
                break;

            case $db_level == 4 && $request['kode_kecamatan'] != $firstItem->kode_kecamatan:
                $message = 'Kode Kecamatan Tidak Sesuai, Pastikan Kode Kecamatan Sesuai Dengan Lingkup Wilayah Penggunaan.';
                break;

            case $db_level == 3 && $request['kode_kabupaten'] != $firstItem->kode_kabupaten:
                $message = 'Kode kabupaten tidak sesuai. Pastikan kode kabupaten sesuai dengan lingkup wilayah penggunaan.';
                break;

            case $db_level == 2 && $request['kode_propinsi'] != $firstItem->kode_propinsi:
                $message = 'Kode Provinsi Tidak Sesuai, Pastikan Kode Provinsi Sesuai Dengan Lingkup Wilayah Penggunaan.';
                break;

            default:
                $status = true;
                break;
        }

        return ['status' => $status, 'message' => $message];
    }
}
