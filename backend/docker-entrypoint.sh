#!/bin/bash
set -e

echo "Rodando migrações..."
php artisan migrate --force
echo "Migrações finalizadas..."
echo ""

echo "Iniciando PHP-FPM..."
exec "$@"