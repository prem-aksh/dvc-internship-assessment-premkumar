<?php
/**
 * Plugin Name:       DVC Testimonials
 * Plugin URI:        https://digitalvisibilityconcepts.com
 * Description:       A complete testimonials management system with custom post type, meta fields, and a responsive shortcode display with slider.
 * Version:           1.0.0
 * Author:            Intern Candidate
 * License:           GPL-2.0+
 * Text Domain:       dvc-testimonials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ==========================================================================
   PART A – Custom Post Type Registration
   ========================================================================== */

function dvc_register_testimonials_cpt() {
    $labels = array(
        'name'                  => _x( 'Testimonials', 'Post type general name', 'dvc-testimonials' ),
        'singular_name'         => _x( 'Testimonial', 'Post type singular name', 'dvc-testimonials' ),
        'menu_name'             => _x( 'Testimonials', 'Admin Menu text', 'dvc-testimonials' ),
        'name_admin_bar'        => _x( 'Testimonial', 'Add New on Toolbar', 'dvc-testimonials' ),
        'add_new'               => __( 'Add New', 'dvc-testimonials' ),
        'add_new_item'          => __( 'Add New Testimonial', 'dvc-testimonials' ),
        'new_item'              => __( 'New Testimonial', 'dvc-testimonials' ),
        'edit_item'             => __( 'Edit Testimonial', 'dvc-testimonials' ),
        'view_item'             => __( 'View Testimonial', 'dvc-testimonials' ),
        'all_items'             => __( 'All Testimonials', 'dvc-testimonials' ),
        'search_items'          => __( 'Search Testimonials', 'dvc-testimonials' ),
        'not_found'             => __( 'No testimonials found.', 'dvc-testimonials' ),
        'not_found_in_trash'    => __( 'No testimonials found in Trash.', 'dvc-testimonials' ),
        'featured_image'        => __( 'Client Photo', 'dvc-testimonials' ),
        'set_featured_image'    => __( 'Set client photo', 'dvc-testimonials' ),
        'remove_featured_image' => __( 'Remove client photo', 'dvc-testimonials' ),
        'use_featured_image'    => __( 'Use as client photo', 'dvc-testimonials' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'testimonials' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'       => true, // Gutenberg support
    );

    register_post_type( 'testimonial', $args );
}
add_action( 'init', 'dvc_register_testimonials_cpt' );


/* ==========================================================================
   PART B – Meta Box & Custom Fields
   ========================================================================== */

function dvc_add_testimonial_meta_box() {
    add_meta_box(
        'dvc_testimonial_details',
        __( 'Client Details', 'dvc-testimonials' ),
        'dvc_testimonial_meta_box_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'dvc_add_testimonial_meta_box' );

/**
 * Meta box HTML output.
 *
 * @param WP_Post $post Current post object.
 */
function dvc_testimonial_meta_box_callback( $post ) {
    // Security nonce.
    wp_nonce_field( 'dvc_save_testimonial_meta', 'dvc_testimonial_nonce' );

    $client_name     = get_post_meta( $post->ID, '_dvc_client_name', true );
    $client_position = get_post_meta( $post->ID, '_dvc_client_position', true );
    $company_name    = get_post_meta( $post->ID, '_dvc_company_name', true );
    $rating          = get_post_meta( $post->ID, '_dvc_rating', true );
    ?>
    <style>
        .dvc-meta-table { width: 100%; border-collapse: collapse; }
        .dvc-meta-table th { text-align: left; padding: 8px 12px 8px 0; font-weight: 600; width: 160px; vertical-align: top; padding-top: 10px; }
        .dvc-meta-table td { padding: 6px 0; }
        .dvc-meta-table input[type="text"],
        .dvc-meta-table select { width: 100%; max-width: 400px; padding: 6px 10px; border: 1px solid #ddd; border-radius: 4px; }
        .dvc-required { color: #c00; margin-left: 2px; }
    </style>
    <table class="dvc-meta-table">
        <tr>
            <th>
                <label for="dvc_client_name">
                    <?php esc_html_e( 'Client Name', 'dvc-testimonials' ); ?>
                    <span class="dvc-required" aria-label="required">*</span>
                </label>
            </th>
            <td>
                <input
                    type="text"
                    id="dvc_client_name"
                    name="dvc_client_name"
                    value="<?php echo esc_attr( $client_name ); ?>"
                    required
                    placeholder="<?php esc_attr_e( 'e.g. Jane Smith', 'dvc-testimonials' ); ?>"
                />
            </td>
        </tr>
        <tr>
            <th>
                <label for="dvc_client_position"><?php esc_html_e( 'Position / Title', 'dvc-testimonials' ); ?></label>
            </th>
            <td>
                <input
                    type="text"
                    id="dvc_client_position"
                    name="dvc_client_position"
                    value="<?php echo esc_attr( $client_position ); ?>"
                    placeholder="<?php esc_attr_e( 'e.g. CEO', 'dvc-testimonials' ); ?>"
                />
            </td>
        </tr>
        <tr>
            <th>
                <label for="dvc_company_name"><?php esc_html_e( 'Company Name', 'dvc-testimonials' ); ?></label>
            </th>
            <td>
                <input
                    type="text"
                    id="dvc_company_name"
                    name="dvc_company_name"
                    value="<?php echo esc_attr( $company_name ); ?>"
                    placeholder="<?php esc_attr_e( 'e.g. Acme Corp', 'dvc-testimonials' ); ?>"
                />
            </td>
        </tr>
        <tr>
            <th>
                <label for="dvc_rating"><?php esc_html_e( 'Rating', 'dvc-testimonials' ); ?></label>
            </th>
            <td>
                <select id="dvc_rating" name="dvc_rating">
                    <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                        <option value="<?php echo esc_attr( $i ); ?>" <?php selected( $rating, $i ); ?>>
                            <?php echo esc_html( $i ); ?> <?php echo esc_html( _n( 'Star', 'Stars', $i, 'dvc-testimonials' ) ); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data.
 *
 * @param int $post_id Post ID.
 */
function dvc_save_testimonial_meta( $post_id ) {
    // Verify nonce.
    if ( ! isset( $_POST['dvc_testimonial_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dvc_testimonial_nonce'] ) ), 'dvc_save_testimonial_meta' ) ) {
        return;
    }

    // Bail on auto-save or insufficient permissions.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Sanitize & save client name (required).
    if ( isset( $_POST['dvc_client_name'] ) ) {
        $client_name = sanitize_text_field( wp_unslash( $_POST['dvc_client_name'] ) );
        update_post_meta( $post_id, '_dvc_client_name', $client_name );
    }

    // Client position.
    if ( isset( $_POST['dvc_client_position'] ) ) {
        update_post_meta( $post_id, '_dvc_client_position', sanitize_text_field( wp_unslash( $_POST['dvc_client_position'] ) ) );
    }

    // Company name.
    if ( isset( $_POST['dvc_company_name'] ) ) {
        update_post_meta( $post_id, '_dvc_company_name', sanitize_text_field( wp_unslash( $_POST['dvc_company_name'] ) ) );
    }

    // Rating (integer 1–5).
    if ( isset( $_POST['dvc_rating'] ) ) {
        $rating = absint( $_POST['dvc_rating'] );
        $rating = max( 1, min( 5, $rating ) );
        update_post_meta( $post_id, '_dvc_rating', $rating );
    }
}
add_action( 'save_post_testimonial', 'dvc_save_testimonial_meta' );


/* ==========================================================================
   PART C & D – Shortcode [testimonials]
   ========================================================================== */

function dvc_testimonials_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'   => -1,
            'orderby' => 'date',
            'order'   => 'DESC',
        ),
        $atts,
        'testimonials'
    );

    // Sanitize shortcode attributes.
    $count   = intval( $atts['count'] );
    $orderby = sanitize_key( $atts['orderby'] );
    $order   = strtoupper( sanitize_text_field( $atts['order'] ) );
    $order   = in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : 'DESC';

    $allowed_orderby = array( 'date', 'title', 'rand', 'menu_order', 'modified' );
    if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
        $orderby = 'date';
    }

    $query_args = array(
        'post_type'      => 'testimonial',
        'posts_per_page' => $count,
        'orderby'        => $orderby,
        'order'          => $order,
        'post_status'    => 'publish',
    );

    $testimonials = new WP_Query( $query_args );

    if ( ! $testimonials->have_posts() ) {
        return '<p class="dvc-no-testimonials">' . esc_html__( 'No testimonials found.', 'dvc-testimonials' ) . '</p>';
    }

    // Enqueue inline CSS & JS once.
    static $dvc_styles_enqueued = false;
    if ( ! $dvc_styles_enqueued ) {
        dvc_enqueue_testimonial_assets();
        $dvc_styles_enqueued = true;
    }

    $unique_id = 'dvc-slider-' . uniqid();

    ob_start();
    ?>
    <div class="dvc-testimonials-slider" id="<?php echo esc_attr( $unique_id ); ?>" aria-label="<?php esc_attr_e( 'Testimonials', 'dvc-testimonials' ); ?>">
        <div class="dvc-slider-track" role="list">
            <?php
            $index = 0;
            while ( $testimonials->have_posts() ) :
                $testimonials->the_post();
                $client_name     = esc_html( get_post_meta( get_the_ID(), '_dvc_client_name', true ) );
                $client_position = esc_html( get_post_meta( get_the_ID(), '_dvc_client_position', true ) );
                $company_name    = esc_html( get_post_meta( get_the_ID(), '_dvc_company_name', true ) );
                $rating          = absint( get_post_meta( get_the_ID(), '_dvc_rating', true ) );
                $active_class    = ( 0 === $index ) ? ' dvc-active' : '';
                ?>
                <div class="dvc-slide<?php echo esc_attr( $active_class ); ?>" role="listitem" aria-hidden="<?php echo $index > 0 ? 'true' : 'false'; ?>">

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="dvc-client-photo">
                            <?php the_post_thumbnail( 'thumbnail', array( 'alt' => $client_name, 'loading' => 'lazy' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $rating ) : ?>
                        <div class="dvc-rating" aria-label="<?php printf( esc_attr__( 'Rating: %d out of 5 stars', 'dvc-testimonials' ), $rating ); ?>">
                            <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                                <span class="dvc-star<?php echo $s <= $rating ? ' dvc-star--filled' : ''; ?>" aria-hidden="true">★</span>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <blockquote class="dvc-testimonial-text">
                        <?php echo wp_kses_post( get_the_content() ); ?>
                    </blockquote>

                    <div class="dvc-client-info">
                        <?php if ( $client_name ) : ?>
                            <strong class="dvc-client-name"><?php echo $client_name; ?></strong>
                        <?php endif; ?>
                        <?php if ( $client_position || $company_name ) : ?>
                            <span class="dvc-client-meta">
                                <?php
                                $meta_parts = array_filter( array( $client_position, $company_name ) );
                                echo implode( ', ', $meta_parts );
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>

                </div><!-- .dvc-slide -->
                <?php
                $index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div><!-- .dvc-slider-track -->

        <?php if ( $testimonials->post_count > 1 ) : ?>
        <nav class="dvc-slider-nav" aria-label="<?php esc_attr_e( 'Testimonial Navigation', 'dvc-testimonials' ); ?>">
            <button class="dvc-prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'dvc-testimonials' ); ?>">&#8592;</button>
            <span class="dvc-slide-counter" aria-live="polite">1 / <?php echo esc_html( $testimonials->post_count ); ?></span>
            <button class="dvc-next" aria-label="<?php esc_attr_e( 'Next testimonial', 'dvc-testimonials' ); ?>">&#8594;</button>
        </nav>
        <?php endif; ?>
    </div><!-- .dvc-testimonials-slider -->
    <?php
    return ob_get_clean();
}
add_shortcode( 'testimonials', 'dvc_testimonials_shortcode' );


/* ==========================================================================
   Assets (Inline CSS + JS)
   ========================================================================== */

function dvc_enqueue_testimonial_assets() {
    $css = '
    .dvc-testimonials-slider {
        max-width: 700px;
        margin: 40px auto;
        font-family: Georgia, serif;
        position: relative;
        overflow: hidden;
    }
    .dvc-slider-track {
        display: flex;
        transition: transform 0.45s ease;
        will-change: transform;
    }
    .dvc-slide {
        min-width: 100%;
        padding: 40px 32px;
        box-sizing: border-box;
        text-align: center;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
    }
    .dvc-client-photo {
        margin: 0 auto 20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #f0e8d8;
    }
    .dvc-client-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .dvc-rating {
        margin-bottom: 16px;
        font-size: 22px;
        letter-spacing: 2px;
    }
    .dvc-star { color: #ddd; }
    .dvc-star--filled { color: #f5a623; }
    .dvc-testimonial-text {
        font-size: 17px;
        line-height: 1.7;
        color: #444;
        margin: 0 0 20px;
        font-style: italic;
        border: none;
        padding: 0;
    }
    .dvc-client-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    .dvc-client-name {
        font-size: 15px;
        font-weight: 700;
        color: #1a1208;
        font-family: sans-serif;
    }
    .dvc-client-meta {
        font-size: 13px;
        color: #888;
        font-family: sans-serif;
    }
    .dvc-slider-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 20px;
    }
    .dvc-prev, .dvc-next {
        background: #1a1208;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .2s;
    }
    .dvc-prev:hover, .dvc-next:hover { background: #c8902a; }
    .dvc-slide-counter { font-size: 13px; color: #888; font-family: sans-serif; }
    @media (max-width: 600px) {
        .dvc-slide { padding: 28px 20px; }
        .dvc-testimonial-text { font-size: 15px; }
    }
    ';

    $js = '
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".dvc-testimonials-slider").forEach(function(slider) {
            var track   = slider.querySelector(".dvc-slider-track");
            var slides  = slider.querySelectorAll(".dvc-slide");
            var prev    = slider.querySelector(".dvc-prev");
            var next    = slider.querySelector(".dvc-next");
            var counter = slider.querySelector(".dvc-slide-counter");
            var total   = slides.length;
            var current = 0;

            function goTo(index) {
                current = (index + total) % total;
                track.style.transform = "translateX(-" + (current * 100) + "%)";
                slides.forEach(function(s, i) {
                    s.setAttribute("aria-hidden", i !== current ? "true" : "false");
                });
                if (counter) counter.textContent = (current + 1) + " / " + total;
            }

            if (prev) prev.addEventListener("click", function() { goTo(current - 1); });
            if (next) next.addEventListener("click", function() { goTo(current + 1); });

            // Keyboard support
            slider.addEventListener("keydown", function(e) {
                if (e.key === "ArrowLeft")  goTo(current - 1);
                if (e.key === "ArrowRight") goTo(current + 1);
            });
        });
    });
    ';

    wp_add_inline_style( 'wp-block-library', $css );
    wp_add_inline_script( 'jquery', $js );
}
