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

namespace App\Models;

use App\Traits\ConfigId;
use App\Traits\ShortcutCache;

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukMandiri extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * {@inheritDoc}
     */
    public const CREATED_AT = 'tanggal_buat';

    /**
     * {@inheritDoc}
     */
    public const UPDATED_AT = 'updated_at';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'id_pend';

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_penduduk_mandiri';

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'penduduk',
    ];

    /**
     * Scope query untuk aktif
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatus($query, mixed $value = 1)
    {
        return $query->where('aktif', $value);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_pend');
    }

    /**
     * Get email penduduk attribute.
     *
     * @return string
     */
    public function getEmailAttribute()
    {
        return $this->penduduk->email;
    }

    public function generate_pin(): string
    {
        return strrev(random_int(100000, 999999));
    }

    public function gantiPin($id_pend, $nama, $data): array
    {
        $ganti    = $data;
        $pin_lama = hash_pin(bilangan($ganti['pin_lama']));
        hash_pin(bilangan($ganti['pin_baru1']));
        $pin_baru2 = hash_pin(bilangan($ganti['pin_baru2']));

        $pilihan_kirim = $ganti['pilihan_kirim'];

        // Ganti password
        $pin = PendudukMandiri::where('id_pend', $id_pend)->first()->pin;

        $data = [
            'id_pend'    => $id_pend,
            'pin'        => $pin_baru2,
            'last_login' => date('Y-m-d H:i:s', NOW()),
            'ganti_pin'  => 0,
        ];

        switch (true) {
            case akun_demo($id_pend):
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Tidak dapat mengubah PIN akun demo',
                ];
                break;

            case $pin_lama != $pin:
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'PIN gagal diganti, <b>PIN Lama</b> yang anda masukkan tidak sesuai',
                ];
                break;

            case $pin_baru2 == $pin:
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => '<b>PIN</b> gagal diganti, Silahkan ganti <b>PIN Lama</b> anda dengan <b>PIN Baru</b> ',
                ];
                break;

            case $pilihan_kirim == 'kirim_telegram':
                if ($this->kirimTelegram(['id_pend' => $id_pend, 'pin' => $ganti['pin_baru2'], 'nama' => $nama])) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'aksi'   => site_url('layanan-mandiri/keluar'),
                        'pesan'  => 'PIN Baru sudah dikirim ke Akun Telegram Anda',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => '<b>PIN Baru</b> gagal dikirim ke Telegram, silahkan hubungi operator',
                    ];
                }
                break;

            case $pilihan_kirim == 'kirim_email':
                if ($this->kirimEmail(['id_pend' => $id_pend, 'pin' => $ganti['pin_baru2'], 'nama' => $nama])) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'aksi'   => site_url('layanan-mandiri/keluar'),
                        'pesan'  => 'PIN Baru sudah dikirim ke Akun Email Anda',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => '<b>PIN Baru</b> gagal dikirim ke Email, silahkan hubungi operator',
                    ];
                }
                break;

            default:
                PendudukMandiri::where('id_pend', $id_pend)->update($data);

                $respon = [
                    'status' => 1, // Notif berhasil
                    'aksi'   => site_url('layanan-mandiri/keluar'),
                    'pesan'  => 'PIN berhasil diganti, silahkan masuk kembali dengan Kode PIN : ' . $ganti['pin_baru2'],
                ];
                break;
        }

        set_session('notif', $respon);

        return $respon;
    }
}
