.DEFAULT_GOAL := help
.ONESHELL:

PHP_VERSION ?= 8.4
DEPENDENCIES_LOWEST ?= 0
COMPOSER_IMAGE ?= composer:2
COMPOSER_CACHE_DIR ?= $(HOME)/.cache/composer
SUPPORTED_PHP_VERSIONS := 7.4 8.0 8.1 8.2 8.3 8.4
COMPOSER_PROXY_ENV := $(foreach name,HTTP_PROXY HTTPS_PROXY NO_PROXY http_proxy https_proxy no_proxy,-e $(name))
COMPOSER_CA_ARGS = $(if $(CA_CERT_FILE),-v "$(abspath $(CA_CERT_FILE)):/ca/ca.crt:ro" -e COMPOSER_CAFILE=/ca/ca.crt)
COMPOSER_DOCKER_ARGS = -v "$(CURDIR):/app" -w /app -v "$(COMPOSER_CACHE_DIR):/composer-cache" -e COMPOSER_CACHE_DIR=/composer-cache $(COMPOSER_PROXY_ENV) $(COMPOSER_CA_ARGS)

.PHONY: help clean check-php-version install lint test analyze audit validate

help:
	@printf '%s\n' \
	  'Targets:' \
	  '  check-php-version  Validate the requested PHP version' \
	  '  install  Resolve dependencies for PHP_VERSION (optionally DEPENDENCIES_LOWEST=1)' \
	  '  lint     Check PHP syntax in src/ and tests/' \
	  '  test     Run PHPUnit' \
	  '  analyze  Run PHPStan' \
	  '  audit    Audit installed Composer dependencies' \
	  '  validate Validate composer.json' \
	  '  clean    Remove dependencies, lockfile, and PHPUnit cache'

clean:
	rm -rf vendor composer.lock .phpunit.result.cache

check-php-version:
	@case " $(SUPPORTED_PHP_VERSIONS) " in \
	  *" $(PHP_VERSION) "*) ;; \
	  *) echo "Unsupported PHP_VERSION=$(PHP_VERSION); use one of: $(SUPPORTED_PHP_VERSIONS)" >&2; exit 1 ;; \
	esac

install: check-php-version
	mkdir -p "$(COMPOSER_CACHE_DIR)"
	set +e
	docker run --rm -i --user "$$(id -u):$$(id -g)" --entrypoint sh \
	  $(COMPOSER_DOCKER_ARGS) \
	  $(COMPOSER_IMAGE) -s <<'SCRIPT'
	set +e
	composer config platform.php "$(PHP_VERSION)"
	status=$$?
	if [ "$$status" -eq 0 ]; then
	  if [ "$(DEPENDENCIES_LOWEST)" = "1" ]; then
	    composer update --prefer-lowest --prefer-dist --no-interaction --no-progress
	  else
	    composer update --prefer-dist --no-interaction --no-progress
	  fi
	  status=$$?
	fi
	composer config --unset platform.php >/dev/null 2>&1 || true
	composer config --unset platform >/dev/null 2>&1 || true
	php -r '$$manifest = json_decode(file_get_contents("composer.json"), true); if (isset($$manifest["config"]) && $$manifest["config"] === []) { unset($$manifest["config"]); file_put_contents("composer.json", json_encode($$manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL); }'
	cleanup_status=$$?
	if [ "$$status" -eq 0 ] && [ "$$cleanup_status" -ne 0 ]; then status=$$cleanup_status; fi
	rm -f composer.lock
	exit "$$status"
	SCRIPT
	status=$$?
	rm -f composer.lock
	exit "$$status"

lint:
	find src tests -type f -name '*.php' -print0 | xargs -0 -r -n1 php -l

test:
	vendor/bin/phpunit

analyze:
	vendor/bin/phpstan analyse --no-progress

audit:
	docker run --rm --user "$$(id -u):$$(id -g)" $(COMPOSER_DOCKER_ARGS) $(COMPOSER_IMAGE) audit

validate:
	docker run --rm --user "$$(id -u):$$(id -g)" $(COMPOSER_DOCKER_ARGS) $(COMPOSER_IMAGE) validate --strict
