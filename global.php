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
        $price_range = 'Price not available';
    }

    // Wrap the price range in a div
    return '<div class="price-range">' . $price_range . '</div>';
}

add_shortcode('price_range', 'custom_price_range_display');

function custom_related_products_display() {
    // Get the related products from the relationship field
    $related_products = get_field('product_decorations'); // Ensure 'product_decorations' is the correct field name

    // Debugging: Check if the field is returning the expected data
    if ( ! $related_products ) {
        return '<p>Relationship field is not returning any products. Please check the ACF field setup.</p>';
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
    return __( 'Request a quote', 'woocommerce' ); 
}

// Change add to cart text on product archives page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_add_to_cart_button_text_archives' );  
function woocommerce_add_to_cart_button_text_archives() {
    return __( 'Request a quote', 'woocommerce' );
}

// Set MOQ based on field
// 
// 
// Set a fallback MOQ if no specific MOQ is set
$default_moq = 100;

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

// Validate the cart to ensure MOQ is respected
add_action('woocommerce_check_cart_items', 'check_minimum_order_quantity_with_fallback');
function check_minimum_order_quantity_with_fallback() {
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        // Get the ACF MOQ field value
        $moq = get_field('minimum_order_quantity', $product_id);

        // If no specific MOQ is set, use the fallback MOQ
        if (!$moq) {
            $moq = $GLOBALS['default_moq']; // Use the global variable for default MOQ
        }

        if ($quantity < $moq) {
            wc_add_notice(
                sprintf('The minimum order quantity for %s is %d.', $cart_item['data']->get_name(), $moq),
                'error'
            );
        }
    }
}

function custom_color_swatches_display() {
    // Check if there are colors in the ACF repeater field
    if( have_rows('colors') ):
        $output = '<div class="color-swatches">';
        
        // Loop through each color in the repeater field
        while( have_rows('colors') ): the_row();
            $color = get_sub_field('color_picker_field');
            
            // If the color exists, create a swatch
            if ($color) {
                $output .= '<div class="color-swatch-item" style="background-color:' . esc_attr($color) . '; border: 1px solid; border-color: #F1F1F1; width: 20px; height: 20px; border-radius: 9px; display: inline-block;"></div>';
            }
        endwhile;

        // Add the "your custom pantone color" section
        $output .= '<div class="color-swatch-item pantone-color" style="display: flex; gap: 10px; align-items:center;">';
        $output .= '<div style="background: linear-gradient(90deg, red, orange, yellow, green, blue, indigo, violet); border: none; width: 20px; height: 20px; border-radius: 9px; display: inline-block;"></div>';
        $output .= '<p style="margin: 0; padding:0; color: black;">Custom</p>';
        $output .= '</div>';

        $output .= '</div>';
    else:
        $output = '<p>No colors available</p>';
    endif;
    
    // Return the output to be used in a shortcode
    return $output;
}

// Create the shortcode
add_shortcode('color_swatches', 'custom_color_swatches_display');


// Display mock ups on the product page
function display_design_inspiration_gallery() {
    // Get the ACF gallery field
    $images = get_field('mock_ups');

    // Check if there are any images in the gallery
    if( $images ):
        $output = '<div class="design-inspiration-gallery">';

        // Loop through the images and generate HTML
        foreach( $images as $image ):
            $img_url = $image['url']; // Full image URL
            $img_alt = $image['alt']; // Alt text for accessibility
            
            $output .= '<div class="gallery-image">';
            $output .= '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($img_alt) . '" style="width:100%;">';
            $output .= '</div>';
        endforeach;

        $output .= '</div>';
    else:
        $output = '<p>No design inspiration images available.</p>';
    endif;

    return $output;
}

// Create a shortcode for the gallery
add_shortcode('design_inspiration_gallery', 'display_design_inspiration_gallery');

// Display real life pictures on the product page
function display_real_life_gallery() {
    // Get the ACF gallery field
    $images = get_field('real_life');

    // Check if there are any images in the gallery
    if( $images ):
        $output = '<div class="design-inspiration-gallery">';

        // Loop through the images and generate HTML
        foreach( $images as $image ):
            $img_url = $image['url']; // Full image URL
            $img_alt = $image['alt']; // Alt text for accessibility
            
            $output .= '<div class="gallery-image">';
            $output .= '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($img_alt) . '" style="width:100%;">';
            $output .= '</div>';
        endforeach;

        $output .= '</div>';
    else:
        $output = '<p>No real life images available.</p>';
    endif;

    return $output;
}

// Create a shortcode for the real life pictures
add_shortcode('real_life_gallery', 'display_real_life_gallery');