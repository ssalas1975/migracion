# /audit-plugins

**Command**: Scans `wp-content` for PHP 8.4 incompatibilities.

**Workflow**:
1. Orchestrator invokes `explorer` + `auditor-php` agents
2. Explorer maps all plugin/theme files
3. Auditor scans for PHP 8.4 issues (deprecated functions, syntax)
4. Findings aggregated and prioritized by impact

**Entry point**: Any agent or direct user command
