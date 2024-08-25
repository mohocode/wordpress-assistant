<?php

use App\Util\Jalali;
use Morilog\Jalali\Jalalian;

function set_active_menu($url, $class = "active")
{
    global $wp;

    return  home_url($wp->request . '/') === $url ? $class : '';
}

function theme__thumbnail_url($size = 'medium')
{

    $post_id = get_the_ID(); // Assuming you're in the loop
    // The desired image size

    $thumbnail_url = get_the_post_thumbnail_url($post_id, $size);

    if ($thumbnail_url) return $thumbnail_url;

    return 'No Url';
}


function theme__content($maxLength = 50)
{
    // Get the raw content of the post
    $content = get_the_content();

    // Strip out any images from the content
    $content = preg_replace('/<img [^>]+./', '', $content);

    // Strip out any shortcodes from the content
    $content = strip_shortcodes($content);

    // Trim the content to 50 words
    $content = wp_trim_words($content, $maxLength);

    // Echo the content
    return $content;
}

function theme__title($maxLength = 50)
{
    // Get the raw content of the post
    $content = get_the_title();

    // Trim the content to 50 words
    $content = wp_trim_words($content, $maxLength);

    // Echo the content
    return $content;
}


function theme__get_date()
{

    $date = Jalali::gregorianToJalali(get_the_date('Y-m-d'));

    return Jalali::printMonthName($date);
}

function includeWithVariables($filePath, $variables = array(), $print = true)
{

    $output = NULL;

    if (file_exists($filePath)) {
        // Extract the variables to a local namespace
        extract($variables);

        // Start output buffering
        ob_start();

        // Include the template file
        include $filePath;

        // End buffering and return its contents
        $output = ob_get_clean();
    }

    if ($print) {
        print $output;
    }

    return $output;
}

function getTermPostType($post_id, $taxonomy)
{

    return wp_get_post_terms($post_id, $taxonomy, array("fields" => "all"));
}

function dd($var)
{
    echo '<pre>';
    var_export($var);
    echo '</pre>';
    die();
}


