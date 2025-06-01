#!make

.DEFAULT_GOAL := help

# ———————————————————————— 🔧 Environment Imports ———————————————————————————————
-include .env.dist
-include .env.dev
-include .env.override
export

# ———————————————————————— 🧩 Variables —————————————————————————————————————————
MERGED_FILE := .env
ENV_SOURCES := $(wildcard .env.dist .env .env.$(APP_ENV) .env.override)

# Docker command helpers
DOCKER = docker
MAKE_SILENT = @$(MAKE) --no-print-directory

# Docker Compose with auto env-merge
DOCKER_COMPOSE = $(MAKE_SILENT) env-merge >/dev/null && docker compose --env-file $(MERGED_FILE)

# Log formatting helpers
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

# PHP container interaction
EXEC = $(DOCKER) exec -it $(DOCKER_SERVICE_NAME_PHP)
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer

## ————————————————— 🔄 Project Lifecycle ———————————————————————————————————————
.PHONY: init rebuild terminate php

init: ## Init the project
	$(MAKE_SILENT) env-merge
	$(DOCKER_COMPOSE) build
	$(MAKE_SILENT) start
	$(COMPOSER) install --prefer-dist
	$(COMPOSER) dev-tools-setup
	@$(call GREEN,"The application installed successfully.")

rebuild: ## Rebuild all Docker containers
	$(MAKE_SILENT) terminate
	$(MAKE_SILENT) init

terminate: ## Unsets all the set
	$(MAKE_SILENT) stop
	$(DOCKER_COMPOSE) down --remove-orphans --volumes
	$(DOCKER_COMPOSE) rm -vsf
	@$(call GREEN,"The application was terminated successfully.")

php: ## Open Bash shell inside PHP container
	$(DOCKER_COMPOSE) up -d php-fpm
	$(DOCKER_COMPOSE) exec --user $(SYS_USER_NAME) php-fpm bash -l

## ————————————————— 🏁 Runtime Control —————————————————————————————————————————
.PHONY: start stop down restart

start: ## Start the application
	$(DOCKER_COMPOSE) up -d
	@$(call GREEN,"The application is started successfully.")

stop: ## Stop Docker containers
	$(DOCKER_COMPOSE) stop
	@$(call GREEN,"The containers are now stopped.")

down: ## Completely remove all containers
	$(DOCKER_COMPOSE) down
	@$(call GREEN,"The containers are now down.")

restart: ## Restart containers
	$(MAKE_SILENT) stop
	$(MAKE_SILENT) start

## ————————————————— ✅️ Quality & Testing ———————————————————————————————————————
.PHONY: test code-fix

test: ## Run all tests
	$(DOCKER_COMPOSE) up -d php-fpm
	$(COMPOSER) test
	$(DOCKER_COMPOSE) stop

code-fix: ## Runs quality tools to fix common issues
	$(DOCKER_COMPOSE) up -d
	$(COMPOSER) code-fix

## ————————————————— 🎻 Composer —————————————————————————————————————————————
.PHONY: composer-install composer-update composer-clear-cache composer-normalize

composer-install: ## Install composer dependencies
	$(COMPOSER) install

composer-update: ## Update composer dependencies
	$(COMPOSER) update

composer-clear-cache: ## Clear composer cache
	$(COMPOSER) clear-cache

composer-normalize: ## Normalize composer.json
	$(COMPOSER) normalize

## ————————————————— 🛠️ Utilities ——————————————————————————————————————————————
.PHONY: status env-merge doctor print-env

status: ## Show container status
	$(DOCKER_COMPOSE) ps

env-merge: ## Merge environment variables into .env
	@NEW_ENV=$$(cat /dev/null \
		$(shell [ -f .env.dist ] && echo .env.dist) \
		$(shell [ -f .env ] && echo .env) \
		$(shell [ -f .env.dev ] && echo .env.dev) \
		$(shell [ -f .env.override ] && echo .env.override) \
		| grep -v '^#' | grep -v '^\s*$$' | awk -F= '!seen[$$1]++'); \
	OLD_ENV=$$(cat $(MERGED_FILE) 2>/dev/null || echo ""); \
	if [ "$$NEW_ENV" != "$$OLD_ENV" ]; then \
		echo "$$NEW_ENV" > $(MERGED_FILE); \
		echo "🔄 Regenerated .env"; \
	else \
		echo "✅ .env is up to date."; \
	fi

doctor: ## Check system requirements
	@command -v docker >/dev/null 2>&1 || (echo "❌ Docker not found!" && exit 1)
	@command -v docker compose >/dev/null 2>&1 || (echo "❌ Docker Compose not found!" && exit 1)
	@test -f $(MERGED_FILE) || (echo "❌ .env file missing!" && exit 1)
	@$(call GREEN,"✅ All checks passed. System ready.")

## ————————————————— 📚 Help ————————————————————————————————————————————————————
.PHONY: help
help: ## Show all commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
