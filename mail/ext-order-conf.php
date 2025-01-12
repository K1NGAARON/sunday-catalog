<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p>Hey, just to let you know we received your quote request. It’s now being processed. Discover your items below.</p>
<p>Our team will be in touch in the next 24 hours.</p>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
    <thead>
        <tr>
            <th class="td" scope="col" style="text-align: left;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="text-align: left;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ( $order->get_items() as $item_id => $item ) :
            $product = $item->get_product();
            ?>
            <tr>
                <td class="td" style="text-align: left;"><?php echo esc_html( $item->get_name() ); ?></td>
                <td class="td" style="text-align: left;"><?php echo esc_html( $item->get_quantity() ); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Display additional content set in WooCommerce email settings.
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );