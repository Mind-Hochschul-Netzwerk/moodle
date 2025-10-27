include .env

check-traefik:
ifeq (,$(shell docker ps -f name=^traefik$$ -q))
	$(error docker container traefik is not running)
endif

.env:
	$(error file .env is missing, see .env.sample)

image:
	@echo "(Re)building docker image"
	docker build --no-cache -t ghcr.io/mind-hochschul-netzwerk/${SERVICENAME}:latest .

rebuild:
	php ./dependencies.php
	@echo "Rebuilding docker image"
	docker build -t ghcr.io/mind-hochschul-netzwerk/${SERVICENAME}:latest .
	@echo "Restarting service"
	docker compose up -d --force-recreate --remove-orphans app

dev: .env check-traefik
	@echo "Starting DEV Server"
	docker compose up -d --force-recreate --remove-orphans

prod: .env check-traefik
	@echo "Starting Production Server"
	docker compose up --pull always -d --force-recreate --remove-orphans app

upgrade:
	git pull
	make prod

shell:
	docker compose exec app sh

rootshell:
	docker compose exec --user root app sh

logs:
	docker compose logs -f

adminer: .env check-traefik
	docker compose up -d adminer

database: .env
	docker compose up -d --force-recreate db

mysql: .env
	@echo "docker compose exec db mariadb --user=user --password=\"...\" database"
	@docker compose exec db mariadb --user=user --password="$(MYSQL_PASSWORD)" database
