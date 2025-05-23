#!/bin/sh

echo "⏳ Aguardando o banco de dados iniciar..."

# Loop até conseguir conectar ao banco
until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" --silent; do
  echo "⏳ Ainda aguardando o MySQL em $DB_HOST:$DB_PORT..."
  sleep 2
done

echo "✅ Banco de dados está pronto!"

# Executa as migrations e inicia o servidor
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=8000
