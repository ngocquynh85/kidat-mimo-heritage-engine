// Capture README screenshots from the public prototype.
// Usage from repo root: npm --prefix /tmp/kidat-playwright install playwright && NODE_PATH=/tmp/kidat-playwright/node_modules node scripts/capture_demo_screenshots.mjs
import { chromium } from 'playwright';

const url = process.env.KIDAT_DEMO_URL || 'http://127.0.0.1:33399/kidat/';
const out = new URL('../docs/assets/', import.meta.url).pathname;
const browser = await chromium.launch({ headless: true, channel: 'chrome' });
const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
await page.goto(url, { waitUntil: 'networkidle' });
await page.addStyleTag({ content: '.nav{display:none!important}.reveal{opacity:1!important;transform:none!important}' });
await page.screenshot({ path: `${out}/kidat-demo-hero.png`, fullPage: false });
for (const [id, name] of [['corpus','corpus'],['pipeline','pipeline'],['lab','slab-lab'],['scale','token-scale']]) {
  await page.locator(`#${id}`).scrollIntoViewIfNeeded();
  await page.waitForTimeout(500);
  await page.screenshot({ path: `${out}/kidat-demo-${name}.png`, fullPage: false });
}
await browser.close();
