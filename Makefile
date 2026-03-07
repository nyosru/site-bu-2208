prod:
	@echo "- - -"
	@echo "- - -"
	@echo "+++ prod environment started"
	make create_web_laravel
#	@echo "- - -"
#	@echo "+++2 prod environment started"
#	cp caddy/prod.Caddyfile caddy/Caddyfile
	cp docker-compose.prod.yml docker-compose.yml
	docker-compose down --rmi all -v
	docker-compose up -d --build
	make caddy_refresh_cfd_prod
	@echo "- - -"
	@echo "чистим кещ докера"
	make clear_docker_cache



# линтер с фиксацией исправлениями
linter:
	 ./vendor/bin/pint -v
# линтер одногоо файла с фиксацией исправлениями
linter-file:
	 ./vendor/bin/pint -v $(file)

# линетр с показов без сохранения (общий)
linter-test:
	./vendor/bin/pint --test

# линетр с показов без сохранения (один файл)
linter-test-file:
	./vendor/bin/pint --test -v $(file)
