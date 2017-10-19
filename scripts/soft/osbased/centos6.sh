#!/bin/bash

sudo yum update
sudo yum upgrade

sudo yum install mc git wget curl -y

if $INSTALL_MYSQL; then
    #install mysql
    sudo rpm -Uvh http://dev.mysql.com/get/mysql-community-release-el6-4.noarch.rpm
    sudo yum install mysql-community-server -y
    sudo service mysqld start
    sudo mysqladmin -uroot password $DBPASSWD
    sudo service mysqld restart
    sudo chkconfig mysqld on
fi

if $INSTALL_APACHE; then
    sudo yum install httpd -y
    sudo chkconfig httpd on
    sudo rm /etc/httpd/conf.d/*

            # Check if it is vagrant or real server
    if [ -d /opt/modules ]; then #vagrant
        sudo sed -i "s/User\sapache/User vagrant/g" /etc/httpd/conf/httpd.conf
        sudo sed -i "s/Group\sapache/Group vagrant/g" /etc/httpd/conf/httpd.conf
    fi
fi

if $INSTALL_PHP; then
    sudo rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm
    sudo yum install php70w php70w-cli php70w-common php70w-bcmath php70w-dba php70w-devel php70w-embedded php70w-fpm php70w-gd php70w-imap php70w-intl php70w-ldap php70w-mbstring php70w-mysqlnd php70w-odbc php70w-opcache php70w-pear php70w-process php70w-pspell php70w-recode php70w-tidy php70w-xml php70w-xmlrpc -y
fi