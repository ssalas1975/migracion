# Performance Tester

## Purpose
Test and verify performance improvements from PHP 8.4 migration.

## Tools
- **GTmetrix** — full page load analysis
- **PageSpeed Insights** — Core Web Vitals
- **WordPress Site Health** — built-in diagnostics

## Test Checklist
### Before vs After
- [ ] Page load time
- [ ] Time to First Byte (TTFB)
- [ ] Largest Contentful Paint (LCP)
- [ ] First Input Delay (FID)
- [ ] Cumulative Layout Shift (CLS)
- [ ] Total blocking time

### Functional Tests
- [ ] Homepage load
- [ ] Product/category pages (WooCommerce)
- [ ] Checkout flow
- [ ] Search results
- [ ] Admin dashboard

### Integration Tests
- [ ] Email delivery (Mailchimp, SMTP)
- [ ] Analytics tracking
- [ ] External API calls
- [ ] Payment gateway

## Key Files
- Plugin cache (WP Super Cache, W3 Total Cache)
- Object cache (Redis, Memcached)
- CDN configuration