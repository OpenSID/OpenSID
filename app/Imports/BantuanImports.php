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

namespace App\Imports;

use App\Models\Bantuan;
use App\Models\Penduduk;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;

class BantuanImports
{
    protected $path;

    public function __construct($path = null, protected $ganti_program = 0, protected $kosongkan_peserta = 0, protected $ganti_peserta = 0, protected $rand_kartu_peserta = 0)
    {
        $this->path = $path ?? DEFAULT_LOKASI_IMPOR . 'bantuan.xlsx';
    }

    private function getValue($array): array
    {
        return array_values($array);
    }

    private function getId($array): array
    {
        return array_keys($array);
    }

    public function import(): bool
    {
        try {
            $ganti_program      = $this->ganti_program;
            $kosongkan_peserta  = $this->kosongkan_peserta;
            $ganti_peserta      = $this->ganti_peserta;
            $rand_kartu_peserta = $this->rand_kartu_peserta;
            $daftar_program     = Bantuan::pluck('id')->toArray();

            $data = (new FastExcel())->importSheets($this->path);

            foreach ($data as $key => $sheet) {
                $no_baris  = 0;
                $no_gagal  = 0;
                $no_sukses = 0;

                $data_peserta = [];
                $data_diubah  = '';

                // data program
                if ($key == 0) {
                    $pesan_program = '';
                    $field         = ['id', 'nama', 'sasaran', 'ndesc', 'asaldana', 'sdate', 'edate'];

                    $data_program['id'] = $this->getId($sheet[0])[1];
                    if (in_array((int) $data_program['id'], $daftar_program)) {
                        $program_id = $data_program['id'];
                        if ($ganti_program === null) {
                            $pesan_program .= 'Data program dengan <b> id = ' . ($data_program['id']) . '</b> ditemukan, data lama tetap digunakan <br>';
                        } else {
                            $pesan_program .= 'Data program dengan <b> id = ' . ($data_program['id']) . '</b> ditemukan, data lama diganti dengan data baru <br>';
                        }
                    } elseif (! in_array((int) $data_program['id'], $daftar_program)) {
                        $program_id = null;
                        $pesan_program .= 'Data program dengan <b> id = ' . ($data_program['id']) . '</b> tidak ditemukan, program baru ditambahkan secara otomatis) <br>';
                    }

                    for ($i = 0; $i <= 5; $i++) {
                        $title = $this->getValue($sheet[$i])[0];
                        $value = $this->getValue($sheet[$i])[1];
                        if (in_array($i, [4, 5]) && ! validate_date($value, 'Y-m-d')) {
                            $msg = ', Data program baris <b> Ke-' . ($no_baris) . '</b> berisi tanggal yang salah. Cek kembali data ' . $title . ' = ' . $value;
                            redirect_with('error', $msg);
                        }
                        $data_program[$field[$i + 1]] = $value;
                        $no_baris                     = $i + 1;
                    }
                    $program_id = Bantuan::impor_program($program_id, $data_program, $ganti_program);
                }

                // data peserta
                if ($key == 1) {
                    // cek gunakan program lain
                    $pesan_peserta = '';
                    $ambil_peserta = Bantuan::select('id', 'sasaran')->with(['peserta' => static function ($query): void {
                        $query->select('program_id', 'peserta');
                    }])->find($program_id);
                    $sasaran           = (int) $ambil_peserta->sasaran;
                    $terdaftar_peserta = $ambil_peserta->peserta->pluck('peserta')->toArray();

                    if ($kosongkan_peserta == 1) {
                        $pesan_peserta .= '- Data peserta ' . ($ambil_peserta[0]['nama']) . ' sukses dikosongkan<br>';
                        $terdaftar_peserta = [];
                    }

                    foreach ($sheet as $value) {
                        $no_baris++;

                        $cells = array_values($value);

                        $peserta = (string) $cells[0];
                        $nik     = (string) $cells[2];

                        // Cek valid data peserta sesuai sasaran
                        $cek_peserta = Bantuan::cek_peserta($peserta, $sasaran);

                        if (! in_array($nik, $cek_peserta['valid'])) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / ' . $cek_peserta['sasaran_peserta'] . ' = ' . $peserta . '</b> tidak ditemukan <br>';

                            continue;
                        }

                        // Cek valid data penduduk sesuai nik
                        $cek_penduduk = Penduduk::where('nik', $nik)->first();

                        if (! $cek_penduduk['id']) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / NIK = ' . $nik . '</b> yang terdaftar tidak ditemukan <br>';

                            continue;
                        }

                        // Cek data peserta yg akan dimpor dan yg sudah ada
                        if (in_array($peserta, $terdaftar_peserta) && $ganti_peserta != 1) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> sudah ada <br>';

                            continue;
                        }

                        if (in_array($peserta, $terdaftar_peserta) && $ganti_peserta == 1) {
                            $data_diubah   .= ', ' . $peserta;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> ditambahkan menggantikan data lama <br>';
                        }

                        // Jika kosong ambil data dari database
                        $no_id_kartu         = (string) $cells[1];
                        $kartu_nama          = (string) $cells[3];
                        $kartu_tempat_lahir  = (string) $cells[4];
                        $kartu_tanggal_lahir = $cells[5];
                        // $kartu_tanggal_lahir = $this->cek_is_date($kartu_tanggal_lahir);
                        $kartu_alamat = (string) $cells[6];

                        if (empty($kartu_tanggal_lahir)) {
                            $kartu_tanggal_lahir = $cek_penduduk['tanggallahir'];
                        } elseif (! validate_date($kartu_tanggal_lahir, 'Y-m-d')) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> berisi tanggal yang salah<br>';

                            continue;
                        }
                        // Random no. kartu peserta
                        if ($rand_kartu_peserta == 1) {
                            $no_id_kartu = 'acak_' . random_int(1, 1000);
                        }

                        // Ubah data peserta menjadi id (untuk saat ini masih data kelompok yg menggunakan id)
                        // Berkaitan dgn issue #3417
                        if ($sasaran == 4) {
                            $peserta = $cek_peserta['id'];
                        }

                        $simpan = [
                            'config_id'           => identitas('id'),
                            'peserta'             => $peserta,
                            'program_id'          => $program_id,
                            'no_id_kartu'         => $no_id_kartu,
                            'kartu_nik'           => $nik,
                            'kartu_nama'          => $kartu_nama ?: $cek_penduduk['nama'],
                            'kartu_tempat_lahir'  => $kartu_tempat_lahir ?: $cek_penduduk['tempatlahir'],
                            'kartu_tanggal_lahir' => $kartu_tanggal_lahir,
                            'kartu_alamat'        => $kartu_alamat ?: $cek_penduduk['alamat_wilayah'],
                            'kartu_id_pend'       => $cek_penduduk['id'],
                        ];
                        $data_peserta[] = $simpan;
                        $no_sukses++;
                    }

                    $notif = [
                        'program_id' => $program_id,
                        'program'    => $pesan_program,
                        'gagal'      => $no_gagal,
                        'sukses'     => $no_sukses,
                        'peserta'    => $pesan_peserta,
                    ];

                    // Proses impor peserta
                    if ($no_baris <= 0) {
                        $pesan_peserta .= '- Data peserta tidak tersedia<br>';
                    } else {
                        $imporPeserta = Bantuan::impor_peserta($program_id, $data_peserta, $kosongkan_peserta, $data_diubah);
                    }

                }
            }

            set_session('notif', $notif);
            status_sukses($imporPeserta, true);
            redirect_with('success', 'Data berhasil disimpan', ci_route('peserta_bantuan/detail_clear', ['program_id' => $notif['program_id']]));
        } catch (Exception $e) {
            log_message('error', $e);

            return false;
        }

        return false;
    }
}
