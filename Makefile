.PHONY: install cs

COMPOSE=docker-compose -f docker-compose.yml -f docker-compose.override.yml

DOCKER_EXEC_PHP=$(COMPOSE) exec php

install: env docker-up c-inst

setup: m db-seed clear-cache

c-inst:
	$(DOCKER_EXEC_PHP) composer install

docker-up:
	$(COMPOSE) up -d

docker-down:
	$(COMPOSE) down

cs:
	vendor/bin/php-cs-fixer fix --verbose --config=.php_cs/.php-cs-fixer.php

clear-cache:
	$(DOCKER_EXEC_PHP) bin/console cache:clear

docker-exec-php:
	$(COMPOSE) exec php bash

docker-exec-nginx:
	$(COMPOSE) exec nginx bash

docker-rebuild: docker-down
	$(COMPOSE) build

md:
	$(DOCKER_EXEC_PHP) bin/console doctrine:migrations:diff

m:
	$(DOCKER_EXEC_PHP) bin/console doctrine:migrations:migrate --no-interaction

mp:
	$(DOCKER_EXEC_PHP) bin/console doctrine:migrations:migrate prev --no-interaction

ms:
	$(DOCKER_EXEC_PHP) bin/console doctrine:migrations:list

db-seed:
	$(DOCKER_EXEC_PHP) bin/console doctrine:fixtures:load --no-interaction

log:
	$(COMPOSE) logs -f

env:
	cp -n .env .env.local || true
