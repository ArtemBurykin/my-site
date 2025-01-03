!#/bin/bash
docker compose build
docker compose down --remove-orphans && docker compose -f docker-compose.prod.yaml down --remove-orphans
docker compose up -d
docker compose run --rm php-fpm composer install
docker compose run --rm php-fpm php bin/console doctrine:migrations:migrate
docker compose run --rm php-fpm php bin/console cache:clear
docker compose run --rm node npm ci
docker compose run --rm node npm run build
docker compose up -d
