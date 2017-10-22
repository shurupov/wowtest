#!/bin/bash

sudo apt update -q
sudo apt autoremove -y
sudo apt upgrade -y

sudo apt install mc git wget curl -y

if $INSTALL_MYSQL; then
    #install mysql
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASSWD"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASSWD"
    sudo apt install -y mysql-server
fi

if $INSTALL_APACHE; then
    sudo apt install apache2 -y
    sudo rm /etc/apache2/sites-enabled/*

    #todo change condition
        # Check if it is vagrant or real server
#    if [ -d /opt/modules ]; then #vagrant
        sudo sed -i "s/www-data/ubuntu/g" /etc/apache2/envvars
#    fi

    sudo a2enmod rewrite

fi

if $INSTALL_PHP; then
    sudo apt install php-cli php-common php-json php-mysql php-mbstring php-gd php-curl php-zip libapache2-mod-php php-xml php-intl php-mongodb php-imagick wget curl php-solr -y
fi

if $INSTALL_PHP && $INSTALL_APACHE; then
    sudo apt install libapache2-mod-php -y
fi

if $INSTALL_PHP && $INSTALL_MYSQL; then
    sudo apt-get install php-mysql php-mbstring php-gd php-curl php-zip libapache2-mod-php php-xml php-intl php-mongodb wget curl -y
fi
