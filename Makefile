.PHONY: install migrate db test run

db:
	docker-compose up -d

install:
	composer install

migrate:
	symfony console doctrine:migrations:migrate

test:
	php ./bin/phpunit

run:
	symfony serve
