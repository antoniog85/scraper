#!/usr/bin/env bash

apt-get update
apt-get install -y git
docker-php-ext-install mbstring pcntl pdo pdo_mysql
pecl install xdebug
echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini
php-fpm
