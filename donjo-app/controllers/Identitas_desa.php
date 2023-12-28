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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Config;
use App\Models\Pamong;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Schema;

class Identitas_desa extends Admin_Controller
{
    private $cek_kades;
    protected $identitas_desa;

    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 'info-desa';
        $this->sub_modul_ini = 'identitas-desa';

        if (Schema::hasTable('ref_jabatan')) {
            $this->cek_kades = Pamong::kepalaDesa()->exists();
            // TODO: Cek bagian ini selalu bermasalah jika model penduduk atau pamong aktifkan global observer config_id
            $config               = Config::appKey()->first();
            $this->identitas_desa = $config ? $config->toArray() : null;
        }
    }

    /**
     * View index identitas desa
     *
     * @return void
     */
    public function index()
    {
        return view('admin.identitas_desa.index', [
            'main'      => $this->identitas_desa,
            'cek_kades' => $this->cek_kades,
        ]);
    }

    /**
     * View form ubah identitas desa
     *
     * @return void
     */
    public function form()
    {
        $this->redirect_hak_akses('u');
        $data['main']           = $this->identitas_desa;
        $data['cek_kades']      = $this->cek_kades;
        $data['form_action']    = route('identitas_desa.update');
        $data['nomor_operator'] = Schema::hasColumn('config', 'nomor_operator');

        return view('admin.identitas_desa.form', $data);
    }

    /**
     * Proses tambah identitas desa
     *
     * @return void
     */
    public function insert()
    {
        $this->redirect_hak_akses('u');

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
        $this->redirect_hak_akses('u');

        $id       = $this->identitas_desa['id'];
        $validate = $this->validate($this->request, $id);

        $cek = $this->cek_kode_wilayah($validate);
        if ($cek['status'] && Config::find($id)->update($validate)) {
            return json(['status' => true]);
        }

        return json(['status' => false, 'message' => $cek['message']]);
    }

    /**
     * View Form Ubah Peta
     *
     * @param string $tipe
     *
     * @return void
     */
    public function maps($tipe = 'kantor')
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
            ['link' => route('identitas_desa'), 'judul' => 'Identitas ' . ucwords(setting('sebutan_desa'))],
        ];

        $data['form_action'] = route('identitas_desa.update_maps', $tipe);

        $this->render('sid/wilayah/maps_' . $tipe, $data);
    }

    /**
     * Proses ubah peta
     *
     * @param string $tipe
     *
     * @return void
     */
    public function update_maps($tipe = 'kantor')
    {
        $this->redirect_hak_akses('u');

        $data['zoom'] = bilangan($this->request['zoom']);

        if ($tipe == 'kantor') {
            $data['lat'] = koordinat($this->request['lat']);
            $data['lng'] = koordinat($this->request['lng']);
        } else {
            $data['path']  = htmlentities($this->request['path']);
            $data['warna'] = warna($this->request['warna']);
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
     *
     * @return void
     */
    public function kosongkan()
    {
        $this->redirect_hak_akses('u');

        if (Config::find($this->identitas_desa['id'])->update(['path' => null])) {
            redirect_with('success', 'Berhasil Kosongkan Peta');
        }
        redirect_with('error', 'Gagal Kosongkan Peta');
    }

    // Hanya filter inputan
    protected static function validate($request = [])
    {
        $data = [];
        if ($request['ukuran'] == '') {
            $request['ukuran'] = 100;
        }

        $config = [
            'logo'              => static::unggah('logo', true, bilangan($request['ukuran'])) ?? $request['old_logo'],
            'kantor_desa'       => static::unggah('kantor_desa') ?? $request['old_kantor_desa'],
            'nama_desa'         => nama_desa($request['nama_desa']),
            'kode_desa'         => bilangan($request['kode_desa']),
            'kode_pos'          => bilangan($request['kode_pos']),
            'alamat_kantor'     => alamat($request['alamat_kantor']),
            'email_desa'        => email($request['email_desa']),
            'telepon'           => bilangan($request['telepon']),
            'website'           => alamat_web($request['website']),
            'nama_kecamatan'    => nama_desa($request['nama_kecamatan']),
            'kode_kecamatan'    => bilangan($request['kode_kecamatan']),
            'nama_kepala_camat' => nama($request['nama_kepala_camat']),
            'nip_kepala_camat'  => nomor_surat_keputusan($request['nip_kepala_camat']),
            'nama_kabupaten'    => nama($request['nama_kabupaten']),
            'kode_kabupaten'    => bilangan($request['kode_kabupaten']),
            'nama_propinsi'     => nama_terbatas($request['nama_propinsi']),
            'kode_propinsi'     => bilangan($request['kode_propinsi']),
        ];

        if (Schema::hasColumn('config', 'nomor_operator')) {
            $config['nomor_operator'] = bilangan($request['nomor_operator']);
        }

        if (Schema::hasColumn('config', 'nama_kepala_desa')) {
            $config['nama_kepala_desa'] = '';
        }

        if (Schema::hasColumn('config', 'nip_kepala_desa')) {
            $config['nip_kepala_desa'] = '';
        }

        if (Schema::hasColumn('config', 'g_analitic')) {
            $config['g_analitic'] = '';
        }

        if (Schema::hasColumn('config', 'pamong_id')) {
            $config['pamong_id'] = 0;
        }

        return $config;
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

            default:
                $status = true;
                break;
        }

        return ['status' => $status, 'message' => $message];
    }

    public function reset()
    {
        $this->redirect_hak_akses('u');

        if (null === $this->identitas_desa) {
            unlink(DESAPATH . 'app_key');
            hapus_cache('identitas_desa');

            set_session('error', 'Berhasil Reset AppKey, Silahkan Tentukan Identitas Desa');
        }

        redirect('identitas_desa');
    }
}
