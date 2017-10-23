<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8e541fab05bad6f1af69105f66500340
{
    public static $prefixLengthsPsr4 = array(
        'V' =>
            array(
                'View\\' => 5,
            ),
        'M' =>
            array(
                'Modules\\' => 8,
                'Model\\' => 6,
            ),
        'C' =>
            array(
                'Controller\\' => 11,
                'Cache\\' => 6,
            ),
    );

    public static $prefixDirsPsr4 = array(
        'View\\' =>
            array(
                0 => __DIR__ . '/../..' . '/generator/view',
            ),
        'Modules\\' =>
            array(
                0 => __DIR__ . '/../..' . '/generator/modules',
            ),
        'Model\\' =>
            array(
                0 => __DIR__ . '/../..' . '/generator/model',
            ),
        'Controller\\' =>
            array(
                0 => __DIR__ . '/../..' . '/generator/controller',
            ),
        'Cache\\' =>
            array(
                0 => __DIR__ . '/../..' . '/generator/modules/Cache',
            ),
    );

    public static $fallbackDirsPsr4 = array(
        0 => __DIR__ . '/../..' . '/',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8e541fab05bad6f1af69105f66500340::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8e541fab05bad6f1af69105f66500340::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit8e541fab05bad6f1af69105f66500340::$fallbackDirsPsr4;

        }, null, ClassLoader::class);
    }
}
