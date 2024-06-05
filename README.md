# spiral app sandbox

app:        http://localhost:8080
adminer:    http://localhost:8090
kafka-ui:   http://localhost:8091
zipkin:     http://localhost:9411

## before start
```shell
docker-compose up -d
```

## queue consume issue

```shell
docker-compose exec app  php app.php rr:jobs:list
```
