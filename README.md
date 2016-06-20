# OpenSID
OpenSID adalah Sistem Informasi Desa CRI 3.04 yang disesuaikan supaya terbuka dan dapat dikembangkan bersama-sama oleh komunitas peduli SID.

Tujuan penyesuaian SID-CRI ini adalah untuk:
- memudahkan pengguna menyerap rilis SID baru
- memungkinkan pegiat SID untuk membuat kontribusi langsung pada _source code_ aplikasi SID

Untuk melihat perbedaan antara OpenSID dan SID-CRI, silakan klik [Tanya-Jawab OpenSID vs SID-CRI](Tanya-Jawab-OpenSID-vs-SID-CRI).

OpenSID dikelola di Github untuk:
- merekam semua perubahan yg dibuat dari versi asli SID-CRI yg diperoleh dari Andi Anwar (30 Mei 2016)
- memungkinkan kembali ke revisi sebelumnya, apabila diperlukan
- memudahkan kolaborasi antar pegiat SID dan juga dengan desa dampingan dalam mengembangkan SID
- backup online _source code_ SID yg dapat diaskses setiap saat

Wiki OpenSID (https://github.com/eddieridwan/opensid/wiki) akan dikembangkan sesuai kebutuhan.

Catatan:
- sistem ini dikelola dengan merujuk pada lisensi GNU GENERAL PUBLIC LICENSE Version 3 (http://www.gnu.org/licenses/gpl.html)
- pengembang dan pemegang hak cipta aslinya adalah Combine Resource Institution (http://lumbungkomunitas.net/)
- SID 3.04 CRI terakhir yang telah digabung dengan OpenSID di-share oleh Andi Anwar pada 30 Mei 2016
- OpenSID akan terus menggabung versi baru yang mungkin sewaktu-waktu direlease oleh CRI melalui portal mereka di http://sid.web.id.

Di mana perubahan dilakukan/diperlukan untuk mengatasi suatu permasalahan atau memenuhi suatu permintaan,
kami akan usahakan untuk merekam masalahnya dan permintaannya di https://github.com/eddieridwan/opensid/issues.

## SID Inti v SID Desa
Repository ini mempunyai dua cabang (git branch):
- cabang _master_, yang mengembangkan aplikasi OpenSID inti, untuk di-release ke desa dampingan
- cabang _desa_, yang mengembangkan contoh penerapan OpenSID di desa

Salah satu tujuan utama pengembangan OpenSID adalah untuk memisahkan file SID inti dari file yang telah disesuaikan untuk keperluan desa. Pemisahan ini dimaksudkan untuk memudahkan upgrade SID di desa setiap kali ada release SID inti yang baru.

## Demo
Demo aplikasi OpenSID dapat dilihat di http://sid.bangundesa.info. Kami usahakan agar versi yang terlihat di demo itu sesuai dengan status release terakhir repository ini. Demo itu menampilkan aplikasi desa yang dikembangkan di cabang _desa_.

Modul administrasi OpenSID dapat diaskses pada http://sid.bangundesa.info/index.php/siteman. Masukkan Username = admin dan Password = sid304.
