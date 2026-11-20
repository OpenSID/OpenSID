// Issue: #10918 — Insecure server-side validation: diskon lapak lebih dari 100%
// https://github.com/OpenSID/OpenSID/issues/10918
import { test, expect } from '../../../utils/regressionTest';


const NAMA_PRODUK_PERSEN = 'E2E Test - Produk Persen';
const NAMA_PRODUK_NOMINAL = 'E2E Test - Produk Nominal';

test.describe('Bug/error: Insecure Server-Side Validation & Logic Flaw pada Modul Lapak (Potongan Harga > 100%) #10918', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('layanan-mandiri/produk');

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

  test('fix: perbaiki Insecure Server-Side Validation & Logic Flaw pada Modul Lapak (Potongan Harga > 100%)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10918',
    },
  }, async ({ page }) => {
    // Harus menampilkan error validasi untuk diskon persen > 100
    await page.goto('layanan-mandiri/produk/form');
    await expect(page.locator('h3.box-title')).toContainText('Formulir Produk');

    await page.locator('input[name="nama"]').fill(NAMA_PRODUK_PERSEN);
    await page.locator('select[name="id_produk_kategori"]').selectOption({ index: 1 });
    await page.locator('input[name="harga"]').fill('50000');
    await page.locator('input[name="satuan"]').fill('Buah');
    await page.locator('textarea[name="deskripsi"]').fill('Deskripsi produk untuk testing E2E.');

    await page.locator('input[name="tipe_potongan"][value="1"]').check();

    const persenInput = page.locator('input[name="persen"]');
    await persenInput.evaluate(el => el.removeAttribute('max'));
    await persenInput.fill('101');

    await page.locator('button[type="submit"]:has-text("Simpan")').click();

    const pesanError = page.locator('div.alert.alert-danger');
    await expect(pesanError).toBeVisible();
    await expect(pesanError).toContainText('Persentase potongan harus di antara 0 dan 100');
  });

  test('Harus menampilkan error validasi untuk diskon nominal melebihi batas', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10918',
    },
  }, async ({ page }) => {
    await page.goto('layanan-mandiri/produk/form');
    await expect(page.locator('h3.box-title')).toContainText('Formulir Produk');

    await page.locator('input[name="nama"]').fill(NAMA_PRODUK_NOMINAL);
    await page.locator('select[name="id_produk_kategori"]').selectOption({ index: 1 });
    await page.locator('input[name="harga"]').fill('50000');
    await page.locator('input[name="satuan"]').fill('Buah');
    await page.locator('textarea[name="deskripsi"]').fill('Deskripsi produk untuk testing E2E.');

    await page.locator('input[name="tipe_potongan"][value="2"]').check();

    const nominalInput = page.locator('input[name="nominal"]');
    await nominalInput.evaluate(el => el.removeAttribute('max'));
    await nominalInput.fill('100000000000');

    await page.locator('button[type="submit"]:has-text("Simpan")').click();

    const pesanError = page.locator('div.alert.alert-danger');
    await expect(pesanError).toBeVisible();
    await expect(pesanError).toContainText('Nominal potongan tidak valid');
  });
});
