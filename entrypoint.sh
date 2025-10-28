
#!/usr/bin/env bash
set -e
cd /var/www/html

# Ensure .env
if [ ! -f .env ]; then
  cp .env.example .env || true
fi

php artisan key:generate --force || true

# Parse DATABASE_URL if present (Railway sets it)
php -r '
$env = file_get_contents(".env");
$put = function($k,$v) use (&$env){ $line="$k=$v"; if(preg_match("/^{$k}=.*/m",$env)){$env=preg_replace("/^{$k}=.*/m",$line,$env);} else { $env .= "\n".$line; } };
$put("APP_ENV","production");
$put("APP_URL", getenv("RAILWAY_PUBLIC_DOMAIN") ? ("https://".getenv("RAILWAY_PUBLIC_DOMAIN")) : (getenv("APP_URL")?: "http://localhost"));
$put("FRONTEND_URL", getenv("FRONTEND_URL") ?: "https://example.com");
$put("SESSION_DRIVER","file");
$put("SESSION_DOMAIN","");
$put("SANCTUM_STATEFUL_DOMAINS","");
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
  } else { // assume mysql
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

# Optimize & migrate
php artisan config:clear || true
php artisan cache:clear || true
php artisan migrate --force || true
php artisan db:seed --force || true

exec "$@"
