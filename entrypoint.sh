#!/usr/bin/env bash
set -e
cd /var/www/html

# .env
if [ ! -f .env ]; then
  cp .env.example .env || true
fi

php artisan key:generate --force || true

# Monta .env desde variables de Railway (MySQL o Postgres)
php -r '
$env = file_get_contents(".env");
$set = function($k,$v) use (&$env) {
  $line = "{$k}={$v}";
  if (preg_match("/^{$k}=.*/m", $env)) { $env = preg_replace("/^{$k}=.*/m", $line, $env); }
  else { $env .= "\n".$line."\n"; }
};
$set("APP_ENV","production");
$set("APP_URL", getenv("APP_URL") ?: "https://railway.app");
$set("FRONTEND_URL", getenv("FRONTEND_URL") ?: "https://example.vercel.app");
$set("SESSION_DRIVER","file");
$set("LOG_LEVEL","info");

# Si Railway te da PG*, usamos PG. Si no, MySQL con MYSQL*
if (getenv("DB_CONNECTION")==="pgsql" || getenv("PGHOST")) {
  $set("DB_CONNECTION","pgsql");
  $set("DB_HOST", getenv("PGHOST") ?: "localhost");
  $set("DB_PORT", getenv("PGPORT") ?: "5432");
  $set("DB_DATABASE", getenv("PGDATABASE") ?: "pawction");
  $set("DB_USERNAME", getenv("PGUSER") ?: "pawction");
  $set("DB_PASSWORD", getenv("PGPASSWORD") ?: "secret");
} else {
  $set("DB_CONNECTION","mysql");
  $set("DB_HOST", getenv("MYSQLHOST") ?: "localhost");
  $set("DB_PORT", getenv("MYSQLPORT") ?: "3306");
  $set("DB_DATABASE", getenv("MYSQLDATABASE") ?: "pawction");
  $set("DB_USERNAME", getenv("MYSQLUSER") ?: "pawction");
  $set("DB_PASSWORD", getenv("MYSQLPASSWORD") ?: "secret");
}

$set("STRIPE_SECRET", getenv("STRIPE_SECRET") ?: "");
$set("STRIPE_PUBLIC", getenv("STRIPE_PUBLIC") ?: "");
$set("STRIPE_WEBHOOK_SECRET", getenv("STRIPE_WEBHOOK_SECRET") ?: "");
$set("PAYPAL_MODE", getenv("PAYPAL_MODE") ?: "sandbox");
$set("PAYPAL_CLIENT_ID", getenv("PAYPAL_CLIENT_ID") ?: "");
$set("PAYPAL_SECRET", getenv("PAYPAL_SECRET") ?: "");

file_put_contents(".env", $env);
'

php artisan config:clear || true
php artisan cache:clear || true
php artisan migrate --force || true
php artisan db:seed --force || true

exec "$@"
