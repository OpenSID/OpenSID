<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	$title = (!empty($judul_kategori))? $judul_kategori: 'Artikel Terkini';
	if(is_array($title)){
		foreach($title as $item){
			$title = $item;
		}
	}
?>

<?php
	if (empty($this->input->get('cari')) AND
		((count($slide_galeri) > 0 || count($slide_artikel) > 0)) AND 
			$this->uri->segment(2) != 'kategori') {
		$this->load->view($folder_themes .'/partials/slider');
	}
?>

<?php if(empty($this->input->get('cari')) AND $headline AND $this->uri->segment(2) != 'kategori') : ?>
	<?php $this->load->view($folder_themes .'/partials/headline') ?>
<?php endif ?>

	<section id="news-list">
		<h3 class="content__heading --mb-4 <?php empty($this->input->get('cari')) AND $headline AND $this->uri->segment(2) != 'kategori' AND print('--mt-5') ?>"><i class="fa fa-newspaper-o"></i> <?= $title ?></h3>
		<ul class="content__list">
			<?php if($artikel) : ?>
				<?php foreach($artikel as $article) : ?>
					<?php $data['article'] = $article ?>
					<?php $this->load->view($folder_themes .'/partials/article_list', $data) ?>
				<?php endforeach ?>
				<?php else : ?>
					<?php $data['title'] = $title ?>
					<?php $this->load->view($folder_themes .'/partials/empty_article', $data) ?>
			<?php endif ?>
		</ul>
		<?php $this->load->view($folder_themes .'/commons/paging') ?>
	</section>