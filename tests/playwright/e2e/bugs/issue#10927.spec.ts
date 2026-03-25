import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Inkonsisten Status pada Rekap Kehadiran Jika Keluar dari Presensi PC dan Kelola Desa #10927', () => {
  const tanggalUji = '2099-12-31';
  const jamMasukUji = '07:13:00';
  const jamKeluarUji = '17:13:00';
  let pamongIdUji: number | null = null;

  test.beforeAll(async () => {
    const pamong = await Laravel.select(
      `SELECT pamong_id
       FROM tweb_desa_pamong
       WHERE pamong_status = 1 AND kehadiran = 1
       ORDER BY pamong_id ASC
       LIMIT 1`
    ) as Array<{ pamong_id: number }>;

    if (pamong.length === 0) {
      return;
    }

    pamongIdUji = pamong[0].pamong_id;

    await Laravel.query(
      `INSERT INTO kehadiran_perangkat_desa (config_id, tanggal, pamong_id, jam_masuk, jam_keluar, status_kehadiran)
       VALUES (1, ?, ?, ?, ?, 'hadir')`,
      [tanggalUji, pamongIdUji, jamMasukUji, jamKeluarUji]
    );
  });

  test.afterAll(async () => {
    if (!pamongIdUji) {
      return;
    }

    await Laravel.query(
      `DELETE FROM kehadiran_perangkat_desa
       WHERE config_id = 1
         AND tanggal = ?
         AND pamong_id = ?
         AND jam_masuk = ?
         AND jam_keluar = ?
         AND status_kehadiran = 'hadir'`,
      [tanggalUji, pamongIdUji, jamMasukUji, jamKeluarUji]
    );
  });

  test('fix: status rekap dengan jam keluar harus tampil Tidak Berada Di Kantor', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10927'
    }
  }, async ({ page }) => {
    test.skip(!pamongIdUji, 'Tidak ada pamong aktif dengan fitur kehadiran untuk data uji');

    await page.goto('kehadiran_rekapitulasi');
    await page.waitForLoadState('networkidle');

    const searchInput = page.locator('#tabeldata_filter input[type="search"]');
    await expect(searchInput).toBeVisible();

    await searchInput.fill('2099-12-31');

    const row = page.locator('#tabeldata tbody tr', { hasText: '07:13' }).first();
    await expect(row).toBeVisible();

    await expect(row).toContainText('Tidak Berada Di Kantor');
    await expect(row).not.toContainText('Hadir');
  });
});
