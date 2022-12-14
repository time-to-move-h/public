<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit76a3ec47559be9b7eadbdab90e89dc10
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit76a3ec47559be9b7eadbdab90e89dc10::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit76a3ec47559be9b7eadbdab90e89dc10::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit76a3ec47559be9b7eadbdab90e89dc10::$classMap;

        }, null, ClassLoader::class);
    }
}
