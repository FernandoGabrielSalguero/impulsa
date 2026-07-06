#!/usr/bin/env bash
# Ejecutar por SSH dentro de la carpeta impulsa_back en Hostinger.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACK="${ROOT}/impulsa_back"

cd "$BACK"

if [[ ! -f .env ]]; then
  echo "Creando .env desde deploy/hostinger.env.example..."
  cp "${ROOT}/deploy/hostinger.env.example" .env
  echo "Edita .env con DB_USERNAME, DB_PASSWORD y APP_KEY antes de continuar."
  exit 1
fi

composer install --no-dev --optimize-autoloader

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
  php artisan key:generate --force
fi

php artisan migrate --force || {
  echo "Si migrate falla por tablas existentes, crea solo Sanctum:"
  echo "  php artisan migrate --path=database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php --force"
  echo "  o importa impulsa_back/database/sql/personal_access_tokens.sql en phpMyAdmin"
}
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "Setup listo. Prueba:"
echo "  curl -X POST https://impulsagroup.com/api/v1/auth/check-email \\"
echo "    -H 'Content-Type: application/json' \\"
echo "    -d '{\"correo\":\"test@test.com\"}'"
echo ""
echo "Si sigue el 500, revisa: tail -50 storage/logs/laravel.log"
