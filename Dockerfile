FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install git -y
RUN apt-get install unzip -y
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer config -g repo.packagist composer https://packagist.phpcomposer.com && \
    composer install
EXPOSE 8000
CMD ["php", "artisan", "serve"]
