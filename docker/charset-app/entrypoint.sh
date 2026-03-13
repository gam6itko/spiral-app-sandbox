#!/bin/sh

mkdir -p ./runtime

if [ ! -d ./vendor ]; then
  composer install
fi

exec php app.php "$@"
