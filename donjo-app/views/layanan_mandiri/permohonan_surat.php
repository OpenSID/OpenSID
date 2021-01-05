<div class="box box-solid" id="wrapper-mandiri">
	<div class="box-header with-border bg-green">
		<h4 class="box-title">Surat</h4>
	</div>
	<div class="box-body box-line">
		<div class="box-body permohonan-surat">
			<?php
				$nama_surat = $url;
				$form_surat = LOKASI_SURAT_DESA . $nama_surat . "/" . $nama_surat . ".php";
				if (is_file($form_surat))
					include($form_surat);
				elseif (is_file(LOKASI_SURAT_FORM_DESA . $nama_surat . ".php"))
					include(LOKASI_SURAT_FORM_DESA . $nama_surat . ".php");
				else
					include("template-surat/$nama_surat/$nama_surat.php");
			?>
			<textarea id="isian_form" hidden="hidden"><?= $isian_form; ?></textarea>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Di form surat ubah isian admin menjadi disabled
		$("#wrapper-mandiri .readonly-permohonan").attr('disabled', true);
		$("#wrapper-mandiri form#validasi").removeAttr('target');
		$("#wrapper-mandiri .tdk-permohonan textarea").removeClass('required');
		$("#wrapper-mandiri .tdk-permohonan select").removeClass('required');
		$("#wrapper-mandiri .tdk-permohonan input").removeClass('required');
	});

	$(document).ready(function() {
		// Di form surat ubah isian admin menjadi disabled
		$("#periksa-permohonan .readonly-periksa").attr('disabled', true);

		if ($('#isian_form').val()) {
			setTimeout(function() {isi_form();}, 100);
		}
	});

	function isi_form() {
		var isian_form = JSON.parse($('#isian_form').val(), function(key, value) {

			if (key) {
				var elem = $('*[name=' + key + ']');
				elem.val(value);
				elem.change();
				// Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
				if (elem.is(":hidden")) {
					var show = $('#' + key + '_show');
					show.val(value);
					show.change();
				}
			}
		});
	}
</script>
