check-traefik:
ifeq (,$(shell docker ps -f name=^traefik$$ -q))
	$(error docker container traefik is not running)
endif

.env:
	$(error .env is missing. See .env.sample)

image:
	@echo "(Re)building docker image"
	docker build --pull --no-cache -t mindhochschulnetzwerk/moodle:latest .

quick-image:
	@echo "Rebuilding docker image"
	docker build -t mindhochschulnetzwerk/moodle:latest .

dev: .env check-traefik
	@echo "Starting DEV Server"
	docker-compose -f docker-compose.base.yml -f docker-compose.dev.yml up -d --force-recreate --remove-orphans

prod: .env check-traefik
	@echo "(Re)pulling docker image"
	docker pull mindhochschulnetzwerk/moodle:latest
	@echo "Starting Production Server"
	docker-compose -f docker-compose.base.yml up -d --force-recreate --remove-orphans

adminer:
	docker-compose -f docker-compose.base.yml -f docker-compose.dev.yml up -d moodle-adminer

database: .env
	docker-compose -f docker-compose.base.yml up -d moodle-database
