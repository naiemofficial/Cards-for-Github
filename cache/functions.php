<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function github_card_clear_cache() {
    global $wpdb;

    $like = $wpdb->esc_like( 'github_card_' ) . '%';

    // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $result = $wpdb->query(
        $wpdb->prepare(
            "
            DELETE FROM {$wpdb->options}
            WHERE option_name LIKE %s
            OR option_name LIKE %s
            ",
            '_transient_' . $like,
            '_transient_timeout_' . $like
        )
    );
    // phpcs:enable

    return $result;
}