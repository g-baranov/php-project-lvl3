start:
	php artisan serve --host 0.0.0.0

test:
	php artisan test

lint:
	./vendor/bin/phpcs --standard=PSR12 app
