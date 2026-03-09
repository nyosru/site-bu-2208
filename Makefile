b:
	npm run build

seed:
	php artisan migrate:fresh --seed

prod:
	@echo "- - -"
	@echo "- - -"
	@echo "+++ prod go go go"

	docker exec 2208bu php artisan migrate
	docker exec 2208bu php artisan cache:clear

	#make linter-test




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
