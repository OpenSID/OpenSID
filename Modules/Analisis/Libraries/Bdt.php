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

namespace Modules\Analisis\Libraries;

use App\Models\Penduduk;
use App\Models\Rtm;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisRespon;
use Spreadsheet_Excel_Reader;

defined('BASEPATH') || exit('No direct script access allowed');

class Bdt
{
    private $idMaster;
    private $periode;
    private $analisisMaster;
    private $jml_baris;
    private $baris_pertama;
    private array $kolom = [
        'id_rtm'               => 2,
        'nama'                 => 80,
        'nik'                  => 81,
        'rtm_level'            => 82,
        'awal_respon_rt'       => 14,
        'awal_respon_penduduk' => 82,
    ];
    private $kolom_subjek;
    private $kolom_indikator_pertama;
    private $list_id_subjek;

    public function __construct($idMaster, $periode)
    {
        $this->idMaster       = $idMaster;
        $this->periode        = $periode;
        $this->analisisMaster = AnalisisMaster::findOrFail($idMaster);
    }

    private function fileImportValid()
    {
        // error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
        // TODO: pakai cara upload yg disediakan Codeigniter
        if ($_FILES['bdt']['error'] == 1) {
            $upload_mb = max_upload();
            $_SESSION['error_msg'] .= ' -> Ukuran file melebihi batas ' . $upload_mb . ' MB';
            $_SESSION['success'] = -1;

            return false;
        }
        $tipe_file       = TipeFile($_FILES['bdt']);
        $mime_type_excel = ['application/vnd.ms-excel', 'application/octet-stream'];
        if (! in_array($tipe_file, $mime_type_excel)) {
            $_SESSION['error_msg'] .= ' -> Jenis file salah: ' . $tipe_file;
            $_SESSION['success'] = -1;

            return false;
        }

        return true;
    }

    /*
     * 1. Impor pengelompokan rumah tangga
     * 2. Impor data BDT 2015 ke dalam analisis_respon
     *
     * Abaikan subjek di data BDT yang tidak ada di database
    */
    public function impor(): void
    {
        $_SESSION['error_msg'] = '';
        $_SESSION['success']   = 1;
        if ($this->fileImportValid() == false) {
            return;
        }

        // Pakai parameter 'false' untuk mengurangi penggunaan memori
        // https://github.com/jasonrogena/php-excel-reader/issues/96
        $data = new Spreadsheet_Excel_Reader($_FILES['bdt']['tmp_name'], false);
        // Baca jumlah baris berkas BDT
        $this->jml_baris     = $data->rowcount($sheet_index = 0);
        $this->baris_pertama = $this->cariBarisPertama($data, $this->jml_baris);
        if ($this->baris_pertama <= 0) {
            $_SESSION['error_msg'] .= ' -> Tidak ada data';
            $_SESSION['success'] = -1;

            return;
        }

        // BDT2015 terbatas pada subjek rumah tangga dan penduduk
        if ($_SESSION['subjek_tipe'] == 3) {
            // Rumah tangga
            $this->kolom_subjek            = $this->kolom['id_rtm'];
            $this->kolom_indikator_pertama = $this->kolom['awal_respon_rt'];
        } else {
            // Penduduk
            $this->kolom_subjek            = $this->kolom['nik'];
            $this->kolom_indikator_pertama = $this->kolom['awal_respon_penduduk'];
        }

        $data_sheet = $data->sheets[0]['cells'];
        $this->imporRespon($data_sheet);
    }

    private function imporRespon($data_sheet): void
    {
        $gagal        = 0;
        $ada          = 0;
        $sudah_proses = [];
        $per          = $this->periode;
        $indikator    = AnalisisIndikator::where('id_master', $this->idMaster)->orderBy('id')->get()->toArray();

        $respon = [];

        for ($i = $this->baris_pertama; $i <= $this->jml_baris; $i++) {
            $data_subjek = $this->tulisRtm($data_sheet[$i], $rtm);
            if (! $data_subjek) {
                $gagal++;

                continue; // Jangan impor jika NIK tidak ada di database
            }
            // Proses setiap subjek sekali saja
            if (! in_array($data_sheet[$i][$this->kolom_subjek], $sudah_proses)) {
                // $list_id_subjek[nik] = id-penduduk atau $list_id_subjek[id_rtm] = id-rumah-tangga
                if ($this->analisisMaster->subjek_tipe == 3) {
                    $this->list_id_subjek[$data_sheet[$i][$this->kolom_subjek]] = $rtm;
                } else {
                    $this->list_id_subjek[$data_sheet[$i][$this->kolom_subjek]] = $data_subjek['id_penduduk'];
                }
                $this->siapkanRespon($indikator, $per, $data_sheet[$i], $respon);
                $sudah_proses[] = $data_sheet[$i][$this->kolom_subjek];
                $ada++;
            }
        }

        // echo '<br><br>';
        // echo var_dump($this->list_id_subjek);
        $this->hapusRespon($this->list_id_subjek);

        // echo '<br><br>';
        // echo var_dump($respon);

        $outp = empty($respon) ? false : AnalisisRespon::insert($respon);
        (new AnalisisRespon())->pre_update($this->idMaster, $this->periode);

        if (! $outp) {
            $_SESSION['success'] = -1;
        }

        $nama_subjek = ($_SESSION['subjek_tipe'] == 3) ? 'RUMAH TANGGA' : 'PENDUDUK';
        echo "<br>JUMLAH PENDUDUK GAGAL    : {$gagal}</br>";
        echo "<br>JUMLAH {$nama_subjek} BERHASIL : {$ada}</br>";
        echo '<a href="' . site_url('analisis_respon') . '">LANJUT</a>';
    }

