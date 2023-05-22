# spiral kafka issue

## init

```shell
docker-compose up -d
```

Kafka-ui at http://localhost:8080/

## push jobs

```shell
docker-compose exec app-producer  php app.php kafka-push
```

The `app-consumer` does not handle job
