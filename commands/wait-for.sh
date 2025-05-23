#!/bin/sh

echo "⏳ Aguardando o banco de dados iniciar..."

# Loop até conseguir conectar ao banco
until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" --silent; do
  echo "⏳ Ainda aguardando o MySQL em $DB_HOST:$DB_PORT..."
  sleep 2
done

echo "✅ Banco de dados está pronto!"

# Cria .env se não existir
if [ ! -f .env ]; then
  echo "📝 Criando .env a partir de .env.example"
  cp .env.example .env

  # Substitui variáveis do banco no .env
  sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
  sed -i "s/^DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
  sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
  sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
  sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
fi

# Gera chave da aplicação se necessário
if ! grep -q "APP_KEY=base64" .env; then
  echo "🔑 Gerando APP_KEY..."
  php artisan key:generate --force
fi

# Gera segredo JWT se necessário
if ! grep -q "JWT_SECRET=" .env; then
  echo "🔐 Gerando JWT_SECRET..."
  php artisan jwt:secret --force
fi

# Executa as migrations
php artisan migrate --force

# Inicia o servidor
php artisan serve --host=0.0.0.0 --port=8000
