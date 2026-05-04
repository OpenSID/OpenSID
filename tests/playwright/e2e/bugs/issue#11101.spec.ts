import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tombol2 ketika lihat detail data penduduk tidak beraturan #11101', () => {
    test('fix: tampilkan tombol2 ketika lihat detail data penduduk tidak beraturan', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11101',
        },
    }, async ({ page }) => {
        // Set mobile viewport
        await page.setViewportSize({ width: 375, height: 667 });

        await page.goto('/penduduk/detail/7802');

        const toolbar = page.locator('.btn-toolbar');
        await expect(toolbar).toBeVisible();

        const toolbarBox = await toolbar.boundingBox();
        if (!toolbarBox) throw new Error('Toolbar bounding box is null');

        const docBtn = page.locator('a.btn:has-text("Manajemen Dokumen")');
        const kembaliBtn = page.locator('a.btn:has-text("Kembali Ke Daftar Penduduk")');
        const ubahBtn = page.locator('a.btn:has-text("Ubah Biodata")');
        const cetakBtn = page.locator('a.btn:has-text("Cetak Biodata")');
        const anggotaBtn = page.locator('a.btn:has-text("Anggota Keluarga")');
        const tambahBtn = page.locator('a.btn:has-text("Tambah Penduduk")');

        // Check visibility and widths of full-width buttons
        if (await docBtn.isVisible()) {
            const box = await docBtn.boundingBox();
            expect(box?.width).toBeCloseTo(toolbarBox.width, 0);
        }

        if (await kembaliBtn.isVisible()) {
            const box = await kembaliBtn.boundingBox();
            expect(box?.width).toBeCloseTo(toolbarBox.width, 0);
        }

        // Check widths of 50% buttons
        if (await ubahBtn.isVisible() && await cetakBtn.isVisible()) {
            const boxUbah = await ubahBtn.boundingBox();
            const boxCetak = await cetakBtn.boundingBox();
            expect(boxUbah?.width).toBeLessThan(toolbarBox.width * 0.6);
            expect(boxCetak?.width).toBeLessThan(toolbarBox.width * 0.6);
        }

        // Verify that Anggota Keluarga and Tambah Penduduk have aligned heights
        if (await anggotaBtn.isVisible() && await tambahBtn.isVisible()) {
            const boxAnggota = await anggotaBtn.boundingBox();
            const boxTambah = await tambahBtn.boundingBox();
            expect(boxAnggota?.width).toBeLessThan(toolbarBox.width * 0.6);
            expect(boxTambah?.width).toBeLessThan(toolbarBox.width * 0.6);
            
            // Heights must be equal
            expect(boxAnggota?.height).toBeCloseTo(boxTambah?.height!, 0);
        }
    });
});
