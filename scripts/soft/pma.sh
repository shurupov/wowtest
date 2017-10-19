#!/bin/bash

if $INSTALL_PHPMYADMIN && $INSTALL_PHP && $INSTALL_APACHE && $INSTALL_MYSQL; then
    echo "Loading phpmyadmin from http://downloads.sourceforge.net/project/phpmyadmin/phpMyAdmin/4.3.0/phpMyAdmin-4.3.0-all-languages.tar.gz"
    echo "This may take a few minutes..."
    wget -q http://files.phpmyadmin.net/phpMyAdmin/4.4.11/phpMyAdmin-4.4.11-all-languages.tar.gz
    echo "Loading phpmyadmin completed"
    sudo mkdir /var/www/html/pma
    sudo mkdir /var/log/pma
    sudo tar -xf phpMyAdmin-4.4.11-all-languages.tar.gz -C /var/www/html/pma
    rm phpMyAdmin-4.4.11-all-languages.tar.gz
    sudo mv /var/www/html/pma/phpMyAdmin-4.4.11-all-languages /var/www/html/pma/www
    cd $SOURCES_PATH

    if [ "$OS_TYPE" == "ubuntu1604" ]; then
        sudo cp vhosts/pma.conf /etc/apache2/sites-available
        sudo ln -s /etc/apache2/sites-available/pma.conf /etc/apache2/sites-enabled
    fi
    if [ "$OS_TYPE" == "centos6" ]; then
        sudo cp vhosts/pma.conf /etc/httpd/conf.d
    fi
fi