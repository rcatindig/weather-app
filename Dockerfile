#### Step 1 : composer

FROM cylab/php81 AS composer

COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --optimize-autoloader

#### Step 2 : actual docker image

FROM php:8.1-apache

## PHP extensions

RUN docker-php-ext-install pdo_mysql

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear  \
    &&  docker-php-ext-enable redis

## Apache

# change the document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

## Laravel application

COPY . /var/www/html
# I like to use a dedicated .env file to prive sound defaults
COPY env.docker /var/www/html/.env
COPY --from=composer /var/www/html/vendor /var/www/html/vendor

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && php artisan config:clear \
    && php artisan optimize