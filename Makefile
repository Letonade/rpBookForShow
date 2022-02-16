start:
	docker-compose up

start-detached:
	docker-compose up -d

stop:
	docker-compose stop

enter:
	docker-compose exec php bash

load-fixtures:
	docker-compose exec php bash -c "bin/console d:mo:fi:l --no-interaction && bin/console d:fi:l --no-interaction"

test-full:
	docker-compose exec php sh phpunit.sh

test-integration:
	docker-compose exec php bash -c "vendor/bin/simple-phpunit --group integration"
