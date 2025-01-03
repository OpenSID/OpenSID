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

namespace Modules\Lapak\Models;

use App\Models\BaseModel;
use App\Models\MediaSosial;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Support\Facades\DB;

class Produk extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    protected $table   = 'produk';
    protected $guarded = [];
    protected $appends = [
        'harga_diskon',
        'pesan_wa',
    ];
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];

    /**
     * @var array
     */
    protected $list_satuan = ['lusin', 'gross', 'rim', 'lembar', 'pcs', 'gram', 'kg', 'paket'];

    public function kategori()
    {
        return $this->belongsTo(ProdukKategori::class, 'id_produk_kategori', 'id');
    }

    public function pelapak()
    {
        return $this->belongsTo(Pelapak::class, 'id_pelapak', 'id');
    }

    public function scopeListProduk($query)
    {
        $kantor  = identitas();
        $telepon = MediaSosial::where(['id' => 6, 'tipe' => 1, 'enabled' => 1])->first()->link;

        return $this->withoutGlobalScopes()
            ->withConfigId('produk')
            ->select(
                'produk.*',
                'pk.kategori',
                'p.nik',
                'lp.zoom',
                DB::raw("(case when p.nama is null then 'Admin' else p.nama end) as pelapak"),
                DB::raw("(case when p.nama is null then '{$telepon}' else lp.telepon end) as telepon"),
                DB::raw("if(lp.lat is null or lp.lat = ' ', if(m.lat is null or m.lat = ' ', '{$kantor->lat}', m.lat), lp.lat) as lat"),
                DB::raw("if(lp.lng is null or lp.lng = ' ', if(m.lng is null or m.lng = ' ', '{$kantor->lng}', m.lng), lp.lng) as lng")
            )
            ->leftJoin('produk_kategori as pk', 'produk.id_produk_kategori', '=', 'pk.id')
            ->leftJoin('pelapak as lp', 'produk.id_pelapak', '=', 'lp.id')
            ->leftJoin('penduduk_hidup as p', 'lp.id_pend', '=', 'p.id')
            ->leftJoin('tweb_penduduk_map as m', 'p.id', '=', 'm.id')
            ->where('lp.status', '=', 1)
            ->where('pk.status', '=', 1);
    }

    public function scopeListSatuan($query)
    {
        $query = $this->withoutGlobalScopes()
            ->withConfigId('produk')
            ->distinct()
            ->get();

        foreach ($query as $value) {
            if (! in_array($value->satuan, $this->list_satuan)) {
                $this->list_satuan[] = $value->satuan;
            }
        }
        usort($this->list_satuan, 'strnatcasecmp');

        return $this->list_satuan;
    }

    public static function navigasi(): array
    {
        return [
            'jml_produk' => [
                'aktif' => Produk::listProduk()->where('produk.status', 1)->count(),
                'total' => Produk::listProduk()->count(),
            ],

            'jml_pelapak' => [
                'aktif' => Pelapak::listPelapak()->where('pelapak.status', 1)->count(),
                'total' => Pelapak::listPelapak()->count(),
            ],

            'jml_kategori' => [
                'aktif' => ProdukKategori::listKategori()->where('produk_kategori.status', 1)->count(),
                'total' => ProdukKategori::listKategori()->count(),
            ],
        ];
    }

    public function produkInsert(array $post = [])
    {
        $data = $this->produkValidasi($post);

        return $this->create($data);
    }

    public function produkUpdate($id = null, array $post = [])
    {
        $data = $this->produkValidasi($post);

        return $this->where('id', $id)->update($data);
    }

    public function produkDelete($id = 0)
    {
        $this->hapusFotoProduk('id', $id);

        return $this->where('id', $id)->delete();
    }

    public function produkDeleteAll()
    {
        $id_cb  = request('id_cb', []);
        $result = false;

        foreach ($id_cb as $id) {
            $result = $this->produkDelete($id);
        }

        return $result;
    }

    private function produkValidasi(array $post = []): array
    {
        $foto = [];

        for ($i = 0; $i < ci()->setting->banyak_foto_tiap_produk; $i++) {
            $value = $this->uploadFotoProduk($i + 1);
            if ($value == null) {
                continue;
            }
            $foto[] = $value;
        }

        $data = [
            'id_pelapak'         => bilangan($post['id_pelapak']),
            'nama'               => judul($post['nama']),
            'id_produk_kategori' => alfanumerik_spasi($post['id_produk_kategori']),
            'harga'              => bilangan($post['harga']),
            'satuan'             => alfanumerik_spasi($post['satuan']),
            'tipe_potongan'      => bilangan($post['tipe_potongan']),
            'deskripsi'          => ci()->security->xss_clean($post['deskripsi']),
            'foto'               => ($foto == []) ? null : json_encode($foto, JSON_THROW_ON_ERROR),
            'potongan'           => ($post['potongan'] == null) ? '0' : $post['potongan'],
        ];

        if ($post['tipe_potongan'] == 1 && ! empty($post['persen'])) {
            $data['potongan'] = bilangan($post['persen']);
        }

        if ($post['tipe_potongan'] == 2 && ! empty($post['nominal'])) {
            $data['potongan'] = bilangan($post['nominal']);
        }

        if (isset($post['status'])) {
            $data['status'] = $post['status'];
        }

        return $data;
    }

    private function uploadFotoProduk(int $key = 1)
    {
        ci()->load->library('upload');
        // Adakah berkas yang disertakan?
        if (empty($_FILES["foto_{$key}"]['name'])) {
            // Jika hapus (ceklis)
            if (isset($_POST["hapus_foto_{$key}"])) {
                unlink(LOKASI_PRODUK . ci()->input->post("old_foto_{$key}"));

                return null;
            }

            return ci()->input->post("old_foto_{$key}");
        }

        $uploadData = null;
        // Inisialisasi library 'upload'
        ci()->upload->initialize([
            'upload_path'   => LOKASI_PRODUK,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => 1024, // 1 MB
        ]);
        // Upload gagal
        if (! ci()->upload->do_upload("foto_{$key}")) {
            redirect_with('error', ci()->upload->display_errors(), 'lapak_admin/produk');
        }
        // Upload sukses
        else {
            unlink(LOKASI_PRODUK . ci()->input->post("old_foto_{$key}"));
            $uploadData = ci()->upload->data()['file_name'];
        }

        return $uploadData;
    }

    private function hapusFotoProduk(string $where = 'id', $value = 0): void
    {
        // Hapus semua foto produk jika produk/kategori/pelapak dihapus agar tidak meninggalkan sampah
        $list_data = $this->select('foto')->where($where, $value)->get();

        if (! $list_data) {
            return;
        }

        foreach ($list_data as $data) {
            $foto = json_decode($data->foto, true) ?? [];

            foreach ($foto as $file_name) {
                $file = LOKASI_PRODUK . $file_name;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    protected function scopeActive($query)
    {
        return $query->whereHas('kategori', static fn ($query) => $query->active())
            ->whereHas('pelapak', static fn ($query) => $query->active())
            ->whereStatus(StatusEnum::YA);
    }

    protected function getHargaDiskonAttribute()
    {
        if ($this->potongan == 0) {
            return $this->harga;
        }

        return $this->tipe_potongan == 1
            ? $this->harga - ($this->harga * $this->potongan / 100)
            : $this->harga - $this->potongan;
    }

    protected function getPesanWaAttribute()
    {
        $pesan = strReplaceArrayRecursive(
            [
                '[nama_produk]' => $this->nama,
                '[link_web]'    => base_url('lapak'),
                '<br />'        => '%0A',
            ],
            nl2br(setting('pesan_singkat_wa'))
        );

        $telepon = $this->pelapak->telepon ? format_telpon($this->pelapak->telepon) : null;

        return $telepon ? "https://api.whatsapp.com/send?phone={$telepon}&text={$pesan}" : null;
    }
}
