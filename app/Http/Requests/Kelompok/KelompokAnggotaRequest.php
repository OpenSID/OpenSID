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

namespace App\Http\Requests\Kelompok;

use Illuminate\Foundation\Http\FormRequest;

class KelompokAnggotaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return can('u');
    }

    /**
     * Konversi empty string ke null untuk kolom integer/date.
     */
    protected function prepareForValidation(): void
    {
        $nullableFields = ['id_penduduk', 'sex_luar', 'tanggallahir_luar', 'tgl_sk_pengangkatan', 'tgl_sk_pemberhentian', 'agama_luar', 'pendidikan_luar'];
        $dateFields     = ['tanggallahir_luar', 'tgl_sk_pengangkatan', 'tgl_sk_pemberhentian'];

        $data = $this->all();

        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $data) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        // Konversi format tanggal d-m-Y (datepicker) → Y-m-d (MySQL)
        foreach ($dateFields as $field) {
            if (! empty($data[$field]) && preg_match('/^\d{2}-\d{2}-\d{4}$/', $data[$field])) {
                $data[$field] = \Carbon\Carbon::createFromFormat('d-m-Y', $data[$field])->format('Y-m-d');
            }
        }

        $this->data = $data;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $luarDesa = (int) request()->input('luar_desa', 0) === 1;

        return [
            'luar_desa'            => 'required|boolean',
            'id_penduduk'          => $luarDesa ? 'nullable|integer' : 'required|integer',
            'nama_luar'            => $luarDesa ? 'required|string|max:100' : 'nullable|string|max:100',
            'nik_luar'             => $luarDesa ? 'required|string|min:16|max:16' : 'nullable|string|min:16|max:16',
            'sex_luar'             => $luarDesa ? 'required|integer' : 'nullable|integer',
            'tempatlahir_luar'     => $luarDesa ? 'required|string|max:100' : 'nullable|string|max:100',
            'tanggallahir_luar'    => $luarDesa ? 'required|date' : 'nullable|date',
            'alamat_luar'          => $luarDesa ? 'required|string|max:300' : 'nullable|string|max:300',
            'agama_luar'           => $luarDesa ? 'required|integer' : 'nullable|integer',
            'pendidikan_luar'      => $luarDesa ? 'required|integer' : 'nullable|integer',
            'no_anggota'           => 'required|string|max:50',
            'jabatan'              => 'required|string|max:50',
            'no_sk_jabatan'        => 'nullable|string|max:50',
            'keterangan'           => 'nullable|string|max:300',
            'nmr_sk_pengangkatan'  => 'sometimes|nullable|string|max:50',
            'tgl_sk_pengangkatan'  => 'sometimes|nullable|date',
            'nmr_sk_pemberhentian' => 'sometimes|nullable|string|max:50',
            'tgl_sk_pemberhentian' => 'sometimes|nullable|date',
            'periode'              => 'sometimes|nullable|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'id_penduduk'          => 'Nama Anggota',
            'nama_luar'            => 'Nama Lengkap',
            'nik_luar'             => 'NIK',
            'sex_luar'             => 'Jenis Kelamin',
            'tempatlahir_luar'     => 'Tempat Lahir',
            'tanggallahir_luar'    => 'Tanggal Lahir',
            'alamat_luar'          => 'Alamat',
            'agama_luar'           => 'Agama',
            'pendidikan_luar'      => 'Pendidikan Terakhir',
            'no_anggota'           => 'Nomor Anggota',
            'jabatan'              => 'Jabatan',
            'no_sk_jabatan'        => 'Nomor SK Jabatan',
            'keterangan'           => 'Keterangan',
            'nmr_sk_pengangkatan'  => 'Nomor SK Pengangkatan',
            'tgl_sk_pengangkatan'  => 'Tanggal SK Pengangkatan',
            'nmr_sk_pemberhentian' => 'Nomor SK Pemberhentian',
            'tgl_sk_pemberhentian' => 'Tanggal SK Pemberhentian',
            'periode'              => 'Masa Jabatan',
        ];
    }
}
