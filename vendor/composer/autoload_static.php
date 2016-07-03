<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd3539326e43efbf64d2f3ef7d5b28f97
{
    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Kernel\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Kernel\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Kernel',
        ),
    );

    public static $classMap = array (
        'Kernel\\Controllers\\BaseController' => __DIR__ . '/../..' . '/Kernel/controllers/BaseController.php',
        'Kernel\\Interfaces\\Database\\Database' => __DIR__ . '/../..' . '/Kernel/Interfaces/Database/Database.php',
        'Kernel\\Interfaces\\Router\\Router' => __DIR__ . '/../..' . '/Kernel/Interfaces/Router/Router.php',
        'Kernel\\Interfaces\\Session\\Session' => __DIR__ . '/../..' . '/Kernel/Interfaces/Session/Session.php',
        'Kernel\\Interfaces\\View\\view' => __DIR__ . '/../..' . '/Kernel/Interfaces/View/View.php',
        'Kernel\\classes\\Database\\Database' => __DIR__ . '/../..' . '/Kernel/classes/Database/Database.php',
        'Kernel\\classes\\Router\\Router' => __DIR__ . '/../..' . '/Kernel/classes/Router/Router.php',
        'Kernel\\classes\\Session\\Session' => __DIR__ . '/../..' . '/Kernel/classes/Session/Session.php',
        'Kernel\\classes\\View\\View' => __DIR__ . '/../..' . '/Kernel/classes/View/View.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd3539326e43efbf64d2f3ef7d5b28f97::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd3539326e43efbf64d2f3ef7d5b28f97::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd3539326e43efbf64d2f3ef7d5b28f97::$classMap;

        }, null, ClassLoader::class);
    }
}