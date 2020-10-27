FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install git unzip -y
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer config -g repo.packagist composer https://packagist.phpcomposer.com && \
    composer global require "laravel/installer"
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install
RUN mv .env.example .env \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite \ 
    && mkdir /var/www/html/storage/aml \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage 
