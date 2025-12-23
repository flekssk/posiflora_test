.PHONY: artisan build-prod octane-reload

artisan:
	docker compose -f docker-compose.yml exec php php artisan $(filter-out $@,$(MAKECMDGOALS))

bash:
	docker compose -f docker-compose.yml exec bash

build:
	docker compose up -d --build --force-recrete

error:
	tail -f -n 2000000 storage/logs/laravel.log | grep "production.ERROR"

octane-reload:
	@echo "→ Перезагрузка Laravel Octane"
	@docker compose exec -T php php artisan octane:reload

%:
	@:
