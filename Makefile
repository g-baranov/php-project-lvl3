start:
	php artisan serve --host 0.0.0.0

test:
	php artisan test
test-coverage:
	composer exec phpunit -- -c phpunit.xml --coverage-clover build/logs/clover.xml
lint:
	composer exec phpcs -- --standard=PSR12 app


install:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm install

deploy:
	git push heroku main
