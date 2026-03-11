import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Insecure Server-Side Validation & Logic Flaw pada Modul Lapak (Potongan Harga > 100%) #10918', () => {
  test('fix: perbaiki Insecure Server-Side Validation & Logic Flaw pada Modul Lapak (Potongan Harga > 100%)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10918',
    },
  }, async ({ page }) => {
    const NAMA_PRODUK_PERSEN = 'E2E Test - Produk Persen';
    const NAMA_PRODUK_NOMINAL = 'E2E Test - Produk Nominal';

    // Hapus produk yang mungkin tersisa dari tes sebelumnya untuk memastikan kebersihan state
    test.beforeEach(async ({ page }) => {
        await page.goto('layanan-mandiri/produk');
        
        // Cari produk berdasarkan nama dan hapus jika ada
        const produkPersen = page.locator(`tr:has-text("${NAMA_PRODUK_PERSEN}")`);
        if (await produkPersen.isVisible()) {
            await produkPersen.locator('a[title="Hapus"]').click();
            await page.once('dialog', dialog => dialog.accept());
            await expect(page.locator('div.alert-success')).toBeVisible();
        }

        const produkNominal = page.locator(`tr:has-text("${NAMA_PRODUK_NOMINAL}")`);
        if (await produkNominal.isVisible()) {
            await produkNominal.locator('a[title="Hapus"]').click();
            await page.once('dialog', dialog => dialog.accept());
            await expect(page.locator('div.alert-success')).toBeVisible();
        }
    });

    test('Harus menampilkan error validasi untuk diskon persen > 100', async ({ page }) => {
        // Navigasi ke form tambah produk
        await page.goto('layanan-mandiri/produk/form');
        await expect(page.locator('h3.box-title')).toContainText('Formulir Produk');

        // Isi semua field yang wajib diisi
        await page.locator('input[name="nama"]').fill(NAMA_PRODUK_PERSEN);
        await page.locator('select[name="id_produk_kategori"]').selectOption({ index: 1 });
        await page.locator('input[name="harga"]').fill('50000');
        await page.locator('input[name="satuan"]').fill('Buah');
        await page.locator('textarea[name="deskripsi"]').fill('Deskripsi produk untuk testing E2E.');

        // Pilih tipe diskon persen
        await page.locator('input[name="tipe_potongan"][value="1"]').check();

        // Hapus atribut 'max' untuk bypass validasi client-side dan isi dengan nilai tidak valid
        const persenInput = page.locator('input[name="persen"]');
        await persenInput.evaluate(el => el.removeAttribute('max'));
        await persenInput.fill('101');

        // Submit form
        await page.locator('button[type="submit"]:has-text("Simpan")').click();

        // Verifikasi bahwa pesan error dari server ditampilkan
        const pesanError = page.locator('div.alert.alert-danger');
        await expect(pesanError).toBeVisible();
        await expect(pesanError).toContainText('Persentase potongan harus di antara 0 dan 100');
    });

    test('Harus menampilkan error validasi untuk diskon nominal melebihi batas', async ({ page }) => {
        // Navigasi ke form tambah produk
        await page.goto('layanan-mandiri/produk/form');
        await expect(page.locator('h3.box-title')).toContainText('Formulir Produk');

        // Isi semua field yang wajib diisi
        await page.locator('input[name="nama"]').fill(NAMA_PRODUK_NOMINAL);
        await page.locator('select[name="id_produk_kategori"]').selectOption({ index: 1 });
        await page.locator('input[name="harga"]').fill('50000');
        await page.locator('input[name="satuan"]').fill('Buah');
        await page.locator('textarea[name="deskripsi"]').fill('Deskripsi produk untuk testing E2E.');

        // Pilih tipe diskon nominal
        await page.locator('input[name="tipe_potongan"][value="2"]').check();

        // Hapus atribut 'max' untuk bypass validasi client-side dan isi dengan nilai tidak valid
        const nominalInput = page.locator('input[name="nominal"]');
        await nominalInput.evaluate(el => el.removeAttribute('max'));
        await nominalInput.fill('100000000000'); // Nilai ini > 99999999999

        // Submit form
        await page.locator('button[type="submit"]:has-text("Simpan")').click();

        // Verifikasi bahwa pesan error dari server ditampilkan
        const pesanError = page.locator('div.alert.alert-danger');
        await expect(pesanError).toBeVisible();
        await expect(pesanError).toContainText('Nominal potongan tidak valid');
    });

  });
});
