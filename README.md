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
# Узнаём подсеть. В последний октет заменяем на единицу (1 - host machine).
docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' sas-mysql
```
