.PHONY: up down stop start install cli test
include .env

default: install

up:
	docker-compose up -d
down:
	docker-compose down
stop:
	docker-compose stop
start:
	docker-compose start
install: up
	docker-compose exec -T php composer install --no-interaction
	docker-compose exec -T php bash -c "drush site:install --existing-config --db-url=mysql://$(MYSQL_USER):$(MYSQL_PASS)@$(MYSQL_HOST):$(MYSQL_PORT)/$(MYSQL_DB_NAME) -y"
#	docker-compose exec -T php bash -c "drush user:create sasha --mail=\"admin@example.com\" --password=\"123\""
#	docker-compose exec -T php bash -c "drush user:role:add administrator sasha"
#	docker-compose exec -T php bash -c "drush user:block admin"
	docker-compose exec -T php bash -c 'mkdir -p "drush" && echo -e "options:\n  uri: http://$(PROJECT_BASE_URL)" > drush/drush.yml'
	docker-compose exec php bash
cli:
	docker-compose exec php bash
test:
	docker-compose exec -T php curl 0.0.0.0:80 -H "Host: $(PROJECT_BASE_URL)"
