<?php
/**
 * Plugin Name: LinkGather
 * Description: Admin utility to gather internal post/page URLs with post type, author, title, and date filters, one-click URL copying, sorting, pagination, and CSV export (including author).
 * Version: 2.3.0
 * Requires at least: 5.6
 * Tested up to: 7.0.3
 * Requires PHP: 8.0
 * Author: TechyGeeksHome
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: linkgather
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'linkgather_add_admin_menu' );

function linkgather_add_admin_menu() {
    // Shared "TGH" top-level menu — registered once no matter how many of our
    // plugins are active at the same time (first one to load wins the
    // registration; every plugin still adds its own submenu page below).
    if ( ! defined( 'TGH_HUB_MENU_REGISTERED' ) ) {
        define( 'TGH_HUB_MENU_REGISTERED', true );
        add_menu_page(
            'TGH',
            'TGH',
            'manage_options',
            'tgh-hub',
            'tgh_hub_render_landing_page',
            'data:image/svg+xml;base64,' . base64_encode( tgh_hub_menu_icon_svg() ),
            3
        );
    }

    add_submenu_page(
        'tgh-hub',
        __( 'LinkGather', 'linkgather' ),
        __( 'LinkGather', 'linkgather' ),
        'manage_options',
        'linkgather',
        'linkgather_render_admin_page'
    );
}

/* ---------------------------
   Shared "TGH" hub landing page (identical copy lives in every TGH plugin;
   function_exists() guards mean whichever plugin loads first "wins" and
   renders it — keep this block in sync across all TGH plugins when the
   plugin/theme/software list changes).
   --------------------------- */

if ( ! function_exists( 'tgh_hub_menu_icon_svg' ) ) {
    function tgh_hub_menu_icon_svg() {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="10,7 13.46,9 13.46,13 10,15 6.54,13 6.54,9" fill="none" stroke="black" stroke-width="1.6"/><line x1="10" y1="7" x2="10" y2="3" stroke="black" stroke-width="1.4"/><line x1="13.46" y1="13" x2="16.93" y2="15" stroke="black" stroke-width="1.4"/><line x1="6.54" y1="13" x2="3.07" y2="15" stroke="black" stroke-width="1.4"/><circle cx="10" cy="3" r="1.4" fill="black"/><circle cx="16.93" cy="15" r="1.4" fill="black"/><circle cx="3.07" cy="15" r="1.4" fill="black"/></svg>';
    }
}

