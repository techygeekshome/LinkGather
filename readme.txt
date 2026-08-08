=== LinkGather ===
Contributors: techygeekshome
Donate link: https://ko-fi.com/techygeekshome
Tags: links, url audit, csv export, content audit, admin
Requires at least: 5.6
Tested up to: 7.0.2
Requires PHP: 8.0
Stable tag: 2.3.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Admin utility to gather internal post/page URLs with filters, sorting, pagination, and CSV export.

== Description ==

LinkGather is a lightweight admin tool for WordPress that lets you:

* View all published content in a sortable table — any public post type, not just posts and pages
* Filter by post type, author, title keyword, and date range
* Sort by title or date
* Paginate results (25 per page)
* Copy any URL to your clipboard with one click
* Export filtered results to CSV, including the author column
* Click through to view each post/page directly

Built for site managers, content auditors, and anyone needing fast access to internal URLs.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin via the Plugins menu
3. Access LinkGather from the admin sidebar under TGH → LinkGather

== Screenshots ==

1. Admin table with filters and export button [screenshot-1.png]

== Changelog ==

= 2.3.1 =
* Fixed a bug where filtering by Post Type (Post, Page, or Product) could show a "Cannot load linkgather" error instead of the filtered list.

= 2.3.0 =
* Added an Author filter and Author column, for auditing content by who wrote it.
* Added a one-click "Copy" button next to each URL.
* Author is now included in the CSV export.
* Confirmed compatible with WordPress 7.0.2.
* Refreshed the readme's tags for accuracy.

= 2.2.0 =
* The plugin's admin page now lives under a shared "TGH" menu alongside our other plugins, with a landing page cross-promoting everything we've built.
* Added a "Settings" link on the Plugins screen for quicker access.
* Added a Ko-fi donate link.

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

= 2.3.1 =
Bug fix: resolves a "Cannot load linkgather" crash when filtering by Post Type. Recommended update for all users.

= 2.3.0 =
Adds an Author filter/column and one-click URL copying. Tested with WordPress 7.0.2. No settings changes needed.

= 2.2.0 =
The admin page has moved under a shared "TGH" menu. Your saved settings and data are unaffected — only the menu location changed.

= 2.1.0 =
Adds custom post type support, sortable columns, and translation readiness. Recommended for all users.
