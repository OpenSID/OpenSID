// Issue: #11015 — DTSEN form fixes
// https://github.com/OpenSID/OpenSID/issues/11015
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug #11015: DTSEN Form Fixes', () => {
  
  test('tab navigation in correct order (I→II→III→IV→V→VII)', async ({ page }) => {
    await page.goto('/dtsen/pendataan/form/1');
    
    // Navigate through tabs
    await page.click('#nav-bagian-3'); // Tab II
    expect(await page.textContent('.nav-tabs li.active a')).toContain('II');
    
    await page.click('#nav-bagian-4'); // Tab III
    expect(await page.textContent('.nav-tabs li.active a')).toContain('III');
    
    await page.click('#nav-bagian-2'); // Tab V
    expect(await page.textContent('.nav-tabs li.active a')).toContain('V');
    
    await page.click('#nav-bagian-7'); // Tab VII
    expect(await page.textContent('.nav-tabs li.active a')).toContain('VII');
  });

  test('spinner shows and auto-hides on save', async ({ page }) => {
    await page.goto('/dtsen/pendataan/form/1');
    
    const btn = await page.$('#form-1 button[type="submit"]');
    await btn?.click();
    
    // Spinner should disappear automatically
    await page.waitForSelector('i.fa-spinner', { state: 'hidden', timeout: 5000 });
    expect(await page.$('i.fa-spinner')).toBeNull();
  });

  test('Tab VII back button returns to Tab V', async ({ page }) => {
    await page.goto('/dtsen/pendataan/form/1');
    
    await page.click('a[href*="#bagian-7"]');
    await page.click('button:has-text("Sebelumnya")');
    
    expect(await page.textContent('.nav-tabs li.active a')).toContain('V');
  });

  test('spinner shows and auto-hides on save modal anggota keluarga', async ({ page }) => {
    await page.goto('/dtsen/pendataan/form/1');
    
    const btn = await page.$('#form-4 button[type="submit"]');
    await btn?.click();
    
    // Spinner should disappear automatically
    await page.waitForSelector('i.fa-spinner', { state: 'hidden', timeout: 5000 });
    expect(await page.$('i.fa-spinner')).toBeNull();
  });
});