if ( ! function_exists( 'tgh_hub_render_landing_page' ) ) {
    function tgh_hub_render_landing_page() {
        $plugins = apply_filters( 'tgh_hub_plugins', array(
            array(
                'name'        => 'Controlled Draft Publisher',
                'description' => 'Hold posts as controlled drafts and publish them on your own schedule.',
                'url'         => 'https://wordpress.org/plugins/controlled-draft-publisher/',
            ),
            array(
                'name'        => 'LinkGather',
                'description' => 'Collects and manages links across your site.',
                'url'         => 'https://wordpress.org/plugins/linkgather/',
            ),
        ) );

        $themes = array(
            array(
                'name'        => 'NeoDark Free',
                'description' => 'A fast, dark-mode WordPress theme for tech blogs, tutorials, and reviews.',
                'url'         => 'https://techygeekshome.info/neodark-free/',
                'cta'         => 'View Theme',
            ),
        );

        $software = array(
            array(
                'name'        => 'DiskGeek',
                'description' => 'Free disk space analyser for Windows: scan, find duplicates, and reclaim space.',
                'url'         => 'https://techygeekshome.info/introducing-diskgeek-a-free-disk-space-analyser-for-windows/',
                'cta'         => 'View / Download',
            ),
        );
        ?>
        <div class="wrap tgh-hub-dashboard">
            <h1>TechyGeeksHome</h1>
            <p>A shared home for everything we have built &mdash; our WordPress plugins, our theme, and our standalone software.</p>

            <h2>Our Plugins</h2>
            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:12px;">
                <?php foreach ( $plugins as $p ) : ?>
                    <div style="border:1px solid #dcdcde;border-radius:6px;padding:16px;width:280px;background:#fff;">
                        <h3 style="margin-top:0;"><?php echo esc_html( $p['name'] ); ?></h3>
                        <p><?php echo esc_html( $p['description'] ); ?></p>
                        <a href="<?php echo esc_url( $p['url'] ); ?>" class="button button-primary" target="_blank" rel="noopener">View Plugin</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 style="margin-top:32px;">Our Themes</h2>
            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:12px;">
                <?php foreach ( $themes as $t ) : ?>
                    <div style="border:1px solid #dcdcde;border-radius:6px;padding:16px;width:280px;background:#fff;">
                        <h3 style="margin-top:0;"><?php echo esc_html( $t['name'] ); ?></h3>
                        <p><?php echo esc_html( $t['description'] ); ?></p>
                        <a href="<?php echo esc_url( $t['url'] ); ?>" class="button" target="_blank" rel="noopener"><?php echo esc_html( $t['cta'] ); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 style="margin-top:32px;">Our Software</h2>
            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:12px;">
                <?php foreach ( $software as $s ) : ?>
                    <div style="border:1px solid #dcdcde;border-radius:6px;padding:16px;width:280px;background:#fff;">
                        <h3 style="margin-top:0;"><?php echo esc_html( $s['name'] ); ?></h3>
                        <p><?php echo esc_html( $s['description'] ); ?></p>
                        <a href="<?php echo esc_url( $s['url'] ); ?>" class="button" target="_blank" rel="noopener"><?php echo esc_html( $s['cta'] ); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

/* ---------------------------
   Add "Settings" link to the plugin's row on the Plugins screen
   --------------------------- */

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'linkgather_plugin_action_links' );

function linkgather_plugin_action_links( $links ) {
    $settings_link = '<a href="' . admin_url( 'admin.php?page=linkgather' ) . '">' . esc_html__( 'Settings', 'linkgather' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

/* ---------------------------
   Cross-promotion notice (own admin page only, dismissible)
   --------------------------- */

add_action( 'admin_notices', 'linkgather_cross_promo_notice' );

function linkgather_cross_promo_notice() {
    $screen = get_current_screen();
    if ( ! $screen || false === strpos( $screen->id, 'linkgather' ) ) {
        return;
    }

    if ( get_user_meta( get_current_user_id(), 'linkgather_promo_dismissed', true ) ) {
        return;
    }

    echo '<div class="notice notice-info is-dismissible linkgather-promo-notice"><p>' .
        wp_kses_post( sprintf(
            /* translators: 1: Controlled Draft Publisher link, 2: NeoDark Pro link */
            __( 'Also by TechyGeeksHome: %1$s (auto-publish drafts on a schedule) and %2$s (a dark-mode WordPress theme built for tech guides and reviews).', 'linkgather' ),
            '<a href="https://wordpress.org/plugins/controlled-draft-publisher/" target="_blank" rel="noopener noreferrer">Controlled Draft Publisher</a>',
            '<a href="https://techygeekshome.info/neodark-pro/" target="_blank" rel="noopener noreferrer">NeoDark Pro</a>'
        ) ) .
        '</p></div>';
    ?>
    <script>
    document.addEventListener( 'DOMContentLoaded', function () {
        var notice = document.querySelector( '.linkgather-promo-notice' );
        if ( ! notice ) return;
        notice.addEventListener( 'click', function ( e ) {
            if ( e.target && e.target.classList.contains( 'notice-dismiss' ) ) {
                var xhr = new XMLHttpRequest();
                xhr.open( 'POST', ajaxurl, true );
                xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
                xhr.send( 'action=linkgather_dismiss_promo&_wpnonce=<?php echo esc_js( wp_create_nonce( 'linkgather_dismiss_promo' ) ); ?>' );
            }
        } );
    } );
    </script>
    <?php
}

add_action( 'wp_ajax_linkgather_dismiss_promo', 'linkgather_dismiss_promo' );

function linkgather_dismiss_promo() {
    check_ajax_referer( 'linkgather_dismiss_promo' );
    update_user_meta( get_current_user_id(), 'linkgather_promo_dismissed', 1 );
    wp_die();
}

/* ---------------------------
   Query helpers
   --------------------------- */

function linkgather_get_supported_post_types() {
    $post_types = get_post_types( [ 'public' => true ], 'objects' );
    unset( $post_types['attachment'] );
    return $post_types;
}

function linkgather_get_orderby_whitelist() {
    return [
        'title' => 'title',
        'date'  => 'date',
    ];
}

/**
 * Authors who have actually published one of the supported post types —
 * not every registered user — so the filter dropdown stays short and useful
 * on sites with a lot of subscriber/customer accounts.
 */
function linkgather_get_authors() {
    return get_users( [
        'has_published_posts' => array_keys( linkgather_get_supported_post_types() ),
        'orderby'             => 'display_name',
        'order'               => 'ASC',
        'fields'              => [ 'ID', 'display_name' ],
    ] );
}

global $linkgather_title_search;
$linkgather_title_search = '';

add_filter( 'posts_where', 'linkgather_title_where_filter', 10, 2 );

function linkgather_title_where_filter( $where, $query ) {
    global $wpdb, $linkgather_title_search;
    if ( ! empty( $linkgather_title_search ) && $query->get( 'linkgather_query' ) ) {
        $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like( $linkgather_title_search ) . '%' );
    }
    return $where;
}

function linkgather_build_query( $args ) {
    global $linkgather_title_search;

    $defaults = [
        'post_type'      => '',
        'author'         => '',
        'search_title'   => '',
        'start_date'     => '',
        'end_date'       => '',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'posts_per_page' => 25,
        'paged'          => 1,
    ];
    $args = wp_parse_args( $args, $defaults );

    $orderby_whitelist = linkgather_get_orderby_whitelist();
    $orderby = isset( $orderby_whitelist[ $args['orderby'] ] ) ? $orderby_whitelist[ $args['orderby'] ] : 'date';
    $order   = strtoupper( $args['order'] ) === 'ASC' ? 'ASC' : 'DESC';

    $supported_types = array_keys( linkgather_get_supported_post_types() );
    $post_type = ( $args['post_type'] && in_array( $args['post_type'], $supported_types, true ) )
        ? $args['post_type']
        : $supported_types;

    $query_args = [
        'linkgather_query'    => true,
        'post_type'           => $post_type,
        'post_status'         => 'publish',
        'posts_per_page'      => $args['posts_per_page'],
        'paged'               => $args['paged'],
        'orderby'             => $orderby,
        'order'               => $order,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => false,
    ];

    if ( $args['start_date'] || $args['end_date'] ) {
        $date_query = [ 'inclusive' => true ];
        if ( $args['start_date'] ) {
            $date_query['after'] = $args['start_date'];
        }
        if ( $args['end_date'] ) {
            $date_query['before'] = $args['end_date'];
        }
        $query_args['date_query'] = [ $date_query ];
    }

    if ( $args['author'] ) {
        $query_args['author'] = absint( $args['author'] );
    }

    // Set for the posts_where filter, then clear immediately after the query runs
    // so it can't leak into any other query on the same request.
    $linkgather_title_search = $args['search_title'];
    $query = new WP_Query( $query_args );
    $linkgather_title_search = '';

    return $query;
}

/* ---------------------------
   Admin page
   --------------------------- */

function linkgather_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'linkgather' ) );
    }

    $post_type    = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
    $author       = isset( $_GET['author'] ) ? absint( wp_unslash( $_GET['author'] ) ) : 0;
    $search_title = isset( $_GET['search_title'] ) ? sanitize_text_field( wp_unslash( $_GET['search_title'] ) ) : '';
    $start_date   = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
    $end_date     = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';
    $orderby      = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'date';
    $order        = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'DESC';
    $paged        = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
    $limit        = 25;

    $supported_types = linkgather_get_supported_post_types();
    $authors         = linkgather_get_authors();

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'LinkGather', 'linkgather' ) . '</h1>';
    echo '<form method="get">';
    echo '<input type="hidden" name="page" value="linkgather" />';
    echo '<label for="post_type">' . esc_html__( 'Post Type:', 'linkgather' ) . '</label> ';
    echo '<select name="post_type" id="post_type">';
    echo '<option value="">' . esc_html__( 'All', 'linkgather' ) . '</option>';
    foreach ( $supported_types as $slug => $type_obj ) {
        echo '<option value="' . esc_attr( $slug ) . '"' . selected( $post_type, $slug, false ) . '>' . esc_html( $type_obj->labels->singular_name ) . '</option>';
    }
    echo '</select> ';
    echo '<label for="author">' . esc_html__( 'Author:', 'linkgather' ) . '</label> ';
    echo '<select name="author" id="author">';
    echo '<option value="">' . esc_html__( 'All', 'linkgather' ) . '</option>';
    foreach ( $authors as $author_obj ) {
        echo '<option value="' . esc_attr( $author_obj->ID ) . '"' . selected( $author, $author_obj->ID, false ) . '>' . esc_html( $author_obj->display_name ) . '</option>';
    }
    echo '</select> ';
    echo '<label for="search_title">' . esc_html__( 'Title Contains:', 'linkgather' ) . '</label> ';
    echo '<input type="text" name="search_title" id="search_title" value="' . esc_attr( $search_title ) . '" /> ';
    echo '<label for="start_date">' . esc_html__( 'Start Date:', 'linkgather' ) . '</label> ';
    echo '<input type="date" name="start_date" id="start_date" value="' . esc_attr( $start_date ) . '" /> ';
    echo '<label for="end_date">' . esc_html__( 'End Date:', 'linkgather' ) . '</label> ';
    echo '<input type="date" name="end_date" id="end_date" value="' . esc_attr( $end_date ) . '" /> ';
    echo '<input type="hidden" name="orderby" value="' . esc_attr( $orderby ) . '" />';
    echo '<input type="hidden" name="order" value="' . esc_attr( $order ) . '" />';
    echo '<input type="submit" class="button button-secondary" value="' . esc_attr__( 'Filter Links', 'linkgather' ) . '" />';
    echo '</form>';

    $query = linkgather_build_query( [
        'post_type'      => $post_type,
        'author'         => $author,
        'search_title'   => $search_title,
        'start_date'     => $start_date,
        'end_date'       => $end_date,
        'orderby'        => $orderby,
        'order'          => $order,
        'posts_per_page' => $limit,
        'paged'          => $paged,
    ] );

    $export_args = [
        'action'       => 'linkgather_export_csv',
        'post_type'    => $post_type,
        'author'       => $author,
        'search_title' => $search_title,
        'start_date'   => $start_date,
        'end_date'     => $end_date,
        'orderby'      => $orderby,
        'order'        => $order,
    ];
    $export_url = wp_nonce_url( add_query_arg( $export_args, admin_url( 'admin-post.php' ) ), 'linkgather_export' );

    echo '<hr>';
    echo '<a href="' . esc_url( $export_url ) . '" class="button button-primary">' . esc_html__( 'Export to CSV', 'linkgather' ) . '</a>';

    $base_args = [
        'page'         => 'linkgather',
        'post_type'    => $post_type,
        'author'       => $author,
        'search_title' => $search_title,
        'start_date'   => $start_date,
        'end_date'     => $end_date,
    ];

    $make_sort_link = function( $key, $label ) use ( $base_args, $orderby, $order ) {
        $next_order = ( $orderby === $key && $order === 'ASC' ) ? 'DESC' : 'ASC';
        $args = array_merge( $base_args, [ 'orderby' => $key, 'order' => $next_order ] );
        $url = esc_url( add_query_arg( $args, admin_url( 'admin.php' ) ) );
        $indicator = '';
        if ( $orderby === $key ) {
            $indicator = ( $order === 'ASC' ) ? ' &uarr;' : ' &darr;';
        }
        return '<a href="' . $url . '">' . esc_html( $label ) . '</a>' . $indicator;
    };

    echo '<table class="widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th>' . $make_sort_link( 'title', __( 'Title', 'linkgather' ) ) . '</th>';
    echo '<th>' . esc_html__( 'Type', 'linkgather' ) . '</th>';
    echo '<th>' . esc_html__( 'Author', 'linkgather' ) . '</th>';
    echo '<th>' . $make_sort_link( 'date', __( 'Date', 'linkgather' ) ) . '</th>';
    echo '<th>' . esc_html__( 'URL', 'linkgather' ) . '</th>';
    echo '<th>' . esc_html__( 'View', 'linkgather' ) . '</th>';
    echo '</tr></thead><tbody>';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $url = get_permalink();
            $type_obj = get_post_type_object( get_post_type() );
            echo '<tr>';
            echo '<td>' . esc_html( get_the_title() ) . '</td>';
            echo '<td>' . esc_html( $type_obj ? $type_obj->labels->singular_name : get_post_type() ) . '</td>';
            echo '<td>' . esc_html( get_the_author() ) . '</td>';
            echo '<td>' . esc_html( get_the_date( 'Y-m-d' ) ) . '</td>';
            echo '<td class="linkgather-url-cell"><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $url ) . '</a> <button type="button" class="button button-small linkgather-copy-url" data-url="' . esc_attr( $url ) . '">' . esc_html__( 'Copy', 'linkgather' ) . '</button></td>';
            echo '<td><a href="' . esc_url( $url ) . '" class="button" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View', 'linkgather' ) . '</a></td>';
            echo '</tr>';
        }
        wp_reset_postdata();
    } else {
        echo '<tr><td colspan="6">' . esc_html__( 'No results found.', 'linkgather' ) . '</td></tr>';
    }

    echo '</tbody></table>';

    // Small inline script for the per-row "Copy" button — clipboard API with an
    // execCommand fallback for older browsers. No external requests, no CDN.
    ?>
    <script>
    document.addEventListener( 'DOMContentLoaded', function () {
        document.querySelectorAll( '.linkgather-copy-url' ).forEach( function ( btn ) {
            btn.addEventListener( 'click', function () {
                var url = btn.getAttribute( 'data-url' );
                var done = function () {
                    var original = btn.textContent;
                    btn.textContent = '<?php echo esc_js( __( 'Copied!', 'linkgather' ) ); ?>';
                    setTimeout( function () { btn.textContent = original; }, 1500 );
                };
                if ( navigator.clipboard && window.isSecureContext ) {
                    navigator.clipboard.writeText( url ).then( done );
                } else {
                    var temp = document.createElement( 'textarea' );
                    temp.value = url;
                    temp.style.position = 'fixed';
                    temp.style.opacity = '0';
                    document.body.appendChild( temp );
                    temp.select();
                    try { document.execCommand( 'copy' ); } catch ( e ) {}
                    document.body.removeChild( temp );
                    done();
                }
            } );
        } );
    } );
    </script>
    <?php

    $total_pages = (int) $query->max_num_pages;
    if ( $total_pages > 1 ) {
        echo '<div class="tablenav"><div class="tablenav-pages">';
        $pagination_args = array_merge( $base_args, [ 'orderby' => $orderby, 'order' => $order ] );
        $pagination_base = admin_url( 'admin.php' );

        for ( $i = 1; $i <= $total_pages; $i++ ) {
            $link = add_query_arg( array_merge( $pagination_args, [ 'paged' => $i ] ), $pagination_base );
            $class = ( $i === $paged ) ? ' class="current-page"' : '';
            echo '<a href="' . esc_url( $link ) . '"' . $class . '>' . esc_html( $i ) . '</a> ';
        }

        echo '</div></div>';
    }

    echo '</div>';
}

