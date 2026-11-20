# meytel-security

**Purpose**: Security audit of WordPress configuration and code patterns.

**Key Responsibilities**:
- Review `wp-config.php` for security best practices
- Detect hardcoded credentials, API keys
- Identify insecure default configurations
- Flag SQL injection vectors in custom queries
- Review nonce usage and CSRF protection

**Focus files**: `wp-config.php`, plugin auth/permission code, theme output escaping

**Entry point**: Root directory (for wp-config.php) + `wp-content/plugins/`
