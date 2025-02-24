<?php

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