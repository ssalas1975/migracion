# May be useful if you want to test with a different php version
# (note that variables can also be overwritten in cli argument)
PHP_BIN = $(shell which php)
PHP_VERSION := $(shell $(PHP_BIN) --version | head -n 1 | cut -d " " -f 2 | cut -d "-" -f 1)
PHP_MAJOR_VERSION := $(shell $(PHP_BIN) --version | head -n 1 | cut -d " " -f 2 | cut -d "-" -f 1 | cut -c 1,1)

# how to run composer (you can also use `$(PHP_BIN) composer.phar`
# to make sure composer uses the project's php binary)
COMPOSER_BIN = $(shell which composer)
CONSOLE_BIN := ./bin/console
PHPCSFIXER_BIN := php-cs-fixer
PHPSTAN_BIN := phpstan
PHPUNIT_BIN := bin/phpunit
PHPUNIT_EXCLUDE_GROUP = --exclude-group functional

# PHP's built-in dev server runs on this address
DEV_SERVER_ADDR := 127.0.0.1:8080

# used for PID (process id) files, dev server logs, ...
TMP_DIR := tmp-dev

#checking validators BIN
PHPCSFIXER_CHECK := $(shell command -v $(PHPCSFIXER_BIN) 2> /dev/null)
PHPSTAN_CHECK := $(shell command -v $(PHPSTAN_BIN) 2> /dev/null)

.PHONY: list
list:
	@echo ""
	@echo "Useful targets:"
	@echo ""
	@echo "  install      > install composer dependencies"
	@echo "  update       > explicitly update dependencies (composer update)"
	@echo ""
	@echo "  clean        > removes cache directory & build, vendor dirs & .php_cs.cache"
	@echo ""
	@echo "  tests        > run php-cs-fixer, phpstan and phpunit tests (default target)"
	@echo "  tests_unit   > run only phpunit tests"
	@echo "  standards    > run only php-cs-fixer & phpstan tests"



.PHONY: all
all: clean install tests

# Setting features
#-----------------

.PHONY: install
install: vendor/autoload.php

# task to explicitly update the dependencies
.PHONY: update
update:
	$(PHP_BIN) $(COMPOSER_BIN) update

# we only do `install`, as composer.json may change
# without wanting to update dependencies.
# Please check the composer warning of out-of-sync
# packages.
composer.lock: composer.json
	$(PHP_BIN) $(COMPOSER_BIN) install --no-interaction
	touch $@

# make sure deps get installed even if
# composer.lock exists initially (or
# after `git pull`)
vendor/autoload.php: composer.lock
	$(PHP_BIN) $(COMPOSER_BIN) install --no-interaction
	touch $@


# Testing features
#-----------------

tests: standards tests_unit

.PHONY: standards
standards: cache-validators
ifeq (${PHP_MAJOR_VERSION}, 7)
	@echo "\n\033[92m***** COMPROBANDO ESTANDARES DE CALIDAD *****\033[0m"
ifndef PHPCSFIXER_CHECK
	$(error "php-cs-fixer is not available please install it with composer global install friendsofphp/php-cs-fixer")
endif
	-$(PHPCSFIXER_BIN) fix -v
ifndef PHPSTAN_CHECK
	$(error "phpstan is not available please install it with composer global install phpstan/phpstan")
endif
	-$(PHPSTAN_BIN) analyze --level=7 src/ tests/
else
	@echo "\n\033[92m***** LOS ESTANDARES DE CALIDAD DEBEN EJECUTARSE CON PHP >7.1 *****\033[0m"
endif

.PHONY: tests_unit
tests_unit: install
	@echo "\n\033[92m***** COMPROBANDO CODIGO UNITARIO *****\033[0m"
ifdef GROUP
	-$(PHP_BIN) $(PHPUNIT_BIN) $(PHPUNIT_EXCLUDE_GROUP) --group $(GROUP)
else
	-$(PHP_BIN) $(PHPUNIT_BIN) $(PHPUNIT_EXCLUDE_GROUP)
endif

# Cleaning features
#------------------

.PHONY: cache-validators
cache-validators:
	$(RM) -r build .php_cs.cache

.PHONY: clean
clean: cache-validators
	$(RM) -r vendor