    // Hapus semua respon untuk semua subjek pada periode aktif
    private function hapusRespon($list_id_subjek): void
    {
        if (empty($list_id_subjek)) {
            return;
        }

        $per    = $this->periode;
        $prefix = $list_id_subjek_str = '';

        foreach ($list_id_subjek as $id) {
            $list_id_subjek_str .= $prefix . "'" . $id . "'";
            $prefix = ', ';
        }

        AnalisisRespon::where('id_periode', $per)->whereRaw("id_subjek in({$list_id_subjek_str})")->delete();
    }

    private function cariBarisPertama(Spreadsheet_Excel_Reader $data, $jml_baris)
    {
        if ($jml_baris <= 1) {
            return 0;
        }

        $ada_baris = false;

        // Baris pertama baris judul kolom
        for ($i = 2; $i <= $jml_baris; $i++) {
            // Baris kedua yang mungkin ditambahkan untuk memudahkan penomoran kolom
            if ($data->val($i, 1) == 'KOLOM') {
                continue;
            }
            if (empty($data->val($i, 1))) {
                continue;
            }
            $ada_baris     = true;
            $baris_pertama = $i;
            break;
        }
        if ($ada_baris) {
            return $baris_pertama;
        }

        return 0;
    }

    private function tulisRtm($baris, &$rtm)
    {
        $id_rtm    = $baris[$this->kolom['id_rtm']];
        $rtm_level = $baris[$this->kolom['rtm_level']];
        if ($rtm_level > 1) {
            $rtm_level = 2;
        } //Hanya rekam kepala & anggota rumah tangga
        $nik = $baris[$this->kolom['nik']];

        $pendudukObj = Penduduk::where('nik', $nik)->first();
        if (! $pendudukObj) {
            // Laporkan penduduk BDT tidak ada di database
            echo "<a style='color: red;'>" . $id_rtm . ' ' . $rtm_level . ' ' . $nik . ' ' . $baris[$this->kolom['nama']] . ' == tidak ditemukan di database penduduk. </a><br>';

            return false;
        }

        $rtm = Rtm::where('no_kk', $id_rtm)->first()->id;
        if ($rtm) {
            // Update rumah tangga
            if ($rtm_level == 1) {
                Rtm::where('id', $rtm)->update(['nik_kepala' => $pendudukObj->id]);
            }
        } else {
            // Tambah rumah tangga
            $rtm_data          = [];
            $rtm_data['no_kk'] = $id_rtm;
            if ($rtm_level == 1) {
                $rtm_data['nik_kepala'] = $pendudukObj->id;
            }

            $rtm = (Rtm::create($rtm_data))->id;
        }

        $penduduk               = [];
        $penduduk['id_rtm']     = $id_rtm;
        $penduduk['rtm_level']  = $rtm_level;
        $penduduk['updated_at'] = date('Y-m-d H:i:s');
        $penduduk['updated_by'] = auth()->id;
        Penduduk::where('nik', $nik)->update($penduduk);
        $penduduk['id_penduduk'] = $pendudukObj->id;

        return $penduduk;
    }

    private function siapkanRespon($indikator, $per, $baris, &$respon): void
    {
        foreach ($indikator as $key => $indi) {
            $isi = $baris[$this->kolom_indikator_pertama + $key];

            switch ($indi['id_tipe']) {
                case 1:
                    $list_parameter = $this->parameterPilihanTunggal($indi['id'], $isi);
                    break;

                case 2:
                    $list_parameter = $this->parameterPilihanGanda($indi['id'], $isi);
                    break;

                default:
                    $list_parameter = $this->parameterIsian($indi['id'], $isi);
                    break;
            }

            // Himpun respon untuk semua indikator untuk semua baris
            foreach ($list_parameter as $parameter) {
                if (! empty($parameter)) {
                    $respon[] = [
                        'id_indikator' => $indi['id'],
                        'id_subjek'    => $this->list_id_subjek[$baris[$this->kolom_subjek]],
                        'id_periode'   => $per,
                        'id_parameter' => $parameter,
                    ];
                }
            }
        }
    }

    private function parameterPilihanTunggal($id_indikator, $isi)
    {
        $param = AnalisisParameter::select('id')->where('id_indikator', $id_indikator)->where('kode_jawaban', $isi)->first()->toArray();
        if ($param) {
            $in_param = $param['id'];
        } elseif ($isi == '') {
            $in_param = 0;
        } else {
            $in_param = -1;
        }

        return [$in_param];
    }

    private function parameterPilihanGanda($id_indikator, $isi)
    {
        if (empty($isi)) {
            return [null];
        }
        $id_isi   = explode(',', $isi);
        $in_param = [];

        foreach ($id_isi as $isi) {
            $param = AnalisisParameter::select('id')->where('id_indikator', $id_indikator)->where('kode_jawaban', $isi)->first()->toArray();
            if ($param['id'] != '') {
                $in_param[] = $param['id'];
            }
        }

        return $in_param;
    }

    private function parameterIsian($id_indikator, $isi)
    {
        if (empty($isi)) {
            return [null];
        }
        $param = AnalisisParameter::select('id')->where('id_indikator', $id_indikator)->where('jawaban', $isi)->first()->toArray();

        // apakah sdh ada jawaban yg sama
        if ($param) {
            $in_param = $param['id'];
        } else {
            // simpan setiap jawaban yang baru
            $parameter                 = [];
            $parameter['jawaban']      = $isi;
            $parameter['id_indikator'] = $id_indikator;
            $parameter['asign']        = 0;

            $in_param = (AnalisisParameter::create($parameter))->id;
        }

        return [$in_param];
    }
}
