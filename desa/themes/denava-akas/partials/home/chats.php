<script>
$('#ChatSupport').czmChatSupport({
	button: {
		position: "left", /* left, right atau false. "position:false" untuk nonaktifkan */
		style: 1, /* Jenis tombol. Tulis antara nomor 1 sampai 7 */
		src: '<i class="fa fa-whatsapp"></i>',
		backgroundColor: "#10c379",
		effect: 3, /* Efek tombol. Tulis antara nomor 1 sampai 7 */
		notificationNumber: false,
		speechBubble: false,
		pulseEffect: true,
		text: {
			title: "<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'] ?>",
			description: "<?= THEME_NAME ?> <?= THEME_VERSION ?>",
			online: "Selamat Datang.",
			offline: "Kami segera kembali."
		}
	},
	popup: {
		automaticOpen: false,
		outsideClickClosePopup: true,
		effect: 1, /* Efek pembuka icon. Tulis antara nomor 1 sampai 15 */
		header: {
			backgroundColor: "#10c379",
		},
		persons: [
		{
			avatar: {
				src: '<img src="<?= gambar_desa($desa['logo']) ?>" alt="">',
				backgroundColor: "#ffffff",
				onlineCircle: false
			},
			text: {
				title: "<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'] ?>",
				description: "Tema <?= THEME_NAME ?> <?= THEME_VERSION ?>",
				message: "Halo 🙂<br>Ada yang bisa saya bantu?",
				textbox: "Ketik di sini",
				button: false
			},
			link: {
				desktop: "https://api.whatsapp.com/send?phone=<?= format_telpon($desa['nomor_operator']) ?>&text=<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'] ?>",
				mobile: "https://wa.me/<?= format_telpon($desa['nomor_operator']) ?>/?text=<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'] ?>"
			},
			onlineDay: {
				sunday: false,
				monday: "00:00-23:59",
				tuesday: "00:00-23:59",
				wednesday: "00:00-23:59",
				thursday: "00:00-23:59",
				friday: "00:00-23:59",
				saturday: false
			}
		},
		]
	},
	sound: false,
	changeBrowserTitle: "<?= $this->setting->website_title.' '.trim(ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']) ?>",
	cookie: false,
});
</script>
