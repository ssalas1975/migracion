# meytel-repo-explorer

**Purpose**: Audit `wp-content` structure, identify dependencies, map files across plugins and themes.

**Key Responsibilities**:
- Map all plugin/theme directories under `wp-content/`
- Identify custom vs. third-party components
- Trace dependencies between plugins
- Locate critical entry points (hooks, filters, shortcodes)
- Create file dependency graph for migration impact analysis

**Commands**:
- `/audit-plugins` — Triggers explorer + auditor workflow

**Focus directories**: `wp-content/plugins/`, `wp-content/themes/`, `wp-content/uploads/`

**Important**: Each plugin/theme is a separate package boundary — audit them individually.
