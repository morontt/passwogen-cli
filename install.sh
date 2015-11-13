#!/usr/bin/env bash

if type composer &> /dev/null; then
    composer install --optimize-autoloader
else
    if [ ! -f ./composer.phar ]; then
        curl -sS https://getcomposer.org/installer | php
    fi

    php composer.phar self-update
    php composer.phar install --optimize-autoloader
fi

if type box &> /dev/null; then
    box build -v
else
    if [ ! -f ./box.phar ]; then
        curl -LSs http://box-project.github.io/box2/installer.php | php
    fi

    php box.phar update
    php box.phar build -v
fi
