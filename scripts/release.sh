!#/bin/bash
docker compose -f docker-compose.prod.yaml build
docker compose down --remove-orphans && docker compose -f docker-compose.prod.yaml down --remove-orphans
docker compose -f docker-compose.prod.yaml up -d
docker compose -f docker-compose.prod.yaml run --rm php-fpm composer install --no-dev --optimize-autoloader
docker compose -f docker-compose.prod.yaml run --rm php-fpm composer dump-autoload --no-dev --classmap-authoritative
docker compose -f docker-compose.prod.yaml run --rm php-fpm php bin/console doctrine:migrations:migrate
docker compose -f docker-compose.prod.yaml run --rm php-fpm php bin/console cache:clear
docker compose -f docker-compose.prod.yaml run --rm node npm ci
docker compose -f docker-compose.prod.yaml run --rm node npm run build
docker compose -f docker-compose.prod.yaml up -d
