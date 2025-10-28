FROM php:8.2-apache

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libicu-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl gd \
    && a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App Laravel base
WORKDIR /var/www/html
RUN composer create-project laravel/laravel:^10 .

# Copia el overlay del proyecto
COPY overlay/ /var/www/html/

# Paquetes Laravel requeridos por el overlay
RUN composer require laravel/sanctum \
    && composer require laravel/breeze --dev \
    && php artisan breeze:install api \
    && composer require stripe/stripe-php:^14 barryvdh/laravel-dompdf:^2.1 \
    && php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider" --force || true

# Copia el entrypoint y elimina CRLF -> LF para evitar "Exec format error"
COPY entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

# Por defecto el sitio escucha 8080; en entrypoint se cambia a $PORT si lo hay
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# Usa /bin/sh explícitamente
ENTRYPOINT ["/bin/sh", "/entrypoint.sh"]
CMD ["apache2-foreground"]
