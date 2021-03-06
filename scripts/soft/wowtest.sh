#!/bin/bash

sudo mkdir /var/log/wowtest

sudo cp vhosts/wowtest.conf /etc/apache2/sites-available
sudo ln -s /etc/apache2/sites-available/wowtest.conf /etc/apache2/sites-enabled
sudo service apache2 restart #todo move this to another place

composer global require "fxp/composer-asset-plugin:^1.2.0"
composer config -g github-oauth.github.com $GITHUB_ACCESS_TOKEN

sudo chmod 777 /var/www/html/wowtest -R
cd /var/www/html/wowtest
composer install
