import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Fix Auto-Restore Anjungan dari Cookie', () => {
    test('Auto-Restore Anjungan dari Cookie seharusnya berhasil masuk ke anjungan-mandiri tanpa redirect', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/auto-restore-anjungan-dari-cookie',
        },
    }, async ({ page }) => {
        // 1. Tambah data anjungan baru
        await page.goto('anjungan/form');

        // Ambil UUID yang digenerate otomatis di input #anjungan_id
        const uuid = await page.inputValue('#anjungan_id');
        expect(uuid).not.toBeNull();
        expect(uuid).not.toBe('');

        // Gunakan data random untuk memastikan test independen & bisa dijalankan berulang
        const ipAddress = `192.168.1.${Math.floor(Math.random() * 253) + 1}`;
        const macAddress = `00:1A:2B:3C:4D:${Math.floor(Math.random() * 99).toString().padStart(2, '0')}`;

        await page.locator('#ip_address').fill(ipAddress);
        await page.locator('#mac_address').fill(macAddress);
        await page.locator('#tambahDaftarAnjungan').click();

        // Tunggu redirect dan pastikan notifikasi sukses muncul
        await page.waitForURL('**/anjungan');
        await expect(page.locator('.alert.alert-success')).toBeVisible();

        // Simpan semua cookies (termasuk session admin dan cookie anjungan_uuid yang baru dibuat)
        const originalCookies = await page.context().cookies();

        // Pastikan cookie anjungan_uuid berhasil diset
        const anjunganCookie = originalCookies.find(c => c.name === 'anjungan_uuid');
        expect(anjunganCookie).toBeDefined();
        expect(anjunganCookie?.value).toBe(uuid);

        // 2. Simulasikan Session Expiration
        // Hapus semua cookies terlebih dahulu
        await page.context().clearCookies();

        // Set ONLY cookie anjungan_uuid untuk mensimulasikan session baru/terhapus tapi cookie anjungan tetap ada
        const domain = new URL(page.url()).hostname;
        await page.context().addCookies([{
            name: 'anjungan_uuid',
            value: uuid,
            domain: domain,
            path: '/'
        }]);

        // Buka halaman anjungan-mandiri (kiosk public page)
        await page.goto('anjungan-mandiri');
        await page.waitForLoadState('networkidle');

        // Hapus localStorage agar kita benar-benar yakin session direstore dari cookie, bukan dari client-side localStorage
        await page.evaluate(() => localStorage.clear());

        // Refresh halaman
        await page.reload({ waitUntil: 'networkidle' });

        // Verifikasi: Kita harus tetap berada di halaman anjungan-mandiri dan TIDAK diredirect ke login/masuk
        await expect(page).toHaveURL(/.*anjungan-mandiri.*/);

        // Verifikasi elemen visual di anjungan-mandiri untuk memastikan terakses dengan sukses
        await expect(page.locator('body')).toBeVisible();
        await expect(page).not.toHaveURL(/.*layanan-mandiri\/masuk.*/);

        // 3. Cleanup: Kembalikan session admin untuk menghapus anjungan test tadi
        await page.context().clearCookies();
        await page.context().addCookies(originalCookies);

        // Pergi ke daftar anjungan
        await page.goto('anjungan');
        await page.waitForLoadState('networkidle');

        // Cari baris anjungan yang telah dibuat dan hapus
        const row = page.locator('tr', { hasText: macAddress });
        await expect(row).toBeVisible();
        await row.locator('input[name="id_cb[]"]').check();
        await page.locator('a.hapus-terpilih').click();
        await page.locator('#ok-delete').click();

        // Tunggu notifikasi sukses penghapusan
        await expect(page.locator('.alert.alert-success')).toBeVisible();
        await expect(page.locator('tr', { hasText: macAddress })).toHaveCount(0);
    });
});
