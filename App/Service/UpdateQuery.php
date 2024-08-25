<?php

namespace App\Service;

class UpdateQuery
{


    public function updateContentExceprt($postType = "post" , $content = "" , $excerpt = "")
    {
        global $wpdb;

        $postType = "product";

        $query = "UPDATE {$wpdb->prefix}posts SET post_content = %s, post_excerpt = %s WHERE post_type = %s ";

        $results = $wpdb->get_results(
            $wpdb->prepare($query, $content, $excerpt, $postType)
        );

        var_dump($results);

        return $results;
    }
}
