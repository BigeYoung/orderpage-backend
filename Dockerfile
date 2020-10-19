FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install git unzip -y
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer config -g repo.packagist composer https://packagist.phpcomposer.com && \
    composer install
RUN mv .env.example .env \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite 
    && service apache2 start
