import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: DETAIL SHORTCUT MENAMPILKAN DATA YANG TIDAK SESUAI #9490', () => {
  test.beforeEach(async ({ page }) => {
    test.setTimeout(100000);
    await page.goto('shortcut');
    // create shortcut
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="judul"]').click();
    await page.locator('input[name="judul"]').fill('Penduduk Laki-laki');
    await page.locator('#select2-raw_query-container').click();
    await page.getByRole('treeitem', { name: 'Jumlah Penduduk Laki-laki' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).click();
    await page.locator('.input-group-addon').click();
    await page.locator('.colorpicker-saturation').click();
    await page.getByRole('textbox', { name: 'Tidak' }).click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="judul"]').click();
    await page.locator('input[name="judul"]').fill('Penduduk Wanita');
    await page.locator('#select2-raw_query-container').click();
    await page.getByRole('treeitem', { name: 'Jumlah Penduduk Perempuan' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).fill('#ffffff');
    await page.getByRole('textbox', { name: 'Tidak' }).click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="judul"]').click();
    await page.locator('input[name="judul"]').fill('Keluarga');
    await page.locator('input[name="judul"]').press('Home');
    await page.locator('input[name="judul"]').fill('Kepala Keluarga');
    await page.locator('input[name="judul"]').press('End');
    await page.locator('input[name="judul"]').fill('Kepala Keluarga Laki-laki');
    await page.locator('#select2-raw_query-container').click();
    await page.getByRole('treeitem', { name: 'Jumlah Kepala Keluarga Laki-' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).fill('#de0d0d');
    await page.getByRole('textbox', { name: 'Tidak' }).click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="judul"]').click();
    await page.locator('input[name="judul"]').fill('Kepala Keluarga Wanita');
    await page.locator('#select2-raw_query-container').click();
    await page.getByRole('treeitem', { name: 'Jumlah Kepala Keluarga Perempuan' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).click();
    await page.getByRole('textbox', { name: 'Pilih Warna' }).fill('#c42020');
    await page.getByRole('textbox', { name: 'Tidak' }).click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();

    await page.goto('beranda');
    await expect(page.getByText('Penduduk Laki-laki')).toBeVisible();    
  });
  test.afterEach(async ({ page }) => {
    // hapus data shortcut setelah test selesai
    // hapus shortcut
    await page.goto('shortcut');
    await page.getByLabel('Tampilkan 102550100Semua entri').selectOption('-1');    
    // get by role row has text 
    await page.locator('tr:has-text("Penduduk Laki-laki")').getByRole('checkbox').check();
    await page.locator('tr:has-text("Penduduk Wanita")').getByRole('checkbox').check();   
    await page.locator('tr:has-text("Kepala Keluarga Laki-laki")').getByRole('checkbox').check();
    await page.locator('tr:has-text("Kepala Keluarga Wanita")').getByRole('checkbox').check();        
    await page.getByRole('link', { name: ' Hapus' }).click();
    await page.locator('#confirm-delete a').click();
    await expect(page.getByText('Berhasil Hapus Data')).toBeVisible();
  });
      
  test('fix: perbaiki link shortcut', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9490'
    }
  }, async ({ page }) => {    
    await page.goto('beranda');
    await expect(page.getByText('Penduduk Laki-laki')).toBeVisible();
    const formatter = new Intl.NumberFormat('id-ID')
    const widgetPendudukPria = page.locator('div.small-box:has-text("Penduduk Laki-laki")');   
    const jumlahPendudukLk = await widgetPendudukPria.locator('.inner > h3').textContent() ?? '0'; 
      
    // klik widget penduduk laki-laki
    await widgetPendudukPria.locator('.small-box-footer').click();
    await expect(page.getByText(formatter.format(parseInt(jumlahPendudukLk)))).toBeVisible();    
    
    await page.goto('beranda');
    const widgetPendudukWanita = page.locator('div.small-box:has-text("Penduduk Wanita")');   
    const jumlahPendudukWanita = await widgetPendudukWanita.locator('.inner > h3').textContent() ?? '0';  
    // klik widget penduduk wanita
    await widgetPendudukWanita.locator('.small-box-footer').click();
    await expect(page.getByText(formatter.format(parseInt(jumlahPendudukWanita)))).toBeVisible();    

    await page.goto('beranda');
    const widgetKeluargaPria = page.locator('div.small-box:has-text("Kepala Keluarga Laki-laki")');   
    const jumlahKeluargaPria = await widgetKeluargaPria.locator('.inner > h3').textContent() ?? '0';  
    // klik widget penduduk wanita
    await widgetKeluargaPria.locator('.small-box-footer').click();
    await expect(page.getByText(formatter.format(parseInt(jumlahKeluargaPria)))).toBeVisible();
    
    await page.goto('beranda');
    const widgetKeluargaWanita = page.locator('div.small-box:has-text("Kepala Keluarga Wanita")');   
    const jumlahKeluargaWanita = await widgetKeluargaWanita.locator('.inner > h3').textContent() ?? '0';  
    // klik widget penduduk wanita
    await widgetKeluargaWanita.locator('.small-box-footer').click();
    await expect(page.getByText(formatter.format(parseInt(jumlahKeluargaWanita)))).toBeVisible();    
  });  
});