Rilis versi 2607.0.1 ini berisi [untuk diisi] dan perbaikan lainnya yang diminta oleh komunitas SID.

## KEAMANAN

1. [#6671](https://github.com/OpenSID/premium/issues/6671) Perbaikan keamanan untuk mengatasi celah Privilege Escalation (eskalasi hak akses) serta optimalisasi Mass Assignment pada model User.
2. [#6680](https://github.com/OpenSID/premium/issues/6680) Perbaikan kerentanan enumerasi pengguna (user enumeration) pada endpoint /siteman/otp.
3. [#6675](https://github.com/OpenSID/premium/issues/6675) Perbaikan 2FA Toggle Without Password Verification — Pengguna::update_keamanan().
4. [#6672](https://github.com/OpenSID/premium/issues/6672) Perbaikan keamanan IDOR — Citizen Portal Surat Proses.
5. [#6673](https://github.com/OpenSID/premium/issues/6673) Perbaikan keamanan IDOR — Citizen Portal (fmandiri) Surat Cetak, Unauthorized Letter Download.
6. [#6674](https://github.com/OpenSID/premium/issues/6674) Perbaikan keamanan IDOR — Citizen Portal (fmandiri) Pesan Baca, Read Any Citizen's Messages.
7. [#6341](https://github.com/OpenSID/premium/issues/6341) Perbaikan keamanan Unauthenticated Arbitrary Artisan Command Execution via PlaywrightController.
8. [#6793](https://github.com/OpenSID/premium/issues/6793) Perbaikan celah LFI pada endpoint theme_asset, module_asset, dan file storage-desa.
9. [#6807](https://github.com/OpenSID/premium/issues/6807) Perbaikan RCE via Insecure Deserialization pada endpoint /index.php/tampil/ dan unduh.
