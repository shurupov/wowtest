#!/bin/bash

export OS_TYPE="ubuntu1604"

if [ -d /opt/scripts ]; then
    cd /opt/scripts
    chmod +x ./install.sh
    ./install.sh
fi

if [ -d ../../scripts ]; then
    cd ../../scripts
    chmod +x ./install.sh
    ./install.sh
fi
