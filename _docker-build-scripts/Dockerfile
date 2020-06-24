FROM php:7.3-fpm
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libxslt1-dev && docker-php-ext-configure gd && docker-php-ext-install -j$(nproc) gd iconv pdo_mysql && apt-get install -y git
RUN docker-php-ext-install xsl soap mysqli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY ./build /var/www/init/
COPY ./cfg/start.sh /
RUN chmod +x /start.sh
RUN cd /var/www/init && composer install
WORKDIR /var/www/ngcms
#CMD [ "php", "./engine/install.php" ]
#CMD ["php-fpm"]
CMD [ "/start.sh" ]

