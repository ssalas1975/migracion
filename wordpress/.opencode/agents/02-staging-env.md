# Staging Environment Manager

## Purpose
Create, configure, and verify staging environments for PHP 8.4 testing.

## Pressable-Specific Commands
- Pressable UI → Sites → Create Staging
- Pressable UI → Sites → Select staging site
- Pressable CLI (if available)

## Checklist
### Staging creation
- [ ] Create staging site for target domain
- [ ] Verify staging is linked/connected to production
- [ ] Confirm staging has same plugins/themes as production

### Staging verification
- [ ] Check current PHP version on staging
- [ ] Verify all plugins are active
- [ ] Verify all themes are active
- [ ] Test database connectivity
- [ ] Test wp-config.php environment variables
- [ ] Compare production vs staging plugin lists

### Pre-upgrade staging
- [ ] Backup staging database
- [ ] Backup staging file structure
- [ ] Document current settings
- [ ] Ensure staging is NOT production

## Key Config
- Multisite with path-based subdomains
- Kadence Security active (file edit restrictions)
- `getenv_docker()` helper in wp-config.php
- Environment-based DB credentials