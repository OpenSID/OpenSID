import { test, expect } from '@playwright/test';

test.describe('Bug/error: Layanan Mandiri masih bisa diakses tanpa login ulang setelah reset PIN #11233', () => {
  test('teknis: logout other device layanan mandiri - simulate 2 browsers/devices', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11233',
    },
  }, async ({ browser }) => {
    const NIK = '1307042611960001';
    const PIN_LAMA = '654321';
    const PIN_BARU = '123456';

    // Create two separate contexts to simulate two different browsers/devices
    const contextA = await browser.newContext();
    const contextB = await browser.newContext();

    const pageA = await contextA.newPage();
    const pageB = await contextB.newPage();

    try {
      // Step 1: Login di Browser A
      console.log('Step 1: Login di Browser A');
      await pageA.goto('layanan-mandiri/masuk');
      await expect(pageA.getByRole('button', { name: 'MASUK', exact: true })).toBeVisible();
      await pageA.getByRole('textbox', { name: 'NIK' }).fill(NIK);
      await pageA.getByRole('textbox', { name: 'PIN' }).fill(PIN_LAMA);
      await pageA.getByRole('button', { name: 'MASUK', exact: true }).click();
      await expect(pageA.getByRole('link', { name: ' Profil' })).toBeVisible();
      console.log('✓ Browser A berhasil login');

      // Step 2: Login di Browser B dengan akun yang sama
      console.log('Step 2: Login di Browser B dengan akun yang sama');
      await pageB.goto('layanan-mandiri/masuk');
      await expect(pageB.getByRole('button', { name: 'MASUK', exact: true })).toBeVisible();
      await pageB.getByRole('textbox', { name: 'NIK' }).fill(NIK);
      await pageB.getByRole('textbox', { name: 'PIN' }).fill(PIN_LAMA);
      await pageB.getByRole('button', { name: 'MASUK', exact: true }).click();
      await expect(pageB.getByRole('link', { name: ' Profil' })).toBeVisible();
      console.log('✓ Browser B berhasil login');

      // Step 3: Di Browser A lakukan ganti PIN
      console.log('Step 3: Di Browser A lakukan ganti PIN');
      await pageA.goto('layanan-mandiri/ganti-pin');
      await pageA.getByRole('link', { name: ' Ganti PIN' }).click();
      await pageA.getByRole('textbox', { name: 'Masukkan PIN Lama' }).fill(PIN_LAMA);
      await pageA.getByRole('textbox', { name: 'PIN Baru', exact: true }).fill(PIN_BARU);
      await pageA.getByRole('textbox', { name: 'Konfirmasi PIN Baru' }).fill(PIN_BARU);
      await pageA.getByRole('button', { name: 'Simpan' }).click();
      console.log('✓ Browser A berhasil mengubah PIN');

      // Step 4: Verifikasi notifikasi di Browser A dan logout
      await expect(pageA.locator('#notif')).toContainText('Pin Anda telah berubah. Silakan login kembali.');
      await pageA.getByText('OK', { exact: true }).click();
      await expect(pageA.getByRole('button', { name: 'MASUK', exact: true })).toBeVisible();
      console.log('✓ Browser A menampilkan notifikasi dan logout');

      // Step 5: Di Browser B refresh halaman
      console.log('Step 5: Di Browser B refresh halaman');
      await pageB.reload();
      
      // Step 6: Verifikasi notifikasi popup di Browser B
      console.log('Step 6: Verifikasi notifikasi popup di Browser B');
      // Tunggu notifikasi muncul dengan timeout lebih lama
      const notifLocator = pageB.locator('#notif');
      await expect(notifLocator).toContainText('Pin Anda telah berubah. Silakan login kembali.', { timeout: 5000 });
      console.log('✓ Browser B menampilkan notifikasi bahwa PIN telah berubah');

      // Step 7: Verifikasi Browser B di-redirect ke login page
      console.log('Step 7: Verifikasi Browser B di-redirect ke login page');
      await pageB.getByText('OK', { exact: true }).click();
      await expect(pageB.getByRole('button', { name: 'MASUK', exact: true })).toBeVisible();
      console.log('✓ Browser B berhasil di-redirect ke login page');

      // Step 8: Verifikasi login dengan PIN baru di Browser B
      console.log('Step 8: Verifikasi login dengan PIN baru di Browser B');
      await pageB.getByRole('textbox', { name: 'NIK' }).fill(NIK);
      await pageB.getByRole('textbox', { name: 'PIN' }).fill(PIN_BARU);
      await pageB.getByRole('button', { name: 'MASUK', exact: true }).click();
      await expect(pageB.getByRole('link', { name: ' Profil' })).toBeVisible();
      console.log('✓ Browser B berhasil login dengan PIN baru');

    } finally {
      // Cleanup
      await contextA.close();
      await contextB.close();
    }
  });

  test('teknis: logout other device - check session invalidation without page reload', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11233',
    },
  }, async ({ browser }) => {
    const NIK = '1307042611960001';
    const PIN_LAMA = '654321';
    const PIN_BARU = '123456';

    const contextA = await browser.newContext();
    const contextB = await browser.newContext();

    const pageA = await contextA.newPage();
    const pageB = await contextB.newPage();

    try {
      // Login both browsers
      console.log('Login di Browser A');
      await pageA.goto('layanan-mandiri/masuk');
      await pageA.getByRole('textbox', { name: 'NIK' }).fill(NIK);
      await pageA.getByRole('textbox', { name: 'PIN' }).fill(PIN_LAMA);
      await pageA.getByRole('button', { name: 'MASUK', exact: true }).click();
      await expect(pageA.getByRole('link', { name: ' Profil' })).toBeVisible();

      console.log('Login di Browser B');
      await pageB.goto('layanan-mandiri/masuk');
      await pageB.getByRole('textbox', { name: 'NIK' }).fill(NIK);
      await pageB.getByRole('textbox', { name: 'PIN' }).fill(PIN_LAMA);
      await pageB.getByRole('button', { name: 'MASUK', exact: true }).click();
      await expect(pageB.getByRole('link', { name: ' Profil' })).toBeVisible();

      // Navigate both to dashboard/homepage
      console.log('Navigate ke dashboard/homepage');
      await pageA.goto('layanan-mandiri/');
      await pageB.goto('layanan-mandiri/');
      await expect(pageA.getByRole('link', { name: ' Profil' })).toBeVisible();
      await expect(pageB.getByRole('link', { name: ' Profil' })).toBeVisible();

      // Change PIN in Browser A
      console.log('Ganti PIN di Browser A');
      await pageA.goto('layanan-mandiri/ganti-pin');
      await pageA.getByRole('link', { name: ' Ganti PIN' }).click();
      await pageA.getByRole('textbox', { name: 'Masukkan PIN Lama' }).fill(PIN_LAMA);
      await pageA.getByRole('textbox', { name: 'PIN Baru', exact: true }).fill(PIN_BARU);
      await pageA.getByRole('textbox', { name: 'Konfirmasi PIN Baru' }).fill(PIN_BARU);
      await pageA.getByRole('button', { name: 'Simpan' }).click();

      // Browser B melakukan action yang memerlukan authentication
      // Misalnya navigate ke halaman lain
      console.log('Browser B navigate ke halaman lain');
      await pageB.goto('layanan-mandiri/ganti-pin');
      
      // Verifikasi bahwa Browser B di-logout karena session invalid
      console.log('Verifikasi Browser B di-logout');
      // Bisa check apakah ada notifikasi atau redirect ke login
      const loginButton = pageB.getByRole('button', { name: 'MASUK', exact: true });
      const notifElement = pageB.locator('#notif');
      
      try {
        // Check apakah notifikasi muncul
        await expect(notifElement).toContainText('Pin Anda telah berubah. Silakan login kembali.', { timeout: 3000 });
        console.log('✓ Browser B menampilkan notifikasi dan logout');
      } catch {
        // Atau check apakah redirect ke login page
        await expect(loginButton).toBeVisible({ timeout: 3000 });
        console.log('✓ Browser B di-redirect ke login page');
      }

    } finally {
      await contextA.close();
      await contextB.close();
    }
  });
});
