<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb836d8a54faf1742cf62c4ff76728d97
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

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb836d8a54faf1742cf62c4ff76728d97::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb836d8a54faf1742cf62c4ff76728d97::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
