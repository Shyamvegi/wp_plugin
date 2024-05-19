<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Create the custom table on plugin activation.
 */
function custom_table_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_table';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Insert data into the custom table.
 *
 * @param string $name The name to be inserted.
 * @return bool|int False on failure, number of rows affected on success.
 */
function custom_table_insert_data( $name ) {
    if ( empty( $name ) ) {
        return false;
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_table';

    return $wpdb->insert(
        $table_name,
        [ 'name' => sanitize_text_field( $name ) ],
        [ '%s' ]
    );
}

/**
 * Retrieve data from the custom table.
 *
 * @param int $page The page number.
 * @param int $per_page The number of items per page.
 * @param string $orderby The column to order by.
 * @param string $order The order direction.
 * @param string $search The search term.
 * @return array The retrieved data.
 */
function custom_table_get_data( $page = 1, $per_page = 10, $orderby = 'id', $order = 'ASC', $search = '' ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_table';
    $offset = ( $page - 1 ) * $per_page;

    $where_clause = $search ? $wpdb->prepare( " WHERE name LIKE %s", '%' . $wpdb->esc_like( $search ) . '%' ) : '';
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name $where_clause ORDER BY $orderby $order LIMIT %d OFFSET %d",
        $per_page, $offset
    );

    return $wpdb->get_results( $query );
}
