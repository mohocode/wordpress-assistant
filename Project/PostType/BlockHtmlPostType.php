<?php

namespace Project\PostType;

class BlockHtmlPostType
{

    public function __construct()
    {
        add_action('init', 
                   array($this, 'register_block_html_post_type'));
                   
        add_shortcode('block_html', 
                      array($this, 'block_html_shortcode'));

        add_filter('manage_block-html_posts_columns', 
                   array($this, 'add_shortcode_column'));

        add_action('manage_block-html_posts_custom_column', 
                   array($this, 'fill_shortcode_column'), 10, 2);
    }

    public function register_block_html_post_type()
    {
        $labels = array(
            'name'          => __('Block HTML', 'ziba'),
            'singular_name' => __('Block HTML', 'ziba'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'menu_icon'          => 'dashicons-html',
            'rewrite'            => array('slug' => 'block-html'),
            'show_in_rest'       => true,
            'supports'           => array('title',),
        );

        register_post_type('block-html', $args);
    }

    public function block_html_shortcode($atts)
    {
        $atts = shortcode_atts(array('slug' => ''), $atts, 'block_html');

        $query = new \WP_Query(array(
            'post_type'      => 'block-html',
            'name'           => sanitize_text_field($atts['slug']),
            'posts_per_page' => 1,
        ));

        $output = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $items = getMetaBlockHtmlById(get_the_ID(), 'slider_product');
                ob_start(); ?>
                <div class="row row-cols-xl-3 row-cols-sm-2 row-cols-1 gy-4 gx-3 gx-xxl-4 py-4">
                    <?php foreach ($items as $item) :  ?>
                        <!-- Item-->
                        <div class="col pb-sm-2">
                            <article class="position-relative">
                                <div class="position-relative mb-3">
                                    <img class="rounded-3" src="<?php echo $item['block_html_header_slide']; ?>" alt="<?php echo $item['block_html_header_title']; ?>">
                                </div>
                                <h3 class="mb-2 fs-lg">
                                    <a class="nav-link stretched-link" href="<?php echo $item['block_html_header_link']; ?>">
                                        <?php echo $item['block_html_header_title']; ?>
                                    </a>
                                </h3>

                            </article>
                        </div>

                    <?php endforeach; ?>
                </div> <?php
                    }
                }

                wp_reset_postdata();
                return ob_get_clean();
            }

            // Add a new column to the admin list to display the shortcode
            public function add_shortcode_column($columns)
            {
                $columns['shortcode'] = __('Shortcode', 'ziba');
                return $columns;
            }

            // Fill the new column with the shortcode text for each post
            public function fill_shortcode_column($column, $post_id)
            {
                if ($column == 'shortcode') {
                    echo '<input type="text" readonly="readonly" value="[block_html slug=&quot;' . get_post_field('post_name', $post_id) . '&quot;]" onclick="this.select();">';
                }
            }
        }
