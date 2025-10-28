#!/bin/sh
set -e
cd /var/www/html

# 1) Hacer que Apache escuche en $PORT (que define Railway)
if [ -n "${PORT}" ]; then
  sed -i "s/Listen .*/Listen ${PORT}/g" /etc/apache2/ports.conf || true
fi

# 2) Asegurar .env y APP_KEY
if [ ! -f .env ]; then
  cp .env.example .env || true
fi
php artisan key:generate --force || true

# 3) Escribir .env a partir de variables de Railway (incl. DATABASE_URL)
php -r '
$env = file_get_contents(".env");
$put = function($k,$v) use (&$env){
  $line="$k=$v";
  if (preg_match("/^{$k}=.*/m",$env)) { $env = preg_replace("/^{$k}=.*/m",$line,$env); }
  else { $env .= "\n".$line; }
};
$put("APP_ENV","production");
$host = getenv("RAILWAY_PUBLIC_DOMAIN");
$put("APP_URL", $host ? ("https://".$host) : (getenv("APP_URL")?: "http://localhost"));
$fe = getenv("FRONTEND_URL") ?: "";
if ($fe) $put("FRONTEND_URL", $fe);
$put("SESSION_DRIVER","file");

$dbUrl = getenv("DATABASE_URL") ?: "";
if ($dbUrl) {
  $parts = parse_url($dbUrl);
  $scheme = $parts["scheme"] ?? "";
  $host = $parts["host"] ?? "";
  $port = $parts["port"] ?? "";
  $user = $parts["user"] ?? "";
  $pass = $parts["pass"] ?? "";
  $db   = isset($parts["path"]) ? ltrim($parts["path"],"/") : "";
  if ($scheme === "postgres" || $scheme === "postgresql") {
    $put("DB_CONNECTION","pgsql");
    $put("DB_HOST",$host); $put("DB_PORT",$port?:5432); $put("DB_DATABASE",$db); $put("DB_USERNAME",$user); $put("DB_PASSWORD",$pass);
  } else {
    $put("DB_CONNECTION","mysql");
    $put("DB_HOST",$host); $put("DB_PORT",$port?:3306); $put("DB_DATABASE",$db); $put("DB_USERNAME",$user); $put("DB_PASSWORD",$pass);
  }
}

$put("STRIPE_SECRET", getenv("STRIPE_SECRET") ?: "");
$put("STRIPE_PUBLIC", getenv("STRIPE_PUBLIC") ?: "");
$put("STRIPE_WEBHOOK_SECRET", getenv("STRIPE_WEBHOOK_SECRET") ?: "");
$put("PAYPAL_MODE", getenv("PAYPAL_MODE") ?: "sandbox");
$put("PAYPAL_CLIENT_ID", getenv("PAYPAL_CLIENT_ID") ?: "");
$put("PAYPAL_SECRET", getenv("PAYPAL_SECRET") ?: "");

file_put_contents(".env",$env);
'

# 4) Optimizar y migrar/sembrar
php artisan config:clear || true
php artisan cache:clear || true
php artisan migrate --force || true
php artisan db:seed --force || true

exec "$@"
