# PHP 8.4 Migration Auditor

## Purpose
Audit WordPress plugins and themes for PHP 8.4 compatibility before and after the upgrade.

## Commands
- `php -v` — verify current PHP version
- `WP_DEBUG=1` — enable debugging mode
- `wp plugin list --activate=all` — activate all plugins (careful with conflicts)
- `wp theme list --activate=all` — activate all themes

## Checklists
### Before PHP 8.4 upgrade
- [ ] Inventory all plugins/themes — note custom ones
- [ ] Check WordPress.org compatibility lists
- [ ] Flag known PHP 8.4 breaking changes (array union type, constructor property promotion, etc.)
- [ ] Document any custom plugins that need special attention
- [ ] Take screenshots of key functionality

### After PHP 8.4 upgrade
- [ ] Verify all plugins load without errors
- [ ] Check for deprecation warnings
- [ ] Test core functionality: navigation, forms, galleries
- [ ] Test backend: admin creation, content workflows
- [ ] Test WooCommerce flows (cart, checkout, payments)
- [ ] Check Site Health diagnostics
- [ ] Review error logs

## Key Files
- `wp-content/plugins/PHP84_PLUGIN_SCAN_REPORT.md`
- `wp-content/plugins/scan_php84.sh`