<script  TYPE='text/javascript'>
  $(function() {
    var keyword = <?php echo $keyword?> ;
    $( "#cari" ).autocomplete({
    source: keyword
    });
  });
</script>

<div id="pageC">
	<table class="inner">
		<tr style="vertical-align:top">
			<td style="background:#fff;padding:0px;">
				<div class="content-header">
				    <h3>Manajemen Penduduk</h3>
				</div>
				<div id="contentpane">
					<form id="mainform" name="mainform" action="" method="post">
					  <input type="hidden" name="rt" value="">

					  <div class="ui-layout-north panel">
						  Content North
						</div>
					  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
						  Content Center
					  </div>
						<div class="ui-layout-south panel bottom">
							Content South
						</div>
					</form>
				</div>
			</td>
		</tr>
	</table>
</div>
