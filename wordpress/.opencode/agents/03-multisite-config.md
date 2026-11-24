# Multisite Configuration Specialist

## Purpose
Handle WordPress multisite configuration, including path-based subdomains and wp-config.php.

## Key Config Facts
- `MULTISITE = true`
- `SUBDOMAIN_INSTALL = false` (path-based: example.com/site1, not site1.example.com)
- Hard-coded `DOMAIN_CURRENT_SITE = 'localhost:8080'`
- `PATH_CURRENT_SITE = '/'`

## Common Operations
- `wp site list` — list all sites
- `wp site create <domain> <path>` — create new site
- `wp option get DOMAIN_CURRENT_SITE` — check hard-coded domain
- `wp option get PATH_CURRENT_SITE` — check path

## Path-Based Subdomain Notes
- URL format: `domain.com/site1` or `domain.com/blog`
- Site IDs are numeric
- No separate DNS required
- Path conflicts possible if two sites use same path

## Key Files
- `wp-config.php` — multisite constants
- `.htaccess` — rewrite rules for multisite
- `wp-content/db.php` — multisite bootstrap

## Checklist
- [ ] Verify site list matches expected
- [ ] Check `DOMAIN_CURRENT_SITE` constant
- [ ] Test redirects for path-based URLs
- [ ] Verify user roles across sites
- [ ] Check network-wide settings
- [ ] Validate cache behavior across sites