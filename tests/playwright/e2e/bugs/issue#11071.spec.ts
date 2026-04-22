import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: pada hasil pemantauan dtsen, nama petugas tidak tampil, padahal sudah di isi manual #11071', () => {
    test('fix: perbaiki pada hasil pemantauan dtsen, nama petugas tidak tampil, padahal sudah di isi manual', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11071',
        },
    }, async ({ page }) => {
        // Buka halaman utama pendataan DTSEN
        await page.goto('/dtsen/pendataan');

        // Pastikan tabel termuat
        await page.waitForSelector('#tabeldata tbody tr');

        // Jika tabel kosong, maka tidak ada yang bisa dites ubah
        // (Biasanya di OpenSID e2e test database sudah di-seed, jadi pasti ada data)
        const ubahBtn = page.locator('a[title="Lihat & Ubah Data"]').first();
        await expect(ubahBtn).toBeVisible();
        await ubahBtn.click();

        // Tunggu dan buka Tab V. PETUGAS
        const navTabPetugas = page.locator('a#nav-bagian-2');
        await expect(navTabPetugas).toBeVisible();
        await navTabPetugas.click();

        // Isi kolom "202. Nama PPL"
        const testerName = 'Tester Playwright ' + Date.now();
        const inputNamaPpl = page.locator('input[id="input_2_202"]');
        await expect(inputNamaPpl).toBeVisible();
        await inputNamaPpl.fill(testerName);

        // Klik tombol Simpan pada Tab V
        const btnSimpan = page.locator('#form-2 button[type="submit"]');
        await btnSimpan.click();

        // Tunggu loading spinner selesai dan alert/toast sukses muncul/menghilang
        await page.waitForSelector('i.fa-spinner', { state: 'hidden', timeout: 5000 });
        
        // Opsional: Tunggu sebentar agar proses ajax dan swal selesai
        await page.waitForTimeout(1000);

        // Kembali ke halaman daftar pendataan
        await page.goto('/dtsen/pendataan');
        await page.waitForSelector('#tabeldata tbody tr');

        // Lakukan pengecekan pada tabel, pastikan nama yang baru kita input muncul
        const tableBody = page.locator('#tabeldata tbody');
        await expect(tableBody).toContainText(testerName);
    });
});
