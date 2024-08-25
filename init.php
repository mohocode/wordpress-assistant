<?php

use App\Base\Enqueue;
use App\Base\ThemeSetup;
use App\Service\GetWpQuery;
use App\Service\UpdateQuery;

use App\DB\WordpressDb;
use Project\Enqueue\FrontEnqueue;
use Project\Woocommerce\WcArchiveCustomize;
use Project\Woocommerce\WcSingleCustomize;

require dirname(__DIR__) . "/core/vendor/autoload.php";

// Instantiate the class to register the custom post type and taxonomy
// new VideoPostType();
// new GalleryPostType();
// new BlockHtmlPostType();
// new ProductPostType();
// new NewsPostType();

global $wooArchive;
$wooArchive = new WcArchiveCustomize();

global $wooSingle;
$wooSingle = new WcSingleCustomize();

global $theme_setup;
$theme_setup = new ThemeSetup();

global $get_wp_query;
$get_wp_query = new GetWpQuery();

global $update_query;
$update_query = new UpdateQuery();
new FrontEnqueue();
// Initialize WordPressQueryExecutor
WordpressDb::init();

require_once get_template_directory() . '/inc/core/cmb2/init.php';
require_once get_template_directory() . '/inc/core/cmb2-tabs/plugin.php';


/**
 * |-------------------------------------------------------------|
 * | Pluck Options -- True/False ::                              |
 * |-------------------------------------------------------------|
 */
$theme_setup->extraSetup([
    //'disable_Gutenberg'
]);

