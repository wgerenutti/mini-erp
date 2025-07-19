FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git oniguruma-dev libxml2-dev zip unzip curl mysql-client \
    nodejs npm

RUN docker-php-ext-install pdo_mysql mbstring xml bcmath

COPY --from=composer:2.6 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --optimize-autoloader --no-dev

RUN cp .env.example .env \
    && php artisan key:generate

RUN npm install bootstrap \
    && npm run build

RUN chmod -R 0777 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
