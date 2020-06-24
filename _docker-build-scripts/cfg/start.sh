#!/bin/sh

#
# NGCMS Docker image - startup script
cd /var/www/ngcms
if [ ! -e index.php ] && [ ! -e engine/core.php ]; then
    echo "Startup init..."
    cd /var/www/init
    cp -r . /var/www/ngcms/
    chown -R www-data.www-data /var/www/ngcms/
    #tar xzf /var/www/init/build.tgz
fi

# Run PHP-FPM
/usr/local/sbin/php-fpm