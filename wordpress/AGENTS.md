# AGENTS.md

## Core Identity
- **WordPress multisite** with hard-coded domain `localhost:8080` in `wp-config.php`
- **PHP 8.4 migration** — staging rollout to production (Pressable hosting)
- **Docker-ready** config with `getenv_docker()` helper for environment variables

## Key Directories
- **Themes**: `wp-content/themes/`
- **Plugins**: `wp-content/plugins/`
- **Custom plugins**: `vocento-analytics`, `configurador-eventos`, `pidecita-prescripcion-reserva`, `voc-abc-salud`, `voc-gistau`, `voc-premios-*`

## Critical Config Facts
1. **Multisite on**: path-based subdomains (`SUBDOMAIN_INSTALL = false`)
2. **Kadence Security active**: hardened `.htaccess` + `wp-config.php`
3. **Table prefix**: env-configurable (`WORDPRESS_TABLE_PREFIX`, default `wp_`)
4. **Debug off by default**: `WORDPRESS_DEBUG` env var

## Migration Workflow (the core job)
1. **Staging audit** — inventory plugins/themes, flag PHP 8.4 risks
2. **PHP 8.4 upgrade** on staging only
3. **Functional testing** — frontend/backend/ecommerce/integrations
4. **Performance** — GTmetrix/PageSpeed
5. **Production rollout** — file + DB migration
6. **Monitor 24-48h** — catch latent errors

## Testing Checklist
- [x] Frontend: navigation, forms, galleries
- [x] Backend: admin, content creation
- [x] E-commerce: carts, payments, shipping (WooCommerce)
- [x] Performance: GTmetrix/PageSpeed
- [x] Integrations: Mailchimp, Google Analytics, external APIs

## Common Pitfalls
- Hard-coded `localhost:8080` breaks on domain change
- Path-based subdomains (not subdomain-based)
- Custom themes depend on `wp_booster` framework (ionMag)
- Heavy plugin stack — audit before PHP 8.4 upgrade
- Staging-only: **never** upgrade production directly

## Key Commands
- `php -v` — verify PHP version
- `WP_DEBUG=1` — enable debugging
- `GTmetrix` / `PageSpeed Insights` — performance
- Site Health check — diagnostics

## Agent Specializations
- **migration-orchestrator** — Coordina la migración completa de PHP 7.4 → 8.4 y WP 6.9 → 7.1.5, delegando a `php84-auditor` y `migration-planner` con aprobación humana y verificación en cada fase
- **php84-auditor** — Auditor de código PHP para detectar incompatibilidades, depreciaciones y cambios de comportamiento al migrar a PHP 8.4 (propiedades dinámicas, paso de null, firmas de métodos, funciones obsoletas, WordPressisms)
- **migration-planner** — Diseña planes de implementación paso a paso con matriz de riesgo, fases con rollback y verificaciones QA para cada componente
