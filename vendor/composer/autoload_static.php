<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4563f1d0f8f3741fef607ec714efc4e6
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/class',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4563f1d0f8f3741fef607ec714efc4e6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4563f1d0f8f3741fef607ec714efc4e6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4563f1d0f8f3741fef607ec714efc4e6::$classMap;

        }, null, ClassLoader::class);
    }
}
