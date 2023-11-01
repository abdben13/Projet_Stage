<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit41d662c47447174c1680bec3e7b5251f
{
    public static $files = array (
        '48483d6c44b015b6d6d681c009d084a7' => __DIR__ . '/../..' . '/src/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'AltoRouter' => __DIR__ . '/..' . '/altorouter/altorouter/AltoRouter.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit41d662c47447174c1680bec3e7b5251f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit41d662c47447174c1680bec3e7b5251f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit41d662c47447174c1680bec3e7b5251f::$classMap;

        }, null, ClassLoader::class);
    }
}
