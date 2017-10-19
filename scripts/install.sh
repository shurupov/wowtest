#!/bin/bash

wget http://stedolan.github.io/jq/download/linux64/jq
chmod +x ./jq
sudo mv jq /usr/local/bin

export SOURCES_PATH=$(pwd)

export INSTALL_PHP=$(cat installSettings.json | /usr/local/bin/jq '.install.php' -r)
export INSTALL_MYSQL=$(cat installSettings.json | /usr/local/bin/jq '.install.mysql' -r)
export INSTALL_APACHE=$(cat installSettings.json | /usr/local/bin/jq '.install.apache' -r)

export INSTALL_PHPMYADMIN=$(cat installSettings.json | /usr/local/bin/jq '.install.phpmyadmin' -r)

export INSTALL_PROJECT=$(cat installSettings.json | /usr/local/bin/jq '.install.project' -r)

export DBPASSWD=$(cat installSettings.json | /usr/local/bin/jq '.dbPassword' -r)

if [ "$OS_TYPE" == "ubuntu1604" ]; then
    chmod +x soft/osbased/ubuntu1604.sh
    soft/osbased/ubuntu1604.sh
fi
if [ "$OS_TYPE" == "centos6" ]; then
    chmod +x soft/osbased/centos6.sh
    soft/osbased/centos6.sh
fi

if $INSTALL_PHPMYADMIN; then
    cd $SOURCES_PATH
    chmod +x soft/pma.sh
    soft/pma.sh
fi

if $INSTALL_PROJECT && $INSTALL_PHP && $INSTALL_APACHE; then

    if ! [ -d /opt ]; then
        sudo mkdir /opt
    fi

    cd $SOURCES_PATH
    chmod +x soft/composer.sh
    soft/composer.sh

    cd $SOURCES_PATH
    chmod +x soft/wowtest.sh
    soft/wowtest.sh

fi

echo "Server successfully installed!"
echo "Congratulations!"

