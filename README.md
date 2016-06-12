# Play

cd play

docker network create development

export UID
export GID
docker-compose \
-f docker/all.yml \
-p symsonte_framework \
up -d \
--remove-orphans --force-recreate

docker exec -it symsonte_framework_php sh

php play/bin/app.php /test