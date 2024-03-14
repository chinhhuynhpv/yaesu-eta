FROM php:8.0.7-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libwebp-dev libjpeg62-turbo-dev libpng-dev \
    libfreetype6-dev \
    libxml2-dev \
    unzip && \
    a2enmod rewrite headers && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install bcmath pdo_mysql zip opcache gd

ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser && \
    mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser
