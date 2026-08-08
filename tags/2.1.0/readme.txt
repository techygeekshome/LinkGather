=== LinkGather ===
Contributors: techygeekshome
Tags: admin, links, export, post urls, page urls
Requires at least: 5.6
Tested up to: 6.9
Requires PHP: 8.0
Stable tag: 2.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Admin utility to gather internal post/page URLs with filters, sorting, pagination, and CSV export.

== Description ==

LinkGather is a lightweight admin tool for WordPress that lets you:

* View all published content in a sortable table — any public post type, not just posts and pages
* Filter by post type, title keyword, and date range
* Sort by title or date
* Paginate results (25 per page)
* Export filtered results to CSV
* Click through to view each post/page directly

Built for site managers, content auditors, and anyone needing fast access to internal URLs.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin via the Plugins menu
3. Access LinkGather from the admin sidebar

== Screenshots ==

1. Admin table with filters and export button [screenshot-1.png]

== Changelog ==

= 2.1.0 =
* Now lists all public post types (not just Post/Page), including custom post types.
* Added sortable Title and Date columns.
* Rewrote the listing/export queries to use WP_Query instead of raw SQL, for consistency and cache-friendliness.
* Added translation support (text domain, translatable strings).
* Added missing plugin header fields (License, License URI, Requires PHP, Text Domain).
* Fixed readme Stable tag (was set to "trunk").
* Added a small dismissible notice on the plugin's own admin page pointing to our other plugin and theme.

= 2.0.3 =
* Fixed fatal error on activation due to incomplete function block
* Removed deprecated `wpdb::prepare()` usage without placeholders
* Cleaned up pagination and export logic
* Removed second screenshot from readme

= 2.0.2 =
* Added CSV export with nonce protection
* Improved filter UI and pagination

= 2.0.1 =
* Initial relaunch with WordPress 6.8 and PHP 8.0 compatibility
* Reset version history and codebase

== Upgrade Notice ==

Version 2.1.0 adds custom post type support, sortable columns, and translation readiness. Recommended for all users.
