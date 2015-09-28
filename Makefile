CONTAINER_NAME = php1
APP_PORT = 8888
ADMINER_PORT = 9999

ROOT_DIR:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

run:
	docker run -d -p $(APP_PORT):80 -p $(ADMINER_PORT):88 -v $(ROOT_DIR):/var/www --name $(CONTAINER_NAME) freemountain/php-development
	chmod +x composer.phar
	mkdir -p generated-classes

insert-sql:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./vendor/bin/propel sql:insert"

build-model:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./vendor/bin/propel model:build"

build-sql:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./vendor/bin/propel build-sql --overwrite"

build-conf:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./vendor/bin/propel convert-conf "

autoloader-dump:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./composer.phar dumpautoload"

autoloader-install:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./composer.phar install"

autoloader-update:
	docker exec $(CONTAINER_NAME) bash -c "cd /var/www; ./composer.phar update"
	chmod +x vendor/propel/propel/bin/propel

error.log:
	docker exec $(CONTAINER_NAME) bash -c "tail -f /var/log/apache2/error.log"

build: autoloader-install build-conf build-model build-sql insert-sql autoloader-dump
