FROM php:7.3-apache

RUN apt-get update && apt-get install -y \
    wget zip unzip git \
    build-essential g++ \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libzip-dev

RUN docker-php-ext-install iconv sockets mbstring mysqli pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip

# Configure PHP
RUN echo "\
max_execution_time = 6000\n\
memory_limit = 1G\n\
upload_max_filesize = 20M\n\
max_file_uploads = 20\n\
default_charset = \"UTF-8\"\n\
date.timezone = \"Europe/Berlin\"\n\
short_open_tag = On" > /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN chmod +x /usr/bin/composer

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite \
 && a2enmod headers \
 && a2enmod expires

RUN ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log

RUN usermod -u 1000 www-data && usermod -a -G users www-data

RUN mkdir -p /var/www
WORKDIR /var/www

COPY . .

EXPOSE 80