function getOption(string $optionKey = "cmb2-main", string $key = '', bool $default = false)
{

    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option($optionKey, $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option($optionKey, $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}


function getPostMetaLanguage($slug , $key, $postType, $lang )
{
    $post = get_page_by_path($slug, OBJECT, $postType);

    // Original post ID and post type
    $original_post_id = $post->ID; // Example ID
    $post_type = $postType; // Your custom post type
    $language_code = $lang; // Target language code, e.g., Spanish



    $translated_post_id = apply_filters('wpml_object_id', $original_post_id, $post_type, true, $language_code);

    if ($translated_post_id) {
        // Retrieve and use post meta
        $meta_value = get_post_meta($translated_post_id, $key, true);

        if (!empty($meta_value)) {
            return $meta_value;
        }
    } else {
        return false;
    }
}

function getPostMetaLanguageById($id, $key, $postType, $lang = 'fa')
{
    // Original post ID and post type
    $original_post_id = $id; // Example ID
    $post_type = $postType; // Your custom post type
    $language_code = $lang; // Target language code, e.g., Spanish



    $translated_post_id = apply_filters('wpml_object_id', $original_post_id, $post_type, true, $language_code);

    if ($translated_post_id) {
        // Retrieve and use post meta
        $meta_value = get_post_meta($translated_post_id, $key, true);


        if (!empty($meta_value)) {
            return $meta_value;
        }
    } else {
        return false;
    }
}

function getOptionCmb2Lang($key, $keyFa, $metaName)
{

    $language_code = apply_filters('wpml_current_language', NULL);

    $options = null;


    switch ($language_code) {
        case 'en':
            $options =  getOption($key, $metaName);
            break;

        default:
            $options = getOption($keyFa, $metaName);
            break;
    }

    return $options;
}

function MOHO__statusLanguage()
{
    $language_code = apply_filters('wpml_current_language', NULL);
    return $language_code == 'fa' ? 'fa' : 'en';
}


function theme__getTheDate()
{
    $date = null;
    switch (MOHO__statusLanguage()) {
        case 'en':
            $date = the_time('F j, Y');
            break;

        default:
            $date = theme__get_date();
            break;
    }

    return $date;
}

function MOHO__getHomeUrl()
{
    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function MOHO__getBaseUrl()
{
    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
}

function MOHO_getCurrentUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $current_url;
}


function theme__thumbnail($size = 'medium_large')
{

    if (has_post_thumbnail()) {
        echo the_post_thumbnail($size);
    } else {
        echo '<img src="' . get_template_directory_uri() . '/assets/img/image_not_available.png">';
    }
}

function MOHO__base_url_ml($language_code = 'fa')
{
    
    // Get the current URL
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    if($language_code == 'fa')
       $current_url =  str_replace('en/', '' , $current_url );

    // Parse the URL to get its components
    $parsed_url = parse_url($current_url);

    // Construct the new path with the language code segment
    $new_path = '/' . $language_code . $parsed_url['path'];

    // Rebuild the URL with the new path
    $new_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $new_path;

    // Check if there's a query string and append it if present
    if (!empty($parsed_url['query'])) {
        $new_url .= '?' . $parsed_url['query'];
    }

    // Check if there's a fragment and append it if present
    if (!empty($parsed_url['fragment'])) {
        $new_url .= '#' . $parsed_url['fragment'];
    }
    

    return $new_url;
}

function MOHO_WP_breadcrumbs()
{
    // Settings
    $separator = ' > ';
    $home_title = 'Home';

    // Get the query & post information
    global $post, $wp_query;

    // Do not display on the homepage
    if (!is_front_page()) {

        // Build the breadcrums
        echo '<ul class="breadcrumbs">';
        echo '<li><a href="' . get_home_url() . '">' . $home_title . '</a></li>';

        if (is_category() || is_single()) {
            $cats = get_the_category($post->ID);

            if (!empty($cats)) {
                $cat = $cats[0];
                echo '<li>' . $separator . '<a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a></li>';
            }

            if (is_single()) {
                echo '<li>' . $separator . get_the_title() . '</li>';
            }
        } elseif (is_page()) {
            if ($post->post_parent) {
                // If child page, get parents 
                $anc = get_post_ancestors($post->ID);
                // Get parents in the right order
                $anc = array_reverse($anc);
                // Parent page loop
                foreach ($anc as $ancestor) {
                    $parents .= '<li>' . $separator . '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                }
                // Display parent pages
                echo $parents;
                // Current page
                echo '<li>' . $separator . get_the_title() . '</li>';
            } else {
                // Just display current page if not parents
                echo '<li>' . $separator . get_the_title() . '</li>';
            }
        } elseif (is_tag()) {
            // Tag page
            echo '<li>' . $separator . 'Tag: ' . single_tag_title('', false) . '</li>';
        } elseif (is_day()) {
            // Day archive
            // Year link
            echo '<li>' . $separator . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . ' Archives</a></li>';
            // Month link
            echo '<li>' . $separator . '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('M') . ' Archives</a></li>';
            // Day display
            echo '<li>' . $separator . '' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</li>';
        } elseif (is_month()) {
            // Month Archive
            // Year link
            echo '<li>' . $separator . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . ' Archives</a></li>';
            // Month display
            echo '<li>' . $separator . 'Archive for ' . get_the_time('M') . '</li>';
        } elseif (is_year()) {
            // Display year archive
            echo '<li>' . $separator . 'Archive for ' . get_the_time('Y') . '</li>';
        } elseif (is_author()) {
            // Auhor archive
            // Get the author information
            global $author;
            $userdata = get_userdata($author);
            // Display author name
            echo '<li>' . $separator . 'Author: ' . $userdata->display_name . '</li>';
        } elseif (get_query_var('paged')) {
            // Paginated archives
            echo '<li>' . $separator . 'Page ' . get_query_var('paged') . '</li>';
        } elseif (is_search()) {
            // Search results page
            echo '<li>' . $separator . 'Search results for: ' . get_search_query() . '</li>';
        } elseif (is_404()) {
            // 404 page
            echo '<li>' . $separator . 'Error 404</li>';
        }

        echo '</ul>';
    }

    
}

function get__actual_url()
{
    global $theme_setup;
    global $wp;

    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function path_inc($path = "") {
    return get_template_directory() . "/inc/$path";
}

function path_admin($path = "") {
    return get_template_directory() . "/admin/$path";
}

function path_woo($path = "") {
    return get_template_directory() . "/woocommerce/$path";
}

function path_customize_woo($path="") {
    return get_template_directory() . "/woocommerce/custom/$path";
}

function __layout($path = "") {
    return "/layouts/$path/$path";
}

function load_layout($name , $layout) {
   return get_template_part(__layout($name) , $layout);
}

function load_component($layout , Array $args) {
    return get_template_part('/layouts/components/component' , 
                              $layout ,  $args);
}

function echoItem($item) {
    
    if(isset($item) && !empty($item) && $item !== null)
        echo $item;

    echo '';

}