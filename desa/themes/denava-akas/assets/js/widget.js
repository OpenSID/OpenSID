
function numberFormat(num) {
	return new Intl.NumberFormat('id-ID').format(num);
}

function parseToNum(data) {
	return parseFloat(data.toString().replace(/,/g, ''));
}

function showError(elem = '') {
	$(`${elem} .shimmer`).html('<span class="small"><i class="fa fa-exclamation-triangle"></i> Gagal memuat...</span>');
	$(`${elem} .shimmer`).removeClass('shimmer');
}

$(document).ready(function () {
	if ($('#jadwal-shalat').length) {

		const SHALAT_API_URL = 'https://api.myquran.com/';
		const ENDPOINT_KOTA = `v2/sholat/kota/${KODE_KOTA}`;
		const ENDPOINT_JADWAL = `v2/sholat/jadwal/${KODE_KOTA}/${TANGGAL}`;

		try {
			$.ajax({
				async: true,
				cache: true,
				url: SHALAT_API_URL + ENDPOINT_KOTA,
				success: function (res) {
					$('[data-name=kota]').html(res.data.lokasi).removeClass('shimmer line-short');
				},
				error: function (err) {
					showError('#jadwal-shalat');
				}
			});

			$.ajax({
				url: SHALAT_API_URL + ENDPOINT_JADWAL,
				async: true,
				cache: true,
				success: function (res) {
					$('#jadwal-shalat .shimmer').removeClass('shimmer');
					const attrs = ['tanggal', 'imsak', 'subuh', 'terbit', 'dhuha', 'dzuhur', 'ashar', 'maghrib', 'isya'];
					attrs.forEach(function (val) {
						$(`[data-name=${val}]`).html(`<span>${res.data.jadwal[val]}</span>`);
					})
				},
				error: function (err) {
					showError('#jadwal-shalat');
				}
			});
		} catch (err) {
			showError('#jadwal-shalat');
		}

	}
})