# spiral app sandbox

app:            http://localhost:8080

buggregator:    http://localhost:8000

adminer:        http://localhost:8090

kafka-ui:       http://localhost:8091

zipkin:         http://localhost:9411

## before start
```shell
docker compose up -d
docker compose exec app  php app.php cycle:sync
```

## queue consume issue

```shell
docker compose exec app  php app.php rr:jobs:list
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

## kafka driver ignores requeue delay

RoadRunner `v2025.1.15` (kafka driver `v5.2.5`, jobs `v5.1.9`). `RetryException` with `withDelay(10)` is requeued by the kafka driver immediately: `kafkajobs/item.go` — `func (i *Item) Requeue(headers map[string][]string, _ int)` drops the delay, and the copy is re-produced into the source topic (seen by every consumer group of the topic). The memory driver honors the same delay.

Handler: `src/app-consumer/app/src/Job/RetryDelayJobHandler.php` — throws `RetryException` (delay 10s) 3 times and logs seconds since the previous attempt.

```shell
docker compose up -d
# control: memory pipeline, delay honored (~10s between attempts)
docker compose exec app-consumer php app.php app:issue:retry-delay:local
# bug: kafka pipeline, retries arrive immediately (~0.5s between attempts)
docker compose exec app php app.php app:issue:retry-delay:kafka
docker compose logs app-consumer | grep -E '\[retry-delay\]|re-queued'
# every retry is a new record in the topic
docker compose exec kafka kafka-get-offsets --bootstrap-server localhost:9092 --topic sas.foo-bar
```

Result:

```
[retry-delay] via=local id=42201a52-... attempt=1 since_prev=10.59s expected_delay=10s
[retry-delay] via=local id=42201a52-... attempt=2 since_prev=10.52s expected_delay=10s
[retry-delay] via=local id=42201a52-... attempt=3 since_prev=10.54s expected_delay=10s

[retry-delay] via=kafka id=63cfbf6f-... attempt=1 since_prev=0.65s expected_delay=10s
[retry-delay] via=kafka id=63cfbf6f-... attempt=2 since_prev=0.50s expected_delay=10s
[retry-delay] via=kafka id=63cfbf6f-... attempt=3 since_prev=0.50s expected_delay=10s
```

RR log shows `job was re-queued {"delay": 10, ...}` for each kafka retry, topic end offset grows by 4 (1 original + 3 requeued copies). Note: the consumer group must be joined before producing (`consumer_offset: AtEnd`), otherwise the message is skipped.
