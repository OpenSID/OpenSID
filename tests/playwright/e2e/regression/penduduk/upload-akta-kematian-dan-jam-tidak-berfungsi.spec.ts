// Issue: #11083 — Upload akta kematian dan input jam kematian tidak berfungsi
// https://github.com/OpenSID/OpenSID/issues/11083
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: upload akta kematian dan input jam kematian tidak berfungsi #11083', () => {
  test('fix: tombol Cari membuka file chooser dan jam kematian terinisialisasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11083',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form_peristiwa/2');
    await page.bringToFront();

    // #mainform has height:0 (Bootstrap 3 float collapse of col-md-* children)
    // Verify form exists and has content via child elements instead
    await expect(page.locator('#mainform')).toBeAttached({ timeout: 10000 });
    await expect(page.locator('#mainform #nik')).toBeVisible({ timeout: 10000 });

    // Scroll to jam_mati then verify it's in the DOM and visible
    await page.locator('#jam_mati').scrollIntoViewIfNeeded();
    await expect(page.locator('#jam_mati')).toBeVisible({ timeout: 10000 });
    await expect(page.locator('#akta_mati_file')).toBeAttached();

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
