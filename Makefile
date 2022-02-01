SHELL := /bin/bash
EXEC_COMMAND ?= docker-compose exec application

help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
up:
	docker-compose up -d
install:
	${EXEC_COMMAND} composer install
test_db_setup:
	${EXEC_COMMAND} bin/console doctrine:database:create --env=test -n --if-not-exists || true
	${EXEC_COMMAND} bin/console doctrine:migration:migrate --env=test -n || true
migrate:
	${EXEC_COMMAND} bin/console doctrine:migration:migrate -n
	${EXEC_COMMAND} bin/console doctrine:migration:migrate -n --env=test
build:
	docker-compose build
bash:
	docker-compose exec application bash
fixtures:
	${EXEC_COMMAND} php bin/console doctrine:fixtures:load
create_volumes:
	docker volume create --name=medtester-api-postgresql || true
create_networks:
	docker network create nginx-proxy || true
start: ## start and install dependencies
start: build create_volumes create_networks up install test_db_setup migrate
phpcsfixer:
	docker-compose exec application php -dmemory_limit=-1 vendor/bin/php-cs-fixer --allow-risky=yes --no-interaction --dry-run --diff fix
phpcsfixer_fix:
	${EXEC_COMMAND} php -dmemory_limit=-1 vendor/bin/php-cs-fixer --no-interaction --allow-risky=yes --ansi fix
psalm:
	${EXEC_COMMAND} vendor/bin/psalm --no-cache
check: ## run QA checks
check: psalm phpcsfixer
phpunit:
	${EXEC_COMMAND} bin/phpunit --stop-on-failure
behat:
	${EXEC_COMMAND} php -d error_reporting="E_ALL&E_STRICT&~E_DEPRECATED" vendor/bin/behat --stop-on-failure
unit_test:
unit_test: phpunit
test:
test: unit_test behat
clear: ## clear after docker
	docker-compose down
