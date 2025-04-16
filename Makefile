init:
	docker compose up -d --build
	docker compose exec app composer install
	docker compose exec app cp .env.example .env
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan storage:link
	docker compose exec app chmod -R 777 storage bootstrap/cache
	docker compose exec app sh -c 'until nc -z mysql 3306; do sleep 1; done'
	docker compose exec app php artisan migrate:fresh --seed

fresh:
	docker compose exec app php artisan migrate:fresh --seed

restart:
	@make down
	@make up

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

cache:
	docker compose exec app php artisan cache:clear
	docker compose exec app php artisan config:cache

stop:
	docker compose stop
