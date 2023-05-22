#!/bin/sh

mkdir -p ./runtime
chmod -R a+rw ./runtime

if [ ! -d ./vendor ]; then
  composer install
fi

php app.php cache:clean -v

rr serve
