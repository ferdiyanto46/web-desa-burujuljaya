import { test, expect } from '@playwright/test';

test.describe('Complaint Flow', () => {
  test('submits a public complaint with correct network payload', async ({ page }) => {
    const errors: string[] = [];
    page.on('pageerror', (err) => {
      console.error(`PAGE ERROR: ${err.message}`);
      errors.push(err.message);
    });
    page.on('console', (msg) => {
      console.log(`CONSOLE [${msg.type()}]: ${msg.text()} | URL: ${msg.location().url}`);

      if (msg.type() === 'error' && !msg.text().includes('404') && !msg.text().includes('Cross-Origin-Opener-Policy')) {
        errors.push(`${msg.text()} (${msg.location().url})`);
      }
    });

    // Intercept network requests to /pengaduan
    let requestPayload: any = null;
    await page.route('**/*', async (route) => {
      const request = route.request();

      if (request.url().includes('/pengaduan') && request.method() === 'POST') {
        const postData = request.postData();

        if (postData) {
            requestPayload = postData;
        }
      }

      await route.continue();
    });

    page.on('response', async (response) => {
      if (response.url().includes('/pengaduan') && response.request().method() === 'POST') {
        console.log(`POST /pengaduan Response Status: ${response.status()}`);
        console.log(`POST /pengaduan Response Headers:`, response.headers());

        try {
          const body = await response.text();
          console.log(`POST /pengaduan Response Body:`, body.substring(0, 500));
        } catch (e) {}
      }
    });

    // Go to complaint page
    await page.goto('/pengaduan');
    
    // Check for any initial page load errors
    expect(errors.length, `Browser errors occurred on load: ${errors.join(', ')}`).toBe(0);

    // Wait for the form to be visible
    await page.waitForSelector('form', { timeout: 5000 });

    // Upload file with a valid 1x1 pixel PNG buffer to pass Laravel finfo_file mime validation
    const validPngBuffer = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAZdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuOWwzfk4AAAANSURBVBhXY3jP4PgfAAWgA30F9o+EAAAAAElFTkSuQmCC', 'base64');
    await page.setInputFiles('input[type="file"]', {
      name: 'test-image.png',
      mimeType: 'image/png',
      buffer: validPngBuffer
    });

    // Fill Nama Lengkap
    await page.getByLabel(/Nama Lengkap/i).fill('John Doe');

    // Fill Nomor Telepon/WA
    await page.getByLabel(/Nomor Telepon\/WA/i).fill('081234567890');

    // Fill Email
    await page.getByLabel(/Email \(Opsional\)/i).fill('john@example.com');

    // Fill Judul Laporan
    await page.getByLabel(/Judul Laporan/i).fill('Jalan Berlubang di RT 01');

    // Select Category (assume at least one exists from seeder)
    await page.locator('select').selectOption({ index: 1 });

    // Fill Lokasi Kejadian
    await page.getByLabel(/Lokasi Kejadian/i).fill('Depan Balai Desa');

    // Fill Deskripsi Detail (body)
    await page.getByLabel(/Deskripsi Detail/i).fill('Ini adalah testing isi pengaduan dari Playwright E2E.');

    // Submit the form
    const submitBtn = page.getByRole('button', { name: /Kirim Laporan/i });
    
    // Capture the response
    const [response] = await Promise.all([
      page.waitForResponse(res => res.url().includes('/pengaduan') && res.request().method() === 'POST'),
      submitBtn.first().click(),
    ]);
    expect([200, 302, 303]).toContain(response.status());

    // Check payload
    expect(requestPayload).not.toBeNull();
    console.log('Intercepted Payload:', requestPayload);
    
    // Assert that the payload contains 'body' and not 'description'
    // Since it might be multipart/form-data or JSON, we check string inclusion
    expect(requestPayload).toContain('body');
    expect(requestPayload).not.toContain('description');
    // Check for validation errors on the page
    const errorElements = await page.locator('.text-red-600').all();
    const validationErrors = [];

    for (const el of errorElements) {
        if (await el.isVisible()) {
            validationErrors.push(await el.textContent());
        }
    }

    if (validationErrors.length > 0) {
        console.error('Validation errors found:', validationErrors.join(', '));
    }

    // Wait for the page to navigate to the tracking page
    await expect(page).toHaveURL(/.*lacak-pengaduan.*/, { timeout: 10000 });
    
    // Ensure no runtime errors during submission
    expect(errors.length, `Browser errors occurred during submit: ${errors.join(', ')}`).toBe(0);
  });
});
