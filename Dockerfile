FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libicu-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl gd \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
# Crea Laravel 10
RUN composer create-project laravel/laravel:^10 .

# Aplica overlay (rutas, controladores, modelos, migraciones, vistas, etc.)
COPY overlay/ /var/www/html/

# Paquetes SPA + pagos + PDF
RUN composer require laravel/sanctum \
    && composer require laravel/breeze --dev \
    && php artisan breeze:install api \
    && composer require stripe/stripe-php:^14 barryvdh/laravel-dompdf:^2.1 \
    && php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider" --force || true \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

CMD ["apache2-foreground"]
