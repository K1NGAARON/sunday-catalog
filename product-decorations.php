<?php

function display_related_decorations($atts) {
    $atts = shortcode_atts(
        array(
            'field' => '',
        ), 
        $atts, 
        'decoration_products'
    );

    if (empty($atts['field'])) {
        return '<p>No decoration field provided.</p>';
    }

    $related_products = get_field($atts['field']);

    if (!$related_products) {
        return ''; // Return empty if no related products are found
    }

    $output = '<div class="related-decorations">';
    
    foreach ($related_products as $product) {
        $product_id = $product->ID;
        $product_title = get_the_title($product_id);
        $product_link = get_permalink($product_id);
        $product_image = get_the_post_thumbnail($product_id, 'thumbnail');

        $output .= '<div class="decoration-item">';
        $output .= '<a href="' . esc_url($product_link) . '">';
        $output .= $product_image;
        $output .= '<p>' . esc_html($product_title) . '</p>';
        $output .= '</a>';
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}

add_shortcode('decoration_products', 'display_related_decorations');


// [decoration_products field="product_decorations_embroidery"]
// [decoration_products field="product_decorations_printing"]
// [decoration_products field="product_decorations_heat_transfer"]