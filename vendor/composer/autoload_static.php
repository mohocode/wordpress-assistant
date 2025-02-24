<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd6ca6d67ed7ea6738f742f14e7aad217
{
    public static $files = array (
        'a3a4a4c063798e229ee2b3e2b91cb189' => __DIR__ . '/../..' . '/Functions/wp-helper.php',
        '582bceda06c75c41ba3148d1d719b57a' => __DIR__ . '/../..' . '/Functions/wc-helper.php',
        '45c4bf7ae9f31d46c7ef5e3c97c0768d' => __DIR__ . '/../..' . '/Functions/project.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Project\\' => 8,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Project\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Project',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd6ca6d67ed7ea6738f742f14e7aad217::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd6ca6d67ed7ea6738f742f14e7aad217::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd6ca6d67ed7ea6738f742f14e7aad217::$classMap;

        }, null, ClassLoader::class);
    }
}
