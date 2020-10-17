FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && \
    apt-get install composer -y
RUN composer install
COPY . .
EXPOSE 8000
CMD ["php", "artisan", "serve"]
