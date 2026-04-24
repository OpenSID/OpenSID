import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: upload akta kematian dan input jam kematian tidak berfungsi #11083', () => {
  test('fix: tombol Cari membuka file chooser dan jam kematian terinisialisasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11083',
    },
  }, async ({ page }) => {
    await page.goto('/penduduk/form_peristiwa/2');
    await expect(page.locator('#mainform')).toBeVisible();

    await expect(page.locator('#jam_mati')).toBeVisible();
    await expect(page.locator('#akta_mati_file')).toBeVisible();

    const jamMatiReady = await page.locator('#jam_mati').evaluate((element) => {
      const picker = (window as Window & { jQuery?: typeof $ }).jQuery?.(element).data('DateTimePicker');
      return Boolean(picker);
    });
    expect(jamMatiReady).toBeTruthy();

    const [fileChooser] = await Promise.all([
      page.waitForEvent('filechooser'),
      page.locator('#akta_mati_file_browser').click(),
    ]);

    await fileChooser.setFiles({
      name: 'akta-kematian-test.pdf',
      mimeType: 'application/pdf',
      buffer: Buffer.from('%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF'),
    });

    await expect(page.locator('#akta_mati_file_path')).toHaveValue(/akta-kematian-test\.pdf$/);

    await page.locator('#jam_mati').click();
    await expect(page.locator('.bootstrap-datetimepicker-widget')).toBeVisible();
  });
});