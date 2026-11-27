// Issue: #10850 — Database pollution & broken logic pada modul dokumen penduduk
// https://github.com/OpenSID/OpenSID/issues/10850
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Database Pollution & Broken Logic pada Modul Dokumen Penduduk #10850', () => {
  test('fix: perbaikan Database Pollution & Broken Logic pada Modul Dokumen Penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10850'
    }
  }, async ({ page }) => {
    // navigate to the penduduk listing and grab the first penduduk's dokumen link
    await page.goto('penduduk');
    // wait for table rows to appear (datatable ajax)
    await page.waitForSelector('table tbody tr');

    // try to extract href of the first "dokumen" action button
    // href is absolute (site_url), so use *=contains rather than ^=starts-with
    const dokumenAnchor = page.locator('a[href*="/penduduk/dokumen/"]').first();
    const href = await dokumenAnchor.getAttribute('href');
    expect(href).toBeTruthy();

    // visit the dynamic dokumen page
    await page.goto(href!);

    // assert we are on a dokumen page showing penduduk data
    await expect(page).toHaveURL(/penduduk\/dokumen\/\d+/);
    await expect(page.locator('text=Nama Penduduk')).toBeVisible();

    // open the "Tambah" modal and verify form appears (covers insertion workflow)
    await page.click('text=Tambah');
    await page.waitForSelector('#modalBox');
    await expect(page.locator('#modalBox form')).toBeVisible();

  });
});
