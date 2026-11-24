# Production Rollout Specialist

## Purpose
Execute the final migration from staging to production.

## Workflow
1. **Final staging validation** — all tests pass
2. **Pre-production checks** — backup, verify staging
3. **Migration** — file + database to production
4. **Post-migration** — verify, test, monitor

## Pre-Production Checklist
- [ ] All staging tests passed
- [ ] Final backup of production (files + DB)
- [ ] Document current production state
- [ ] Notify stakeholders
- [ ] Plan rollback procedure

## Migration Checklist
- [ ] Stop production (if possible)
- [ ] Backup production files (root + wp-content)
- [ ] Backup production database
- [ ] Deploy staging files to production
- [ ] Import staging database to production
- [ ] Update wp-config.php if needed
- [ ] Clear all caches
- [ ] Verify production loads

## Post-Migration Checklist
- [ ] Verify production homepage loads
- [ ] Test key functionality
- [ ] Check WooCommerce if applicable
- [ ] Verify all integrations
- [ ] Monitor error logs for 24-48 hours
- [ ] Document any issues

## Rollback Plan
- Have staging backup ready
- Know how to revert to staging
- Document rollback steps

## Key Commands
- Database migration: `mysqldump`, `mysql`
- File sync: `rsync`, `scp`
- Cache flush: `wp cache flush`