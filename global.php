<?php
/**
 * Enqueue script and styles for child theme
 */

function woodmart_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}

add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );

function custom_price_range_display() {
    $price_min = get_field('low_price');
    $price_max = get_field('high_price');

    // Create the price range display
    $price_range = '';
    if ($price_min && $price_max) {
        $price_range = 'Price ranging from €' . number_format($price_min, 2) . ' to €' . number_format($price_max, 2);
    } else {
        $price_range = 'Price on demand';
    }

    // Wrap the price range in a div
    return '<div class="price-range">' . $price_range . '</div>';
}

add_shortcode('price_range', 'custom_price_range_display');

// New function for product card display
function custom_price_range_product_card() {
    $price_min = get_field('low_price');
    $price_max = get_field('high_price');

    // Create the price range display for the product card
    if ($price_min && $price_max) {
        $price_range = '€' . number_format($price_min, 2) . ' - €' . number_format($price_max, 2);
    } else {
        $price_range = 'Price on demand';
    }

    // Wrap the price range in a div
    return '<div class="price-range-product-card">' . $price_range . '</div>';
}

add_shortcode('price_range_product_card', 'custom_price_range_product_card');

function custom_related_products_display() {
    // Get the related products from the relationship field
    $related_products = get_field('product_decorations'); // Ensure 'product_decorations' is the correct field name

    // Debugging: Check if the field is returning the expected data
    if ( ! $related_products ) {
        return '';
    }

    // Create the HTML for related products
    $products_html = '';
    if ($related_products) {
        $products_html .= '<div class="related-products">';
        foreach ($related_products as $product) {
            $product_id = $product->ID;
            $product_title = get_the_title($product_id);
            $product_link = get_permalink($product_id);
            $product_image = get_the_post_thumbnail($product_id, 'thumbnail'); // You can use 'thumbnail', 'medium', 'large', or 'full'

            $products_html .= '<div class="related-product">';
            $products_html .= '<a href="' . esc_url($product_link) . '">';
            $products_html .= $product_image; // Display the featured image
            $products_html .= '<p>' . esc_html($product_title) . '</p>'; // Display the product title
            $products_html .= '</a>';
            $products_html .= '</div>';
        }
        $products_html .= '</div>';
    } else {
        $products_html = '<p>No related products available.</p>';
    }

    // Wrap the related products in a div
    return '<div class="related-products-wrapper">' . $products_html . '</div>';
}

add_shortcode('product_decorations', 'custom_related_products_display');

// Change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_add_to_cart_button_text_single' );
function woocommerce_add_to_cart_button_text_single() {
    return __( 'Add to quote', 'woocommerce' );
}

// Change add to cart text on product archives page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_add_to_cart_button_text_archives' );
function woocommerce_add_to_cart_button_text_archives() {
    return __( 'Add to quote', 'woocommerce' );
}

// Hook into WooCommerce to modify the quantity input field
add_filter('woocommerce_quantity_input_args', 'custom_minimum_order_quantity_with_fallback', 10, 2);
function custom_minimum_order_quantity_with_fallback($args, $product) {
    // Get the ACF field value (MOQ)
    $moq = get_field('moq', $product->get_id());

    // If no specific MOQ is set, use the default MOQ
    if (!$moq) {
        $moq = $GLOBALS['default_moq']; // Use the global variable for default MOQ
    }

    // Ensure the minimum value and input value are set to the MOQ, not 1
    $args['min_value'] = $moq;
    $args['input_value'] = max($args['input_value'], $moq); // Ensure the input value is at least the MOQ

    return $args;
}

add_filter('woocommerce_order_button_text', 'custom_checkout_button_text');

function custom_checkout_button_text($button_text) {
    // Change the default text to "Request Quote"
    return 'Request Quote';
}

// Custom color swatches
function custom_color_swatches_display() {
    $output = '<div class="color-swatches">';

    // Check if there are colors in the ACF repeater field
    if (have_rows('colors')) {
        // Loop through each color in the repeater field
        while (have_rows('colors')) {
            the_row();
            $color = get_sub_field('color_picker_field');

            // If the color exists, create a swatch
            if ($color) {
                $output .= '<div class="color-swatch-item" style="background-color:' . esc_attr($color) . '; border: 1px solid; border-color: #F6F6F6; width: 20px; height: 20px; border-radius: 9px; display: inline-block;"></div>';
            }
        }
    } else {}

    // Check if the custom color checkbox field is checked
    if (get_field('custom_color')) {
        $output .= '<div class="color-swatch-item pantone-color" style="display: flex; gap: 10px; align-items:center; background-color: #F6F6F6; margin-bottom: 5px; padding: 3px;">';
        $output .= '<div style="background-image: url(https://catalog.teamsunday.com/wp-content/uploads/2025/02/custom-color.svg); background-position: center center; background-repeat: no-repeat; background-size: contain; border: none; width: 20px; height: 20px; border-radius: 9px; display: inline-block;"></div>';
        $output .= '<p style="font-size: 14px; margin: 0; padding:0; color: black;">Custom color</p>';
        $output .= '</div>';
    }

    $output .= '</div>';

    // Return the output for use in the shortcode
    return $output;
}

// Create a single shortcode
add_shortcode('color_swatches', 'custom_color_swatches_display');

// Truncate product short description on shop and product pages
add_action('wp_footer', 'truncate_short_description');

function truncate_short_description() {
    if (is_shop() || is_product()) { // Apply to both shop and product pages
        wc_enqueue_js('
            var show_char = 100; // Number of characters to show
            var ellipses = "..."; // Ellipses to display
            $(".woocommerce-product-details__short-description").each(function() {
                var content = $(this).html();
                if (content.length > show_char) {
                    var truncated = content.substr(0, show_char) + ellipses;
                    $(this).html("<span style=\'font-size: 14px; font-weight: 400;\'>" + truncated + "</span>");
                }
            });
        ');
    }
}

add_filter('acf/format_value/name=genders', function ($value, $post_id, $field) {
    // If the value is empty, return it as-is
    if (empty($value)) {
        return $value;
    }

    // Check if "Unisex" is in the array
    if (in_array('Unisex', $value)) {
        return 'Unisex'; // Only return "Unisex" if it's checked
    }

    // Otherwise, return the values as a comma-separated list
    return implode(', ', $value);
}, 10, 3);
