=== NS Cloner - Site Cloner and Multisite Duplicator ===
Contributors: neversettle, pkns, sdkns
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=53JXD4ENC8MM2&rm=2
Tags: site migration, clone site, duplicate site, backup and staging site
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.0
Stable tag: 4.4.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Clone, copy, and duplicate WordPress sites, single sites, and multisite subsites with NS Cloner, a fast WordPress site cloner and site duplicator.

== Description ==
NS Cloner is a WordPress site cloner and site duplicator built for WordPress Multisite networks. It lets you clone an existing multisite subsite into a new site with the same content, media, theme settings, plugin settings, menus, widgets, posts, pages, custom post types, taxonomies, and site configuration.
Instead of rebuilding the same WordPress site setup manually, NS Cloner copies a fully configured source site and creates a new duplicate site inside your multisite network. It automatically updates the new site name, title, URLs, and other background references so the cloned site works like a clean, properly configured WordPress site.
NS Cloner is ideal for developers, agencies, designers, schools, franchises, membership organizations, SaaS-style WordPress platforms, and teams that need to duplicate WordPress multisite subsites quickly and reliably.
Important: the free version of NS Cloner is for WordPress Multisite only. It works from the WordPress Network Admin dashboard. If you need to clone a single WordPress site, clone between single-site and multisite installs, or copy a site to a remote WordPress install, [WP Site Cloner Pro](https://wpsitecloner.com/pricing/?utm_campaign=in+plugin+referral&utm_source=ns-cloner&utm_medium=plugin&utm_content=pro+pricing) adds those capabilities.


= What NS Cloner Does =

NS Cloner helps you:

* Clone a WordPress Multisite subsite
* Duplicate a WordPress site inside a multisite network
* Copy an existing site setup into a new site
* Create staging, testing, template, regional, client, or member sites
* Save time when building multiple similar WordPress sites
* Preserve theme settings, plugin settings, content, media, menus, widgets, and site options
* Avoid repetitive manual setup across a multisite network


= Typical Workflow =

1. Set up a source site exactly the way you want future sites to start.
2. Go to Network Admin > NS Cloner.
3. Choose the existing multisite subsite you want to clone.
4. Enter the new site URL and title.
5. Click clone.

NS Cloner handles the site duplication process in the background and creates a fully copied site inside your WordPress Multisite network.


= Primary Use Cases =

* Agencies and developers cloning baseline client sites
* Multisite networks that need repeatable site templates
* Designers testing multiple layout or theme variations
* Teams creating staging or QA copies of existing sites
* Organizations creating regional, chapter, location, school, member, or affiliate sites
* WordPress platform owners spinning up new subsites from a preconfigured template
* Multisite administrators replacing slow manual setup with one-click site duplication

= Core Features =

* Clone an entire WordPress Multisite subsite in seconds
* Copy posts, pages, custom post types, taxonomies, and comments
* Copy uploaded media files and associated data
* Copy widgets, menus, theme settings, plugin settings, and WordPress options
* Works with subdomain and subdirectory multisite networks
* Background cloning process helps avoid timeouts on large sites
* Close your browser tab without interrupting the clone
* Live progress view while cloning
* Automatic URL replacement and background updates
* Advanced validation and preemptive issue detection
* Robust process logging for troubleshooting
* Developer-friendly cloning pipeline with hooks
* Translation ready


= WP Site Cloner Pro =

For more advanced WordPress cloning, [WP Site Cloner Pro](https://wpsitecloner.com/pricing/?utm_campaign=in+plugin+referral&utm_source=ns-cloner&utm_medium=plugin&utm_content=pro+pricing) adds support for single-site cloning, remote site cloning, WP-CLI cloning, custom search and replace, database table control, user cloning, and saved cloning presets.

Pro features include:

* Teleport: clone a subsite to a remote network or move an entire multisite network
* Single Site: clone standard WordPress sites, not just multisite subsites
* WP-CLI Addon: copy sites from the command line
* Search & Replace: run custom search and replace on cloned content
* Table Manager: control exactly which database tables are cloned
* Users Cloning: clone users and permissions or create new users for cloned sites
* Presets: save common cloning settings for repeatable site duplication workflows


== Installation ==

1. Log in as a WordPress Multisite super admin.
2. Go to Network Admin > Plugins > Add New.
3. Install NS Cloner from the WordPress.org plugin directory or upload the plugin ZIP file.
4. Network Activate NS Cloner.
5. Go to Network Admin > NS Cloner to start cloning sites.


= Standard Precautions =

NS Cloner works at the database level to duplicate WordPress sites inside a multisite network. It has been used on production systems for years, but you should still test it in your own environment before using it on a critical network.
Always back up your data before cloning, duplicating, or making large database-level changes to any WordPress site.


== Frequently Asked Questions ==

= Can I clone a WordPress site with NS Cloner? =
Yes, but the free version is specifically for cloning sites inside a WordPress Multisite network. It lets you clone one multisite subsite into another subsite on the same network.

= Does NS Cloner work as a WordPress site duplicator? =
Yes. NS Cloner duplicates WordPress Multisite subsites, including content, media, settings, menus, widgets, plugin settings, theme settings, and WordPress options.

= Can I duplicate a WordPress Multisite subsite? =
Yes. This is the core purpose of NS Cloner. You can choose an existing subsite in your multisite network and clone it into a new subsite.

= Can I clone a single WordPress site that is not part of a multisite network? =
Single-site cloning is available in WP Site Cloner Pro. The free version is for WordPress Multisite only.

= What does NS Cloner copy? =
NS Cloner copies the content and configuration needed to create an identical multisite subsite, including media uploads, posts, pages, custom post types, taxonomies, comments, menus, widgets, WordPress options, theme settings, plugin settings, and active plugin configuration.
Users and roles are not copied in the free version. User cloning is available in WP Site Cloner Pro.

= Does NS Cloner copy theme and plugin files? =
No. In a WordPress Multisite network, theme and plugin files are shared across the network, so there is no need to copy those files when cloning a subsite.

= Can I use NS Cloner to create staging sites? =
Yes. NS Cloner is commonly used to create staging, testing, QA, development, and template-based sites inside WordPress Multisite networks.

= What happens if my site is large? =
NS Cloner includes background cloning, which helps avoid timeout and memory issues on larger sites. You can close your browser and return later without interrupting the cloning process.

= Where do I access NS Cloner? =
Because NS Cloner is a multisite plugin, you access it from the WordPress Network Admin dashboard at Network Admin > NS Cloner.


== Screenshots ==

1. Main cloning options.
2. Cloning in progress.

== Changelog ==

= 4.4.9.2 - 2026.05.04 =
* Fix: Handle hooks when a site is being created to avoid errors with site activated plugins
* Code improvements

= 4.4.9.1 - 2025.11.26 =
* Fix translation notices
* Other code fixes

= 4.4.9 - 2025.06.10 =
* Fix: Custom code for cloning from external plugins.
* Code improvements

= 4.4.8 - 2025.04.24 =
* Fix: Cron scheduling - do not schedule cloner cron on sub-sites
* Fix: PHP notice on number precision
* Code improvements

= 4.4.7.5 - 2024.11.25 =
* Fix: Text domain loading

= 4.4.7.4 - 2024.11.13 =
* Fix: Correct theme set on the cloned site from the source site
* Fix: PHP notices

= 4.4.7.3 - 2024.10.17 =
* Cron subsite fixes
* Other improvements.

= 4.4.7.2 - 2024.08.28 =
* Cron code execution fix

= 4.4.7.1 - 2024.08.27 =
* Code improvements

= 4.4.7 - 2024.08.20 =
* Fix PHP notices and other code improvements

= 4.4.6 - 2024.08.08 =
* Better handling of scheduled actions.
* Code improvements.

= 4.4.5 - 2024.06.20 =
* Add hooks to bypass immediate cloning in the admin ajax action.
* Fix initial form setting option loading. Hide those that are not for current clone option.

= 4.4.4 - 2024.05.13 =
* Code updates and other improvements.

= 4.4.2 - 2024.01.30 =
* Enhancement: Improved locale loading.
* Fix: PHP notices.

= 4.4.1 - 2024.01.25 =
* New: Introduce new function `ns_cloner_perform_clone` to make it easier to perform cloning from other plugins.
* Enhancement: Improved checks for file loading.
* Fix: PHP notices.

= 4.4 - 2023.11.20 =
* New: Updated the admin area for a more modern experience.
* Enhancement: Code improvements.

= 4.3.3 - 2023.08.23 =
* Enhancement: Better loading of cloner core files for frontend
* Fix: Clone templates

= 4.3.2 - 2023.08.15 =
* Fix: Fix issues with Background process class in initializing clone

= 4.3.1 - 2023.08.11 =
* Fix vendor paths

= 4.3 - 2023.08.11 =
* Enhancement: Better dependency management
* Enhancement: Ability to override and define own custom logs location. Use `NS_CLONER_LOG_DIR` to define a custom log directory. Use `NS_CLONER_LOG_URL` to define a custom log url.
* Fix: PHP Compatibility with Background process manager
* Fix: PHP Notices

= 4.2.2.2 - 2023.05.09 =
* Fix critical php error

= 4.2.2.1 - 2022.12.19 =
* Remove unnecessary vendor files

= 4.2.2 - 2022.12.16 =
* Fix table constraint issue when cloning
* Update plugin url

= 4.2.1 - 2022.09.19 =
* Fix inaccurate memory limit warning
* Fix default cloning mode selection
* Remove unnecessary vendor files

= 4.2.0 - 2022.08.25 =
* Improve cache flushing logic
* Improve internal coding standards
* Fix handling for undefined classes in serialized values

= 4.1.9.3 - 2022.01.06 =
* Optimize with reduction of unnecessary all hook when not cloning

= 4.1.9.2 - 2022.01.03 =
* Fix compatibility issue with WP Simple Pay

= 4.1.9.1 - 2021.12.23 =
* Optimize performance by preventing initialization for non-admin requests
* Fix unnecessary object cache flushes and add filter to disable entirely

= 4.1.8 - 2021.09.30 =
* Optimize WP site list for networks with large numbers of sites
* Add proactive resolution attempts for duplicate option keys during cloning
* Fix misc. other plugin compatibility issues

= 4.1.7 - 2021.09.02 =
* Apply styling tweaks to admin interface
* Fix bug with networks where the root/main blog ID is not equal to 1
* Fix bug with "Delete All Plugin Data" reset button not working fully
* Fix compatibility issue with Collisimo Shipping Methods plugin

= 4.1.6 - 2021.05.31 =
* Update composer dependencies for PHP 8 compatibility

= 4.1.5.3 - 2021.02.10 =
* Fix compatibility error with SEO by Rank Math Pro

= 4.1.5.2 - 2021.02.03 =
* Fix bug with new site title not being applied after cloning

= 4.1.5.1 - 2021.02.02 =
* Fix slug change that broke compatibility with pro version

= 4.1.5 - 2021.01.31 =
* Add wp_cache_flush() call to resolve object caching issues
* Add option to clear plugin data for stuck processes
* Update internationalization to follow WP recommendations
* Fix misc. warnings, typos

= 4.1.4 - 2020.12.30 =
* Fix visibility error on magic wakeup method
* Fix cache clearing mechanism for WP Engine sites
* Fix conflict with Woo Discount Rules

= 4.1.3 - 2020.11.17 =
* Add new options to help avoid database view / constraint errors
* Make compatible with pro SFTP transfer feature
* Fix inefficient database calls, unresponsive analytics setting UI

= 4.1.0 - 2020.09.25 =
* Add productivity statistics capture and analytics
* Fix jQuery 1.8 compatibility, debug notices, more misc. bugfixes

= 4.0.9 - 2020.06.15 =
* Add new performance control options
* Simplify and improve new site URL validation
* Fix handling for memory limit of -1
* Fix compatibility issue with Yoast WP SEO
* Fix other bugs with stalled clone recovery, notices from options table, etc.

= 4.0.8 - 2020.06.11 =
* Add automatic cache flush after cloning
* Add new filters for performance tweaking and search/replace control
* Fix upload_path replacement for root site with hardcoded upload_path
* Fix sites with non-latin chars in site title being unavailable for cloning
* Fix other bugs with failed finishes, database prefix irregularities, etc.

= 4.0.7 - 2020.01.30 =
* Fix non-prefixed custom tables being included in root site clone
* Fix private/public blog property not being reflected on cloned sites

= 4.0.6 - 2020.01.13 =
* Update admin styles for WP 5.3 UI changes
* Add new recovery capability for stalled cloning processes
* Fix bugs with serialized search/replace updates, unnecessary sitemeta cloning

= 4.0.5 - 2019.10.17 =
* Enable number-only site names
* Add automatic cache-flush at end of cloning for WPEngine sites
* Fix memory/time overload when queueing very large tables
* Fix misc. plugin compatibility issues

= 4.0.4 - 2019.09.02 =
* Fix database handling for null values
* Fix fatal site creation error in pre-5.1 WordPress
* Fix error on servers that don't allow multiple SQL statements in one query
* Fix misc other bugs and plugin compatibility issues

= 4.0.3 - 2019.08.19 =
* Fix missing rows due to lost insert or incorrect row query
* Fix max_allowed_packet database error

= 4.0.2 - 2019.08.15 =
* Fix timing conflicts for cleanup tasks.
* Fix issue where process would keep running after manually cancelling.
* Rewrite row processing class for much better performance and fewer queries.
* Add fallback AJAX process dispatching for environments where background requests stall.
* Add queue batching for large tables.
* Improve debug logging.

= 4.0.1 - 2019.07.26 =
* Fix lock timing on background processes to prevent frozen or conflicting processes.
* Fix handling for SQL views - exclude from table cloning and create at end instead.
* Fix empty target site url caused by object caching on some servers.

= 4.0.0 - 2019.07.22 =
* Release all new rebuilt V4.

= Pre V4.0 - see changelog.txt =

== Upgrade Notice ==

= 4.0.0 =
All new V4, with upgrades to everything - enhanced stability, power, interface and features.