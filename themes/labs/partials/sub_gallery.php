<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed">
    <div class="block-header block-header-default">
        <h3 class="block-title"><i class="si si-picture"></i>  Sub Galeri</h3>
    </div>
    <div class="block-content" style="background-color:#f5f6f7">
        <div class="row items-push js-sub_gallery img-fluid-100 js-sub_gallery-enabled">
            <?php 		$i=1;
			foreach($gallery AS $data){
				if(is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])) {
		echo "
        <div class=\"col-md-6 col-xl-4\">
                    <a class=\"img-link img-link-zoom-in img-thumb img-lightbox\"   data-toggle=\"popover\"
					title=\"Galeri Album: ". $parrent[nama]."\"
					data-placement=\"top\" href=\"".AmbilGaleri($data['gambar'], 'sedang')."\">
					<img class=\"img-fluid\" src=\"".AmbilGaleri($data['gambar'],'sedang')."\">
                    </a>
		</div>";
		if(fmod($i,2)==0){echo "<br class=\"clearboth\">";}
		$i++; } } ?>
        </div>
    </div>
</div>
<div class="row justify-content-center text-center">
    <div class="col-md-4">
        <div class="block">
            <div class="block-content">
                <nav aria-label="Page navigation">
                    <?php 
echo "
			<span>Halaman ".$p." dari ".$paging->end_link." <hr></span>
				<ul class=\"pagination pagination-lg\">";
				// TODO : butuh helper untuk menggenerate html tag untuk paging
				if($paging->start_link){
					echo " <li class=\"page-item\"> <a class=\"page-link\"  href=\"".site_url("first/sub_gallery/y/$parrent[id]/$paging->start_link")."\" title=\"Halaman Pertama\"><i class=\"fa fa-fast-backward\"></i>&nbsp;</a></li>";
				}
				if($paging->prev){
					echo " <li class=\"page-item\"> <a class=\"page-link\"  href=\"".site_url("first/sub_gallery/y/$parrent[id]/$paging->prev")."\" title=\"Halaman Sebelumnya\"><i class=\"fa fa-backward\"></i>&nbsp;</a></li>";
				}

				foreach($pages as $i) {
					$strC = ($p == $i)? "class=\"page-item active\"":"";
					echo "<li ".$strC."> <a class=\"page-link\"  href=\"".site_url("first/sub_gallery/y/$parrent[id]/$i")."\" title=\"Halaman ".$i."\">".$i."</a></li>";
				}

				if($paging->next){
					echo " <li class=\"page-item\"> <a class=\"page-link\"  href=\"".site_url("first/sub_gallery/y/$parrent[id]/$paging->next")."\" title=\"Halaman Selanjutnya\"><i class=\"fa fa-forward\"></i>&nbsp;</a></li>";
				}
				if($paging->end_link){
					echo " <li class=\"page-item\"> <a class=\"page-link\"  href=\"".site_url("first/sub_gallery/y/$parrent[id]/$paging->end_link")."\" title=\"Halaman Terakhir\"><i class=\"fa fa-fast-forward\"></i>&nbsp;</a></li>";
				}
					echo "";
				echo "
				</ul>
	
	";

?>
                </nav>
            </div>
        </div>
    </div>
</div>