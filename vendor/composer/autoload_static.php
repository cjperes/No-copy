<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite0beaeb616843c4a40cce4b30b799d30
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PAnD' => __DIR__ . '/..' . '/collizo4sky/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite0beaeb616843c4a40cce4b30b799d30::$classMap;

        }, null, ClassLoader::class);
    }
}
