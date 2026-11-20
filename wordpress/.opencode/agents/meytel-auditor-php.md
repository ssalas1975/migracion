# meytel-auditor-php

**Purpose**: PHP 8.4 compatibility auditing — syntax, deprecated functions, problematic patterns.

**Key Responsibilities**:
- Scan all PHP files in `wp-content/` for PHP 8.4 incompatibilities
- Identify removed/changed functions and constants
- Detect deprecated patterns (e.g., `create_function`, dynamic eval usage)
- Review type declarations and return types
- Flag potential `TypeError` sources

**Focus**: All `.php` files under `wp-content/plugins/` and `wp-content/themes/`

**Critical target**: PHP 8.4 migration is the primary compatibility goal.
