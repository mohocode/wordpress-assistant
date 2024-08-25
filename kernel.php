<?php

function wpConfig()
{
    $dirPath = get_template_directory() . "/inc/config/";
    $files = scandir($dirPath);

    foreach ($files as $file) {

        $filePath = $dirPath . '/' . $file;

        if (is_file($filePath)) {
            return include $filePath;
        }

        return false;
    }

  
}
