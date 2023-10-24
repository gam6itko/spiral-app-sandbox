# spiral app sandbox


## before start
```shell
docker-compose up -d
docker-compose exec mysql mysql -uroot -proot -e 'CREATE DATABASE app_sandbox'
docker-compose exec app php app.php cycle:sync
```
