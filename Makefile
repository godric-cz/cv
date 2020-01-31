
.PHONY: all

all: cv.pdf

cv.pdf: vendor/autoload.php
	./bin/build

vendor/autoload.php: bin/composer
	./bin/composer install

bin/composer:
	wget https://getcomposer.org/composer-stable.phar -O bin/composer
	chmod a+x bin/composer
