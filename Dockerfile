# Laravel 10 on PHP 8.2 + Apache, built at image time
FROM php:8.2-apache

# System deps
RUN apt-get update && apt-get install -y     git unzip libpq-dev libzip-dev libonig-dev libicu-dev libxml2-dev     && docker-php-ext-install pdo pdo_mysql pdo_pgsql intl zip

# Apache config
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create Laravel project
RUN composer create-project laravel/laravel . --no-interaction --prefer-dist

# Install packages
RUN composer require laravel/sanctum stripe/stripe-php barryvdh/laravel-dompdf simplesoftwareio/simple-qrcode

# Copy overlay (routes, controllers, models, migrations, config)
COPY overlay/ /var/www/html/

# Sanctum & config
RUN php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force || true

# Expose port
EXPOSE 8080

# Entrypoint: optimize, migrate, serve
CMD php artisan key:generate --force && \ 
    php artisan migrate --force && \ 
    php artisan config:cache && php artisan route:cache && \ 
    apache2-foreground
