FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip sqlite3 libsqlite3-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# NÃO expor porta do artisan
# Expor a porta padrão do PHP-FPM (9000)
EXPOSE 9000

#Força a sempre fazer as migrações ao iniciar o container
CMD bash -c "php artisan migrate --force"

# Rodar PHP-FPM
CMD ["php-fpm"]
