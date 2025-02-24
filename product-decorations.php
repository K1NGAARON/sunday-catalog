<?php

function custom_related_products_display() {
    $related_products = get_field('product_decorations');

    if ( ! $related_products ) {
        return '';
    }

    $products_html = '';
    if ($related_products) {
        $products_html .= '<div class="related-products">';
        foreach ($related_products as $product) {
            $product_id = $product->ID;
            $product_title = get_the_title($product_id);
            $product_link = get_permalink($product_id);
            $product_image = get_the_post_thumbnail($product_id, 'thumbnail');

            $products_html .= '<div class="related-product">';
            $products_html .= '<a href="' . esc_url($product_link) . '">';
            $products_html .= $product_image;
            $products_html .= '<p>' . esc_html($product_title) . '</p>';
            $products_html .= '</a>';
            $products_html .= '</div>';
        }
        $products_html .= '</div>';
    } else {
        $products_html = '<p>No related products available.</p>';
    }

    return '<div class="related-products-wrapper">' . $products_html . '</div>';
}

add_shortcode('product_decorations', 'custom_related_products_display');