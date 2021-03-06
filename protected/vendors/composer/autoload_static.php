<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfd1e62ae5663328471d0d7d8e1980430
{
    public static $files = array (
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WindowsAzure\\' => 13,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Message\\' => 17,
        ),
        'M' => 
        array (
            'MicrosoftAzure\\Storage\\' => 23,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WindowsAzure\\' => 
        array (
            0 => __DIR__ . '/..' . '/microsoft/windowsazure/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'MicrosoftAzure\\Storage\\' => 
        array (
            0 => __DIR__ . '/..' . '/microsoft/azure-storage/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PayPal\\Service' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/merchant-sdk-php/lib',
            ),
            'PayPal\\PayPalAPI' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/merchant-sdk-php/lib',
            ),
            'PayPal\\EnhancedDataTypes' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/merchant-sdk-php/lib',
            ),
            'PayPal\\EBLBaseComponents' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/merchant-sdk-php/lib',
            ),
            'PayPal\\CoreComponentTypes' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/merchant-sdk-php/lib',
            ),
            'PayPal' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/rest-api-sdk-php/lib',
                1 => __DIR__ . '/..' . '/paypal/sdk-core-php/lib',
            ),
            'PEAR' => 
            array (
                0 => __DIR__ . '/..' . '/pear/pear_exception',
            ),
        ),
        'M' => 
        array (
            'Mail_mimeDecode' => 
            array (
                0 => __DIR__ . '/..' . '/pear/mail_mime-decode',
            ),
            'Mail' => 
            array (
                0 => __DIR__ . '/..' . '/pear/mail_mime',
            ),
        ),
        'H' => 
        array (
            'HTTP_Request2' => 
            array (
                0 => __DIR__ . '/..' . '/pear/http_request2',
            ),
        ),
        'C' => 
        array (
            'Console' => 
            array (
                0 => __DIR__ . '/..' . '/pear/console_getopt',
            ),
        ),
    );

    public static $fallbackDirsPsr0 = array (
        0 => __DIR__ . '/..' . '/pear/pear-core-minimal/src',
    );

    public static $classMap = array (
        'Net_URL2' => __DIR__ . '/..' . '/pear/net_url2/Net/URL2.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfd1e62ae5663328471d0d7d8e1980430::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfd1e62ae5663328471d0d7d8e1980430::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitfd1e62ae5663328471d0d7d8e1980430::$prefixesPsr0;
            $loader->fallbackDirsPsr0 = ComposerStaticInitfd1e62ae5663328471d0d7d8e1980430::$fallbackDirsPsr0;
            $loader->classMap = ComposerStaticInitfd1e62ae5663328471d0d7d8e1980430::$classMap;

        }, null, ClassLoader::class);
    }
}
