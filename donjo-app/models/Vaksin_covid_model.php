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

defined('BASEPATH') || exit('No direct script access allowed');

use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;

class Vaksin_covid_model extends MY_Model
{
    protected $tabel_penduduk = 'penduduk_hidup';
    protected $table_vaksin   = 'covid19_vaksin';
    protected $penduduk_key   = 'id';
    protected $vaksin_key     = 'id_penduduk';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('referensi_model');
        $this->load->library('MY_Upload', null, 'upload');
    }

    public function jenis_vaksin()
    {
        // Data awal
        $awal = $this->referensi_model->list_ref(JENIS_VAKSIN);

        // Dari database
        $data = $this->config_id()
            ->get($this->table_vaksin)
            ->result_array();

        $jenis_vaksin_1 = array_column($data, 'jenis_vaksin_1');
        $jenis_vaksin_2 = array_column($data, 'jenis_vaksin_2');
        $jenis_vaksin_3 = array_column($data, 'jenis_vaksin_3');
        $jenis_vaksin   = array_unique(array_merge($awal, $jenis_vaksin_1, $jenis_vaksin_2, $jenis_vaksin_3));

        return array_values(array_filter($jenis_vaksin));
    }

    public function dusun_sql(): void
    {
        $kf = $this->session->dusun;
        if (isset($kf)) {
            $this->db->where("((p.id_kk is not null AND cp.dusun = '{$kf}') OR (p.id_kk is null AND ck.dusun = '{$kf}'))");
        }
    }

    public function vaksin_sql(): void
    {
        $kf = $this->session->vaksin;

        if (isset($kf)) {
            if ($kf == '4') {
                $this->db->group_start();
                $this->db->group_start();
                $this->db->where('vaksin_1', 0);
                $this->db->where('vaksin_2', 0);
                $this->db->where('vaksin_3', 0);
                $this->db->where('tunda', 0);
                $this->db->group_end();
                $this->db->or_where('vaksin_1');
                $this->db->group_end();
            } elseif ($kf == '5') {
                $this->db->where('tunda', '1');
            } else {
                $this->db->where("vaksin_{$kf}", '1');
            }
        }
    }

    public function jenis_vaksin_sql(): void
    {
        $kf = $this->session->jenis_vaksin;

        if (isset($kf)) {
            $this->db->group_start();
            $this->db->where('jenis_vaksin_1', $kf);
            $this->db->or_where('jenis_vaksin_2', $kf);
            $this->db->or_where('jenis_vaksin_3', $kf);
            $this->db->group_end();
        }
    }

    public function tanggal_vaksin_sql(): void
    {
        $kf = $this->session->tanggal_vaksin;

        if (isset($kf)) {
            $kf = rev_tgl($kf);
            $this->db->group_start();
            $this->db->where('tgl_vaksin_1', $kf);
            $this->db->or_where('tgl_vaksin_2', $kf);
            $this->db->or_where('tgl_vaksin_3', $kf);
            $this->db->group_end();
        }
    }

    public function umur_sql($umur): void
    {
        $umur = explode('-', $umur);
        $this->db->where("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(p.tanggallahir)),'%Y') + 0) >= " . (int) $umur[0]);
        if (! isset($umur[1])) {
            return;
        }
        if ($umur[1] <= $umur[0]) {
            return;
        }
        $this->db->where("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(p.tanggallahir)),'%Y') + 0) <=  " . (int) $umur[1]);
    }

    public function cari($value = ''): void
    {
        $kf = $this->session->cari;
        if (isset($kf)) {
            $this->db
                ->group_start()
                ->like('p.nik', $kf)
                ->or_like('p.nama', $kf)
                ->group_end();
        }
    }

    public function count_reg()
    {
        $this->config_id('p')
            ->select("count({$this->penduduk_key}) AS count")
            ->from("{$this->tabel_penduduk} as p")
            ->join("{$this->table_vaksin} as v", "p.{$this->penduduk_key} = v.{$this->vaksin_key}", 'left')
            ->join('tweb_keluarga AS kk', 'p.id = kk.id')
            ->join('tweb_wil_clusterdesa AS w', 'kk.id_cluster = w.id');

        return $this->db->get()->row();
    }

    public function penduduk_sql(): void
    {
        $sebutan_dusun = ucwords($this->setting->sebutan_dusun);
        $this->db
            ->select('p.*, v.*, kk.no_kk, ck.rt, ck.rw, ck.dusun, s.nama as jenis_kelamin ')
            ->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.tanggallahir)), '%Y')+0) AS umur")
            ->select("(
                case when (p.id_kk IS NULL)
                    then
                        case when (cp.dusun = '-' or cp.dusun = '')
                            then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
                            else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutan_dusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
                        end
                    else
                        case when (ck.dusun = '-' or ck.dusun = '')
                            then CONCAT(COALESCE(kk.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
                            else CONCAT(COALESCE(kk.alamat, ''), ' {$sebutan_dusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
                        end
                end) AS alamat")
            ->join("{$this->table_vaksin} as v", "p.{$this->penduduk_key} = v.{$this->vaksin_key}", 'left')
            ->join('tweb_wil_clusterdesa cp', 'p.id_cluster = cp.id', 'left')
            ->join('tweb_keluarga AS kk', 'p.id_kk = kk.id', 'left')
            ->join('tweb_wil_clusterdesa ck', 'kk.id_cluster = ck.id', 'left')
            ->join('tweb_penduduk_sex AS s', 'p.sex = s.id', 'left');

        $this->dusun_sql();
        $this->vaksin_sql();
        $this->tanggal_vaksin_sql();
        $this->jenis_vaksin_sql();

        if (isset($this->session->umur)) {
            $this->umur_sql($this->session->umur);
        }

        $this->cari();
        $this->config_id('p');
    }

    public function list_penduduk($page = 1)
    {
        $this->penduduk_sql();
        $per_page = ($page == 0) ? null : $this->session->per_page;

        return $this->db->get("{$this->tabel_penduduk} as p", $per_page, ($page - 1) * $per_page)->result();
    }

    public function paging($p = 0)
    {
        $this->penduduk_sql();
        $jml_data = $this->db->get("{$this->tabel_penduduk} as p")->num_rows();
        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function data_penduduk($id = null)
    {
        if ($id == null) {
            return false;
        }
        $this->penduduk_sql();
        $this->db->where('p.id', $id);

        return $this->db->get("{$this->tabel_penduduk} as p")->row();
    }

    public function update_vaksin(): void
    {
        unset($this->session->validation_error, $this->session->success);
        $data           = $this->input->post();
        $error_validasi = $this->validasi_data($data);

        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $this->session->error_msg .= ': ' . $error . '\n';
            }
            $this->session->post    = $this->input->post();
            $this->session->success = -1;

            return;
        }

        if ($data['tunda'] == 0) {
            $this->upload_sertifikat($data);
        } else {
            $this->upload_surat($data);
        }

        $update = [
            'config_id'        => identitas('id'),
            'id_penduduk'      => $data['id_penduduk'],
            'vaksin_1'         => $data['vaksin_1'],
            'tgl_vaksin_1'     => $data['tgl_vaksin_1'],
            'dokumen_vaksin_1' => $data['dokumen_vaksin_1'] ?? null,
            'jenis_vaksin_1'   => $data['jenis_vaksin_1'] ?? null,
            'vaksin_2'         => $data['vaksin_2'],
            'tgl_vaksin_2'     => $data['tgl_vaksin_2'],
            'dokumen_vaksin_2' => $data['dokumen_vaksin_2'] ?? null,
            'jenis_vaksin_2'   => $data['jenis_vaksin_2'] ?? null,
            'vaksin_3'         => $data['vaksin_3'],
            'tgl_vaksin_3'     => $data['tgl_vaksin_3'] ?? null,
            'dokumen_vaksin_3' => $data['dokumen_vaksin_3'] ?? null,
            'jenis_vaksin_3'   => $data['jenis_vaksin_3'] ?? null,
            'tunda'            => $data['tunda'],
            'keterangan'       => $data['keterangan'] ?? null,
            'surat_dokter'     => $data['surat_dokter'] ?? null,
        ];
        $hasil = $this->config_id()->replace($this->table_vaksin, $update);

        status_sukses($hasil);
    }

    public function upload_sertifikat(&$data): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $file = "vaksin_{$i}";
            if ($_FILES[$file]['size'] != 0 && $data["tgl_vaksin_{$i}"]) {
                $data["dokumen_vaksin_{$i}"] = $this->do_upload($file, $data);
            }
        }
    }

    public function do_upload($file, $data)
    {
        $config['upload_path']   = LOKASI_VAKSIN;
        $config['file_name']     = 'vaksin';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 1024;
        $config['overwrite']     = true;
        $this->upload->initialize($config);

        try {
            $upload = $this->upload->do_upload($file);

            if (! $upload) {
                $this->session->error_msg = $this->upload->display_errors();
                $this->session->success   = -1;

                return redirect('vaksin_covid/form?terdata=' . $data['id_penduduk']);
            }

            $uploadData = $this->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $config['upload_path'] . $uploadData['file_name'],
                $config['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];

            return $uploadData['file_name'];
        } catch (Exception $e) {
            $this->session->post    = $this->input->post();
            $this->session->success = -1;

            return redirect('vaksin_covid/form?terdata=' . $data['id_penduduk']);
        }
    }

    public function upload_surat(&$data): void
    {
        if ($_FILES['surat_dokter']['size'] != 0 && $data['tunda'] == 1) {
            $file                 = 'surat_dokter';
            $data['surat_dokter'] = $this->do_upload($file, $data);
        }
    }

    public function validasi_data(&$data)
    {
        if ((int) ($data['id_penduduk']) == 0) {
            $valid[] = 'NIK belum di pilih';
        }

        //  steril data
        $data['id_penduduk']    = (int) ($data['id_penduduk']);
        $data['vaksin_1']       = (int) ($data['vaksin_1']);
        $data['tgl_vaksin_1']   = (! isset($data['tgl_vaksin_1']) || $data['tgl_vaksin_1'] == '') ? null : rev_tgl($data['tgl_vaksin_1']);
        $data['jenis_vaksin_1'] = (isset($data['jenis_vaksin_1']) || $data['jenis_vaksin_1'] != '') ? alfanumerik_spasi($data['jenis_vaksin_1']) : null;
        $data['vaksin_2']       = (int) ($data['vaksin_2']);
        $data['tgl_vaksin_2']   = (! isset($data['tgl_vaksin_2']) || $data['tgl_vaksin_2'] == '') ? null : rev_tgl($data['tgl_vaksin_2']);
        $data['jenis_vaksin_2'] = (isset($data['jenis_vaksin_2']) || $data['jenis_vaksin_2'] != '') ? alfanumerik_spasi($data['jenis_vaksin_2']) : null;
        $data['vaksin_3']       = (int) ($data['vaksin_3']);
        $data['tgl_vaksin_3']   = (! isset($data['tgl_vaksin_3']) || $data['tgl_vaksin_3'] == '') ? null : rev_tgl($data['tgl_vaksin_3']);
        $data['jenis_vaksin_3'] = (isset($data['jenis_vaksin_3']) || $data['jenis_vaksin_3'] != '') ? alfanumerik_spasi($data['jenis_vaksin_3']) : null;
        $data['tunda']          = (int) ($data['tunda']);
        $data['surat_dokter'] ??= null;
        $data['keterangan'] = alfanumerik_spasi($data['keterangan']);

        $this->session->success = -1;

        return $valid;
    }

    public function rekap($umur)
    {
        $this->penduduk_sql();

        if ($umur != 0) {
            $this->umur_sql($umur);
        }

        //ORDER BERDASARKAN DUSUN
        $this->db->order_by('ck.dusun', 'asc');

        return $this->db->get("{$this->tabel_penduduk} as p")->result();
    }

    public function autocomplete($cari = '')
    {
        $sql_kolom  = [];
        $list_kolom = [
            'nama' => $this->tabel_penduduk,
            'nik'  => $this->tabel_penduduk,
        ];

        foreach ($list_kolom as $kolom => $tabel) {
            $this->config_id()
                ->select($kolom . ' as item')
                ->distinct()->from($tabel)
                ->order_by('item');
            if ($cari) {
                $this->db->like($kolom, $cari);
            }
            $sql_kolom[] = $this->db->get_compiled_select();
        }

        $sql   = '(' . implode(') UNION (', $sql_kolom) . ')';
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        return autocomplete_data_ke_str($data);
    }

    /**
     * Impor Data Penerima Vaksin
     * Alur :
     * Cek apakah NIK ada atau tidak.
     * 1. Jika Ya, update data penduduk (penerima vaksin) berdasarkan data impor.
     * 2. Jika Tidak, tampilkan notifikasi baris data yang gagal.
     *
     * @param mixed $hapus
     */
    public function impor()
    {
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'xlsx';

        $this->upload->initialize($config);

        if (! $this->upload->do_upload('userfile')) {
            return session_error($this->upload->display_errors());
        }

        $this->upload->data();

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($_FILES['userfile']['tmp_name']);

        $outp = true;

        foreach ($reader->getSheetIterator() as $sheet) {
            $baris_pertama = false;
            $gagal         = 0;
            $nomor_baris   = 0;
            $pesan         = '';

            if ($sheet->getName() == 'Vaksin') {
                foreach ($sheet->getRowIterator() as $row) {
                    // Abaikan baris pertama yg berisi nama kolom
                    if (! $baris_pertama) {
                        $baris_pertama = true;

                        continue;
                    }

                    $nomor_baris++;
                    $cells = $row->getCells();

                    $nik = (string) $cells[0];

                    if ($nik === '') {
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} Kolom NIK Tidak Boleh Kosong.</br>";
                        $gagal++;
                        $outp = false;

                        continue;
                    }

                    if ($penduduk = $this->cekPenduduk($nik)) {
                        $id_penduduk = $penduduk['id'];

                        if ((string) $cells[7] === '') {
                            $tunda      = 0;
                            $keterangan = null;
                            if (! empty($tgl_vaksin_1 = $this->cekTgl((string) $cells[1]))) {
                                $vaksin_1       = 1;
                                $jenis_vaksin_1 = $this->jenisVaksin($cells[2]);

                                if (! empty($tgl_vaksin_2 = $this->cekTgl((string) $cells[3]))) {
                                    $vaksin_2       = 1;
                                    $jenis_vaksin_2 = $this->jenisVaksin($cells[4], $jenis_vaksin_1);

                                    if (! empty($tgl_vaksin_3 = $this->cekTgl((string) $cells[5]))) {
                                        $vaksin_3       = 1;
                                        $jenis_vaksin_3 = $this->jenisVaksin($cells[6], $jenis_vaksin_2);
                                    } else {
                                        $pesan .= "Pesan Lainnya : Baris {$nomor_baris} kolom vaksin-3 tidak valid, hanya vaksin 1 dan 2 yang tersimpan.</br>";
                                        $vaksin_3       = 0;
                                        $tgl_vaksin_3   = null;
                                        $jenis_vaksin_3 = null;
                                    }
                                } else {
                                    $pesan .= "Pesan Lainnya : Baris {$nomor_baris} kolom vaksin-2 tidak valid, hanya vaksin 1 yang tersimpan.</br>";
                                    $vaksin_2       = $vaksin_3 = 0;
                                    $tgl_vaksin_2   = $tgl_vaksin_3 = null;
                                    $jenis_vaksin_2 = $jenis_vaksin_3 = null;
                                }
                            } else {
                                // Kolom vaksin 1 tidak boleh kosong jika tunda == 1
                                $pesan .= "Pesan Gagal : Baris {$nomor_baris} kolom vaksin-1 tidak valid.</br>";
                                $gagal++;
                                $outp = false;

                                continue;
                            }
                        } else {
                            $tunda      = 1;
                            $keterangan = $cells[7];
                        }

                        $dataVaksin = [
                            'id_penduduk'    => $id_penduduk,
                            'config_id'      => identitas('id'),
                            'vaksin_1'       => $vaksin_1,
                            'tgl_vaksin_1'   => $tgl_vaksin_1,
                            'jenis_vaksin_1' => $jenis_vaksin_1,
                            'vaksin_2'       => $vaksin_2,
                            'tgl_vaksin_2'   => $tgl_vaksin_2,
                            'jenis_vaksin_2' => $jenis_vaksin_2,
                            'vaksin_3'       => $vaksin_3,
                            'tgl_vaksin_3'   => $tgl_vaksin_3,
                            'jenis_vaksin_3' => $jenis_vaksin_3,
                            'tunda'          => $tunda,
                            'keterangan'     => $keterangan,
                        ];

                        $sql = $this->db->insert_string('covid19_vaksin', $dataVaksin) . ' ON DUPLICATE KEY UPDATE
                            id_penduduk = VALUES(id_penduduk),
                            config_id = VALUES(config_id),
                            vaksin_1 = VALUES(vaksin_1),
                            tgl_vaksin_1 = VALUES(tgl_vaksin_1),
                            jenis_vaksin_1 = VALUES(jenis_vaksin_1),
                            vaksin_2 = VALUES(vaksin_2),
                            tgl_vaksin_2 = VALUES(tgl_vaksin_2),
                            jenis_vaksin_2 = VALUES(jenis_vaksin_2),
                            vaksin_3 = VALUES(vaksin_3),
                            tgl_vaksin_3 = VALUES(tgl_vaksin_3),
                            jenis_vaksin_3 = VALUES(jenis_vaksin_3),
                            tunda = VALUES(tunda),
                            keterangan = VALUES(keterangan)
                            ';

                        if (! $this->db->query($sql)) {
                            $pesan .= "Pesan Gagal : Baris {$nomor_baris} Data penduduk dengan NIK : {$nik} gagal disimpan</br>";
                            $gagal++;
                            $outp = false;

                            continue;
                        }
                    } else {
                        $pesan .= "Pesan Gagal : Baris {$nomor_baris} Data penduduk dengan NIK : {$nik} tidak ditemukan</br>";
                        $gagal++;
                        $outp = false;
                    }
                }
                $berhasil = ($nomor_baris - $gagal);
                $pesan .= "Jumlah Berhasil : {$berhasil} </br>";
                $pesan .= "Jumlah Gagal : {$gagal} </br>";
                $pesan .= "Jumlah Data : {$nomor_baris} </br>";

                break;
            }

            return session_error('-> File impor tidak sesuai');
        }
        $reader->close();
        set_session('pesan_vaksin', $pesan);

        return status_sukses($outp, false, 'Terjadi kesalahan impor data Penerima Vaksin');
    }

    private function cekPenduduk(string $nik = '')
    {
        return $this->config_id()
            ->select('id', 'nama')
            ->where('nik', $nik)
            ->get('tweb_penduduk')
            ->row_array();
    }

    protected function cekTgl(string $value = '')
    {
        return (date('Y-m-d', strtotime($value)) === $value) ? $value : false;
    }

    protected function jenisVaksin(string $cells = '', $default = '')
    {
        if ($cells === '') {
            $this->load->model('referensi_model');

            if (! $default) {
                return $this->referensi_model->list_ref(JENIS_VAKSIN)[0];
            }

            return $default;
        }

        return $cells;
    }
}
