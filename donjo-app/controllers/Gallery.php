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

class Gallery extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('web_gallery_model');
        $this->modul_ini     = 'admin-web';
        $this->sub_modul_ini = 'galeri';
    }

    public function clear(): void
    {
        unset($_SESSION['cari'], $_SESSION['filter']);

        redirect('gallery');
    }

    public function index($p = 1, $o = 0): void
    {
        $data['p'] = $p;
        $data['o'] = $o;

        $data['cari'] = $_SESSION['cari'] ?? '';

        $data['filter'] = $_SESSION['filter'] ?? '';

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['paging']  = $this->web_gallery_model->paging($p, $o);
        $data['main']    = $this->web_gallery_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword'] = $this->web_gallery_model->autocomplete();

        $this->render('gallery/table', $data);
    }

    public function form($p = 1, $o = 0, $id = null): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $data['p'] = $p;
        $data['o'] = $o;

        if (null !== $id) {
            $id                  = decrypt($id);
            $data['gallery']     = $this->web_gallery_model->get_gallery($id);
            $data['form_action'] = site_url("gallery/update/{$id}/{$p}/{$o}");
        } else {
            $data['gallery']     = null;
            $data['form_action'] = site_url('gallery/insert');
        }

        $this->render('gallery/form', $data);
    }

    public function search($gallery = ''): void
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        if ($gallery != '') {
            redirect('gallery/sub_gallery/' . encrypt($gallery));
        } else {
            redirect('gallery');
        }
    }

    public function filter($gallery = ''): void
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        if ($gallery != '') {
            redirect('gallery/sub_gallery/' . encrypt($gallery));
        } else {
            redirect('gallery');
        }
    }

    public function insert(): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->insert();
        redirect('gallery');
    }

    public function update($id = '', $p = 1, $o = 0): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->update($id);
        redirect("gallery/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = ''): void
    {
        $this->redirect_hak_akses('h', "gallery/index/{$p}/{$o}");
        $this->web_gallery_model->delete_gallery(decrypt($id));
        redirect("gallery/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        $this->redirect_hak_akses('h', "gallery/index/{$p}/{$o}");
        $_SESSION['success'] = 1;
        $this->web_gallery_model->delete_all_gallery();
        redirect("gallery/index/{$p}/{$o}");
    }

    public function gallery_lock($id = '', $gallery = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->gallery_lock(decrypt($id), 1);
        if ($gallery != '') {
            redirect("gallery/sub_gallery/{$gallery}/{$p}");
        } else {
            redirect("gallery/index/{$p}/{$o}");
        }
    }

    public function gallery_unlock($id = '', $gallery = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->gallery_lock(decrypt($id), 2);
        if ($gallery != '') {
            redirect("gallery/sub_gallery/{$gallery}/{$p}");
        } else {
            redirect("gallery/index/{$p}/{$o}");
        }
    }

    public function slider_on($id = '', $gallery = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->gallery_slider(decrypt($id), 1);
        if ($gallery != '') {
            redirect("gallery/sub_gallery/{$gallery}/{$p}");
        } else {
            redirect("gallery/index/{$p}/{$o}");
        }
    }

    public function slider_off($id = '', $gallery = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->gallery_slider(decrypt($id), 0);
        if ($gallery != '') {
            redirect("gallery/sub_gallery/{$gallery}/{$p}");
        } else {
            redirect("gallery/index/{$p}/{$o}");
        }
    }

    public function sub_gallery($gal = 0, $p = 1, $o = 0): void
    {
        $gal       = decrypt($gal);
        $data['p'] = $p;
        $data['o'] = $o;

        $data['cari'] = $_SESSION['cari'] ?? '';

        $data['filter'] = $_SESSION['filter'] ?? '';

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['paging']      = $this->web_gallery_model->paging2($gal, $p);
        $data['sub_gallery'] = $this->web_gallery_model->list_sub_gallery($gal, $o, $data['paging']->offset, $data['paging']->per_page);
        $data['gallery']     = $gal;
        $data['sub']         = $this->web_gallery_model->get_gallery($gal);
        $data['keyword']     = $this->web_gallery_model->autocomplete();

        $this->render('gallery/sub_gallery_table', $data);
    }

    public function form_sub_gallery($gallery = null, $id = null): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $gallery = decrypt($gallery);

        if (null !== $id) {
            $id                  = decrypt($id);
            $data['gallery']     = $this->web_gallery_model->get_gallery($id);
            $data['form_action'] = site_url("gallery/update_sub_gallery/{$gallery}/{$id}");
        } else {
            $data['gallery']     = null;
            $data['form_action'] = site_url("gallery/insert_sub_gallery/{$gallery}");
        }
        $data['album'] = $gallery;

        $this->render('gallery/form_sub_gallery', $data);
    }

    public function insert_sub_gallery($gallery = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->insert_sub_gallery($gallery);
        redirect('gallery/sub_gallery/' . encrypt($gallery));
    }

    public function update_sub_gallery($gallery = '', $id = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->update_sub_gallery($id);
        redirect('gallery/sub_gallery/' . encrypt($gallery));
    }

    public function delete_sub_gallery($gallery = '', $id = ''): void
    {
        $this->redirect_hak_akses('h', "gallery/sub_gallery/{$gallery}");
        $this->web_gallery_model->delete(decrypt($id));
        redirect("gallery/sub_gallery/{$gallery}");
    }

    public function delete_all_sub_gallery($gallery = ''): void
    {
        $this->redirect_hak_akses('h', "gallery/sub_gallery/{$gallery}");
        $_SESSION['success'] = 1;
        $this->web_gallery_model->delete_all();
        redirect("gallery/sub_gallery/{$gallery}");
    }

    public function gallery_lock_sub_gallery($gallery = '', $id = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->gallery_lock($id, 1);
        redirect("gallery/sub_gallery/{$gallery}");
    }

    public function gallery_unlock_sub_gallery($gallery = '', $id = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->gallery_lock($id, 2);
        redirect("gallery/sub_gallery/{$gallery}");
    }

    public function urut($id, $arah = 0, $gallery = ''): void
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->web_gallery_model->urut(decrypt($id), $arah, decrypt($gallery));
        if ($gallery != '') {
            redirect("gallery/sub_gallery/{$gallery}");
        } else {
            redirect('gallery/index');
        }
    }
}
