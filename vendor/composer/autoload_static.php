<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite38105fc0c214d25570a82eac9b2fa5c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Manage\\Review\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Manage\\Review\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite38105fc0c214d25570a82eac9b2fa5c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite38105fc0c214d25570a82eac9b2fa5c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite38105fc0c214d25570a82eac9b2fa5c::$classMap;

        }, null, ClassLoader::class);
    }
}
