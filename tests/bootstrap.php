<?php

$autoloaders = [
    __DIR__.'/../vendor/autoload.php',
    __DIR__.'/../../../../adminTest/vendor/autoload.php',
];

foreach ($autoloaders as $autoloader) {
    if (file_exists($autoloader)) {
        $loader = require $autoloader;
        $loader->addPsr4('Ssh521\\LaravelPopup\\', __DIR__.'/../src');
        $loader->addPsr4('Ssh521\\LaravelPopup\\Tests\\', __DIR__);

        return;
    }
}

throw new RuntimeException('Composer autoloader not found. Run composer install or use the adminTest workbench vendor directory.');
