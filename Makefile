start:
	php artisan serve --host 0.0.0.0

test:
	php artisan test

lint:
	./vendor/bin/phpcs --standard=PSR12 app


install:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi

deploy:
	git push heroku main
