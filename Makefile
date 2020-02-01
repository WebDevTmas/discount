ENV = dev

help: ## Show this help message.
	@echo 'usage: make [target] ...'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

install: ## imports data
	composer install

run: ## See discounts on order
	php -S localhost:8000 public/index.php

phpunit: ## Run PHPUnit test
	bin/phpunit --testdox

deptrac: ## Run deptrac
	vendor/bin/deptrac

phpcs: ## Run PHP Code sniffer
	vendor/bin/phpcs -p

tests: phpunit deptrac phpcs ## Runs phpunit, deptrac and codesniffer

.PHONY: install run phpunit phpcs tests
