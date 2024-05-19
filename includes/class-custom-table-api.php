<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Table_API {
    /**
     * Register custom REST API routes.
     */
    public static function register_routes() {
        register_rest_route( 'custom-table/v1', '/insert', [
            'methods'  => 'POST',
            'callback' => [ __CLASS__, 'insert_item' ],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route( 'custom-table/v1', '/select', [
            'methods'  => 'GET',
            'callback' => [ __CLASS__, 'select_items' ],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Handle item insertion via REST API.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response|WP_Error The response object or WP_Error on failure.
     */
    public static function insert_item( $request ) {
        $name = sanitize_text_field( $request->get_param( 'name' ) );
        if ( empty( $name ) ) {
            return new WP_Error( 'empty_name', 'Name cannot be empty', [ 'status' => 400 ] );
        }

        $result = custom_table_insert_data( $name );

        if ( false === $result ) {
            return new WP_Error( 'insert_failed', 'Failed to insert data', [ 'status' => 500 ] );
        }

        return rest_ensure_response( [ 'message' => 'Data inserted successfully!' ] );
    }

    /**
     * Handle item selection via REST API.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response The response object.
     */
    public static function select_items( $request ) {
        $search = sanitize_text_field( $request->get_param( 'search' ) );
        $page = absint( $request->get_param( 'page' ) ) ?: 1;
        $per_page = absint( $request->get_param( 'per_page' ) ) ?: 10;
        $orderby = sanitize_text_field( $request->get_param( 'orderby' ) ) ?: 'id';
        $order = sanitize_text_field( $request->get_param( 'order' ) ) ?: 'ASC';

        $data = custom_table_get_data( $page, $per_page, $orderby, $order, $search );

        return rest_ensure_response( $data );
    }
}
