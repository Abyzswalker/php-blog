<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4c1bd7b28c46d1bac9277be7b06a3c74
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Blog\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Blog\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4c1bd7b28c46d1bac9277be7b06a3c74::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4c1bd7b28c46d1bac9277be7b06a3c74::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}