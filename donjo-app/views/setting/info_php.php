<div class="content-wrapper">
	<section class="content-header">
		<h1>Info Sistem</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Info Sistem</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row" >
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-11">
                <?php
									ob_start();
									phpinfo();
									$phpinfo = array('phpinfo' => array());
									if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER)):
										foreach ($matches as $match):
											if (strlen($match[1])):
												$phpinfo[$match[1]] = array();
											elseif (isset($match[3])):
												$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]): $match[3];
											else:
												$phpinfo[end(array_keys($phpinfo))][] = $match[2];
											endif;
										endforeach;
								?>
										<?php $i = 0;?>
                    <?php foreach ($phpinfo as $name => $section): ?>
											<?php $i++;?>
											<?php if ($i==1): ?>
                      	<h3><div class='table-responsive'><table class='table table-bordered dataTable table-hover'>
											<?php else: ?>
												<h3><?=$name?></h3><div class='table-responsive'><table class='table table-bordered dataTable table-hover'>
											<?php endif ?>
											<?php foreach ($section as $key => $val): ?>
												<?php if (is_array($val)): ?>
                          <tr><td class="col-md-4 info"><?=$key?></td><td><?=$val[0]?></td><td><?=$val[1]?></td></tr>
												<?php elseif (is_string($key)): ?>
                          <tr><td class="col-md-4 info"><?=$key?></td><td colspan='2'><?=$val?></td></tr>
												<?php else: ?>
                          <tr><td class="btn-primary" colspan='3'><?=$val?></td></tr>
												<?php endif; ?>
                      <?php endforeach;?>
                      </table></div>
										<?php endforeach;?>
									<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
