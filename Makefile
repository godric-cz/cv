
.PHONY: all cv.pdf serve watch

all: cv.pdf

cv.pdf: vendor/autoload.php
	php bin/build.php

vendor/autoload.php: bin/composer
	./bin/composer install

bin/composer:
	wget https://getcomposer.org/composer-stable.phar -O bin/composer
	chmod a+x bin/composer

# serve preview output in broser
serve: vendor/autoload.php
	sh -c 'sleep 1; sensible-browser localhost:8000/bin/build.php;' &
	php -S localhost:8000

# regenerate pdf on input file changes
watch: vendor/autoload.php
	while true; do php bin/build.php; inotifywait -e close_write template/* bin/build.php; done
