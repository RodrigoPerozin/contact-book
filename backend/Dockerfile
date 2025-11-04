FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip sqlite3 libsqlite3-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader
# Cria arquivo SQLite se não existir, e define permissões corretas
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database \
    && chmod 664 database/database.sqlite

# Copia entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# NÃO expor porta do artisan
# Expor a porta padrão do PHP-FPM (9000)
EXPOSE 9000

#Força a sempre fazer as migrações ao iniciar o container

# Usa entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Executa PHP-FPM
CMD ["php-fpm"]
