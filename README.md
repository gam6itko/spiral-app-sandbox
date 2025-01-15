# spiral app sandbox

app:            http://localhost:8080

buggregator:    http://localhost:8000

adminer:        http://localhost:8090

kafka-ui:       http://localhost:8091

zipkin:         http://localhost:9411

## before start
```shell
docker-compose up -d
```

## queue consume issue

```shell
docker-compose exec app  php app.php rr:jobs:list
```


### dev help

```shell
# Detect host ip in docker network.
docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' sas-mysql
```

# cli debug
```shell
php -d xdebug.mode=debug -d xdebug.start_with_request=1 -d xdebug.client_host=172.19.0.1 app.php
```
