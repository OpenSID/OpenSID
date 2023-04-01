<script>
	jQuery(function($)  {
		$("#status").on('change', function() {
			var status = $(this).val();
			if (status == "Hapus") {
				$("#mutasi").parent().parent().show();
				$("#mutasi").addClass('required');
			} else {
				$("#mutasi").parent().parent().hide();
				$("#mutasi").removeClass('required');
			}
		});

		var mutasi = "<?= $main->jenis_mutasi ?>";
		cek_mutasi(mutasi);

		$("#mutasi").on('change', function() {
			var mutasi = $(this).val();
			cek_mutasi(mutasi);
		});

		function cek_mutasi(mutasi) {
			if (mutasi == "Masih Baik Disumbangkan" | mutasi == "Barang Rusak Disumbangkan") {
				$(".disumbangkan").show();
				$(".harga_jual").hide();
				$("#sumbangkan").addClass("required");
				$("#harga_jual").removeClass("required");
			} else if (mutasi == "Masih Baik Dijual" | mutasi == "Barang Rusak Dijual") {
				$(".disumbangkan").hide();
				$(".harga_jual").show();
				$("#harga_jual").addClass("required");
				$("#sumbangkan").removeClass("required");
			} else {
				$(".disumbangkan").hide();
				$(".harga_jual").hide();
				$("#harga_jual").removeClass("required");
				$("#sumbangkan").removeClass("required");
			}
		}
	});
</script>