#!/bin/sh

#
# NGCMS Docker image - startup script

cd /var/www/ngcms

# Check for redeploy request
if [ -e .redeploy-preserve ]; then
    echo "##"
    echo "## Redeploy with configuration preserve is requested!"
    rm -rf /var/www/preserve/
    mkdir -p /var/www/preserve/engine/conf/
    cp /var/www/ngcms/engine/conf/* /var/www/preserve/engine/conf/
    rm -rf /var/www/ngcms/*
fi


if [ -e .redeploy-preserve ] || ( [ ! -e index.php ] && [ ! -e engine/core.php ] ); then
    echo "##"
    echo "## Startup script deployment in process"
    cd /var/www/init
    cp -r . /var/www/ngcms/
    chown -R www-data.www-data /var/www/ngcms/
    #tar xzf /var/www/init/build.tgz
    cd /var/www/ngcms/
fi


if [ -e .redeploy-preserve ]; then
    cp /var/www/preserve/engine/conf/* /var/www/ngcms/engine/conf/
    chown -R www-data.www-data /var/www/ngcms/engine/conf/*
    rm .redeploy-preserve
fi

# Run PHP-FPM
/usr/local/sbin/php-fpm