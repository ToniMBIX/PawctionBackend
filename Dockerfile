
FROM php:8.2-apache

# System deps
RUN apt-get update && apt-get install -y     git unzip libzip-dev libpng-dev libonig-dev libicu-dev libpq-dev     && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl gd     && a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create Laravel app
WORKDIR /var/www/html
RUN composer create-project laravel/laravel:^10 .

# Copy overlay (app code: routes, models, controllers, migrations, views, cors)
COPY overlay/ /var/www/html/

# Laravel deps used by overlay
RUN composer require laravel/sanctum     && composer require laravel/breeze --dev     && php artisan breeze:install api     && composer require stripe/stripe-php:^14 barryvdh/laravel-dompdf:^2.1     && php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force || true

# Apache listen on $PORT (Railway)
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf

# Entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

CMD ["apache2-foreground"]
