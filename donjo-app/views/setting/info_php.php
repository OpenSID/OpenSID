<style type="text/css">
	.tdc {
		padding-top:5px;
		padding-bottom:5px;
		border-bottom: 1px solid #e5e5e5;
	}
	.tdc2 {
		border-bottom: 1px solid #e5e5e5;
	}
</style>
<?php
   ob_start();
   phpinfo();
   $Ausgabe = ob_get_contents();
   ob_end_clean();
   preg_match_all("=<body[^>]*>(.*)</body>=siU", $Ausgabe, $a);
	 $phpinfo = $a[1][0];
   $phpinfo = str_replace( '<table border="0" cellpadding="3" width="600">', '<div class="table-responsive"><table class="table table-striped table-hover"><tbdoy>', $phpinfo );
	 $phpinfo = str_replace( '<tr class="h">', '<tr class="text-center">', $phpinfo );
	 $phpinfo = str_replace( '<td class="e">', '<td class="tdc">', $phpinfo );
   $phpinfo = str_replace( '<td class="v">', '<td class="tdc2">', $phpinfo );
	 $phpinfo = str_replace( '</table>', '</tbody></table></div>', $phpinfo );
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Info Sistem</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Info Sistem</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row" >
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-10">
								<?=$phpinfo?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
