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

use App\Models\Config;
use App\Models\Pamong;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Identitas_desa extends Admin_Controller
{
    public $modul_ini     = 'info-desa';
    public $sub_modul_ini = 'identitas-desa';
    private $cek_kades;
    protected $identitas_desa;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->cek_kades = Pamong::kepalaDesa()->exists();
        // TODO: Cek bagian ini selalu bermasalah jika model penduduk atau pamong aktifkan global observer config_id
        $config               = Config::appKey()->first();
        $this->identitas_desa = $config ? $config->toArray() : null;
    }

    /**
     * View index identitas desa
     */
    public function index(): void
    {
        view('admin.identitas_desa.index', [
            'main'      => $this->identitas_desa,
            'cek_kades' => $this->cek_kades,
        ]);
    }

    /**
     * View form ubah identitas desa
     */
    public function form(): void
    {
        isCan('u');
        $data['main']          = $this->identitas_desa;
        $data['cek_kades']     = $this->cek_kades;
        $data['form_action']   = ci_route('identitas_desa.update');
        $data['status_pantau'] = checkWebsiteAccessibility(config_item('server_pantau')) ? 1 : 0;

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

        if (Config::create(static::validate($this->request))) {
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

        $id       = $this->identitas_desa['id'];
        $config   = Config::find($id);
        $validate = static::validate($this->request, $config);
        $cek      = $this->cek_kode_wilayah($validate);

        if ($cek['status'] && $config->update($validate)) {
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
        $data['nama_wilayah'] = ucwords(setting('sebutan_desa') . ' ' . $data_desa->nama_desa);
        $data['wilayah']      = ucwords(setting('sebutan_desa') . ' ' . $data_desa->nama_desa);
        $data['breadcrumb']   = [
            ['link' => ci_route('identitas_desa'), 'judul' => 'Identitas ' . ucwords(setting('sebutan_desa'))],
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
            $data['path']   = htmlentities($this->request['path']);
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

    // Hanya filter inputan
    protected static function validate($request = [], $old = null)
    {
        if ($request['ukuran'] == '') {
            $request['ukuran'] = 100;
        }

        return [
            'logo'              => static::unggah('logo', true, bilangan($request['ukuran'])) ?? $old->logo,
            'kantor_desa'       => static::unggah('kantor_desa') ?? $old->kantor_desa,
            'nama_desa'         => nama_desa($request['nama_desa']),
            'kode_desa'         => substr(bilangan($request['kode_desa']), 0, 10),
            'kode_pos'          => bilangan($request['kode_pos']),
            'alamat_kantor'     => alamat($request['alamat_kantor']),
            'email_desa'        => email($request['email_desa']),
            'telepon'           => bilangan($request['telepon']),
            'website'           => alamat_web($request['website']),
            'nama_kecamatan'    => nama_desa($request['nama_kecamatan']),
            'kode_kecamatan'    => substr(bilangan($request['kode_kecamatan']), 0, 6),
            'nama_kepala_camat' => nama($request['nama_kepala_camat']),
            'nip_kepala_camat'  => nomor_surat_keputusan($request['nip_kepala_camat']),
            'nama_kabupaten'    => nama($request['nama_kabupaten']),
            'kode_kabupaten'    => substr(bilangan($request['kode_kabupaten']), 0, 4),
            'nama_propinsi'     => nama_terbatas($request['nama_propinsi']),
            'kode_propinsi'     => substr(bilangan($request['kode_propinsi']), 0, 2),
            'nomor_operator'    => bilangan($request['nomor_operator']),
        ];
    }

    // TODO : Ganti cara ini
    protected static function unggah($jenis = '', $resize = false, $ukuran = false)
    {
        $CI = &get_instance();
        $CI->load->library('MY_Upload', null, 'upload');
        $config = [
            'upload_path'   => LOKASI_LOGO_DESA,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];
        // Adakah berkas yang disertakan?
        if (empty($_FILES[$jenis]['name'])) {
            return null;
        }
        // Tes tidak berisi script PHP
        if (isPHP($_FILES[$jenis]['tmp_name'], $_FILES[$jenis]['name'])) {
            redirect_with('error', 'Jenis file ini tidak diperbolehkan');
        }

        $uploadData = null;
        // Inisialisasi library 'upload'
        $CI->upload->initialize($config);
        // Upload sukses
        if ($CI->upload->do_upload($jenis)) {
            $uploadData = $CI->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $CI->uploadConfig['upload_path'] . $uploadData['file_name'],
                $CI->uploadConfig['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
        } else {
            redirect_with('error', $CI->upload->display_errors(null, null));
        }

        if (! empty($uploadData)) {
            if ($resize) {
                $tipe_file = TipeFile($_FILES['logo']);
                $dimensi   = ['width' => $ukuran, 'height' => $ukuran];
                resizeImage(LOKASI_LOGO_DESA . $uploadData['file_name'], $tipe_file, $dimensi);
                resizeImage(LOKASI_LOGO_DESA . $uploadData['file_name'], $tipe_file, ['width' => 16, 'height' => 16], LOKASI_LOGO_DESA . 'favicon.ico');
            }

            return $uploadData['file_name'];
        }

        return null;
    }

    private function cek_kode_wilayah(array $request = [])
    {
        $status = false;
        $config = new Config();

        switch (true) {
            case $config->count() <= 1:
            default:
                $status = true;
                break;

            case $request['kode_propinsi'] != $config->first()->kode_propinsi:
                $message = 'Kode Provinsi Tidak Sesuai, Pastikan Kode Provinsi Sesuai Dengan Lingkup Wilayah Penggunaan.';
                break;

            case $request['kode_kabupaten'] != $config->first()->kode_kabupaten:
                $message = 'Kode Kabupaten Tidak Sesuai, Pastikan Kode Kabupaten Sesuai Dengan Lingkup Wilayah Penggunaan.';
                break;

                // TODO: Saat ini penggunaan validassi hanya sampai tingkat kabupaten
                // case $request['kode_kecamatan'] != $config->first()->kode_kecamatan:
                //     $message = 'Kode Kecamatan Tidak Sesuai, Pastikan Kode Kecamatan Sesuai Dengan Lingkup Wilayah Penggunaan.';
                //     break;

            case in_array($request['kode_desa'], $config->where('kode_desa', '!=', $this->identitas_desa['kode_desa'])->pluck('kode_desa')->toArray()):
                $message = 'Kode Desa Sudah Digunakan';
                break;
        }

        return ['status' => $status, 'message' => $message];
    }

    public function reset(): void
    {
        isCan('u');

        if (null === $this->identitas_desa) {
            unlink(DESAPATH . 'app_key');
            cache()->forget('identitas_desa');

            set_session('error', 'Berhasil Reset AppKey, Silahkan Tentukan Identitas Desa');
        }

        redirect('identitas_desa');
    }
}
