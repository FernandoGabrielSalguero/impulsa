#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
BACK="$ROOT/impulsa_back"

if [[ ! -d "$BACK" ]]; then
  echo "No existe impulsa_back en $ROOT"
  exit 1
fi

cd "$BACK"

if [[ ! -f .env ]]; then
  echo "Falta impulsa_back/.env en el servidor. Copialo manualmente antes de continuar."
  exit 1
fi

composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deploy backend OK"
