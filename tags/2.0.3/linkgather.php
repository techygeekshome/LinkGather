<?php
/*
Plugin Name: LinkGather
Description: Admin utility to gather internal post/page URLs with filters, pagination, and CSV export.
Version: 2.0.3
Author: TechyGeeksHome
*/

defined('ABSPATH') || exit;

add_action('admin_menu', 'linkgather_add_admin_menu');

function linkgather_add_admin_menu() {
    add_menu_page('LinkGather', 'LinkGather', 'manage_options', 'linkgather', 'linkgather_render_admin_page');
}

function linkgather_render_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
    $search_title = isset($_GET['search_title']) ? sanitize_text_field($_GET['search_title']) : '';
    $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
    $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $limit = 25;
    $offset = ($paged - 1) * $limit;

    echo '<div class="wrap">';
    echo '<h1>LinkGather</h1>';
    echo '<form method="get">';
    echo '<input type="hidden" name="page" value="linkgather" />';
    echo '<label for="post_type">Post Type:</label> ';
    echo '<select name="post_type" id="post_type">';
    echo '<option value="">All</option>';
    echo '<option value="post"' . selected($post_type, 'post', false) . '>Post</option>';
    echo '<option value="page"' . selected($post_type, 'page', false) . '>Page</option>';
    echo '</select> ';
    echo '<label for="search_title">Title Contains:</label> ';
    echo '<input type="text" name="search_title" id="search_title" value="' . esc_attr($search_title) . '" /> ';
    echo '<label for="start_date">Start Date:</label> ';
    echo '<input type="date" name="start_date" id="start_date" value="' . esc_attr($start_date) . '" /> ';
    echo '<label for="end_date">End Date:</label> ';
    echo '<input type="date" name="end_date" id="end_date" value="' . esc_attr($end_date) . '" /> ';
    echo '<input type="submit" class="button button-secondary" value="Filter Links" />';
    echo '</form>';

    $results = linkgather_get_links($post_type, $search_title, $start_date, $end_date, $limit, $offset);
    $total = linkgather_get_total_count($post_type, $search_title, $start_date, $end_date);
    $export_url = wp_nonce_url(admin_url('admin-post.php?action=linkgather_export_csv&post_type=' . urlencode($post_type) . '&search_title=' . urlencode($search_title) . '&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date)), 'linkgather_export');

    echo '<hr>';
    echo '<a href="' . esc_url($export_url) . '" class="button button-primary">Export to CSV</a>';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr><th>Title</th><th>Type</th><th>Date</th><th>URL</th><th>View</th></tr></thead><tbody>';

    foreach ($results as $link) {
        $url = get_permalink($link->ID);
        echo '<tr>';
        echo '<td>' . esc_html($link->post_title) . '</td>';
        echo '<td>' . esc_html($link->post_type) . '</td>';
        echo '<td>' . date('Y-m-d', strtotime($link->post_date)) . '</td>';
        echo '<td><a href="' . esc_url($url) . '" target="_blank">' . esc_url($url) . '</a></td>';
        echo '<td><a href="' . esc_url($url) . '" class="button" target="_blank">View</a></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';

    $total_pages = ceil($total / $limit);
    if ($total_pages > 1) {
        echo '<div class="tablenav"><div class="tablenav-pages">';
        $base_url = admin_url('admin.php?page=linkgather');
        $query_args = [
            'post_type' => $post_type,
            'search_title' => $search_title,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        for ($i = 1; $i <= $total_pages; $i++) {
            $query_args['paged'] = $i;
            $link = add_query_arg($query_args, $base_url);
            $class = ($i === $paged) ? ' class="current-page"' : '';
            echo '<a href="' . esc_url($link) . '"' . $class . '>' . $i . '</a> ';
        }

        echo '</div></div>';
    }

    echo '</div>';
}

function linkgather_get_links($post_type = '', $search_title = '', $start_date = '', $end_date = '', $limit = 25, $offset = 0) {
    global $wpdb;

    $conditions = ["post_status = 'publish'"];
    $params = [];

    if ($post_type) {
        $conditions[] = "post_type = %s";
        $params[] = $post_type;
    } else {
        $conditions[] = "post_type IN ('post', 'page')";
    }

    if ($search_title) {
        $conditions[] = "post_title LIKE %s";
        $params[] = '%' . $wpdb->esc_like($search_title) . '%';
    }

    if ($start_date) {
        $conditions[] = "post_date >= %s";
        $params[] = $start_date;
    }

    if ($end_date) {
        $conditions[] = "post_date <= %s";
        $params[] = $end_date;
    }

    $where = implode(' AND ', $conditions);
    $sql = $wpdb->prepare("
        SELECT ID, post_title, post_type, post_date
        FROM {$wpdb->posts}
        WHERE $where
        ORDER BY post_date DESC
        LIMIT %d OFFSET %d
    ", array_merge($params, [$limit, $offset]));

    return $wpdb->get_results($sql);
}

function linkgather_get_total_count($post_type = '', $search_title = '', $start_date = '', $end_date = '') {
    global $wpdb;

    $conditions = ["post_status = 'publish'"];
    $params = [];

    if ($post_type) {
        $conditions[] = "post_type = %s";
        $params[] = $post_type;
    } else {
        $conditions[] = "post_type IN ('post', 'page')";
    }

    if ($search_title) {
        $conditions[] = "post_title LIKE %s";
        $params[] = '%' . $wpdb->esc_like($search_title) . '%';
    }

    if ($start_date) {
        $conditions[] = "post_date >= %s";
        $params[] = $start_date;
    }

    if ($end_date) {
        $conditions[] = "post_date <= %s";
        $params[] = $end_date;
    }

    $where = implode(' AND ', $conditions);
    $sql = $wpdb->prepare("
        SELECT COUNT(*) FROM {$wpdb->posts}
        WHERE $where
    ", $params);

    return (int) $wpdb->get_var($sql);
}

add_action('admin_post_linkgather_export_csv', 'linkgather_export_csv');

function linkgather_export_csv() {
    if (!current_user_can('manage_options') || !check_admin_referer('linkgather_export')) {
        wp_die(__('Unauthorized export attempt.'));
    }

    $post_type    = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
    $search_title = isset($_GET['search_title']) ? sanitize_text_field($_GET['search_title']) : '';
    $start_date   = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
    $end_date     = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';

    $links = linkgather_get_links($post_type, $search_title, $start_date, $end_date, 1000, 0);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="linkgather-export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Title', 'Type', 'Date', 'URL']);

    foreach ($links as $link) {
        fputcsv($output, [
            $link->post_title,
            $link->post_type,
            date('Y-m-d', strtotime($link->post_date)),
            get_permalink($link->ID)
        ]);
    }

    fclose($output); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
    exit;
}