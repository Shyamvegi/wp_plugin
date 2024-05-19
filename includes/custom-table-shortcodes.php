<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode for displaying the form to insert data.
 *
 * @return string The form HTML.
 */
function custom_table_shortcode_form() {
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['custom_table_name'] ) ) {
        $result = custom_table_insert_data( $_POST['custom_table_name'] );
        echo $result ? '<p>Data inserted successfully!</p>' : '<p>Failed to insert data.</p>';
    }

    ob_start();
    ?>
    <form method="POST">
        <label for="custom_table_name">Name:</label>
        <input type="text" id="custom_table_name" name="custom_table_name" required>
        <input type="submit" value="Submit">
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode for displaying the list of data with pagination and search.
 *
 * @param array $atts The shortcode attributes.
 * @return string The list HTML.
 */
function custom_table_shortcode_list( $atts ) {
    $atts = shortcode_atts( [
        'page' => 1,
        'per_page' => 10,
        'orderby' => 'id',
        'order' => 'ASC'
    ], $atts, 'custom_table_list' );

    $search = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
    $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : $atts['page'];
    $per_page = $atts['per_page'];
    $orderby = sanitize_text_field( $atts['orderby'] );
    $order = sanitize_text_field( $atts['order'] );

    $data = custom_table_get_data( $page, $per_page, $orderby, $order, $search );

    ob_start();
    ?>
    <form method="GET">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" value="<?php echo esc_attr( $search ); ?>">
        <input type="submit" value="Search">
    </form>
    <ul>
        <?php foreach ( $data as $row ) : ?>
            <li><?php echo esc_html( $row->name ); ?></li>
        <?php endforeach; ?>
    </ul>
    <div>
        <?php if ( $page > 1 ) : ?>
            <a href="<?php echo add_query_arg( [ 'page' => $page - 1, 'search' => $search ] ); ?>">&laquo; Previous</a>
        <?php endif; ?>
        <?php if ( count( $data ) === $per_page ) : ?>
            <a href="<?php echo add_query_arg( [ 'page' => $page + 1, 'search' => $search ] ); ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
