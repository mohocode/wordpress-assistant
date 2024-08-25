<?php 
namespace App\Base;

class Cmb2Setup {

    public function __construct() {


        add_filter('cmb2_meta_box_url', [$this , 'setupUrl']);
         
    }


    public function getOption(string $optionKey = "cmb2-main" , string $key = '', bool $default = false){

        if ( function_exists( 'cmb2_get_option' ) ) {
            // Use cmb2_get_option as it passes through some key filters.
            return cmb2_get_option( $optionKey , $key, $default );
        }
    
        // Fallback to get_option if CMB2 is not loaded yet.
        $opts = get_option( $optionKey, $default );
    
        $val = $default;
    
        if ( 'all' == $key ) {
            $val = $opts;
        } elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
            $val = $opts[ $key ];
        }
    
        return $val;
    }

    public function setupUrl($url) {

        if (strpos($url, basename(get_template_directory())) !== false) {

            return dirname(__FILE__ , 3) ."/cmb2/";

        }

        return $url;
    }
    
}