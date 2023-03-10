<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0c9e33b5c0a4ee5c1390d0c6fb1d0049
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Router\\' => 7,
            'Requests\\' => 9,
        ),
        'M' => 
        array (
            'Models\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Router\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Router',
        ),
        'Requests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Requests',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Models',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Models\\Database' => __DIR__ . '/../..' . '/src/Models/Database.php',
        'Models\\Login' => __DIR__ . '/../..' . '/src/Models/Login.php',
        'Requests\\DepositRequest' => __DIR__ . '/../..' . '/src/Requests/DepositRequest.php',
        'Requests\\LoginRequest' => __DIR__ . '/../..' . '/src/Requests/LoginRequest.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0c9e33b5c0a4ee5c1390d0c6fb1d0049::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0c9e33b5c0a4ee5c1390d0c6fb1d0049::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0c9e33b5c0a4ee5c1390d0c6fb1d0049::$classMap;

        }, null, ClassLoader::class);
    }
}