/* ---------------------------
   CSV export
   --------------------------- */

add_action( 'admin_post_linkgather_export_csv', 'linkgather_export_csv' );

function linkgather_export_csv() {
    if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'linkgather_export' ) ) {
        wp_die( esc_html__( 'Unauthorized export attempt.', 'linkgather' ) );
    }

    $post_type    = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
    $author       = isset( $_GET['author'] ) ? absint( wp_unslash( $_GET['author'] ) ) : 0;
    $search_title = isset( $_GET['search_title'] ) ? sanitize_text_field( wp_unslash( $_GET['search_title'] ) ) : '';
    $start_date   = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
    $end_date     = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';
    $orderby      = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'date';
    $order        = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'DESC';

    $query = linkgather_build_query( [
        'post_type'      => $post_type,
        'author'         => $author,
        'search_title'   => $search_title,
        'start_date'     => $start_date,
        'end_date'       => $end_date,
        'orderby'        => $orderby,
        'order'          => $order,
        'posts_per_page' => 1000,
        'paged'          => 1,
    ] );

    nocache_headers();
    while ( ob_get_level() ) {
        ob_end_clean();
    }

    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="linkgather-export.csv"' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );

    echo "\xEF\xBB\xBF";

    $output = fopen( 'php://output', 'w' );
    fputcsv( $output, [ 'Title', 'Type', 'Author', 'Date', 'URL' ] );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $type_obj = get_post_type_object( get_post_type() );
            fputcsv( $output, [
                get_the_title(),
                $type_obj ? $type_obj->labels->singular_name : get_post_type(),
                get_the_author(),
                get_the_date( 'Y-m-d' ),
                get_permalink(),
            ] );
        }
        wp_reset_postdata();
    }

    fclose( $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
    exit;
}
