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

if (! defined('BASEPATH')) exit('No direct script access allowed');

    // include data kode
    include STORAGEPATH . 'app/template/lampiran/kode.php';

    $input['status_kk_bagi_yang_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_pindah']), array_map('strtolower', $data['kode']['status_kk_pindah']));

    switch (strtoupper($input['klasifikasi_pindah'])) {
        case 'DALAM SATU DESA/KELURAHAN':
            $input['judul_format'] = 'Antar Desa/Kelurahan Dalam Satu Kecamatan';
            $input['kode_format']  = 'F-1.27';
            break;

        case 'ANTAR DESA/KELURAHAN':
            $input['judul_format'] = 'Antar Desa/Kelurahan Dalam Satu Kecamatan';
            $input['kode_format']  = 'F-1.27';
            break;

        case 'ANTAR KECAMATAN':
            $input['judul_format'] = 'Antar Kecamatan Dalam Satu Kabupaten/Kota';
            $input['kode_format']  = 'F-1.31';
            break;

        case 'ANTAR KAB/KOTA' || 'ANTAR PROVINSI':
            $input['judul_format'] = 'Antar Kabupaten/Kota atau Antar Provinsi';
            $input['kode_format']  = 'F-1.39';
            break;

        default:
            $input['judul_format'] = null;
            $input['kode_format']  = null;
            break;
    }
