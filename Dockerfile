FROM php:8.2-fpm

RUN apt-get update && apt-get install -y git unzip curl \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY composer.json composer.lock ./

RUN mkdir -p www && composer install --no-interaction

COPY www ./www

RUN composer dump-autoload --optimize

CMD ["php-fpm"]
